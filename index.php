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
// データの挿入
try
{
	$sql = 'SELECT * FROM `daily-rta` WHERE time < :time';
	$sth = $pdo->prepare($sql);
	$sth->bindValue(':time',  '2018-04-22');
    $sth->execute();
}
catch(PDOException $e)
{
	exit();
}

echo '<table>';
$old = 0;
while ($row = $sth->fetch(PDO::FETCH_ASSOC))
{
    echo '<tr>';
    echo '<td>' . sec2time(strtotime($row['time']) - $old) . '</td>';
    echo '<td>' . label2JPN($row['label']) . '</td>';
    echo '<td>' . $row['state'] . '</td>';
    echo '</tr>';
    $old = strtotime($row['time']);
}
echo '</table>';
?>
</body>
</html>
