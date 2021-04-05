<!-- 数変更のためのファイル -->
<?php
    // セッション開始
    session_start();
    session_regenerate_id(true);

    //パラメータをサニタイジング
    require_once('../common/common.php');
    $post = sanitize($_POST);

    //不明（変数maxにpostで送信されてきたデータの数だけ入る？）後で出力
    $max = $post['max'];

    for($i =0; $i < $max; $i++){
        //数量変更のパラメータが数値であるかどうかのチェック
        if(preg_match("/^[0-9]+$/",$post['kazu'.$i])==0){
            echo '数量に誤りがあります';
            echo '<br/>';
            echo '<br/>';
            echo '<a href="shop_cartlook.php">カートに戻る</a>';
            exit();
        }
        //数量の範囲チェック
        if($post['kazu'.$i]<1||10<$post['kazu'.$i]){
            echo '数量は必ず１個以上、１０個までです。';
            echo '<a href="shop_cartlook.php">カートに戻る</a>';
            echo '<br/>';
            echo '<br/>';
            exit();
        }
        //$kazuの配列変数に商品のどの数量を変更したかのデータを詰める($iが何番目の数量のデータを変更したかを表す)
        $kazu[] = $post['kazu'.$i];
    }
    //セッションのカート情報を呼び出す
    $cart = $_SESSION['cart'];
    //ループを逆回しカウントで配列番号を指定してカート商品データを削除する
    for($i = $max; 0<=$i; $i--){
        if(isset($_POST['sakujo'.$i])==true){
            //削除の命令文
            array_splice($cart,$i,1);
            array_splice($kazu,$i,1);
        }
    }

    //処理結果をセッションに詰める
    $_SESSION['cart'] = $cart;
    $_SESSION['kazu'] = $kazu;

    //数量変更ページへ遷移する
     return header('Location:shop_cartlook.php');

?>    

