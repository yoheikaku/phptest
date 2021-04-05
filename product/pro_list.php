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
<!-- スタッフ一覧画面 -->
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

    //分岐のページへジャンプ
    echo '<form method="post" action="pro_branch.php">';
    while(true){
        //$stmtから１レコード取り出すコード
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        //もしデータがなければbreakでループを抜ける
        if($rec == false){
            break;
        }
        //１レコードずつ取り出しながら表示する
        echo '<input type ="radio" name="procode" value="'.$rec['code'].'">';
        echo $rec['name'].'---';
        echo $rec['price'].'円';
        echo '<br/>';
    }
    echo '<input type="submit" name="disp" value="参照">';
    echo '<input type="submit" name="add" value="追加">';
    echo '<input type="submit" name="edit" value="修正">';
    echo '<input type="submit" name="delete" value="削除">';
    echo '</form>';

}catch(Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}
?>
<br/>
<a href="../staff_login/staff_top.php">トップメニューへ</a><br/>
</body>
</html>