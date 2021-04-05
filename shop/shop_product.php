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
    //修正のために選択された飛ばされてきた商品コードを受け取る　←　GETパラメータで受け取る
    $pro_code = $_GET['procode'];

    //DB接続
    $dsn = 'mysql:dbname=shop;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->query('SET NAMES utf8');

    //選択されたコードを参考にNAMEをSQLで取り出す
    $sql = 'SELECT name,price,gazou FROM mst_product WHERE code=?';
    //sql準備
    $stmt = $dbh->prepare($sql);
    $data[] = $pro_code;
    //実行
    $stmt->execute($data);

    //対象のレコードを取り出す
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    //レコードのnameを取り出す
    $pro_name = $rec['name'];
    $pro_price = $rec['price'];
    $pro_gazou_name = $rec['gazou'];

    //DB切断
    $dbh = null;

    if($pro_gazou_name==''){
        $disp_gazou = '';
    }else{
        $disp_gazou = '<img src="../product/gazou/'.$pro_gazou_name.'">';
    }
//ショッピングカートへGET送信で送られた商品コード（シーケンスの主キー）を元に商品を登録    
echo '<a href="shop_cartin.php?procode='.$pro_code.'">カートに入れる</a><br/><br/>';
    //画像の名前が入っていなかったら、画像を表示しない
}catch(Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}
?>

商品情報参照<br/>
<br/>
商品コード<br/>
<?php
    echo $pro_code;
?>
<br/>
商品名<br/>
<?php
    echo $pro_name;
?>
<br/>
価格<br/>
<?php
    echo $pro_price;
?>円
<br/>
<!-- 画像結果表示 -->
<?php echo $disp_gazou; ?>
<br/>
<!-- 情報をフォームで飛ばす-->
<form>
<input type = "button" onclick = "history.back()" value = "戻る">
</form>
</body>
</html>