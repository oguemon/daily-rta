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
        $sql = 'SELECT * FROM `daily-rta` WHERE time >= :start AND time < :end';
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

    foreach ($result as $row)
    {
        echo '<tr>';
        echo '<td>' . date('H:i.s', strtotime($row['time'])) . '</td>';
        echo '<td>' . label2JPN($row['label']) . '</td>';
        echo '<td>' . state2JPN($row['state']) . '</td>';
        echo '</tr>';
        if ($row['state'] == 'start')
        {
            $old['time']  = strtotime($row['time']);
            $old['label'] = $row['label'];
        }
        else
        {
            if ($old['label'] == $row['label'])
            {
                echo '<td colspan="3">' . sec2time(strtotime($row['time']) - $old['time']) . '</td>';
            }
        }
    }
    echo '</table>';
}
?>
</body>
</html>
