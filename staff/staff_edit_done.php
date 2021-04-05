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

//hiddenで前の画面から渡されてきた入力データ
// $staff_code = $_POST['code'];
// $staff_name = $_POST['name'];
// $staff_pass = $_POST['pass'];

//セキュリティー対策
// $staff_name = htmlspecialchars($staff_name);
// $staff_pass = htmlspecialchars($staff_pass);

require_once('../common/common.php');

//入力値受け取り
$post=sanitize($_POST);
//サニタイジング
$staff_code = $post['code'];
$staff_name = $post['name'];
$staff_pass = $post['pass'];

//DB接続
$dsn = 'mysql:dbname=shop;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn,$user,$password);
$dbh->query('SET NAMES utf8');

//SQL文で入力値をDBに登録(コードの値によって変更するスタッフを決定する)
$sql = 'UPDATE mst_staff SET name=?,password=? WHERE code=?';
$stmt = $dbh->prepare($sql);
$data[] = $staff_name;
$data[] = $staff_pass;
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

修正しました。<br/>
<br/>
<!-- スタッフ一覧画面へ戻るリンク -->
<a href = "staff_list.php">戻る</a>
</body>

</html>	