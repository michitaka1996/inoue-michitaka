<?php
//パスワード変更機能
require('function.php');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debug('パスワード変更ページ');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debugLogStart();

ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');

//ログインしてるかどうかチェック
require('auth.php');

//古いパスワードがDBのものと合っているかどうかのためにユーザー情報を取得
$dbFormData = '';
$dbFormData = (!empty($_SESSION['user_id']))? getUser($_SESSION['user_id']) : '';
debug('取得したユーザ情報：'.print_r($dbFormData, true));

//POSTがあったら
if(!empty($_POST)){
  //DBの中のパスワード
  $db_pass = $dbFormData['password'];
  $pass = $_POST['pass'];//1
  $pass_new = $_POST['pass_new'];//2
  $pass_renew = $_POST['pass_renew'];//3

  //バリデーションチェック
  //パス1はあらかじめフォームに出しておかない
  //パス1の形式があってるか　半角英数字のみ、長さが適正か(10時以内で)
  //パス1はdbのデータとあってるか password_verifyを使う
  //パス2とパス3があってるか
  //パス2は形式があってるか、半角英数　長さ

  //パス１について
  validRequired($pass, 'pass');
  validHalf($pass, 'pass');
  validMinLen($pass, 'pass', 6);
  validMaxLen($pass, 'pass', 15);
  if(empty($err_msg)){
    if(!password_verify($pass, $db_pass)){
      $err_msg['pass'] = MSG12;
    }
  }
  if(empty($err_msg)){
    //パス２について
    validRequired($pass_new, 'pass_new');
    validHalf($pass_new, 'pass_new');
    validMinLen($pass_new, 'pass_new');
    validMaxLen($pass_renew, 'pass_renew');
  }
  if(empty($err_msg)){
    //パス３について
    validRequired($pass_renew, 'pass_renew');
    validMatch($pass_new, $pass_renew, 'pass_new');
  }
  if(empty($err_msg)){
    debug('バリデーションokです');
    try{
      $dbh = dbConnect();
      $sql = 'UPDATE users SET password=:pass WHERE id=:u_id AND delete_flg=0';
      $data = array(':pass'=>password_hash($pass_new, PASSWORD_DEFAULT), ':u_id'=>$_SESSION['user_id']);
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        debug('クエリ成功したのでマイページへ遷移します');
        header("location:mypage.php");
      }
    }catch(Exception $e){
      error_log('エラー発生:'.$e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}

debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理終了');
?>



<!-- html部分 -->
<?php
 $siteTitle = 'パスワードの変更';
 require('head.php');
 ?>

  <body class="page-top page-1colum">
    <?php
     require('header.php');
     ?>


     <div class="site-width" id="contents">
       <section class="main-container">

           <?php
            require('mainhead.php');
            ?>
            <div id="main">
              <div class="form-container">
                <form class="form" action="" method="post">
                  <div class="form-title">パスワードの変更</div>
                  <div class="msg-area">
                    <?php if(!empty($err_msg['common'])){echo $err_msg['common'];} ?>
                  </div>

                  <label>古いパスワード
                    <input type="text" name="pass" value="">
                  </label>
                  <div class="msg-area">
                    <?php if(!empty($err_msg['pass'])){echo $err_msg['pass'];} ?>
                  </div>

                  <label>新しいパスワード
                    <input type="text" name="pass_new" value="" placeholder="20文字以内で入力してください">
                  </label>
                  <div class="msg-area">
                    <?php if(!empty($err_msg['pass_new'])){echo $err_msg['pass_new'];} ?>
                  </div>

                  <label>新しいパスワード(再入力)
                    <input type="text" name="pass_renew" value="">
                  </label>
                  <div class="msg-area">
                    <?php if(!empty($err_msg['pass_renew'])){echo $err_msg['pass_renew'];} ?>
                  </div>

                  <div class="btn-container">
                    <input type="submit" class="btn btn-mid" name="" value="変更する">
                  </div>

                </form>
              </div>
            </div>

       </section>


     </div>

    <?php
     require('footer.php');
     ?>
  </body>
</html>
