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

//入力値受け取り
// $pro_code = $_POST['code'];
// $pro_name = $_POST['name'];
// $pro_price = $_POST['price'];
//修正前の画像をPOSTで受け取り、修正後の画像をファイル形式で受け取る
// $pro_gazou_name_old = $_POST['gazou_name_old'];
$pro_gazou = $_FILES['gazou'];

//セキュリティー対策（サニタイジング　←　入力データに）
// $pro_name = htmlspecialchars($pro_name);
// $pro_price = htmlspecialchars($pro_price);
// $pro_code = htmlspecialchars($pro_code);

require_once('../common/common.php');

//入力値受け取り
$post=sanitize($_POST);
//サニタイジング
$pro_code = $post['code'];
$pro_name = $post['name'];
$pro_price = $post['price'];
$pro_gazou_name_old = $post['gazou_name_old'];

//もし商品名が入力されていなかったら
if($pro_name == ''){
    echo '商品名が入力されていません <br/>';  
}else{
    echo '商品名：';
    echo $pro_name;
    echo '<br>';
}

//もし価格が正しく入力されていなかったら
if(preg_match('/^[0-9]+$/',$pro_price) == 0){
    echo '価格をきちんと入力してください<br>';
//正しく入力された場合
}else{
    echo '価格：';
    echo $pro_price;
    echo '円<br/>';
}

//画像サイズチェック
if($pro_gazou['size'] > 0){
    if($pro_gazou['size'] > 1000000){
        echo '画像が大きすぎます';
    }else{
        move_uploaded_file($pro_gazou['tmp_name'],'./gazou/'.$pro_gazou['name']);
        echo '<img src="./gazou/'.$pro_gazou['name'].'">';
        echo '<br/>';
    }
}

//もし入力に問題があったら「戻る」ボタンだけを表示する
if($pro_name == '' || preg_match('/^[0-9]+$/',$pro_price) == 0 || $pro_gazou['size'] >1000000){
    echo '<form>';
    echo '<input type = "button" onclick = "history.back()" value = "戻る">';
    echo '</form>';
//入力に問題がない場合    
}else{
    echo '上記のようにを変更します。';
    //問題がない場合のフォーム
    echo '<form method = "post" action = "pro_edit_done.php">';
    echo '<input type ="hidden" name = "code" value = "'.$pro_code.'">';
    echo '<input type ="hidden" name = "name" value = "'.$pro_name.'">';
    echo '<input type ="hidden" name = "price" value ="'.$pro_price.'">';
    echo '<input type ="hidden" name = gazou_name_old value ="'.$pro_gazou_name_old.'">';
    echo '<input type ="hidden" name = gazou_name value ="'.$pro_gazou['name'].'">';
    echo '<br/>';
    echo '<input type = "button" onclick = "history.back()" value = "戻る">';
    echo '<input type = "submit" value = "OK">';
    echo '</form>';
}

?>
</body>
</html>	