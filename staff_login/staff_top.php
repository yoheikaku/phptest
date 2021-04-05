<!-- ログインチェック（合言葉の認証） -->
<?php
session_start();
//sessionの合言葉（ここでは１）の合言葉をセキュリティ対策の為自動で変更する
session_regenerate_id(true);
//ログインOKの認証がなかったら
if(isset($_SESSION['login'])==false){
    echo 'ログインされていません。<br/>';
    echo '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
    //プログラムを終了
    exit();
}else{
    //ログインが成功している時、ログイン中はsessionに登録したものが表示できる
    echo $_SESSION['staff_name'];
    echo 'さんログイン中';
    echo '<br/><br/>';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>
ショップ管理トップメニュー<br/>
<br/>
<!-- 遷移先を指定 -->
<a href="../staff/staff_list.php">スタッフ管理</a></br>
<a href="../product/pro_list.php">商品管理</a><br/>
<!-- 注文ダウンロードへ -->
<a href = "../order/order_download.php">注文ダウンロード</a>
<br/>
<!-- ログアウト確認画面へ -->
<a href="staff_logout.php">ログアウト</a>
</body>
</html>