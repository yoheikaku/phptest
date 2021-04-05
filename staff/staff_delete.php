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
    //修正のために選択された飛ばされてきたスタッフコードを受け取る（name="staffcode"なので）←　GETパラメータで受け取る
    $staff_code = $_GET['staffcode'];

    //DB接続
    $dsn = 'mysql:dbname=shop;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->query('SET NAMES utf8');

    //選択されたコードを参考にNAMEをSQLで取り出す
    $sql = 'SELECT name FROM mst_staff WHERE code=?';
    //sql準備
    $stmt = $dbh->prepare($sql);
    $data[] = $staff_code;
    //実行
    $stmt->execute($data);

    //対象のレコードを取り出す
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    //レコードのnameを取り出す
    $staff_name = $rec['name'];

    //DB切断
    $dbh = null;
}catch(Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}
?>

スタッフ削除<br/>
<br/>
スタッフコード<br/>
<?php
    echo $staff_code;
?>
<br/>
スタッフ名<br/>
<?php echo $staff_name;?>
<br/>
このスタッフを削除してよろしいですか？<br/>
<br/>
<!-- 情報をフォームで飛ばす ユーザーを削除する-->
<form method = "post" action = "staff_delete_done.php">
<input type = "hidden" name = "code" value = "<?php echo $staff_code;?>">
<input type = "button" onclick = "history.back()" value = "戻る">
<!-- submitでformを発動させる -->
<input type = "submit" value ="OK">
</form>
</body>
</html>