<?php
require('function.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('トップページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//すでにログインしているユーザーかどうかチェック
require('auth.php');


//必要なデータ,

//現在のページ数デフォルトはページとする
$currentPageNum = (!empty($_GET['p']))? $_GET['p'] : 1;

//検索の場合は、カテゴリとソート いまはやらないでおく

//1ページごとの表示件数
$listSpan = 20;

//表示させる最小の商品iD　一番左上
//1ページ目なら、0番目　2ページ目なら20番目からスタート
$currentMinNum = (($currentPageNum-1) * $listSpan);


//$dbProductDataのgetProductListのsqlで、昇順、降順、ページング

$dbProductData = getProductList($currentMinNum, $listSpan);
debug('取得したデータ:'.print_r($dbProductData,true));


debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理終了');
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
              <form class="" action="" method="get">
                <a href="mypage.php">あああああ</a>

              </form>
            </section>


             <!-- ２カラムのメインの方 -->
            <section id="main" class="">

              <div class="product-wrap">

                <!-- 商品パネルをループ -->
                <!-- getProductListでの$rstで['total']['total_page']['data']と区分けして返したため、['data']の方の$valを取得 -->
                <?php foreach ($dbProductData['data'] as $key => $val) { ?>
                  <div class="product-list">
                    <a href="#">
                      <div class="product-img">
                        <img class="product-pic" src="<?php echo $val['pic1']; ?>" alt="">
                      </div>
                      <div class="product-title">
                        <a href="#">♡♡♡♡</a>
                      </div>
                      <div class="product-price">
                        ¥<a href="#"><?php echo $val['price']; ?></a>
                      </div>
                    </a>
                  </div>
                <?php } ?>
              </div>

              <?php   return pagenation($dbProductData['total_page'],$currentPageNum);
                debug('データが返されているかチェック:'.print_r($minPageNum, true));
              ?>


            </section>


       </section>
     </div>

     <?php
      require('footer.php');
      ?>
   </body>
 </html>
