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


// $pro_code = $_POST['code'];
// $pro_name = $_POST['name'];
// $pro_price = $_POST['price'];
// $pro_gazou_name_old = $_POST['gazou_name_old'];
// $pro_gazou_name = $_POST['gazou_name'];


// $pro_name = htmlspecialchars($pro_name);
// $pro_price = htmlspecialchars($pro_price);
// $pro_code = htmlspecialchars($pro_code);


require_once('../common/common.php');

//入力値受け取り（前画面の）
$post=sanitize($_POST);
//サニタイジング
$pro_code = $post['code'];
$pro_name = $post['name'];
$pro_price = $post['price'];
$pro_gazou_name_old = $post['gazou_name_old'];
$pro_gazou_name = $post['gazou_name'];

//DB接続
$dsn = 'mysql:dbname=shop;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn,$user,$password);
$dbh->query('SET NAMES utf8');

//SQL文で入力値をDBに登録
$sql = 'UPDATE mst_product SET name=?,price=?,gazou=? WHERE code=?';
$stmt = $dbh->prepare($sql);
$data[] = $pro_name;
$data[] = $pro_price;
$data[] = $pro_gazou_name;
$data[] = $pro_code;
$stmt->execute($data);

//修正前の画像がもしあれば削除する
if($pro_gazou_name_old!=''){
    unlink('./gazou/'.$pro_gazou_name_old);
}

//DB接続切断
$dbh = null;

echo '修正しました。<br>';

//サーバーに問題が発生している時にこの処理をする
}catch(Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}

?>
<!-- スタッフ一覧画面へ戻るリンク -->
<a href = "pro_list.php">戻る</a>
</body>
</html>	