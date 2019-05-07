<?php
require('function.php');

debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debug('ユーザー登録ページ');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debugLogStart();



// session_destroy();
// debug('セッション破棄しました');

if(!empty($_POST)){
debug('POST送信があります');
debug('POST情報：'.print_r($_POST, true));

  //POST変数
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];
  debug('メールじょうほう'.print_r($_POST['email']), true);

  //バリデーション
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');

  if(empty($err_msg)){
    validEmail($email, 'email');
    validEmailDup($email);

    if(empty($err_msg)){
      validMatch($pass, $pass_re, 'pass');
      validMinLen($pass, 'pass');
      validMaxLen($pass, 'pass');
    }

    if(empty($err_msg)){
      //例外処理でDB接続
      debug('バリデーションOKです');
      debug('DBに接続します');
      try{
        $dbh = dbConnect();

        debug('okです');

        $sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';

        $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                        ':login_time'=>date('Y-m-d H:i:s'),
                        ':create_date'=> date('Y-m-d H:i:s'));
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
          debug('クエリ成功したのでセッション変数の中に値を保存します');

          // $_SESSION['user_id'] =;
          $_SESSION['login_date'] = time();
          $sesLimit = 60*60;
          $_SESSION['login_limit'] =  $sesLimit;
          $_SESSION['user_id'] = $dbh->lastInsertID();
          debug('セッション変数の中身:'.print_r($_SESSION, true));
          debug('マイページへ遷移します');
          header("Location:mypage.php");
        }

      }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common'] = MSG07;
      }
    }
  }
}


?>



<?php
 $siteTitle = 'ユーザー登録';
 require('head.php');
 ?>

  <body class="page-top page-1colum">
    <?php
     require('header.php');
     ?>


    <div class="site-width" id="contents">
      <div id="main">
        <div class="form-container">
          <form class="form" action="" method="post">
            <div class="form-title">SignUp!</div>
            <div class="msg-area">
              <?php if(!empty($err_msg['common'])){ echo $err_msg['common'];} ?>
            </div>

            <div class="js-form-group">
              <label>Email:<span class="js-help-block"></span>
               <input class="js-valid-email" type="text" name="email" value="">
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

           <div class="js-form-group">
             <label>Password_Retype:<span class="js-help-block"></span>
              <input type="text" name="pass_re" value="">
             </label> 
           </div>
            <div class="msg-area">
              <?php if(!empty($err_msg['pass_re'])){ echo $err_msg['pass_re'];} ?>
            </div>

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
