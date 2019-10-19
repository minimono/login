<?php
session_start();
session_destroy();
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
    <h1>ログアウト完了</h1>
    <a href="login.php" class="btn">またログインする！</a>
  </div>
</body>
</html>