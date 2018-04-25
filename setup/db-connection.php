<?php
/*
 * このファイルはindex.phpと同じディレクトリにおく！
 */

// MySQLサーバの接続情報
$host     = 'example.com';
$dbname   = 'database_name';
$user     = 'username';
$password = 'password';

// MySQLサーバへ接続
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e){
	echo '接続失敗';
	exit();
}