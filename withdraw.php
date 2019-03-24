<?php
//userテーブルのdelete_flg=0からdelete_flg=1に書き換える
//delete_flg=0のものだけsqlからデータを流しこんだり、表示させれば、1は削除されたとみなす

//削除するのは、user,product,likeなのでsql分は３つ必要である

 require('function.php');
 debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
 debug('退会ページ');
 debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
 debugLogStart();

 require('auth.php');

 if(!empty($_POST['withdraw'])){
   debug('POST送信があります');


   //アップデートするだけ
   try{
     $dbh = dbConnect();
     $sql1 = 'UPDATE users SET delete_flg=1 WHERE id=:u_id';
     $sql2 = 'UPDATE product SET delete_flg=1 WHERE id=:u_id';
     $sql3 = 'UPDATE like SET delete_flg=1 WHERE id=:u_id';
     $data = array(':u_id'=> $_SESSION['user_id']);
     $stmt1 = queryPost($dbh, $sql1, $data);
     $stmt2 = queryPost($dbh, $sql2, $data);
     $stmt3 = queryPost($dbh, $sql3, $data);
     if($stmt1){
       debug('クエリが成功したのでセッションを破棄します');
       session_destroy();
       debug('ユーザー登録画面へ遷移します');
       header("Location:signup.php");
     }else{
       debug('クエリに失敗しました');
       $err_msg['common'] = MSG10;
     }
   }catch(Exception $e){
     error_log('エラー発生：'.$e->getMessage());
     $err_msg['common'] = MSG07;
   }
 }
 debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理終了');

 ?>




 <?php
  $siteTitle = '退会する';
  require('head.php');
  ?>

   <body class="page-top page-1colum">
     <?php
      require('header.php');
      ?>


      <div class="site-width" id="contents">
          <form class="form" action="" method="post">
            <div class="msg-area">
              <?php if(!empty($err_msg['common'])){$err_msg['common'];} ?>
            </div>
            <input type="submit" name="withdraw" class="btn btn-mid" value="退会する">
          </form>
      </div>

     <?php
      require('footer.php');
      ?>
   </body>
 </html>
