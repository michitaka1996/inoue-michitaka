<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('トップページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//すでにログインしているユーザーかどうかチェック
require('auth.php');



$dbProductData = getProductList();
debug('取得したデータ:'.print_r($dbProductData,true));





?>

 <?php
  $siteTitle = 'マイページ';
  require('head.php');
  ?>

   <body class="page-top page-2colum">
     <?php
      require('header.php');
      ?>


     <div class="site-width" id="contents">
       <section class="main-container">
         <?php
          require('mainhead.php');
          ?>
            <section class="sidebar">

            </section>


             <!-- ２カラムのメインの方 -->
            <section id="main" class="">

              <div class="product-wrap">

                <?php foreach ($dbProductData as $key => $val) { ?>
                  <div class="product-list">
                    <a href="#">
                      <div class="product-img">
                        <img class="product-pic" src="<?php echo $val['pic1']; ?>" alt="">
                      </div>
                      <div class="product-title">
                        <a href="#">♡♡♡♡</a>
                      </div>
                      <div class="product-price">
                        ¥<a href="#">111</a>
                      </div>
                    </a>
                  </div>
                <?php } ?>


              </div>
            </section>


       </section>
     </div>

     <?php
      require('footer.php');
      ?>
   </body>
 </html>
