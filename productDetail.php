<?php
require('function.php');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debug('商品詳細ページ');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debugLogStart();
//ログイン認証は取らない

//GETに渡したものを$key $valueに分けることができる
debug('変数GET:'.print_r($_GET, true));

//getパラメータがあるかチェック プトダクトid
//これを元に、結合した商品詳細ページを表示
$p_id = (!empty($_GET['p_id']))? $_GET['p_id'] : '';
debug('GET送信:'.print_r($p_id, true));

//画面に表示するデータ
//商品情報　そのカテゴリ

//getパラメータを元に
//その商品情報

$viewData = getProductOne($p_id);
debug('結合した商品情報:'.print_r($viewData, true));


if(!empty($_POST['submit'])){

  debug('POST情報があります');
  debug('POST情報:'.print_r($_POST, true));

  debug('掲示板を作成します');//boardテーブルにインサートして移動するのであらかじめ掲示板を作っておく
  debug('掲示板にデータを挿入します');

  require('auth.php');

  try{

    $dbh = dbConnect();
    $sql = 'INSERT INTO board(sale_user, buy_user, product_id, create_date) VALUES(:s_user, :b_user, :p_id,  :date )';
    $data = array(':s_user'=>$viewData['user_id'], ':b_user'=>$_SESSION['user_id'], 'p_id'=>$p_id, ':date'=>date('Y-m-d H:i:s'));
    debug('流し込んだデータ:'.print_r($data, true));
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      debug('クエリ成功したので掲示板に遷移します');
      $_SESSION['msg_success'] = SUC02;
      header("Location:msg.php?m_id=".$dbh->lastInsertId());

    }else{
      return 0;
    }
  }catch(Exception $e){
    error_log('エラー発生:'.$e->getMessage());
  }
}
debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理終了');
?>


<!-- html部分 -->
<?php
 $siteTitle = '商品詳細';
 require('head.php');
 ?>

  <body class="page-productDetail">

    <?php
     require('header.php');
     ?>


    <div class="site-width" id="contents">
      <section class="main-container">
        <?php
         require('mainhead.php');
         ?>

        <section class="productDetail-wrap" >
          <div class="productDetail-title">
            <a href="index.php">SHOP</a>  >  <a href="">Products</a><br>
            <h2><?php echo sanitize($viewData['name']); ?></h2>
            <div class="product-icon">
            <i class="fa fa-heart fa-2x icon-like js-click-like <?php if(isLike($_SESSION['user_id'], $viewData['id'])){ echo 'active'; } ?>" area-hidden="true" data-productid="<?php echo sanitize($viewData['id']); ?>" style="float: right;" ></i>
            </div>
          </div>
          

          <div id="main" >
            <div class="img-main">
             <img id="js-switch-img-main" src="<?php echo sanitize($viewData['pic1']); ?>" alt="">
            </div>

            <div class="img-sub">
              <img class="js-switch-img-sub" src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="">
              <img class="js-switch-img-sub" src="<?php echo showImg(sanitize($viewData['pic2'])); ?>" alt="">
              <img class="js-switch-img-sub" src="<?php echo showImg(sanitize($viewData['pic3'])); ?>" alt="">
            </div>


          </div>

          <div class="product-detail" >
           
              <h2>¥<?php echo sanitize($viewData['price']); ?></h2><br>
              <p class="product-comment"><?php echo sanitize($viewData['comment']); ?></p>

          </div>

          <form  action="" method="post">
            <div class="btn-container">
              <input type="submit" class="btn btn-buy" style="background: #b6a489; color: #444;" name="submit" value="BUY!">
            </div>
          </form>
          
        </section>

        <a class="product-back" href="index.php "> &lt;商品一覧に戻る</a>
  


      </section>
    </div>



    <?php
     require('footer.php');
     ?>
  </body>
</html>
