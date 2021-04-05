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
try{
    //修正のために選択された飛ばされてきた商品コードを受け取る（name="procode"なので）←　GETパラメータで受け取る
    $pro_code = $_GET['procode'];

    //DB接続
    $dsn = 'mysql:dbname=shop;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->query('SET NAMES utf8');

    //選択されたコードを参考にSQLで取り出す
    $sql = 'SELECT name,price,gazou FROM mst_product WHERE code=?';
    //sql準備
    $stmt = $dbh->prepare($sql);
    $data[] = $pro_code;

    //実行
    $stmt->execute($data);

    //対象のレコードを取り出す
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    //レコードのname,price,gazouを取り出す
    $pro_name = $rec['name'];
    $pro_price = $rec['price'];
    //変更前の画像
    $pro_gazou_name_old = $rec['gazou'];

    if($pro_gazou_name_old == ''){
        $disp_gazou = '';
    }else{
        $disp_gazou = '<img src="./gazou/'.$pro_gazou_name_old.'">';
    }

    //DB切断
    $dbh = null;
}catch(Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}
?>

商品修正<br/>
<br/>
商品コード<br/>
<?php
    echo $pro_code;
?>
<br/>
<br/>
<!-- 情報をフォームで飛ばす 名前と価格を変更（更新）する-->
<form method = "post" action = "pro_edit_check.php" enctype="multipart/form-data">
<input type = "hidden" name = "code" value = "<?php echo $pro_code;?>">
<!-- 修正前の画像をフォームで送信する -->
<input type = "hidden" name = "gazou_name_old" value = "<?php echo $pro_gazou_name_old;?>">
商品名<br/>
<!-- 変更のためのテキストボックス （前の名前がデフォルトで入っている　←　valueの場所）-->
<input type = "text" name = "name" style = "width:200px" value ="<?php echo $pro_name;?>"><br/>
価格<br/>
<input type = "text" name = "price" style = "width:50px" value = "<?php echo $pro_price;?>"><br/>
<br/>
<?php echo $disp_gazou;?>
<br/>
画像を選んでください。<br/>
<input type ="file" name ="gazou" style="width:400px"><br/>
<br/>
<input type = "button" onclick = "history.back()" value = "戻る">
<!-- submitでformを発動させる -->
<input type = "submit" value ="OK">
</form>
</body>
</html>