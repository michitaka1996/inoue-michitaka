<?php 
require('function.php');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debug('オブジェクト指向ログインページ');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debugLogStart();

//ajaxで画面遷移させるのではなく、オブジェクトにセッションを与えてhtmlの画面を切り替える

$monsters = array();









?>
















<?php
 $siteTitle = '簡易ログイン';
 require('head.php');
 ?>

  <body class="page-top page-1colum">
    
    <?php
     require('header.php');
     ?>


     <div class="site-width" id="contents">
       
       <div id="main">
        <section class="object-login-wrap">
         <div id="start-wrap">
          <div  id="start-select">
            <h1 class="game-title" style="margin-bottom:30px;">ログインゲームを始めますか？</h1>
            <a href="">はい</a><br>
            <a href="login.php">いいえ</a>
          </div>
         </div>



        </section>


       </div>


     </div>

    <?php
     require('footer.php');
     ?>
  </body>
</html>
