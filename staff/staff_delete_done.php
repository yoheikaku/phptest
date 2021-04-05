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
<!-- 入力されたデータをDBに登録するファイル -->
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>
<?php

//DBサーバー障害の対策
try{

//hiddenで前の画面から渡されてきたデータ
$staff_code = $_POST['code'];

//DB接続
$dsn = 'mysql:dbname=shop;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn,$user,$password);
$dbh->query('SET NAMES utf8');

//SQL文でユーザーををDBから削除(コードの値によって削除するスタッフを決定する)
$sql = 'DELETE FROM mst_staff WHERE code=?';
$stmt = $dbh->prepare($sql);
$data[] = $staff_code;
$stmt->execute($data);

//DB接続切断
$dbh = null;

//サーバーに問題が発生している時にこの処理をする
}catch(Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}

?>

削除しました。<br/>
<br/>
<!-- スタッフ一覧画面へ戻るリンク -->
<a href = "staff_list.php">戻る</a>
</body>

</html>	