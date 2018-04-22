<?php
session_start();

if (empty($_SESSION['state']) || $_SESSION['state'] != 'login')
{
    header('Location: ./login.php');
    exit();
}

// データベースの接続
include_once('./db-connection.php');
include_once('./util.php');
?>
<html>
<head>
	<meta http-equiv="content-language" content="ja">
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta name="viewport" content="width=device-width,maximum-scale=1">
    <title>生活RTAシステム - トップページ</title>
    <link href="style.css" rel="stylesheet" type="text/css">
<head>
<body>
<div id="header">
生活RTAシステム
</div>

<?php

for ($backday = 0; $backday < 3; $backday++)
{
    $day_pt = time() - $backday * 86400;

    try
    {
        $sql = 'SELECT * FROM `daily-rta` WHERE time >= :start AND time < :end ORDER BY `id` DESC';
        $sth = $pdo->prepare($sql);
        $sth->bindValue(':start', date('Y-m-d', $day_pt));
        $sth->bindValue(':end',   date('Y-m-d', $day_pt + 86400));
        $sth->execute();
        $result = $sth->fetchAll();
    }
    catch(PDOException $e)
    {
        exit();
    }

    echo '<h1>' . date('Y年m月d日', $day_pt) . '</h1>';
    echo '<table>';
    
    $old = array('time' => 0,'label' => '');

    for ($n = 0; $n < count($result); $n++)
    {
        $d = $result[$n];

        echo '<tr>';
        echo '<td>' . date('H:i.s', strtotime($d['time'])) . '</td>';
        echo '<td>' . state2JPN($d['state']) . '</td>';
        
        if ($d['state'] == 'end')
        {
            // 末端なら
            if ($n == count($result) - 1)
            {

                echo '<td>' . label2JPN($d['label']) . '</td>';
                echo '<td>' . sec2time(strtotime($d['time']) - strtotime(date('Y-m-d 00:00:00', $day_pt))) . '</td>';
            }
            //末端でない
            else
            {
                $nextd = $result[$n + 1];
                if($nextd['label'] == $d['label'])
                {
                    echo '<td rowspan="2">' . label2JPN($d['label']) . '</td>';
                }
                else
                {
                    echo '<td rowspan="2">' . label2JPN($nextd['label']) . '</td>';
                }
                echo '<td rowspan="2">' . sec2time(strtotime($d['time']) - strtotime($nextd['time'])) . '</td>';
            }
        }
        else
        if ($d['state'] == 'start' && $n == 0)
        {
            echo '<td>' . label2JPN($d['label']) . '</td>';
            if (date('Y-m-d', $day_pt) == date('Y-m-d'))
            {
                echo '<td>' . sec2time(time() - strtotime($d['time'])) . '</td>';
            }
            else
            {
                echo '<td>' . sec2time(strtotime(date('Y-m-d', strtotime($d['time']))) + 86400 - strtotime($d['time'])) . '</td>';
            }
        }
        
        echo '</tr>';
    }
    echo '</table>';
}
?>
</body>
</html>
