<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>

<?php
//ログイン認証
session_start();
//sessionの合言葉（ここでは１）の合言葉をセキュリティ対策の為自動で変更する
session_regenerate_id(true);
//ログインOKの認証がなかったら
if(isset($_SESSION['login'])==false){
    echo 'ログインされていません。<br/>';
    echo '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
    //プログラムを終了
    exit();
}
//issetでsubmitの種類を判断する
if(isset($_POST['edit']) == true){
    //もし商品が選択されていなかったら商品が選択されていないというページにとばす //rerurnは追記
    if(isset($_POST['procode'])==false){
       return header('Location:pro_ng.php');
    }
    //修正用の画面へ飛ばす（header('Location:~~~')の命令）,GETパラメータで商品コードの値を飛ばす
    //商品コードの値を受け取る
    $pro_code = $_POST['procode'];
    //次に飛ぶURLにスタッフコードを貼っつける（GETパラメータ）
    header('Location:pro_edit.php?procode='.$pro_code);
}

if(isset($_POST['delete']) == true){
     //もし商品が選択されていなかったら商品が選択されていないというページにとばす
     if(isset($_POST['procode']) == false){
        return header('Location:pro_ng.php');
    }
    //削除用の画面へ飛ばす（header('Location:~~~')の命令）,GETパラメータでスタッフコードの値を飛ばす
    //商品コードの値を受け取る
    $pro_code = $_POST['procode'];
    //次に飛ぶURLにスタッフコードを貼っつける（GETパラメータ）
    header('Location:pro_delete.php?procode='.$pro_code);
}

//スタッフ追加ページへ
if(isset($_POST['add']) == true){
    header('Location:pro_add.php');
}

//スタッフ参照ページへ
if(isset($_POST['disp']) == true){
    if(isset($_POST['procode']) == false){
        return header('Location:pro_ng.php');
    }
    $pro_code = $_POST['procode'];
    header('Location:pro_disp.php?procode='.$pro_code);
}


?>
</body>
</html>