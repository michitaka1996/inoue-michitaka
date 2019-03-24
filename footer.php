<footer id='footer'>
  Copyright <a href="Audustry.html">Keijiban</a>.All Rights Reserved.
</footer>
<script src="js/vendor/jquery-2.2.2.min.js"></script>
<script type="text/javascript" src="main.js">//バリデーション</script>


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
  // 画像ライブプレビュー
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




  });
</script>
