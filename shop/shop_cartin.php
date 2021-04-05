<!-- ログインチェック（合言葉の認証） -->
<?php
session_start();
//sessionの合言葉（ここでは１）の合言葉をセキュリティ対策の為自動で変更する
session_regenerate_id(true);
//ログインOKの認証がなかったら
if(isset($_SESSION['member_login'])==false){
    echo 'ようこそゲスト様';
    echo '<a href="member_login.html">会員ログイン</a>';
    echo '<br/><br/>';
}else{
    //ログインが成功している時、ログイン中はsessionに登録したものが表示できる
    echo 'ようこそ';
    echo $_SESSION['member_name'];
    echo '様';
    echo '<br/>';
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

try{
//コードをGETで受け取る
$pro_code = $_GET['procode'];


//もしセッションにカート情報があるときだけ追加
if(isset($_SESSION['cart'])==true){
    //現在のカート内容（セッションのコード内容）を$cartにコピーする
    $cart = $_SESSION['cart'];
    //セッションの中の数を変数に入れる
    $kazu = $_SESSION['kazu'];
    //カートの中身を探索し同じ商品が登録されそうになったら、警告の文を表示(in_array(重複しているか調べたいデータ,データが入っている配列))
    if(in_array($pro_code,$cart)==true){
        echo 'その商品はすでにカートに入っています';
        echo '<a href="shop_list.php">商品一覧に戻る</a>';
        //処理を止める
        exit();
    }
}

//コードをカートに入れる
$cart[] = $pro_code;
//どの画面でも見れるようにカートに入れた情報をセッションに詰める
$kazu[] = 1;
//cartはセッションの名前（echoでSESSION['cart']で中身を取り出せる
$_SESSION['cart'] = $cart;
//セッションに数の情報を登録
$_SESSION['kazu'] = $kazu;

//カートの中身を表示
foreach($cart as $key => $val){
    echo $val;
    echo '<br/>';
}
}catch (Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}    

?>

カートに追加しました。<br/>
<br/>
<a href="shop_list.php">商品一覧に戻る</a>

</body>
</html>