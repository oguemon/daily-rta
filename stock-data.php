<?php

// 不正なアクセスでないかのチェック
if(empty($_POST['token']) || $_POST['token'] != '904857'){
	echo 'アクセス不正';
	exit();
}

if(empty($_POST['btn-name'])){
	echo '値がないよ';
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
	if(info[0] == $labelitem)
	{
		$label = $labelitem;
		break;
	}
}

//状態の設定
if (info[1] == 'start') $state = 'start';
if (info[1] == 'end')   $state = 'end';

// データの挿入
try{
	$pdo->beginTransaction();
	$sql = 'INSERT INTO daily-rta (time,label, state) VALUES (:time, :label, :state)';
	$sth = $pdo->prepare($sql);
	$sth->bindValue(':time',  $time);
	$sth->bindValue(':label', $label);
	$sth->bindValue(':state', $state);
	$sth->execute();
	$pdo->commit();
	echo '登録成功';
}catch(PDOException $e){
	$pdo->rollBack();
	echo '登録失敗';
	exit();
}

// ログを残す
$handle = fopen("./log.txt", "a");
fwrite($handle, $time . ', ' . $_POST["button_name"] . "\n");
fclose($handle);
