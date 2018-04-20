<?php
session_start();

if (empty($_SESSION['state']) || $_SESSION['state'] != 'login')
{
    header('Location: ./login.php');
    exit();
}

echo 'ようこそ私のページへ！';
