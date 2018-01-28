<html>
<head>
<meta charset = "UTF-8">
<title>ディベート掲示板</title>
</head>
<body>

ディベート掲示板<br>
<form action = "mission_3-7.php" method = "post">

　ログイン<br>
　　　　　ID：<input type = "text" name = "ID"><br>
　パスワード：<input type = "text" name = "passwords"><br>

<input type = "submit" value = "送信">

</form>

<?php

//mysqlに接続
$dsn='mysql:dbname=データベース名';
$username='ユーザー名';
$password='パスワード';

if($_POST['ID'] !="" && $_POST['passwords'] !=""){
	
	$ID = $_POST['ID'];
	$passwords = $_POST['passwords'];
	
	//データ取得
	$pdo = new PDO($dsn, $username, $password);
	$sql_login = 'SELECT * FROM register_data order by number';
	$stmt_login = $pdo->query($sql_login);
	var_dump($stmt_login);
	
	foreach($stmt_login as $rows_login){
		$ID2 = $rows_login['ID'];
		echo $ID2."<br>";
		$passwords2 = $rows_login['password'];
		echo $passwords2."<br>";
		
		if($ID == $ID2 && $passwords == $passwords2){
			header("Location: http://co-758.it.99sv-coco.com/mission_3-7-2.php");
			exit;
		}
	}
	if($ID != $ID2 && $passwords != $passwords){
		echo "IDもしくはパスワードが間違っています"."<br>";
	}
}
?>

</body>
</html>