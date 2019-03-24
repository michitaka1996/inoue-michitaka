<?php
require('function.php');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debug('記事投稿・編集ページ');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debugLogStart();
require('auth.php');

//GETパラメータの有無によって記事投稿ページか記事編集ページかを判断する
$edit_flg = (!empty($_GET['p_id']))? $_GET['p_id'] : '';

//フォームに表示させるDBデータを格納
//商品データ u_idを元に
$dbFormData = getProduct($_SESSION['user_id']);
debug('取得してきた商品データ:'.print_r($dbFormData,true));

//DBデータから取得したカテゴリデータ
$dbCategoryData = getCategory($_SESSION['user_id']);
debug('取得してきたカテゴリデータ:'.print_r($dbCategoryData, true));


if(!empty($_POST)){
  debug('POST送信があります。FILE情報があればそれも表示させます');
  debug('POST情報:'.print_r($_POST, true));
  debug('FILE情報:'.print_r($FILES, true));

  $name = $_POST['name'];
  $categoty = $_POST['cagtegory_id'];
  $price = $_POST['price'];
  $comment = $_POST['comment'];

  //各種画像 ファイル情報と、他のフォームを編集した時に画像が空のpostになるのを防ぐためにDBのデータを格納
  $pic1 = (!empty($_FILES['name']['pic1']))? uploadImg($_FILES['name']['pic1'], 'pic1') : '';
  $pic1 = (empty($_FILES['pic1']) && !empty($dbFormData['pic1']))? $dbFormData['pic1'] : '';

  $pic2 = (!empty($_FILES['name']['pic2']))? uploadImg($_FILES['name']['pic2'], 'pic2') : '';
  $pic2 = (empty($_FILES['pic2']) && !empty($dbFormData['pic2']))? $dbFormData['pic2'] : '';

  $pic3 = (!empty($_FILES['name']['pic3']))? uploadImg($_FILES['name']['pic3'], 'pic3') : '';
  $pic1 = (empty($_FILES['pic3']) && !empty($dbFormData['pic3']))? $dbFormData['pic3'] : '';


  if(empty($edit_flg)){ //商品登録の場合だった時のバリデーション
    validRequired($name, 'name');
    validMinLen($name, 'name', 6);
    validMaxLen($name, 'name', 20);
    validRequired($categoty, 'cagtegory_id');
    validRequired($price, 'price');
    validMaxLen($price, 'price', 12);
    validHalf($price, 'price');
    validMaxLen($comment, 'comment', 300);
  }else{  //商品編集の場合で、新たにpostした時に、各々がDBの内容から変更した時
    if($name !== $dbFormData['name'] ){
      validRequired($name, 'name');
      validMinLen($name, 'name', 6);
      validMaxLen($name, 'name', 20);
    }
    if($categoty !== $dbFormData['cagtegory_id']){
      validRequired($categoty, 'cagtegory_id');
    }
    if($price !== $dbFormData['price']){
      validRequired($price, 'price');
      validMaxLen($price, 'price', 12);
      validHalf($price, 'price');
    }
    if($comment !== $dbFormData['comment']){
      validMaxLen($comment, 'comment', 300);
    }
  }
  if(empty($err_msg)){
    debug('バリデーションOKです');
    debug('DBに新規登録します');

    $dbh = dbConnect();

    if(empty($edit_flg)){ //商品登録の場合
      debug('商品をDBに登録します');
      $sql = 'INSERT INTO product (name, cagtegory_id, price, comment, pic1, pic2, pic3, create_date) VALUES(:name, :c_id, :price, :comment, :pic1, :pic2, :pic3, :date)';
      $data = array(':name'=>$name, ':c_id'=>$categoty, ':price'=>$price, ':comment'=>$comment, ':pic1'=>$pic1, ':pic2'=>$pic2, ':pic3'=>$pic3, ':date'=>date('Y-m-d H:i:s'));
      debug('商品登録が完了しました');

    }else{
      $sql = 'UPDATE product SET name=:name, cagtegory_id=:c_id, price=:price, comment=:comment, pic1=:pic1, pic2=:pic2, pic3=:pic3, update_date=:date';
      debug('商品編集が完了しました');
    }
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        debug('マイページに遷移します');
        header("Location:mypage.php");
      }
  }
}
debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>　画面表示処理終了');
?>





<!-- html部分 -->
<?php
 $siteTitle = '記事投稿';
 require('head.php');
 ?>

  <body class="page-registProduct page-2colum">
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
            <form class="form" action="" method="post" enctype="multipart/form-data">
              <div class="msg-area">
                <?php if(!empty($err_msg['common'])){ echo $err_msg['common'];} ?>
              </div>

              <label>タイトル:必須
                <input type="text" name="name" value="">
              </label>
              <div class="msg-area">
                <?php if(!empty($err_msg['name'])){echo $err_msg['name'];} ?>
              </div>

              <label>カテゴリ:必須
                <select class="" name="cagtegory_id">
                  <option>選択してください</option>
                </select>
              </label>

              <label>金額:必須
                <input type="text" name="prie" value="">
              </label>

              <label>コメント
                <textarea name="comment" rows="" cols=""></textarea>
              </label>

              <!-- 画像たち -->
              <div class="imgDrop-container">
                <label class="area-drop" style="margin-left:20px;">画像１
                  <input type="hidden" name="MAX_FILE_SIZE" value="">
                  <input type="file" style="" class="input-file" name="" value="">
                  <img src="<?php echo $dbFormData['pic1']; ?>" style="<?php if(empty($dbFormData['pic1']))echo 'display:none;' ?>" class="prev-img" alt="">
                </label>
                <label class="area-drop">画像2
                  <input type="hidden" name="MAX_FILE_SIZE" value="">
                  <input type="file" class="input-file" name="" value="">
                  <img src="" style="<?php if(empty($dbFormData['pic1']))echo 'display:none;' ?>" class="prev-img" alt="">
                </label>
                <label class="area-drop">画像3
                  <input type="hidden" name="MAX_FILE_SIZE" value="">
                  <input type="file" class="input-file" name="" value="">
                  <img src="" style="<?php if(empty($dbFormData['pic1']))echo 'display:none;' ?>" class="prev-img" alt="">
                </label>
              </div>

              <div class="btn-container">
                <input type="submit" class="btn btn-primary" name="" value="投稿">
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
