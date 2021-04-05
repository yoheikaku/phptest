<!-- 会員登録チェック画面 -->
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>
<?php
    //サーバー接続のためのtry,catch
    try{
        require_once('../common/common.php');

        //入力値受け取り
        $post=sanitize($_POST);
        //サニタイジング
        $member_email = $post['email'];
        $member_pass = $post['pass'];

        //パスワードをハッシュ化
        $member_pass = md5($member_pass);

        //DB接続
        $dsn = 'mysql:dbname=shop;host=localhost';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn,$user,$password);
        $dbh->query('SET NAMES utf8');

        //SQL文準備(会員コードと名前を取り出す)
        $sql = 'SELECT code,name FROM dat_member WHERE email=? AND password=?';
        $stmt = $dbh->prepare($sql);
        $data[]=$member_email;
        $data[]=$member_pass;
        //SQL文実行
        $stmt->execute($data);

        //DB切断
        $dbh = null;

        //レコード取り出し
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        //認証判定
        if($rec==false){
            echo 'メールアドレスかパスワードが間違っています。<br/>';
            echo '<a href="member_login.html">戻る</a>';
        }else{
            //合言葉を決める（自動）
            session_start();
            //メールアドレスとパスワードでログインしたときに’member_login’に１を入れて認証ができたことになる。会員コードと、会員名をセッションに入れておく
            $_SESSION['member_login'] = 1;
            $_SESSION['member_code'] = $rec['code'];
            $_SESSION['member_name'] = $rec['name'];
            //認証成功時に次のページへ遷移
            header('Location:shop_list.php');
        }
    }catch(Exception $e){
        echo 'ただいま障害により大変ご迷惑をお掛けしております';
        exit();
    }

    ?>

</body>
</html>