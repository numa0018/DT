<?php
/*
 * ファイルパス : \Application\xampp\htdocs\DT\shopping\detail.php
 * ファイル名 : detail.php (商品詳細を表示するプログラム、Controller)
 * アクセスURL : http://localhost/DT/shop/detail.php
 */ 
namespace shop;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shop\Bootstrap;
use shop\lib\PDODatabase;
use shop\lib\Session;
use shop\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$itm = new Item($db);

// 未ログイン排除
if (!isset($_SESSION['login_id'])) {
    header('location:'. Bootstrap::ENTRY_URL . 'login.php');
    exit(); 
}


// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);


// セッションに、セッションIDを設定する
$ses->checkSession();

// item_idを取得する
$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/', $_GET['item_id']) === 1) ? $_GET['item_id'] : '';

// login_idを取得
$sesArr = $_SESSION['login_id'];

// item_idが取得できていない場合、商品一覧へ遷移させる
if ($item_id === '') {
    header('Location: ' . Bootstrap::ENTRY_URL. 'list.php');
}

// カテゴリーリスト(一覧)を取得する
$cateArr = $itm->getCategoryList();

// 商品情報を取得する
$itemData = $itm->getItemDetailData($item_id);

$context = [];
$context['cateArr'] = $cateArr;
$context['sesArr'] = $sesArr;
$context['itemData'] = $itemData[0];// なぜ0が必要かは、$itemDataをvar_dumpして見よう！
$template = $twig->loadTemplate('detail.html.twig');
$template->display($context);
