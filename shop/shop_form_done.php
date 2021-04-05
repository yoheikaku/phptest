<?php
    session_start();
    session_regenerate_id(true);
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
    //サニタイジング対策
    require_once('../common/common.php');
    $post=sanitize($_POST);

    $onamae = $post['onamae'];
    $email = $post['email'];
    $postal1 = $post['postal1'];
    $postal2 = $post['postal2'];
    $address = $post['address'];
    $tel = $post['tel'];
    //会員登録するかしないかの入力値
    $chumon = $post['chumon'];
    $pass = $post['pass'];
    $danjo = $post['danjo'];
    $birth = $post['birth'];

    echo $onamae.'様<br/>';
    echo 'ご注文ありがとうございました。<br/>';
    echo $email.'にメールを送りましたのでご確認ください。<br/>';
    echo '商品は以下の住所に発送させていただきます。<br/>';
    echo $postal1.'-'.$postal2.'<br/>';
    echo $address.'<br/>';
    echo $tel.'<br/>';


    //メール本文（改行は\nでダブルクォーテーションでくくる。変数.=で文章を追加していく）
    $honbun = '';
    $honbun.= $onamae."様\n\nこの度はご注文ありがとうございました。\n";
    $honbun.= "\n";
    $honbun.="ご注文商品\n";
    $honbun.="---------------------------\n";

    $cart = $_SESSION['cart'];
    $kazu = $_SESSION['kazu'];
    $max = count($cart);


    //DB接続
    $dsn = 'mysql:dbname=shop;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->query('SET NAMES utf8');

    for($i =0; $i<$max; $i++){
        $sql = 'SELECT name,price FROM mst_product WHERE code=?';
        $stmt = $dbh->prepare($sql);
        //セッションから情報を取り出す
        $data[0] = $cart[$i];
        $stmt->execute($data);

        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        $name = $rec['name'];
        $price = $rec['price'];
        //価格変数に値段を追加
        $kakaku[] = $price;
        //数量
        $suryo = $kazu[$i];
        $shokei = $price * $suryo;

        $honbun.=$name.' ';
        $honbun.=$price.'円 ×';
        $honbun.=$suryo.'個 = ';
        $honbun.=$shokei."円\n";
    }

    //注文データ追加処理中はロックをかける
    $sql = 'LOCK TABLES dat_sales,dat_sales_product,dat_member WRITE';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    //もし会員希望だった場合に、会員情報をDBに登録、会員コードは非会員は0として会員希望だった場合はその会員コーどを登録する
    $lastmembercode = 0;
    if($chumon = 'chumontouroku'){
        $sql = 'INSERT INTO dat_member(password,name,email,postal1,postal2,address,tel,danjo,born) VALUES(?,?,?,?,?,?,?,?,?)';
        $stmt = $dbh->prepare($sql);
        $data = array();
        //パスワードを暗号化
        $data[] = md5($pass);
        $data[] = $onamae;
        $data[] = $email;
        $data[] = $postal1;
        $data[] = $postal2;
        $data[] = $address;
        $data[] = $tel;
        if($danjo=='dan'){
            $data[] = 1;
        }else{
            $data[] = 2;
        }
        $data[] = $birth;
        $stmt->execute($data);

        $sql = 'SELECT LAST_INSERT_ID()';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastmembercode = $rec['LAST_INSERT_ID()'];
    }

    //注文データ追加
    $sql = 'INSERT INTO dat_sales(code_member,name,email,postal1,postal2,address,tel) VALUES(?,?,?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    //すでに入っているデータをクリアしておく
    $data =array();
    $data[] = $lastmembercode; 
    $data[] = $onamae;
    $data[] = $email;
    $data[] = $postal1;
    $data[] = $postal2;
    $data[] = $address;
    $data[] = $tel;
    $stmt->execute($data);

    //注文コード取得
    $sql = 'SELECT LAST_INSERT_ID()';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $lastcode = $rec['LAST_INSERT_ID()'];

    //商品明細のDBに項目追加
    for($i=0; $i<$max; $i++){
        $sql = 'INSERT INTO dat_sales_product(code_sales,code_product,price,quantity)VALUES(?,?,?,?)';
        $stmt = $dbh->prepare($sql);
        $data = array();
        $data[] = $lastcode;
        $data[] = $cart[$i];
        $data[] = $kakaku[$i];
        $data[] = $kazu[$i];
        $stmt->execute($data);
    }

    //注文データ追加処理後にロックを解除する
    $sql = 'UNLOCK TABLES';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    //DB切断
    $dbh = null;

    //会員登録希望だった場合に下記のメッセージを追記する
    if($chumon == 'chumontouroku'){
        echo '会員登録が完了致しました<br/>';
        echo '次回からメールアドレスとパスワードでログインしてください<br/>';
        echo 'ご注文が簡単にできるようになります<br/>';
        echo '<br/>';
    }
    $honbun.="送料は無料です。\n";
    $honbun.="---------------------------\n";
    $honbun.="\n";
    $honbun.="代金は以下の口座にお振込ください。\n";
    $honbun.="ろくまる銀行　やさい支店　普通口座　1234567。\n";
    $honbun.="入金確認が取れ次第、梱包、発送させていただきます。\n";
    $honbun.="\n";

    //会員登録希望だった場合に下記のメッセージを追記する
    if($chumon == 'chumontouroku'){
        $honbun.="会員登録が完了致しました\n";
        $honbun.="次回からメールアドレスとパスワードでログインしてください\n";
        $honbun.= "ご注文が簡単にできるようになります\n";
        $honbun.= "\n";
    }
    
    $honbun.="□ □ □ □ □ □ □ □ □ □ □ □ □ □ □\n";
    $honbun.="　〜　安心野菜のろくまる農園　〜　\n";
    $honbun.="\n";
    $honbun.="六丸県六丸郡六丸村 123-4\n";
    $honbun.="電話 090-6060-6060\n";
    $honbun.="メール info@rokumarunoen.co.jp\n";
    $honbun.="□ □ □ □ □ □ □ □ □ □ □ □ □ □ □\n";

    //ブラウザで本文出力テスト。ブラウザで\nを<br>として表すnl2br
    echo '<br>';
    echo nl2br($honbun);

    //メール送信のプログラム（お客様へ）
    $title = 'ご注文ありがとうございます'; //メールタイトル
    $header = 'From:info@rokumarunouen.co.jp';
    $honbun = html_entity_decode($honbun,ENT_QUOTES,'UTF-8');
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
    mb_send_mail($email,$title,$honbun,$header); //メール送信の命令文（$emailの箇所が送信先のEメールアドレス）

    //メール送信のプログラム（お店側へ）
    $title = 'お客様からご注文がありました';
    $header = 'From:'.$email;
    $honbun = html_entity_decode($honbun,ENT_QUOTES,'UTF-8');
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
    mb_send_mail('info@rokumarunouen.co.jp',$title,$honbun,$header);

}catch(Exception $e){
    echo 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}

?>
<br/>
<a href="shop_list.php">商品画面へ</a>
</body>
</html>