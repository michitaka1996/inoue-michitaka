<?php
require('function.php');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debug('アカウント登録ページ');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debugLogStart();
require('auth.php');

//必要な情報を集める
//DBからユーザー情報を取得
//POSTされた情報とBDからの情報をくらべる


//ユーザーIDを元に,ユーザー情報をとってくる
$dbFormData = (!empty($_SESSION['user_id'])) ? getUser($_SESSION['user_id']) : '';
debug('取得したユーザーデータ：'.print_r($dbFormData, true));

// [id] => 8
//     [username] => えええ
//     [email] => michirug11@i.softbank.jp
//     [tel] => 09030593821
//     [addr] => 大阪府札幌市目黒区西日暮里4丁目43
//     [age] => 6
//     [password] => $2y$10$iNHmYS7863S/hc8kfw4uEutJVmo1OxJlldyr34vQjhPD55fpih.3u
//     [login_time] => 2019-03-29 12:28:45
//     [pic] => uploads/ee99518633a62fcd56e4f49536aa2558cf8fb7cd.jpeg
//     [delete_flg] => 0
//     [create_date] => 2019-03-29 12:28:45
//     [update_date] => 2019-03-29 21:28:45
// )


if(!empty($_POST)){
  debug('POST情報があります');
  debug('POSTの中身：'.print_r($_POST, true));
  debug('FILEの中身:'.print_r($_FILES, true));//FILE情報の中身を確認することが大事
  $name = $_POST['name'];
  $addr = $_POST['addr'];
  $age = $_POST['age'];
  $tel = $_POST['tel'];
  $email = $_POST['email'];
  //アップロードした画像を格納
  $pic = (!empty($_FILES['pic']['name']))? uploadImg($_FILES['pic'], 'pic') : '';
  // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
  //画像は$dbFormDataから保持させておいて、他のフォームだけチェンジさせた場合、空でpostしたことになって、次回プロフ編集に入った場合、画像が表示されてない
  $pic = (empty($pic) && !empty($dbFormData['pic']))? $dbFormData['pic'] : $pic;
  debug('画像ファイルの中身:'.print_r($pic, true));

  if(empty($dbFormData)){
    //アカウント登録のバリデーションチェック
    validRequired($name, 'name');
    validName($name, 'name');
    if(empty($err_msg)){
      validAddr($addr, 'addr');
      validRequired($tel, 'tel');
      validTel($tel, 'tel');
    }
    if(empty($err_msg)){
      validRequired($email, 'email');
      validEmailDup($email);
    }
  }else{
    //アカウント編集用のバリデーションチェック
    if($dbFormData['username'] !== $name){
      validRequired($name, 'name');
      validName($name, 'name');
    }
    if($dbFormData['tel'] !== $tel){
      validRequired($tel, 'tel');
      validTel($tel, 'tel');
    }
    if($dbFormData['email'] !== $email){
      validRequired($email, 'email');
      validEmailDup($email);
    }
  }

  if(empty($err_msg)){
    debug('バリデーションokです');
    try{
      $dbh = dbConnect();

      if(empty($dbFormData)){
        debug('プロフィール登録です');
        $sql = 'INSERT INTO users (username, addr, age, tel, email, pic) VALUES(:username, :addr, :age, :tel, :email, :pic)';
        $data = array(':username', ':addr', ':age', ':tel', ':email', ':pic');
        $stmt = queryPost($dbh, $sql, $data);
      }else{
        debug('プロフィール編集です');
        $sql = 'UPDATE users SET username=:name, addr=:addr, age=:age, tel=:tel, email=:email, pic=:pic WHERE id=:u_id AND delete_flg=0';
        $data = array(':name'=>$name, ':addr'=>$addr, ':age'=>$age, ':tel'=>$tel, ':email'=>$email, ':pic'=>$pic, ':u_id'=>$_SESSION['user_id']);
        $stmt = queryPost($dbh, $sql, $data);
      }

      if($stmt){
        debug('アカウント情報をアップデートしたのでマイページへ遷移します');
        header("Location:mypage.php");
      }
    }catch(Exception $e){
      error_log('エラー発生：'.$e->getMessage());
    }
  }
}

debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理終了');
?>




<?php
 $siteTitle = 'マイページ';
 require('head.php');
 ?>

  <body class="page-profeidt page-1colum">
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

               <!-- フォームの入力保持はdbがpostかどうか -->
               <form class="form" action="" method="post" enctype="multipart/form-data">
                 <div class="form-title">アカウント編集</div>

                 <div class="js-form-group">
                  <label>名前:<span class="help-block"></span>
                   <input class="js-valid-username" type="text" name="name" value="<?php echo getFormData('username', true); ?>">
                  </label>
                  <div class="msg-area">
                   <?php if(!empty($err_msg['name'])){echo $err_msg['name'];} ?>
                  </div>
                 </div>
                 
                
                <div class="js-form-group">
                 <label>住所:<span class="help-block"></span>
                   <input class="js-valid-addr" type="text" name="addr" value="<?php echo getFormData('addr', true); ?>">
                 </label>
                 <div class="msg-area"><?php if(!empty($err_msg['addr'])) echo $err_msg['addr']; ?></div>
                </div>
                  

                <div class="js-form-group">
                 <label>年齢:
                  <input type="number" style="width:150px; margin-right:100%;" name="age" value="<?php echo getFormData('age', true); ?>">
                 </label>
                </div>
                 
                <div class="js-form-group">
                  <label>電話:<span class="help-block"></span>
                   <input type="text" name="tel" value="<?php if(!empty($dbFormData['tel'])){echo $dbFormData['tel'];} ?>">
                 </label>
                 <div class="msg-area">
                   <?php if(!empty($err_msg['tel'])){echo $err_msg['tel'];} ?>
                 </div>
                </div>
                 
                 
                
                <div class="js-form-group">
                   <label>Email:<span class="help-block"></span>
                   <input type="text" name="email" value="<?php echo getFormData('email', true); ?>">
                 </label>
                </div>
                
                 <!-- プロフ画像 -->
                 <div class="imgDrop-container">
                   <label class="area-drop">
                     <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                     <input type="file" class="input-file" name="pic" value="" style="height:450px;">
                     <img src="<?php echo $dbFormData['pic']; ?>" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo "display:none;" ?>max-height:450px;" alt="">
                   </label>
                 </div>
                 <div class="btn-container">
                   <input type="submit" class="btn btn-mid" name="" value="登録">
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
