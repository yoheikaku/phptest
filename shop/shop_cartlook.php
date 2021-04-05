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
    //セッションの中身を戻す($cartは配列変数)
    if(isset($_SESSION['cart'])==true){
        $cart = $_SESSION['cart'];
        $kazu = $_SESSION['kazu'];
        //カートの中身のデータ数を変数に入れる
        $max = count($cart);
    }else{
        $max=0;
    }
    //カートの中身を表示
    //array(2) { [0]=> string(1) "6" [1]=> string(1) "7" }  string(1)→（）の中身は文字列の数
    // var_dump($cart);
    // exit();

    //もしカートの中のデータが空っぽだった時
    if($max==0){
        echo 'カートに商品が入っていません';
        echo '<br/>';
        echo '<a href="shop_list.php">商品一覧へ戻る</a>';
        //下のコードが走らないようにここでプログラムを止めておく
        exit();
    }

    //DB接続
    $dsn = 'mysql:dbname=shop;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->query('SET NAMES utf8');

    //カートの中身の分だけ繰り返しSELECTする($keyは配列番号)
    foreach($cart as $key => $val){
        $sql = 'SELECT code,name,price,gazou FROM mst_product WHERE code=?';
        $stmt = $dbh->prepare($sql);
        //0とかいたのはループが回るたびに１、２、３とらないようにしている(保留)←＄dataの中身を増やさないようにしている
        $data[0] = $val;
        $stmt->execute($data);
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        $pro_name[] = $rec['name'];
        $pro_price[] = $rec['price'];
        //画像の有無判別
        if($rec['gazou']==''){
            $pro_gazou[] = '';
        }else{
            $pro_gazou[] = '<img src="../product/gazou/'.$rec['gazou'].'">';
        }
    }
    //db切断
    $dbh = null; 
}catch(Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}
?>

<!-- cart配列の中身を個数分取り出す -->
カートの中身<br/>
<br/>
<table border="1">
<tr>
    <td>商品</td>
    <td>商品画像</td>
    <td>価格</td>
    <td>数量</td>
    <td>小計</td>
    <td>削除</td>
</tr>
<!-- カート情報をフォーム先に飛ばす -->
<form method="post" action="kazu_change.php">
<?php 
for($i=0; $i<$max; $i++){
?>
<tr>
    <td><?php echo $pro_name[$i];?></td>
    <td><?php echo $pro_gazou[$i];?></td>
    <td><?php echo $pro_price[$i].'円';?></td>
    <!-- 合計金額を表示 -->
    <td><input type="text" name = "kazu<?php echo $i;?>" value="<?php echo $kazu[$i];?>"></td>
    <td><?php echo $pro_price[$i] * $kazu[$i].'円';?></td>
    <!-- カートの商品の配列の何番目かに削除用につけるチェックボックス -->
    <td><input type="checkbox" name = "sakujo<?php echo $i; ?>"></td>
</tr>    
<?php
}
?>
</table>
<input type = "hidden" name ="max" value="<?php echo $max;?>">
<input type = "submit" value="数量変更"><br/>
<input type = "button" onclick = "history.back()" value = "戻る">
</form>
<br/>
<!-- 注文用のフォームへ -->
<a href="shop_form.html">ご購入手続きへ進む</a></br>
<?php
// 会員登録している場合簡単注文へのリンク先を表示
if(isset($_SESSION['member_login'])==true){
    echo '<a href="shop_kantan_check.php">会員かんたん注文へ進む</a>';
}
?>
</body>
</html>