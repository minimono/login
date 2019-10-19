<?php
session_start();
if(isset($_SESSION['USERID'])){
  header('Location: index.php');
  exit();
}
$db['host'] = '';
$db['user'] = 'minimono';
$db['pass'] = '';
$db['dbname'] = '';
$error = '';
if(isset($_POST['signup'])){
  if(empty($_POST['username'])){
    $error = 'ユーザー名が未入力です。';
  }else if(empty($_POST['password'])){
    $error = 'パスワードが未入力です。';
  }
  if(!empty($_POST['username']) && !empty($_POST['password'])){
    try{
      $username = $_POST['username'];
      $password = $_POST['password'];
      $dsn = sprintf('mysql:host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
      $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
      if(mb_strlen($username) > 10){
        throw new Exception('ユーザー名が長すぎます。');
      }
      if(mb_strlen($password) < 8 || strlen($password) > 16){
        throw new Exception('パスワードは8桁以上16桁以下で入力してください。');
      }
      $stmt = $pdo->prepare('SELECT COUNT(*) FROM user WHERE name = :username');
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->execute();
      $count = intval($stmt->fetchColumn());
      if($count > 0){
        throw new Exception('そのユーザー名は既に使用されています。');
      }
      $stmt = $pdo->prepare('INSERT INTO user(name, password) VALUES(:username, :password)');
      $pass = password_hash($password, PASSWORD_DEFAULT);
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
      $stmt->execute();
      $_SESSION['USERID'] = $username;
      echo '<script>
      alert("登録が完了しました。");
      location.href="index.php";
      </script>';
    }catch(Exception $e){
      $error = $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログインフォーム</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.0/css/all.css">
  <link rel="stylesheet" href="css/stylesheet.css">
  <link rel="stylesheet" href="css/responsive.css">
  <script src="js/jquery-3.4.1.min.js" defer></script>
  <script src="js/index.js" defer></script>
</head>
<body>
  <div class="container">
    <form method="POST">
      <h1>登録しましょう！</h1>
      <label for="username">ユーザー名</label>
      <input type="text" name="username" id="username" value="<?php if(!empty($_POST['username'])){echo htmlspecialchars($_POST['username'], ENT_QUOTES);} ?>" placeholder="ユーザー名を入力">
      <label for="password">パスワード(8桁以上16文字以下)</label>
      <input type="password" name="password" id="password" placeholder="パスワードを入力">
      <p class="error"><?php echo $error; ?></p>
      <input type="submit" name="signup" value="新規登録" class="btn">
    </form>
  </div>
</body>
</html>