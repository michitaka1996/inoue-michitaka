<?php
require('function.php');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debug('記事投稿・編集ページ');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debugLogStart();
require('auth.php');


//getパラメータを使って、商品編集と、商品登録の場合で、フォーム出力を使い分ける
$p_id = (!empty($_GET['p_id']))? $_GET['p_id'] : '';

//GETパラメータの有無によって記事投稿ページか記事編集ページかを判断する
$edit_flg = (!empty($_GET['p_id']))? $_GET['p_id'] : '';

//フォームに表示させるDBデータを格納
//商品データ u_idを元に
$dbFormData = getProduct($_SESSION['user_id']);
// debug('取得してきた商品データ:'.print_r($dbFormData,true));

//DBデータから取得したカテゴリデータ
$dbCategoryData = getCategory($_SESSION['user_id']);



if(!empty($_POST)){
  debug('POST送信があります。FILE情報があればそれも表示させます');
  debug('POST情報:'.print_r($_POST, true));
  debug('FILE情報:'.print_r($_FILES, true));

  $name = $_POST['name'];
  $categoty = $_POST['cagtegory_id'];
  $price = $_POST['price'];
  $comment = $_POST['comment'];

  //各種画像 ファイル情報と、他のフォームを編集した時に画像が空のpostになるのを防ぐためにDBのデータを格納
  //２次元配列で、$_FILESの中のキーnameがあれば確実に入っているとわかる
  $pic1 = (!empty($_FILES['pic1']['name']))? uploadImg($_FILES['pic1'], 'pic1') : '';
  $pic1 = (empty($pic1) && !empty($dbFormData['pic1']))? $dbFormData['pic1'] : $pic1;
  debug('画像チェックです:'.print_r($pic1, true));

  $pic2 = (!empty($_FILES['pic2']['name']))? uploadImg($_FILES['pic2'], 'pic2') : '';
  $pic1 = (empty($pic1) && !empty($dbFormData['pic2']))? $dbFormData['pic2'] : $pic1;

  $pic3 = (!empty($_FILES['pic3']['name']))? uploadImg($_FILES['pic3'], 'pic3') : '';
  $pic3 = (empty($pic3) && !empty($dbFormData['pic3']))? $dbFormData['pic3'] : $pic3;



  if(empty($edit_flg)){ //商品登録の場合だった時のバリデーション
    debug('商品登録です。バリデーションします');
    validRequired($name, 'name');
    validRequired($categoty, 'category_id');
    validRequired($price, 'price');
    if(empty($err_msg)){
      validMaxLen($price, 'price', 12);
      validHalf($price, 'price');
    }
  }else{  //商品編集の場合で、新たにpostした時に、各々がDBの内容から変更した時
    debug('商品編集です。バリデーションします');
    if($name !== $dbFormData['name'] ){
      validRequired($name, 'name');
    }
    if($categoty !== $dbFormData['cagtegory_id']){
      validRequired($categoty, 'category_id');
    }
    if($price !== $dbFormData['price']){
      validRequired($price, 'price');
      validHalf($price, 'price');
    }
    if($comment !== $dbFormData['comment']){
      validMaxLen($comment, 'comment', 300);
    }
  }
  if(empty($err_msg)){
    debug('バリデーションOKです');
    debug('DBに新規登録します');

    try{
      $dbh = dbConnect();

      if(empty($edit_flg)){ //商品登録の場合
        debug('商品をDBに登録します');
        $sql = 'INSERT INTO product (name, category_id, price, comment, pic1, pic2, pic3, create_date) VALUES(:name, :c_id, :price, :comment, :pic1, :pic2, :pic3, :date)';
        $data = array(':name'=>$name, ':c_id'=>$categoty, ':price'=>$price, ':comment'=>$comment, ':pic1'=>$pic1, ':pic2'=>$pic2, ':pic3'=>$pic3, ':date'=>date('Y-m-d H:i:s'));
        debug('商品登録が完了しました');

      }else{
        $sql = 'UPDATE product SET name=:name, cagtegory_id=:c_id, price=:price, comment=:comment, pic1=:pic1, pic2=:pic2, pic3=:pic3, update_date=:date';
        $data = array(':name'=>$name, ':c_id'=>$categoty, ':price'=>$price, ':pic1'=>$pic1, ':pic2'=>$pic2, ':pic3'=>$pic3, ':date'=>date('Y-m-d H:i:s'));
        debug('商品編集が完了しました');
      }
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
          debug('流しこんだデータ:'.print_r($data, true));
          debug('商品データ登録が成功したのでマイページに遷移します');
          header("Location:mypage.php");
        }
    }catch(Exception $e){
      error_log('エラー発生:'.$e->getMessage());
      $err_msg['common'] = MSG07;
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

  <body class="page-registProduct page-1colum">
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
                <input type="text" name="name" value="<?php echo getFormData('name'); ?>" >
              </label>
              <div class="msg-area">
                <?php if(!empty($err_msg['name'])){echo $err_msg['name'];} ?>
              </div>

              <label>カテゴリ:必須
                <!-- カテゴリは検証ツールで選択中のカテゴリは'selected'と文字列を表示させる(postの時) -->
                <!-- selectのoptionタグには$dbCategoryDataの中のnameを全部ループさせる -->
                <!-- $keyはたくさんあるカテゴリデータ,$valではその一つのレコードのデータ -->
                <select class="" name="cagtegory_id">
                  <!-- category_idに任意の選択が合った場合、文字列を出す(POSTの時) -->
                    <option value="">選択してください</option>

                  <?php foreach ($dbCategoryData as $key => $value) { ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                  <?php } ?>
                </select>
              </label>
              <div class="msg-area">
                <?php if(!empty($err_msg['category_id'])){echo $err_msg['category_id'];} ?>
              </div>

              <label>金額:必須
                <input type="text" name="price" value="">
              </label>
              <div class="msg-area">
                <?php if(!empty($err_msg['price'])){echo $err_msg['price'];} ?>
              </div>

              <label>コメント
                <textarea name="comment" rows="" cols=""></textarea>
              </label>
              <div class="msg-area">
                <?php if(!empty($err_msg['comment'])){echo $err_msg['comment'];} ?>
              </div>

              <!-- 画像たち -->
              <div class="imgDrop-container">

                <label class="area-drop" style="margin-left:20px;">

                  <input type="hidden" name="MAX_FILE_SIZE" value="">
                  <input type="file" style="" class="input-file" name="pic1" value="">
                  <img src="<?php echo $dbFormData['pic1']; ?>" style="<?php if(empty($dbFormData['pic1']))echo 'display:none;' ?>" class="prev-img" alt="">
                </label>


                <label class="area-drop">
                  <input type="hidden" name="MAX_FILE_SIZE" value="">
                  <input type="file" class="input-file" name="" value="">
                  <img src="" style="<?php if(empty($dbFormData['pic1']))echo 'display:none;' ?>" class="prev-img" alt="">
                </label>


                <label class="area-drop">
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
