<?php

require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('マイページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//すでにログインしているユーザーかどうかチェック
require('auth.php');

//================================
// スライドの表示
//================================
debug('スライドを表示させます');
$_SESSION['msg_success'] = SUC01;


$u_id = (!empty($_SESSION['user_id']))? $_SESSION['user_id'] : '';


$myData = getUser($u_id);
debug('自分のアカウント情報:'.print_r($myData, true));

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


//ユーザーidに基づいて、お気に入りから、商品idを取得して、商品情報を取得してくるには
//外部結合を使えばすぐにできる
$likeProduct = myLikeData($u_id);
debug('ユーザーがお気に入りした商品たち:'.print_r($likeProduct, true));

// 　　[0] => Array
//         (
//             [product_id] => 50
//             [user_id] => 7
//             [delete_flg] => 0
//             [create_date] => 2019-03-29 13:01:56
//             [update_flg] => 2019-04-02 20:24:47
//             [id] => 50
//             [name] => サプリ
//             [category_id] => 4
//             [comment] => ここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここ
//             [price] => 8900
//             [pic1] => uploads/0949fff63756e0d7aa4547f24c9cb5020df0c1d8.jpeg
//             [pic2] => 
//             [pic3] => 
//             [update_date] => 2019-03-29 22:01:56
//         )

//     [1] => Array
//         (
//             [product_id] => 49
//             [user_id] => 7
//             [delete_flg] => 0
//             [create_date] => 2019-03-29 13:00:55
//             [update_flg] => 2019-04-02 20:31:24
//             [id] => 49
//             [name] => シューズ
//             [category_id] => 1
//             [comment] => kokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokoko
//             [price] => 21000
//             [pic1] => uploads/f6a4160bf20e6e172f1681c9e3b38a7e8b050be4.jpeg
//             [pic2] => 
//             [pic3] => 
//             [update_date] => 2019-03-29 22:00:55
//         )

$msgToMe = getMsg($u_id);
debug('自分宛のメッセージ:'.print_r($msgToMe, true));

// [0] => Array //データはメッセージ
//         (
//             [id] => 10　　　ここのidを元にmsg.phpに遷移　　　$msgToMe[0]['id]
//             [board_id] => 102
//             [send_date] => 2019-04-03 08:32:47
//             [to_user] => 7
//             [from_user] => 8
//             [msg] => geqgrqqq
//             [delete_flg] => 0
//             [create_date] => 2019-04-03 08:32:47
//             [update_date] => 
//         )

$sendUserName = getUser($msgToMe[0]['from_user']);
debug('送信者の名前:'.print_r($sendUserName['username'], true));

$buyBoardData = getMsgsAndBoard($msgToMe[0]['board_id']);
debug('掲示板遷移のための掲示板の内容:'.print_r($buyBoardData, true));

// [0] => Array
//         (
//             [m_id] => 1
//             [board_id] => 1
//             [send_date] => 2019-04-07 11:59:03
//             [to_user] => 7
//             [from_user] => 8
//             [msg] => gewgeggqgqggq
//             [sale_user] => 7
//             [buy_user] => 8
//             [product_id] => 50
//             [create_date] => 2019-04-07 11:58:58
//         )

//     [1] => Array
//         (
//             [m_id] => 2
//             [board_id] => 1
//             [send_date] => 2019-04-07 11:59:17
//             [to_user] => 7
//             [from_user] => 8
//             [msg] => gewgeggqgqggq
//             [sale_user] => 7
//             [buy_user] => 8
//             [product_id] => 50
//             [create_date] => 2019-04-07 11:58:58
//         )



debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理終了');
?>



   <?php
    require('head.php');
    ?>

    <!-- jsでのスライド表示 -->
    <p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionOnce('msg_success'); ?>
    </p>


   <body class="page-mypage page-2colum">
     <?php
      $siteTitle = 'TOP';
      require('header.php');
      ?>

     <div class="site-width" id="contents">

         <section class="main-container">
           <div class="page-2colum">
             <?php
              require('mainhead.php');
              ?>
           </div>
           
          <section id="main" style="float: left;">
            <div class="mypage-main-wrap">
              
              <div class="title">
                <h1>お気に入りした商品</h1>
              </div>
              <div class="like-mypage">
                
               <?php $i = 0; ?>
                <?php foreach ($likeProduct as $key => $value) : ?>

                   <a href="productDetail.php">
                    <div class="panel">
                      <div class="panel-head">
                       <img src="<?php echo $value['pic1']; ?>">
                      </div>
                      <div class="panel-body">
                       <p><?php echo $value['name']; ?></p>
                       <p>¥<?php echo $value['price']; ?></p>
                      </div>
                    </div>                       
                   </a>  
                   
                   <?php $i++; ?> 

                 <?php if($i == 4) { break; } ?>                         
                <?php endforeach ?>      
                
                
              </div>
             
              <div class="title">
                <h1>新着連絡</h1>
              </div>
              <div class="msg-mypage">
                <table>
                  <thead>
                    <tr><th>送信日時</th><th>取引相手</th><th>メッセージ</th></tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($msgToMe)){ ?>
                    <?php $i=0; ?>
                    <?php foreach ($msgToMe as $key): ?>
                      <tr><td><?php echo $msgToMe[0]['send_date']; ?></td><td><?php echo $sendUserName['username'];  ?></td><td><a href="msg.php?m_id=<?php echo $msgToMe[0]['board_id']; ?>"><?php echo $msgToMe[0]['msg']; ?></a></td></tr>
                    <?php $i++; ?>
                    <?php if($i == 4){ break; } ?>
                    <?php endforeach ?>
                    <?php } ?>
                  </tbody>
                </table>
              </div>

              <div class="title">
                <h1>出品している商品</h1>
              </div>
              <div class="sale-mypage">
                
              </div>
          
            
          </section>

          <section class="sidebar">
            <img class="my-img" src="<?php echo showImg(sanitize($myData['pic'])); ?>">
            <p class="prof-username"><?php echo sanitize($myData['username']); ?></p>
            <a href="profEdit.php">アカウントを編集する</a><br>
            <a href="blog.php">ブログを書く</a>
          </section>


        </section>

     </div>

   <?php
    require('footer.php');
    ?>
   </body>
 </html>
