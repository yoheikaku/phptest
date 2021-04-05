<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>
    <?php
    //お客様情報入力画面からのパラメータをサニタイジング
    require_once('../common/common.php');
    $post=sanitize($_POST); 
    //パラメータを変数に代入
    $onamae = $post['onamae'];
    $email = $post['email'];
    $postal1 = $post['postal1'];
    $postal2 = $post['postal2'];
    $address = $post['address'];
    $tel = $post['tel'];
    //会員登録希望かどうかの値
    $chumon = $post['chumon'];
    //会員登録だった場合の値
    $pass = $post['pass'];
    $pass2 = $post['pass2'];
    $danjo = $post['danjo'];
    $birth = $post['birth'];
    //入力値チェック
    $okflg = true;
    //名前チェック
    if($onamae==''){
        echo 'お名前が入力されていません。<br/><br/>';
        $okflg = false;
    }else{
        echo 'お名前<br/>';
        echo $onamae;
        echo '<br/><br/>';
    }
    //メールアドレス正規表現チェック
    if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',$email)==0){
        echo 'メールアドレスを正確に入力してください。<br/><br/>';
        $okflg = false;
    }else{
        echo 'メールアドレス<br/>';
        echo $email;
        echo '<br/><br/>';
    }
    //郵便番号正規表現チェック(左)
    if(preg_match('/^[0-9]+$/',$postal1)==0){
        echo '郵便番号は半角数字で入力してください<br/><br/>';
        $okflg = false;
    }else{
        echo '郵便番号<br/>';
        echo $postal1;
        echo '-';
        echo $postal2;
        echo '<br/><br/>';
    }
    //郵便番号正規表現チェック（右）
    if(preg_match('/^[0-9]+$/',$postal2)==0){
        echo '郵便番号は半角数字で入力してください<br/><br/>';
        $okflg = false;
    }
    //住所チェック
    if($address==''){
        echo '住所が入力されていません<br/><br/>';
        $okflg = false;
    }else{
        echo '住所<br/>';
        echo $address;
        echo '<br/><br/>';
    }
    //電話番号正規表現チェック
    if(preg_match('/^\d{2,5}-?\d{2,5}-?\d{4,5}$/',$tel)==0){
        echo '電話番号を正確に入力してください';
        $okflg = false;
    }else{
        echo '電話番号<br/>';
        echo $tel;
        echo '<br/><br/>';
    }
    //会員登録の場合の入力チェック
    if($chumon=='chumontouroku'){
        if($pass==''){
            echo 'パスワードが入力されていません';
            $okflg=false;
        
        }
        if($pass!=$pass2){
            echo 'パスワードが一致しません';
            $okflg=false;
           
        }
        echo '性別<br/>';
        if($danjo=='dan'){
            echo '男性';
        }else{
            echo '女性';
        }
        echo '<br/><br/>';

        echo '生まれ年<br/>';
        echo $birth;
        echo '年代';
        echo '<br/><br/>';
    }

    if($okflg==true){
        //OKボタンが押された時に入力値を次の画面へ渡す
        echo '<form method="post" action="shop_form_done.php">';
        echo '<input type="hidden" name="onamae" value="'.$onamae.'">';
        echo '<input type="hidden" name="email" value="'.$email.'">';
        echo '<input type="hidden" name="postal1" value="'.$postal1.'">';
        echo '<input type="hidden" name="postal2" value="'.$postal2.'">';
        echo '<input type="hidden" name="address" value="'.$address.'">';
        echo '<input type="hidden" name="tel" value="'.$tel.'">';
        //会員登録の場合の値を遷移先に渡す
        echo '<input type="hidden" name="chumon" value="'.$chumon.'">';
        echo '<input type="hidden" name="pass" value="'.$pass.'">';
        echo '<input type="hidden" name="danjo" value="'.$danjo.'">';
        echo '<input type="hidden" name="birth" value="'.$birth.'">';
        echo '<input type="button" onclick="history.back()" value="戻る">';
        echo '<input type="submit" value="OK"><br/>';
        echo '</form>';
    }else{
        echo '<form>';
        echo '<input type="button" onclick="history.back()" value="戻る">';
        echo '</form>';
    }
    ?>
</body>
</html>