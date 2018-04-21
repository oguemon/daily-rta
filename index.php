<?php
session_start();

if (empty($_SESSION['state']) || $_SESSION['state'] != 'login')
{
    header('Location: ./login.php');
    exit();
}

// データベースの接続
include_once('./db-connection.php');
?>
<html>
<head>
	<meta http-equiv="content-language" content="ja">
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <title>生活RTAシステム - トップページ</title>
<head>
<body>
<?php
// データの挿入
try
{
	$sql = 'SELECT * FROM `daily-rta` WHERE state = :state';
	$sth = $pdo->prepare($sql);
	$sth->bindValue(':state',  'start');
    $sth->execute();
}
catch(PDOException $e)
{
	exit();
}
while ($row = $sth->fetch(PDO::FETCH_ASSOC))
{
    echo '> ' . $row['label'] . ' - ' . $row['state'] . '<br>';
}
?>
</body>
</html>
