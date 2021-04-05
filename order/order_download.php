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

<?php
// 関数のファイルを読み込み
require_once('../common/common.php');
?>
ダウンロードしたい注文日を選んでください。<br/>
<form method="post" action="order_download_done.php">
<!-- 年度のプルダウン -->
<?php pulldown_year();?>
年
<!-- 月のプルダウン -->
<?php pulldown_month();?>
月
<!-- 日のプルダウン -->
<?php pulldown_day();?>
日<br/>
<br/>
<!-- submitで遷移先に値を飛ばす -->
<input type="submit" value="ダウンロードへ">
</form>
</body>
</html>