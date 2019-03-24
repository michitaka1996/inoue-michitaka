<?php

//ログアウトはセッションを破棄して、ログインページへ遷移させるだけ

require('function.php');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debug('ログアウトページ');
debugLogStart();

debug('ログアウトします。セッションを破棄します');
session_destroy();

debug('ログインページ遷移します');
header("Location:login.php");



?>
