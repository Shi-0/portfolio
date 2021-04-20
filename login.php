<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>会員ページ</title>
</head>
<body>
	<?php
	session_start();
	if($_SERVER["REQUEST_METHOD"]==="POST"):
		try {
			$pdo = new PDO('mysql:host=localhost;dbname=portfolio;charset=utf8','root','',
			array(PDO::ATTR_EMULATE_PREPARES => false));
		}
		catch (PDOException $e) {
			die('データベース接続失敗。'.$e->getMessage());
		}

		$stmt=$pdo->prepare("SELECT `pass`,`name` FROM `user` WHERE `mail`=:mail");
		$stmt->bindParam(":mail",$_POST["mail"]);
		$stmt->execute();
		$result=$stmt->fetch();
		if($result):
			// var_dump($result); チェック
			// var_dump($_POST); チェック
			if(intval($_POST["pass"])===$result["pass"])://intvalで変数に渡す値を整数に変換している。ブラウザから出力されるものは全て文字列として扱われてしまう。
				// echo "test"; チェック
				session_regenerate_id(true);
				$_SESSION['mail']=$_POST["mail"];
				$_SESSION['name']=$result["name"];
?>
	<header>
		<h1><?php echo $_SESSION['name'] ?>さんのページ</h1>
  </header>
	<img src="" width="120" height="120" alt="本棚の画像">
	<h1>読書レビューサイト</h1>
	<p>本の感想を投稿・閲覧できます</p>
	<ul>
		<li>
			<p><a href="">
				レビューを投稿する
			</a></p>
		</li>
	</ul>
	
	<section>
		<img src="" wodth="260" height="260" alt="本棚の画像">
		<h1>みんなのレビュー</h1>
		<p></p>
		
	</section>
	<section>
		<img src="" wodth="260" height="260" alt="本棚の画像">
		<h1>自分のレビュー</h1>
		<p></p>
		
	</section>
	<p><a href="login.php">マイページ</a></p>
	<p><a href="index.html">ログアウト</a></p>
	<?php
			else:
				$errors="パスワードが違います";
			endif;
		else:
			$errors="ユーザーが存在しません";
		endif;
		$stmt=null;
		if(isset($errors)):
			?>
	<p><?php echo $errors; ?></p>
	<p><a href='login.html'>ログイン画面に戻る</a></p>
	<?php
		endif;
		$pdo=null;	
	else:
		die("直接アクセス禁止");
	endif;
	?>
</body>
</html>