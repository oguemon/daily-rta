<?php
session_start();

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
            if ($_POST['user'] != 'k-ogura' || $_POST['passwd'] != 'pass')
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
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>生活RTAシステム - ログイン</title>
</head>
<body>
    <form action="" method="post">
        ユーザー名：
        <input type="text" name="user" value="">
        <br>
        パスワード：
        <input type="password" name="passwd" value="">
        <br>
        <input type="hidden" name="token" value="<?=$token?>">
        <?php
        if (!empty($error_msg))
        {
            echo '<p><strong>' . $error_msg . '</strong></p>';
        }
        ?>
        <input type="submit" name="submit" value="ログイン">
    </form>
</body>
</html>