<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>
<?php

//入力値受け取り
// $staff_code = $_POST['code'];
// $staff_name = $_POST['name'];
// $staff_pass = $_POST['pass'];
// $staff_pass2 = $_POST['pass2'];

//セキュリティー対策（サニタイジング　←　入力データに）
// $staff_name = htmlspecialchars($staff_name);
// $staff_pass = htmlspecialchars($staff_pass);
// $staff_pass2 = htmlspecialchars($staff_pass2);


require_once('../common/common.php');

//入力値受け取り
$post=sanitize($_POST);
//サニタイジング
$staff_code = $post['code'];
$staff_name = $post['name'];
$staff_pass = $post['pass'];
$staff_pass2 = $post['pass2'];

//もしスタッフ名が入力されていなかったら
if($staff_name == ''){
    echo 'スタッフ名が入力されていません <br/>';  
}else{
    echo 'スタッフ名：';
    echo $staff_name;
    echo '<br>';
}

//もしパスワードが入力されていなかったら
if($staff_pass == ''){
    echo 'パスワードが入力されていません<br>';
}

//再入力パスワードと一致しなかったら
if($staff_pass != $staff_pass2){
    echo 'パスワードが一致しません。<br>';
}

//もし入力に問題があったら「戻る」ボタンだけを表示する
if($staff_name == '' || $staff_pass == '' || $staff_pass != $staff_pass){
    echo '<form>';
    echo '<input type = "button" onclick = "history.back()" value = "戻る">';
    echo '</form>';
//入力に問題がない場合    
}else{
    //文字列(パスワード）をハッシュ化　←　パスワードを見えないようにする
    $staff_pass = md5($staff_pass);
    //問題がない場合のフォーム
    echo '<form method = "post" action = "staff_edit_done.php">';
    echo '<input type = "hidden" name = "code" value ="'.$staff_code.'">';
    echo '<input type ="hidden" name = "name" value = "'.$staff_name.'">';
    echo '<input type ="hidden" name = "pass" value ="'.$staff_pass.'">';
    echo '<br/>';
    echo '<input type = "button" onclick = "history.back()" value = "戻る">';
    echo '<input type = "submit" value = "OK">';
    echo '</form>';
}

?>
</body>
</html>	