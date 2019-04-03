<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('商品連絡掲示板ページ');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debugLogStart();

require('auth.php');

$m_id = (!empty($_GET['m_id'])) ? $_GET['m_id'] : ''; // GETパラメータがあるかどうかチェック 掲示板id
debug('受け渡した掲示板情報:'.print_r($m_id, true));

//BDからデータ取得
//　掲示板とメッセージ, 商品情報,出品者情報 購入社情報, 購入者の掲示板の視点、出品者の掲示板の視点 、両者のプロフ画像
// boardとmessageテーブルが必要である
// 
$viewData = getMsgsAndBoard($m_id); //掲示板とメッセージ
debug('取得した掲示板とメッセージ情報:'.print_r($viewData, true));

// debug('出品者のID:'.print_r($viewData[0]['sale_user'], true));

$saleUserId = $viewData[0]['sale_user'];
debug('出品者のID:'.print_r($saleUserId, true));
$buyUserId = $viewData[0]['buy_user'];
debug('購入者のID:'.print_r($buyUserId, true));

//出品者の情報
$saleUserData = getUser($saleUserId);
debug('出品者のユーザー情報:'.print_r($saleUserData, true));

if(empty($saleUserData)){
  debug('エラー:出品者の情報が取得できませんでした');
  header("Location:mypage.php");
}

//購入者の情報
$buyUserData = getUser($buyUserId);
debug('購入者のユーザー情報:'.print_r($buyUserData, true));

if(empty($buyUserData)){
  debug('エラー:購入者の情報が取得できませんでした');
  header("Location:mypage.php");
}


$msgProductData = getProductOne($viewData[0]['product_id']);
debug('商品情報:'.print_r($msgProductData, true));


if(!empty($_POST['submit'])){
  debug('メッセージのpost送信があります');
  debug('POST情報:'.print_r($_POST, true));

  $msg = $_POST['msg'];

  validMsgLen($msg, 'msg');

  if(empty($err_msg['msg'])){
    debug('バリデーションokです');

    require('auth.php');

    try{
      debug('メッセージをDBに登録します');
      $dbh = dbConnect();
      //to_user from_user は送信者、受信者のユーザーid
      $sql = 'INSERT INTO message (board_id, send_date, to_user, from_user, msg, create_date) VALUES(:board_id, :send_date, :to_user, :from_user, :msg, :create_date)';
      $data = array(':board_id'=>$m_id, ':send_date'=>date('Y-m-d H:i:s'), ':to_user'=>$viewData[0]['sale_user'], ':from_user'=>$viewData[0]['buy_user'], ':msg'=>$msg, ':create_date'=>date('Y-m-d  H:i:s'));

      debug('流し込みデータ:'.print_r($data, true));
      $stmt = queryPost($dbh, $sql, $data);

      if($stmt){
        debug('メッセージを送信できました。自画面へ遷移します');
        header("Location:".$_SERVER['PHP_SELF'].'?m_id='.$m_id);
      }
    }catch(Exception $e){
      error_log('エラー発生:'.$e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理終了');
?>






<?php
 $siteTitle = 'マイページ';
 require('head.php');
 ?>

  <body class="page-top page-2colum">
    <p id="js-show-msg" style="display:none;" class="msg-slide">
    <?php echo getSessionOnce('msg_success'); ?></p>
    <?php
     require('header.php');
     ?>


    <div class="site-width" id="contents">
      <section class="main-container">

        <?php
         require('mainhead.php');
         ?>
         <div class="msg-area">
           <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
         </div>

         <section class="msg-container">
            <div class="msgProduct-container">
              <div class="user-data">
                <img class="msg-pic" src="<?php echo showImg($saleUserData['pic']); ?>" alt="">
                <div class="user-individual">
                  <?php echo $saleUserData['username']; ?><br>
                  <?php echo $saleUserData['addr']; ?><br>
                  TEL:<?php echo $saleUserData['tel']; ?><br>

                </div>
              </div>
              <div class="board-product">
                <img class="msgProduct-pic" src="<?php echo showImg($msgProductData['pic1']); ?>" alt="">
                <div class="product-individual">
                  <?php echo $msgProductData['name']; ?><br>
                  取引金額:<?php echo $msgProductData['price']; ?><br>
                  取引日時:<?php echo $viewData[0]['create_date']; ?>
                </div>
              </div>
            </div>



            <!-- チャットメッセージ -->
            <section class="msg-main">
              <div class="chat-container">

                <?php if(!empty($viewData)): ?>

                    <?php if(!empty($viewData[0]['msg'] && $_SESSION['user_id'] == $buyUserId)): ?>
                     <?php foreach ($viewData as $key => $value): ?>
                      <div class="msg-left">
                        <img class="chat-img-left" src="<?php echo $buyUserData['pic']; ?>" alt=""><br>
                        <div class="chat-date">
                          <?php  ?>
                        </div>
                        <div class="chat-msg-left" style="float: left;">
                          <?php echo (!empty($msg))? $msg : $viewData[0]['msg']; ?>
                        </div>
                      </div>
                     <?php endforeach ?>
                    <?php endif ?>

                    <?php if(!empty($viewData[0]['msg'] && $_SESSION['user_id'] == $saleUserId)):  ?>
                      <?php foreach($viewData as $key => $value): ?>
                        <div class="msg-right">
                          <img class="chat-img-right" src="<?php echo $saleUserData['pic']; ?>"><br>
                          <div class="chat-date">
                            <?php ?>
                          </div>
                          <div class="chat-msg-right" style="float: right;">
                            <?php echo (!empty($msg))? $msg : $viewData[0]['msg']; ?>
                          </div>
                       </div>
                      <?php endforeach ?>               
                    <?php endif ?>

                <?php endif ?>
                
              </div>
            </section>



            <form class="" action="" method="post">
              <textarea style="background: #A9A9A9;" name="msg" rows="8" cols="80"></textarea>0/180<br>
              <div class="msg-area">
                <?php if(!empty($err_msg['msg'])) echo $err_msg['msg']; ?>
              </div>
              <div class="btn-container">
                <input type="submit" name="submit" class="btn-msg btn-mid" value="送信!">
              </div>
            </form>
         </section>



      </section>
    </div>

    <?php
     require('footer.php');
     ?>
  </body>
</html>
