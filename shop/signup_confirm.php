<?php
/*
 * ファイルパス : \Application\xampp\htdocs\DT\shop\signup_confirm.php
 * ファイル名 : signup_confirm.php
 * アクセスURL : http://localhost/DT/shop/signup_confirm.php
 */
namespace shop;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shop\master\initMaster;
use shop\lib\PDODatabase;
use shop\lib\Common;
use shop\lib\Session;
use shop\lib\Customer;

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$cus = new Customer($db);
$common = new Common();

// モード判定(どの画面から来たか判断)
// 登録画面から来た場合
if (isset($_POST['confirm']) === true) {
    $mode = 'confirm';
}
// 戻る場合
if (isset($_POST['back']) === true) {
    $mode = 'back';
}
// 登録完了
if (isset($_POST['complete']) === true) {
    $mode = 'complete';
}
// ボタンのモードによって処理を変える
switch ($mode) {
    case 'confirm': // 新規登録
                    // データを受け継ぐ
                    // ↓この情報は入力には必要ない
        unset($_POST['confirm']);

        $dataArr = $_POST;

        // この値を入れないでPOSTするとUndefinedとなるので未定義の場合は空白状態としてセットしておく
        if (isset($_POST['sex']) === false) {
            $dataArr['sex'] = "";
        }

        // エラーメッセージの配列作成
        $errArr = $common->errorCheck($dataArr);
        $err_check = $common->getErrorFlg();
        // err_check = false →エラーがあります!
        // err_check = true  →エラーがないですよ!
        // エラーがなけらばconfirm.tpl あると regist.tpl
        $template = ($err_check === true) ? 'signup_confirm.html.twig' : 'signup.html.twig';

        break;
    case 'back': //戻ってきた時
                 //ポストされたデータを元に戻すので、$dataArrに入れる
        $dataArr = $_POST;

        unset($dataArr['back']);

        // エラーも定義しておかないと、Undefinedエラーが出る
        foreach ($dataArr as $key => $value) {
            $errArr[$key] = '';
        }

        $template = 'signup.html.twig';
        break;
    
    case 'complete': // 登録完了
        $dataArr = $_POST;

        $res = $cus->registCustomer($dataArr);
        
        if ($res === true) {
            $ses = new Session($db);
            $ses->checksession();
            $_SESSION['login_id'] = $_POST['login_id'];
            $_SESSION['customer_id'] = $db->getLastId();
            
            // 登録成功時は完成ページへ
            header('Location:' . Bootstrap::ENTRY_URL . 'signup_complete.php');
            exit();
        } else {
            // 登録失敗時は登録画面に戻る
            $template = 'signup.html.twig';

            foreach ($dataArr as $key => $value) {
                $errArr[$key] = '';
            }
        }
        break;
}
$sexArr = initMaster::getSex();

list($yearArr, $monthArr, $dayArr) = initMaster::getDate();

$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
$context['sexArr'] = $sexArr;
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$template = $twig->loadTemplate($template);
$template->display($context);

