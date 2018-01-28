<html>
<head>
<meta charset = "UTF-8">
<title>ディベート掲示板</title>
</head>
<body>

ディベート掲示板<br>

<?php
//mysqlに接続
$dsn='mysql:データベース名';
$username='ユーザー名';
$password='パスワード';

$user_name = $_POST['name'];
$passwords = $_POST['passwords'];

//セッションを利用
session_start();
$_SESSION['user_name'] = $user_name;
$_SESSION['password'] = $passwords;
//echo $_SESSION['user_name']."<br>";

//投稿されたら
if($_POST['edit'] =="" && $_POST['delete'] =="" && $_POST['passwords'] != ""){
	
	
	//mysqlに保存
	$pdo = new PDO($dsn, $username, $password);
	
	//データ取得
	$sql_up = 'SELECT * FROM blog_data2 order by number';
	$stmt_up = $pdo->prepare($sql_up);
	$stmt_up->execute();
	$result_up = $stmt_up->fetchALL();
	//var_dump($result_up);
	
	foreach($result_up as $rows_up){
		$num = $rows_up['number'];
	}

	//echo $num."<br>";
	$number = $num +1;
	//echo $number."<br>";
	
	//フォームの受け取り
	$name = $_POST['name'];
	$comments = $_POST['comments'];
	$passwords = $_POST['passwords'];
	$date = date("Y/m/d H:i:s");

	$sql_up2 = 'INSERT INTO blog_data2(number, name, comments, password, date)VALUES(:number, :name, :comments, :password, :date)';
	$result_up2 = $pdo->prepare($sql_up2);
		
		$result_up2->bindValue(':number', $number, PDO::PARAM_INT);
		$result_up2->bindParam(':name', $name, PDO::PARAM_STR);
		$result_up2->bindParam(':comments', $comments, PDO::PARAM_STR);
		$result_up2->bindParam(':password', $passwords, PDO::PARAM_STR);
		$result_up2->bindParam(':date', $date, PDO::PARAM_INT);
	
	$result_up2->execute();
	//var_dump($result_up2);
	
	$pdo = null;
}	
if($_POST['passwords'] ==""){
	echo "パスワードを入力してください"."<br>";
}

//編集する（番号送る）
if($_POST['edit'] != ""){
	//編集フォームを受け取る
	$edit = $_POST['edit'];	
	$pdo = new PDO($dsn, $username, $password);
	//データ取得
	$sql_edit = 'SELECT * FROM blog_data2';
	$stmt_edit = $pdo->query($sql_edit);
	//var_dump($stmt1);
	
	foreach($stmt_edit as $rows_edit){
		$edit_num = $rows_edit['number'];
		//var_dump($edit_num);
	
		if($edit == $edit_num){
			echo "編集対象が見つかりました"."<br>";
			
			//名前とコメントを取得
			$ed_name = $rows_edit['name'];
			$ed_comment = $rows_edit['comments'];	
			//var_dump($ed_name);
			//var_dump($ed_comment);
			break;
		}
	$pdo = null;
	}
	if($edit !=$edit_num){
		echo "編集対象が見つかりませんでした"."<br>";
	}
}

//編集作業
if($_POST['passwords'] !="" && $_POST['edit_mode'] !=""){

	//パスワードが一致するか
	$passwords = $_POST['passwords'];
	
	//データ取得
	$pdo = new PDO($dsn, $username, $password);
	$sql_edit_mode = 'SELECT * FROM blog_data2';
	$stmt_edit_mode = $pdo->query($sql_edit_mode);
	//var_dump($stmt_edit_mode);
	
	foreach($stmt_edit_mode as $rows_edit_mode){
		$passwords2 = $rows_edit_mode['password'];
		//echo $passwords2."<br>";
		
		if($passwords == $passwords2){
			$edit_number = $rows_edit_mode['number'];
			//echo $edit_number."<br>";
			
			if($edit == $edit_number){
				echo "編集内容を受け取りました"."<br>";
				echo "差し替えます"."<br>";
				
				//編集内容を受け取る
				$name_edit = $_POST['name'];
				$comments_edit = $_POST['comments'];
				$date = date("Y/m/d H:i:s");
				
				$sql_edit_mode2 = "UPDATE blog_data2 SET name=:name, comments=:comments WHERE number=$edit_number";
				$result_edit_mode2 = $pdo->prepare($sql_edit_mode2);
				
				$result_edit_mode2->bindParam(':name', $name_edit, PDO::PARAM_STR);
				$result_edit_mode2->bindParam(':comments', $comments_edit, PDO::PARAM_STR);
	
				$result_edit_mode2->execute();
				//var_dump($result_edit_mode2);
			}
			if($edit != $edit_number){
				echo "編集に失敗しました"."<br>";
			}
		}
	}
	$pdo = null;
}
elseif($_POST['passwords'] =="" && $_POST['edit_mode'] != ""){
	echo "パスワードを入力してください"."<br>";
}	

//削除
if($_POST['passwords'] !="" && $_POST['delete'] != ""){

	$delete = $_POST['delete'];
	
	//パスワードが一致するか
	$passwords = $_POST['passwords'];
	
	//データ取得
	$pdo = new PDO($dsn, $username, $password);
	$sql_delete = 'SELECT * FROM blog_data2';
	$stmt_delete = $pdo->query($sql_delete);
	//var_dump($stmt_delete);
	
	foreach($stmt_delete as $rows_delete){
		$passwords3 = $rows_delete['password'];
		//echo $passwords3."<br>";
		
		if($passwords == $passwords3){
			$delete_number = $rows_delete['number'];
			//echo $delete_number."<br>";
			
			if($delete == $delete_number){
				echo "削除対象が見つかりました"."<br>";
				echo "削除します"."<br>";
				
				$sql_delete2 = "DELETE FROM blog_data2 WHERE number=$delete_number";
				$result_delete2 = $pdo->prepare($sql_delete2);
	
				$result_delete2->execute();
				//var_dump($result_delete2);
			}
		}
	}
}

?>

<form action = "mission_3-7-2.php" method = "post">
<input type = "hidden" name = "edit_mode" value = "<?php echo $edit_num;?>">

　　　　名前：<input type = "text" name = "name" value = "<?php echo $_SESSION['user_name'];?>"><br>
　パスワード：<input type = "text" name = "passwords" value = "<?php echo $_SESSION['password'];?>"><br>
　　コメント：<input type = "text" name = "comments" value = "<?php echo $ed_comment;?>"><br>
編集対象番号：<input type = "text" name = "edit" value = "<?php echo $edit;?>"><br>
削除対象番号：<input type = "text" name = "delete"><br>

<input type = "submit" value = "送信">

</form>

<?php

$pdo = new PDO($dsn, $username, $password);
$sql_echo = 'SELECT * FROM blog_data2 order by number';
$stmt_echo = $pdo->query($sql_echo);
//var_dump($stmt_echo);
	
foreach($stmt_echo as $rows_echo){
	echo $rows_echo['number']."<br>";
	echo $rows_echo['name']."<br>";
	echo $rows_echo['comments']."<br>";
	echo $rows_echo['date']."<br>";
	//echo $rows_echo['password']."<br>";
}
$pdo = null;

//セッション削除
unset($_SESSION['user_name']);
unset($_SESSION['password']);
?>
</body>
</html>