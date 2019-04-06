<footer id='footer'>
  Copyright <a href="Audustry.html">Keijiban</a>.All Rights Reserved.
</footer>
<script src="js/vendor/jquery-2.2.2.min.js"></script>



<script>
  "use strict";

  $(function(){
    var $ftr = $('#footer');
    if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
      $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
    }

  //================================
  // スライドでメッセージを表示　5秒間だけ
  //================================
  var $jsShowMsg = $('#js-show-msg');
  var msg = $jsShowMsg.text();
  //全角半角の空白を削除
  if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
    $jsShowMsg.slideToggle('slow');
    setTimeout(function(){ $jsShowMsg.slideToggle('slow');}, 5000);
  }

//================================
  // テキストカウント　商品登録ページ
  //================================
var $countUp = $('#js-count-text'),
    $countVew = $('#js-count-view');
  $countUp.on('keyup', function(e){
    $countVew.html($(this).val().length);
  });




  //================================
  // 画像ライブプレビュー　商品登録ページ
  //================================
  var $dropArea = $('.area-drop');
  var $fileInput = $('.input-file');

  $dropArea.on('dragover', function(e){
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border', '3px #ccc dashed');
  });

  $dropArea.on('dragleave', function(e){
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border', 'none');
  });

  $fileInput.on('change', function(e){
    $dropArea.css('border', 'none');
    var file = this.files[0],//ドロップしたfile情報
        $img = $(this).siblings('.prev-img'),//兄弟のdomを取得
        fileReader = new  FileReader();
    fileReader.onload = function(event){
      $img.attr('src', event.target.result).show();
    };
    fileReader.readAsDataURL(file);
  });
  //================================
  // 商品詳細での画像切り替え　商品詳細ページ
  //================================
  var $switchImgSubs = $('.js-switch-img-sub'),
      $switchImgMain = $('#js-switch-img-main');
  $switchImgSubs.on('click', function(e){
    $switchImgMain.attr('src', $(this).attr('src'));
  });



//================================
  //Ajax処理　商品詳細ページ　お気に入り
  //================================
var $like,
    likeProductId;


$like = $('.js-click-like') || null;
likeProductId = $like.data('productid') || null;

//数値の0はfalseと判定
if(likeProductId !== undefined && likeProductId !== null){
  $like.on('click', function(){
    var $this = $(this);
    $.ajax({
      type:"POST",//通信形式
      url:"likeAjax.php",//通信先
      data:{ productId : likeProductId}//渡すキーはproductIdとして　その値はlikeProductId
    }).done(function(data){
      console.log('Ajax Success');
      $this.toggleClass('active');//activeクラス(html)を付け外しする
    }).fail(function(msg){
      console.log('Ajax Error');
    });
  });
}






//================================
  //　ユーザー側でのバリデーションチェック
  //================================
  const MSG01 = '30文字以内で入力してください';
  const MSG02 = '住所は50文字以内で入力してください'; //住所
  const MSG03 = '入力必須です';
  const MSG04 = 'Emailの形式で入力してください';
  const MGS05 = '半角英数字で入力してください';
  const MSG06 = '本文の文字数は32000字までです'; //投稿本文
  const MSG07 = 'パスワードは20文字以内で入力してください';
  const MSG08 = '商品のコメントは500文字以内で入力してください';
  
  ///////////
  //ユーザー登録,ログインフォームページ
  ///////////

  //email
  $(".js-valid-email").keyup(function(){
    var form_g = $(this).closest('.js-form-group');
    if($(this).val().length > 50 || !$(this).val().match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/)){
      form_g.removeClass('has-success').addClass('has-error');
      form_g.find('.js-help-block').text(MSG04);
    }else if($(this).val().length == 0){
      form_g.removeClass('has-success').addClass('has-error');
      form_g.find('.js-help-block').text(MSG03);
    }else{
      form_g.removeClass('has-error').addClass('has-success');
      form_g.find('.js-help-block').text('');
    }
  });

  //pass
  $(".js-valid-pass").keyup(function(){
     var form_g = $(this).closest('.js-form-group');
     if($(this).val().length === 0){
       form_g.removeClass('has-success').addClass('has-error');
       form_g.find('.ja-help-block').text(MSG03);
     }else if($(this).val().length >= 21){
      form_g.removeClass('has-success').addClass('has-error');
      form_g.find('.js-help-block').text(MSG07); 
     }
  });

  
 ///////////
 //商品登録ページ
 ///////////
  //タイトル
$(".js-valid-name").keyup(function(){
  var form_g = $(this).closest('.js-form-group');
  if($(this).val().length >= 31){
    form_g.removeClass('has-success').addClass('has-error');
    form_g.find('.js-help-block').text(MSG01);
  }
});

//商品のコメント欄
$(".js-valid-comment").keyup(function(){
  var form_g = $(this).closest('.js-form-group');
  if($(this).val().length >= 501){
    form_g.removeClass('has-success').addClass('has-error');
    form_g.find('.js-help-block').text(MSG08);
  }else{
    form_g.removeClass('has-error').addClass('has-success');
    form_g.find('.js-help-block').text('');
  }
});


 ///////////
 //アカウント編集ページ
 ///////////
 //住所
$('.js-valid-addr').keyup(function(){
  var form_g = $(this).closest('.js-form-group');
  if($(this).val().length >= 50){
    form_g.removeClass('has-success').addClass('has-error');
    form_g.find('.js-valid-name').text(MSG02);
  }
});

//電話
$('.js-')



























  });
</script>
