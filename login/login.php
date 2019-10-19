<?php
session_start();
if(isset($_SESSION["USERID"])){
  header('Location: index.php');
  exit();
}
$db['host'] = '';
$db['user'] = 'minimono';
$db['pass'] = '';
$db['dbname'] = '';
$error = '';
if(isset($_POST['login'])){
  if(empty($_POST['username'])){
    $error = 'ユーザー名が未入力です。';
  }else if(empty($_POST['password'])){
    $error = 'パスワードが未入力です。';
  }
  if(!empty($_POST['username']) && !empty($_POST['password'])){
    try{
      $username = $_POST['username'];
      $dsn = sprintf('mysql:host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
      $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
      $stmt = $pdo->prepare('SELECT * FROM user WHERE name = :username');
      $stmt->bindParam(':username', $username, PDO::PARAM_STR);
      $stmt->execute();
      $password = $_POST['password'];
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if(password_verify($password, $result['password'])){
        $_SESSION['USERID'] = $username;
        header('Location: index.php');
        exit();
      }else{
        throw new Exception('ユーザー名またはパスワードに誤りがあります。');
      }
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
      <h1>ログインしましょう！</h1>
      <label for="username">ユーザー名</label>
      <input type="text" name="username" id="username" value="<?php if(!empty($_POST['username'])){echo htmlspecialchars($_POST['username'], ENT_QUOTES);} ?>" placeholder="ユーザー名を入力">
      <label for="password">パスワード</label>
      <input type="password" name="password" id="password" placeholder="パスワードを入力">
      <p class="error"><?php echo $error; ?></p>
      <input type="submit" name="login" value="ログイン" class="btn">
      <p class="signup-link"><a href="signup.php">新規登録はこちら</a></p>
    </form>
  </div>
</body>
</html>