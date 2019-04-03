<?php

if(!empty($_SESSION['login_date'])){
  debug('すでにログインしているユーザーです');
  debug('セッションの中身：'.print_r($_SESSION,true));
  if($_SESSION['login_date'] + $_SESSION['login_limit'] > time()){

    debug('ログイン有効期限内です');
    if(basename($_SERVER['PHP_SELF']) === 'login.php'){
      debug('マイページへ遷移します');
      header("Location:mypage.php");
    }
      

  }else{
    debug('ログイン有効期限オーバーです');
    session_destroy();
    header('Location:login.php');
  }

}else{
  debug('未ログインユーザーです');
  if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
    header("Location:login.php");
  }
}



?>
