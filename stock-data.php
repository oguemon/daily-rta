<?php
// ログを残す
writeLog($time.','.$_POST["btn-name"].','.$_POST["token"]);

// 不正なアクセスでないかのチェック
if(empty($_POST['token']) || $_POST['token'] != '904857')
{
	writeLog('アクセス不正');
	exit();
}

if(empty($_POST['btn-name']))
{
	writeLog('値がないよ');
	exit();
}

// MySQLに接続
include_once('./db-connection.php');

// これから使う変数の初期化
$time  = date("Y-m-d H:i:s");
$label = '';
$state = '';

// POSTデータの分割
$info = explode('-',$_POST['btn-name']);

// ラベルの設定
$labellist = array('bed','dish','bath','out','desk');
foreach ($labellist as $labelitem)
{
	if($info[0] == $labelitem)
	{
		$label = $labelitem;
		break;
	}
}

//状態の設定
if ($info[1] == 'start') $state = 'start';
if ($info[1] == 'end')   $state = 'end';

writeLog('time : '.$time);
writeLog('label: '.$label);
writeLog('state: '.$state);

// データの挿入
try{
	$pdo->beginTransaction();
	$sql = 'INSERT INTO `daily-rta` (`time`,`label`,`state`) VALUES (:time, :label, :state)';
	$sth = $pdo->prepare($sql);
	$sth->bindValue(':time',  $time);
	$sth->bindValue(':label', $label);
	$sth->bindValue(':state', $state);
	$sth->execute();
	$pdo->commit();
	writeLog('登録成功');
}catch(PDOException $e){
	$pdo->rollBack();
	writeLog('登録失敗');
	exit();
}

// ログを残す
function writeLog ($message)
{
	
	$handle = fopen("./log.txt", "a");
	fwrite($handle, $message . "\n");
	fclose($handle);
}