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
        //スタッフコードとパスワードを受け取る
        // $staff_code = $_POST['code'];
        // $staff_pass = $_POST['pass'];

        //パラメータセキュリティ対策
        // $staff_code = htmlspecialchars($staff_code);
        // $staff_pass = htmlspecialchars($staff_pass);

        require_once('../common/common.php');

        //入力値受け取り
        $post=sanitize($_POST);
        //サニタイジング
        $staff_code = $post['code'];
        $staff_pass = $post['pass'];

        //パスワードをハッシュ化
        $staff_pass = md5($staff_pass);

        //DB接続
        $dsn = 'mysql:dbname=shop;host=localhost';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn,$user,$password);
        $dbh->query('SET NAMES utf8');

        //SQL文準備
        $sql = 'SELECT name FROM mst_staff WHERE code=? AND password=?';
        $stmt = $dbh->prepare($sql);
        $data[]=$staff_code;
        $data[]=$staff_pass;
        //SQL文実行
        $stmt->execute($data);

        //DB切断
        $dbh = null;

        //レコード取り出し
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        //認証判定
        if($rec==false){
            echo 'スタッフコードかパスワードが間違っています。<br/>';
            echo '<a href="staff_login.html">戻る</a>';
        }else{
            //合言葉を決める（自動）
            session_start();
            //パスワードとスタッフコードでログインしたときに’login’に１を入れて認証ができたことになる。スタッフコードと、スタッフ名をセッションに入れておく
            $_SESSION['login'] = 1;
            $_SESSION['staff_code'] = $staff_code;
            $_SESSION['staff_name'] = $rec['name'];
            //認証成功時に次のページへ遷移
            header('Location:staff_top.php');
        }
    }catch(Exception $e){
        echo 'ただいま障害により大変ご迷惑をお掛けしております';
        exit();
    }

    ?>

</body>
</html>