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
?>



   <?php
    require('head.php');
    ?>

    <!-- jsでのスライド表示 -->
    <p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionOnce('msg_success'); ?>
    </p>


   <body class="page-top">
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
         </section>

     </div>

   <?php
    require('footer.php');
    ?>
   </body>
 </html>
