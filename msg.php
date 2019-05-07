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
$viewData = getMsgsAndBoard($m_id); //掲示板とメッセージ
debug('取得した掲示板とメッセージ情報:'.print_r($viewData, true));
debug('メッセージ情報:'.print_r($viewData[0]['board_id'], true));


//どちらが相手のユーザーIDなのか判定
$dealUserIds[] = $viewData[0]['sale_user'];
$dealUserIds[] = $viewData[0]['buy_user'];
//自分のユーザーIDを取り除く
//ここのdealUserIdsにはどっちのidも入っている
//自分のセッションIDとマッチしている方のdealUserIdsを削除
//dealUserIdsを削除しているので自分の$_SESSION['user_id]は削除していないことに注意
if(($key = array_search($_SESSION['user_id'], $dealUserIds)) !== false){
  unset($dealUserIds[$key]);
}

//パートナーのユーザーID
//残ってる方を取得する
$partnerUserId = array_shift($dealUserIds);
debug('取得した相手のユーザーID:'.print_r($partnerUserId, true));

//自分のユーザーID
$myUserId = $_SESSION['user_id'];

//パートナーの情報
//自分で自分の商品は買わないとするので、パートナーの情報=出品者という考えかたで良い
$partnerUserData = getUser($partnerUserId);
debug('パートナーのユーザー情報:'.print_r($partnerUserData, true));
if(empty($partnerUserData)){
  debug('エラー:パートナーの情報が取得できませんでした');
  header("Location:mypage.php");
}





//自分の情報
$myUserData = getUser($myUserId);
debug('自分のユーザー情報:'.print_r($myUserData, true));
if(empty($myUserData)){
  debug('エラー:自分の情報が取得できませんでした');
  header("Location:mypage.php");
}



//連絡掲示板に表示させる購入した(された)商品情報
$msgProductData = getProductOne($viewData[0]['product_id']);
debug('商品情報:'.print_r($msgProductData, true));


//連絡掲示板に表示させるその商品の出品者のユーザー情報が必要
//出品者のID
$saleUserId = $viewData[0]['sale_user'];
debug('出品者のID:'.print_r($saleUserId, true));

//出品者情報
$saleUserData = getUser($saleUserId);
debug('出品者の情報:'.print_r($saleUserData, true));

if(empty($saleUserData)){
  debug('この商品の出品者の情報が取得できませんでした');
  header("Location:mypage.php");
}






if(!empty($_POST)){
  debug('メッセージのpost送信があります');
  debug('POST情報:'.print_r($_POST, true));

  $msg = $_POST['msg'];



  validMsgLen($msg, 'msg');
   
  //0も入っているとみなすのでissetにしておくこと
  if(empty($err_msg['msg'])){
    debug('バリデーションokです');

    require('auth.php');

    try{
      debug('メッセージをDBに登録します');
      $dbh = dbConnect();
      //to_user from_user は送信者、受信者のユーザーid
      $sql = 'INSERT INTO message (board_id, send_date, to_user, from_user, msg, create_date) VALUES(:board_id, :send_date, :to_user, :from_user, :msg, :create_date)';
      $data = array(':board_id'=>$m_id, ':send_date'=>date('Y-m-d H:i:s'), ':to_user'=>$partnerUserId, ':from_user'=>$_SESSION['user_id'], ':msg'=>$msg, ':create_date'=>date('Y-m-d  H:i:s'));

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
           <!-- 商品と詳細データ -->
            <div class="msgProduct-container">
              <div class="user-data">
                <img class="msg-pic" src="<?php echo sanitize($saleUserData['pic']);  ?>" alt="">
                <div class="user-individual">
                  <!-- 自分で自分の出品した商品は買わない -->
                  <!-- 出品者 名前 -->
                  <?php echo sanitize($saleUserData['username']); ?><br>
                  <!-- 出品者住所 -->
                  <?php echo sanitize($saleUserData['addr']); ?><br>
                  <!-- 出品者の電話番号 -->
                  TEL:<?php echo sanitize($saleUserData['tel']);  ?><br>

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
            <!-- チャットメッセージは、DBの情報を出すのでそのまま保持される -->
            <!-- もし$viewDataにmsgがあった時......メッセージがDBに存在した時 -->
            <section class="msg-main">
              <div class="chat-container">
            
            <!-- もしメッセージがBDにあったら -->
             <?php if(!empty($viewData[0]['msg'])){ ?>
               <?php foreach($viewData as $key => $val){ ?>
               <!-- from_user to_userどっちでもいいが、それが$partnerUserIdだった時 -->
               <!-- つまりどっちかが$partnerUserIdとおなじ場合 　という状況を明示している -->
                 <?php if(!empty($val['from_user']) && $val['from_user'] == $partnerUserId){ ?>
                 <?php debug('from_user(送信元)がパートナーのIDです!'); ?>
                     <!-- 左メッセージ -->
                     <!-- パートナーの情報 -->
                      <div class="msg-left">
                        <img class="chat-img-left" src="<?php echo sanitize(showImg($partnerUserData['pic']));  ?>" alt=><br>
                        <div class="chat-date">
                          
                        </div>
                        <div class="chat-msg-left" style="float: left;">
                         <!-- 購入者のメッセージを表示 -->
                          <?php echo sanitize($val['msg']);  ?>
                        </div>
                      </div>


                 <?php }else{ ?>
                 <?php debug('msgのfrom_user(送信元)が今やり取りしているパートナーのIDではないです'); ?>
                        <!-- 右のメッセージ -->
                        <!-- $valのfrom_userが$_SESSION['user_id']の時 -->
                        <!-- 自分の情報 -->
                        <div class="msg-right">
                          <img class="chat-img-right" src="<?php echo sanitize(showImg($myUserData['pic'])); ?>"><br>
                          <div class="chat-date">
                           
                          </div>
                          
                          <div class="chat-msg-right" style="float: right;">
                            <?php echo sanitize($val['msg']);  ?>
                          </div>
                        </div>
             
                <?php } ?>
               <?php } ?>
                      
             <?php }else{ ?>
                    <div class="non-msg"><?php echo 'まだメッセージは投稿されていません' ?></div>
           <?php } ?>
                   
                      
              </div>
            </section>



            <form class="" action="" method="post">
              <textarea style="background: #A9A9A9;" name="msg" rows="8" cols="80"></textarea>0/180<br>
              <div class="msg-area">
                
              </div>
              <div class="btn-container">
                <input type="submit" name="" class="btn-msg btn-mid" value="送信!">
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
