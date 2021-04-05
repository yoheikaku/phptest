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
//プルダウンから持ってきた値を受け取る
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
//DB接続
try{
    $dsn = 'mysql:dbname=shop;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->query('SET NAMES utf8');
    $sql = 'SELECT dat_sales.code,dat_sales.date,dat_sales.code_member
    ,dat_sales.name AS dat_sales_name,dat_sales.email,dat_sales.postal1,
    dat_sales.postal2,dat_sales.address,dat_sales.tel,dat_sales_product.code_product,
    mst_product.name AS mst_product_name,dat_sales_product.price,
    dat_sales_product.quantity FROM dat_sales,dat_sales_product,
    mst_product WHERE dat_sales.code=dat_sales_product.code_sales AND dat_sales_product.code_product=mst_product.code AND substr(dat_sales.date,1,4)=? AND substr(dat_sales.date,6,2)=? AND substr(dat_sales.date,9,2)=?';
    // $sql = 'SELECT code,name FROM mst_staff WHERE 1';
    $stmt = $dbh->prepare($sql);
    //SQL文のWHEREの条件の箇所にdataの値を当てはめて実行
    $data[] = $year;
    $data[] = $month;
    $data[] = $day;
    $stmt->execute($data);
    //DB切断
    $dbh = null;

    //csvファイルのタイトル
    $csv = '注文コード,注文日時,会員番号,お名前,メール,郵便番号,住所,TEL,商品コード,商品名,価格,数量';
    $csv.="\n";
    //注文の数だけcsvファイルに読み込む
    while(true){
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if($rec==false){
            break;
        }
        $csv.=$rec['code'];
        $csv.=',';
        $csv.=$rec['date'];
        $csv.=',';
        $csv.=$rec['code_member'];
        $csv.=',';
        $csv.=$rec['dat_sales_name'];
        $csv.=',';
        $csv.=$rec['email'];
        $csv.=',';
        $csv.=$rec['postal1'].'-'.$rec['postal2'];
        $csv.=',';
        $csv.=$rec['address'];
        $csv.=',';
        $csv.=$rec['tel'];
        $csv.=',';
        $csv.=$rec['code_product'];
        $csv.=',';
        $csv.=$rec['mst_product_name'];
        $csv.=',';
        $csv.=$rec['price'];
        $csv.=',';
        $csv.=$rec['quantity'];
        $csv.="\n";
    }
    //csvファイル画面テスト出力用
    // echo nl2br($csv);

    //csvファイルを生成（同じフォルダ内に生成される）←　csvファイルをエクセルで開いて問題ないか確認する
    $file = fopen('./chumon.csv','w');
    //文字コード変換
    $csv = mb_convert_encoding($csv,'SJIS','UTF-8');
    //ファイル書き込み
    fputs($file,$csv);
    //ファイルを閉じる
    fclose($file);
}catch(Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}
?>
<a href="chumon.csv">注文データのダウンロード</a><br/>
</br>
<a href="order_download.php">日付選択へ</a><br/>
<a href="../staff_login/staff_top.php">トップメニューへ</a></br>
</body>
</html>