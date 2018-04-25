<?php
session_start();

$userid = 'root';
$passwd = '1234';

$_SESSION['state'] = '';
$error_msg = '';
if (!empty($_POST['token']))
{
    if ($_POST['token'] != $_SESSION['token'])
    {
        $error_msg = 'アクセスが不正です。';
    }
    else
    {
        if (empty($_POST['user']) || empty($_POST['passwd']))
        {
            $error_msg = 'ユーザー名またはパスワードを入れてください。';
        }
        else
        {
            if ($_POST['user'] != $userid || $_POST['passwd'] != $passwd)
            {
                $error_msg = 'ユーザー名とパスワードの組み合わせが異なります。';
            }
            else
            {
                $_SESSION['state'] = 'login';
                $_SESSION['username'] = $_POST['user'];
                header('Location: ./');
            }
        }
    }
}

// トークンのセット
$token = rand(10000000,99999999);
$_SESSION['token'] = $token;
?>
<html>
<head>
	<meta http-equiv="content-language" content="ja">
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta name="viewport" content="width=device-width,maximum-scale=1">
    <title>生活RTAシステム - ログイン</title>

    <link href="./style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="header">
    生活RTAシステム
    </div>

    <div id="loginform">
        <form action="" method="post">
            <div id="loginhead">ログイン</div>
            <span class="loginitem">ユーザー名</span>
            <input type="text" name="user" value="">
            <br>
            <span class="loginitem">パスワード</span>
            <input type="password" name="passwd" value="">
            <br>
            <input type="hidden" name="token" value="<?=$token?>">
            <?php
            if (!empty($error_msg))
            {
                echo '<div id="errmsg">' . $error_msg . '</div>';
            }
            ?>
            <input type="submit" name="submit" value="ログイン">
        </form>
    </div>
    <style>
    #loginform input[type="text"],
    #loginform input[type="password"],
    #loginform input[type="button"],
    #loginform input[type="submit"],
    #loginform textarea {
        border-radius: 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    #loginform {
        width: 280px;
        margin: 10px auto;
        padding: 20px;
        border: 1px solid #dddddd;
        background: #fdfdfd;
    }
    #loginform #loginhead{
        margin: 0 0 20px 0;
        padding: 0 0 5px 0;
        color: #ff8888;
        font-size: 200%;
        font-weight: bold;
        text-align: center;
        border-width: 0 0 1px 0;
        border-color: #dddddd;
        border-style: solid;
    }
    #loginform .loginitem{
        margin: 0;
        color: #ff8888;
        font-size: 150%;
        font-weight: bold;
        display: block;
    }
    #loginform #errmsg{
        margin: 0 0 20px 0;
        padding: 10px;
        background: #ffffff;
        color: #ff8888;
        font-weight: bold;
        border: 1px solid #ff8888;
    }
    #loginform input[type="text"],
    #loginform input[type="password"]{
        width: 100%;
        margin: 0 0 20px 0;
        padding: 8px 10px;
        border: 1px solid #dddddd;
        background: #ffffff;
        font-size: 120%;
    }
    #loginform input[type="submit"]{
        width: 100%;
        padding: 10px;
        background: #ff8888;
        color: #fff;
        font-size: 120%;
        font-weight: bold;
        border: none;
    }
    </style>
</body>
</html>