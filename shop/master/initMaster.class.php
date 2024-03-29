<?php
/*
 * ファイルパス : \Application\xampp\htdocs\DT\shop\master\initMaster.class.php
 * ファイル名 : initMaster.class.php
 */
namespace shop\master;

class initMaster
{
    public static function getDate()
    {
        $yearArr = [];
        $monthArr = [];
        $dayArr = [];

        $next_year = date('Y') + 1;

        // 年の作成
        for ($i = 1900; $i < $next_year; $i ++) {
            $year = sprintf("%04d", $i);
            $yearArr[$year] = $year . '年';
        }
        // 月の作成
        for ($i = 1; $i < 13; $i ++) {
            $month = sprintf("%02d", $i);
            $monthArr[$month] = $month . '月';
        }
        // 日の作成
        for ($i = 1; $i < 32; $i ++) {
            $day = sprintf("%02d", $i);
            $dayArr[$day] = $day . '日';
        }
        return [$yearArr, $monthArr , $dayArr];
    }

    public static function getSex()
    {
        $sexArr = ['1' => '男性', '2' => '女性'];
        return $sexArr;
    }

    public static function getTrafficWay()
    {
        $trafficArr = ['徒歩', '自転車', 'バス', '電車', '車・バイク'];
        return $trafficArr;
    }

    public static function getScore()
    {
        $scoreArr = [];
        for ($i = 5; $i > 0; $i --) {
            $scoreArr[$i] = $i . '点';
        }
        return $scoreArr;
    }

    public static function getEntry()
    {
        $entryArr = ['1' => '商品について', '2' => '配送について', '3' => 'その他'];

        return $entryArr;
    }

    public static function getNum()
    {
        $numArr = [];

        for ($i = 1; $i < 11; $i ++) {
            $num = $i;
            $numArr[$num] = $num . '個';
        }
        return $numArr;
    }

}