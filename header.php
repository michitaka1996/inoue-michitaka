<header class="site-width">
  <h1><a href="index.php">Keijiban</a></h1>
  <nav>
    <ul>
      <?php if(isset($_SESSION['login_date'])){ ?>
        <li><a href="logout.php">ログアウト</a></li>
        <li><a href="mypage.php">マイページ</a></li>
        <li><a href="#">ME</a></li>
      <!-- ログインしていない場合 -->
      <?php }else{ ?>
        <li><a href="login.php">ログイン</a></li>
        <li><a href="signup.php">ユーザー登録</a></li>
        <li><a href="#">ME</a></li>
      <?php } ?>

    </ul>
  </nav>
</header>
