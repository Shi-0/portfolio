<?php
if($_SERVER["REQUEST_METHOD"]==="POST"):
	try {
		$pdo = new PDO('mysql:host=localhost;dbname=portfolio;charset=utf8','root','',
		array(PDO::ATTR_EMULATE_PREPARES => false));
	}
	catch (PDOException $e) {
		die('データベース接続失敗。'.$e->getMessage());
	}

	$errors=array();

	$mail=null;
	$mailmatch="/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9_-]+.[a-zA-Z0-9\._-]+$/";
	if(!preg_match($mailmatch,$_POST["mail"])):
		$errors["mail"]="メールアドレスを正しく入力してください";
	else:
		if(strlen($_POST["mail"])>50):
			$errors["mail"]="メールアドレスが長すぎます";
		else:
			$stmt=$pdo->prepare("SELECT * FROM `user` WHERE `mail`=:mail");
			$stmt->bindParam(':mail',$_POST["mail"]);
			$stmt->execute();
			$result=$stmt->fetch();
			if($result):
				$errors["mail"]="このメールアドレスは既に登録されています。";
			else:
				$mail=$_POST["mail"];
			endif;
			$stmt=null;
		endif;
	endif;

	$pass=null;
	$passmatch="/^[a-zA-Z0-9]{6,12}$/";
	if(!preg_match($passmatch,$_POST["pass"])):
		$errors["pass"]="パスワードを正しく入力してください";
	else:
		$pass=$_POST["pass"];
	endif;
	
	$name=null;
	$namematch="/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]+$/";
	if(!preg_match($namematch,$_POST["name"])):
		$errors["name"]="表示名を正しく入力してください";
	else:
		$name=$_POST["name"];
	endif;
	
	$sex=null;
	if(!isset($_POST["sex"]) || !strlen($_POST["sex"])):
		$errors["sex"]="性別を選択してください";
	else:
		$sex=$_POST["sex"];
	endif;
	

	if(count($errors)===0):
		$stmt = $pdo -> prepare("INSERT INTO `user` (`mail`,`pass`,`name`,`sex`) VALUES (:mail,:pass,:name,:sex)");
		$stmt->bindParam(':mail',$mail);
		$stmt->bindParam(':pass',$pass);
		$stmt->bindParam(':name',$name);
		$stmt->bindParam(':sex',$sex);
		$stmt->execute();
		$stmt=null;
	endif;
$pdo=null;
else:
	die("直接アクセス禁止");
endif;
?>
<DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>登録</title>
</head>
<body>
<?php if (count($errors)): ?>
	<ul class="error_list">
<?php foreach($errors as $error): ?>
		<li>
<?php echo htmlspecialchars($error,ENT_QUOTES,"UTF-8") ?>
		</li>
<?php endforeach; ?>
		<li><a href="touroku.html">登録画面に戻る</a></li>
	</ul>
<?php else: ?>
	<p>登録完了しました</p>
	<p><a href="login.html">ログインページへ</a></p>
<?php endif; ?>
</body>
</html>