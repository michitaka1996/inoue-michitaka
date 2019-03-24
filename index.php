<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('トップページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//すでにログインしているユーザーかどうかチェック
require('auth.php');

 ?>

 <?php
  $siteTitle = 'マイページ';
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
         <!-- メインコンテナーに遊びを持たせる -->
         <div class="container-wrap">
             <!-- ２カラムのメインの方 -->
             <section id="main" class="">
                 <div class="time-line">

                 </div>
             </section>


         </div>
       </section>
     </div>

     <?php
      require('footer.php');
      ?>
   </body>
 </html>
