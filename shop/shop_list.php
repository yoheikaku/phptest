<!-- ログインチェック（合言葉の認証） -->
<?php
session_start();
//sessionの合言葉（ここでは１）の合言葉をセキュリティ対策の為自動で変更する
session_regenerate_id(true);
//ログインOKの認証がなかったら
if(isset($_SESSION['member_login'])==false){
    echo 'ようこそゲスト様';
    echo '<a href="member_login.html">会員ログイン</a><br/>';
    echo '<br/>';
}else{
    //ログインが成功している時、ログイン中はsessionに登録したものが表示できる
    echo 'ようこそ';
    echo $_SESSION['member_name'];
    echo '様';
    echo '<a href="member_logout.php">ログアウト</a><br/>';
    echo '<br/><br/>';
}
?>
<!DOCTYPE html>
<!-- 商品一覧画面 -->
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>
<?php

//DB接続
try{
 
    $dsn = 'mysql:dbname=shop;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->query('SET NAMES utf8');

    //商品一覧表示（１は全部ということ）
    $sql = 'SELECT code,name,price,gazou FROM mst_product WHERE 1';
    $stmt = $dbh->prepare($sql);
    //↓の命令分が終了した時点で$stmtにデータが全部入っている
    $stmt->execute();
    
    //DB切断
    $dbh = null;

    echo '商品一覧<br/><br/>';

    while(true){
        //$stmtから１レコード取り出すコード
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        //もしデータがなければbreakでループを抜ける
        if($rec == false){
            break;
        }
        //１レコードずつ取り出しながら商品詳細リンクを表示する。商品コードをGETパラメータでURL遷移先に渡す。
        echo '<a href="shop_product.php?procode='.$rec['code'].'">';
        echo $rec['name'].'---';
        echo $rec['price'].'円';
        echo '<br/>';
    }
    
    //カートの中身を見るためのリンク
    echo '<br/>';
    echo '<a href="shop_cartlook.php">カートをみる</a><br/>';
}catch(Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}
?>
</body>
</html>