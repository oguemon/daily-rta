<?php
$handle = fopen("./log.txt", "a");
if(!empty($_POST["button_name"])){
	fwrite($handle, 'button_name > '.$_POST["button_name"] . '\n');
	echo "登録完了";
}else{
	fwrite($handle, 'button_name > [none]\n');
	echo "値がないよ";
}
fclose($handle);
?>