<?php
//ログインするにはセッションにlogin_date,login_limit,user_id(ログイン日時、ログイン有効期限、ユーザーID)としての情報を格納したい
//重要な情報なのでcookieではなくsessionが最適である


require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('ログイン');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//すでにログインしているユーザーかどうかチェック
require('auth.php');


if(!empty($_POST)){
  debug('POST情報があります');
  debug('POST情報:'.print_r($_POST, true));

  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_save = (!empty($_POST['pass_save']))? true : false;

  validRequired($email, 'email');
  validRequired($pass, 'pass');

  if(empty($err_msg)){
    debug('バリデーションokです');
    try{
      $dbh = dbConnect();
      //sessionに保存させるためにpassだけでなくidも取得
      $sql = 'SELECT password,id FROM users WHERE email=:email AND delete_flg=0';
      $data = array(':email'=>$email);
      $stmt = queryPost($dbh, $sql, $data);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      debug('クエリ結果の中身；'.print_r($result, true));

      //今回、passとid２つ取得しているので先頭の方を取得
      if(password_verify($pass, $result['password'])){
        debug('パスワードの照合がマッチしました');
        //デフォルトのセッション保持は1時間
        $sesLimit = 60*60;

        if($pass_save){
          debug('ログイン保持にチェックがあるのでログイン有効期限を30日にします');
          $_SESSION['login_limit'] = $sesLimit*24*30;
        }else{
          debug('ログイン保持にチェックはありません');
          $_SESSION['login_limit'] = $sesLimit;
        }
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['login_date'] = time();
        debug('セッション変数へ入れた中身：'.print_r($_SESSION,true));
        //マイページへ遷移
        debug('マイページへ遷移します');
        header("Location:mypage.php");

      }else {
        debug('パスワードの照合に失敗しました');
        $err_msg['common'] = MSG09;
      }
    }catch(Exception $e){
      error_log('エラー発生：'.$e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>　画面表示処理終了');

?>



<?php
 $siteTitle = 'ログイン';
 require('head.php');
 ?>

  <body class="page-top page-1colum">
    <p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionOnce('msg_success'); ?>
    </p>
    <?php
     require('header.php');
     ?>


     <div class="site-width" id="contents">
       <!--　簡易のボタン -->
       <!-- <a class="form-btn" href="mypage.php"><button type="button" name="button">押すとログイン</button><a> -->
       <div id="main">
         <div class="form-container">

           <form class="form" action="" method="post">
             <div class="form-title">Member Login</div>
             <div class="msg-area">
               <?php if(!empty($err_msg['common'])){ echo $err_msg['common'];} ?>
             </div>

             <div class="js-form-group">
               <label>Email:<span class="js-help-block"></span>
                <input class="js-valid-email" type="text" name="email" placeholder="">
               </label>
             </div>
             <div class="msg-area">
               <?php if(!empty($err_msg['email'])){ echo $err_msg['email'];} ?>
             </div>
            
             <div class="js-form-group">
               <label>Password:<span class="js-help-block"></span>
                <input class="js-valid-pass" type="text" name="pass" value="">
               </label>
             </div>
             <div class="msg-area">
               <?php if(!empty($err_msg['pass'])){ echo $err_msg['pass'];} ?>
             </div>

             <label>
               次回ログインを省略する
               <input type="checkbox" name="pass_save"><br>
               <a href="login-btn.php">ゲームで仮ログインをする</a>
             </label>

             <div class="btn-container">
               <input type="submit" name="" value="GO!" class="btn btn-min">
             </div>
           </form>
         </div>
       </div>


     </div>

    <?php
     require('footer.php');
     ?>
  </body>
</html>
