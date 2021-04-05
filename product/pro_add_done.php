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

//hiddenで前の画面から渡されてきた入力データ
// $pro_name = $_POST['name'];
// $pro_price = $_POST['price'];
//画像名をPOSTで受け取る
// $pro_gazou_name = $_POST['gazou_name'];

require_once('../common/common.php');

//入力値受け取り
$post=sanitize($_POST);
//サニタイジング
$pro_name = $post['name'];
$pro_price = $post['price'];
$pro_gazou_name = $post['gazou_name'];

//DB接続
$dsn = 'mysql:dbname=shop;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn,$user,$password);
$dbh->query('SET NAMES utf8');

//SQL文で入力値をDBに登録
$sql = 'INSERT INTO mst_product(name,price,gazou) VALUES(?,?,?)';
$stmt = $dbh->prepare($sql);
$data[] = $pro_name;
$data[] = $pro_price;
//画像の名前をセットする
$data[] = $pro_gazou_name;
$stmt->execute($data);


//DB接続切断
$dbh = null;

echo $pro_name;
echo 'を追加しました。<br>';

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