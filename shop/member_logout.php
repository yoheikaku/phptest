<!-- sessionの破棄 -->
<?php
//session開始
session_start();
//セッション変数の中身を空にする
$_SESSION = array();
//もしクッキー情報があったら、セッションIDを安全のために削除する
if(isset($_COOKIE[session_name()])==true){
    setcookie(session_name(),'',time()-42000,'/');
}
//session破棄
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>

ログアウトしました。<br/>
<br/>
<a href="shop_list.php">商品一覧へ</a>
</body>
</html>