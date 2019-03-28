<?php
//================================
// ログ
//================================
 ini_set('log_errors', 'on');
 ini_set('error_log', 'php.log');

 //================================
 // デバッグ
 //================================
 $debug_flg = true;
 function debug($str){
   global $debug_flg;
   if(!empty($debug_flg)){
     error_log('デバッグ：'.$str);
   }
 }
 //================================
 // sesisonの有効期限を伸ばす
 //================================
 //var tmpに置くと30日保存される
session_save_path("/var/tmp/");
//100分の１の確率で削除
ini_set('session.gc_maxlifetime', 60*60*24*30);
ini_set('session.cookie_lifetime', 60*60*24*30);
session_start();
//現在のセッションidを新く生成したものに帰る(なりすまし対策)
session_regenerate_id();

//================================
//画面表示処理ログ吐き出し関数
//これを元にページごとのデバッグの処理を出力
//================================
function debugLogStart(){
  debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始(fun)');
  debug('セッションID:'.session_id());
  debug('セッション変数の中身:'.print_r($_SESSION,true));
  debug('現在日時タイムスタンプ:'.time());
  if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
    debug('ログイン期限日時タイムスタンプ:'.($_SESSION['login_date'] + $_SESSION['login_limit']));
  }
}

//DB接続準備関数
function dbConnect(){
  //DBへの接続準備
  $dsn = 'mysql:dbname=keijiban;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = array(
    // SQL実行失敗時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  $dbh = new PDO($dsn, $user, $password, $options);
  return $dbh;
}
//DB接続実行関数
function queryPost($dbh, $sql, $data){

  $stmt = $dbh->prepare($sql);
  debug('クエリ実行準備は完了しています');
  print $sql.'<br>';
  var_dump($data);

  // $stmt->execute($data);
  // debug('エグゼキュート成功です');　これいらない

  //プレースホルダに値をセットし、SQL文を実行
  if(!$stmt->execute($data)){
    debug('クエリに失敗しました');
    debug('失敗したSQL:'.print_r($stmt,true));
    return 0;
  }else{
    debug('クエリに成功しました');
    debug('成功したSQL:'.print_r($stmt, true));
  return $stmt;
 }
}


//バリデーション定数
define('MSG01', '入力必須です');
define('MSG02', 'そのEmailはすでに使われています');
define('MSG03', 'パスワードが合っていません');
define('MSG04', '半角英数字のみご利用できます');
define('MSG05', '6文字以上でご利用いただけます');
define('MSG06', '255文字以内で入力してください');
define('MSG07', 'エラーが発生しました。もう一度やり直してください');
define('MSG08', 'Emailの形式で入力してください');
define('MSG09', 'ログインできません。EmailまたはPasswordが違います');
define('MSG10', '失敗しました');
define('MSG11', '電話番号の形式で入力してください');
define('MSG12', '再入力したパスワードと合っていません');
define('SUC01', 'マイページです！');

$err_msg = array();

//バリデーション関数
function validRequired($str, $key){
  if($str === ''){
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}
function validEmail($str, $key){
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG08;
  }
}
function validEmailDup($email){
  global $err_msg;
  try{
    $dbh = dbConnect();
    $sql = 'SELECT email FROM users WHERE email=:email AND delete_flg=0';
    $data = array(':email'=>$email);
    //バリデーションチェックで読み込まれるのでクエリ成功か失敗かどうかをデバッグで出力させること
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    //検索に引っかかった->そのemailを条件にもっているレコードが存在した時
    if(!empty($result)){
      $err_msg['email'] = MSG02;
    }else {
      debug('Email重複okです');
    }
  }catch(Exception $e){
    error_log('エラー発生'.$e->getMessage());
    $err_msg['common'] = MSG07;
  }
}
function validMatch($str1, $str2, $key){
  if($str1 !== $str2){
    global $err_msg;
    $err_msg[$key] = MSG03;
  }
}
function validHalf($str, $key){
  if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG04;
  }
}
function validMinLen($str, $key, $min = 6){
  if(mb_strlen($str) < $min){
    global $err_msg;
    $err_msg[$key] = MSG05;
  }
}
function validMaxLen($str, $key, $max  = 255){
  if(mb_strlen($str) > $max){
    global $err_msg;
    $err_msg[$key] = MSG06;
  }
}
function validTel($str, $key){
  if(!preg_match("/0\d{1,4}\d{1,4}\d{4}/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG11;
  }
}


//ユーザー情報取得関数
function getUser($u_id){
  debug('ユーザー情報を取得します');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM users WHERE id=:u_id AND delete_flg=0';
    $data = array(':u_id'=>$_SESSION['user_id']);
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    if($result){
      return $result;
    }else{
      debug('フェッチできませんでした');
    }
  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}


//画像アップロード関数
//RuntimeExceptionはPHPが実行されているときに投げられるエラー
function uploadImg($file, $key){
  debug('画像アップロード処理開始');
  //ファイルが画像形式かどうか
  if(isset($file['error']) && is_int($file['error'])){
    try{
      switch($file['error']){
        case UPLOAD_ERR_OK:
          break;
        case UPLOAD_ERR_NO_FILE:
          throw new RuntimeException('ファイルが洗濯されていません');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
          throw new RuntimeException('ファイルサイズが大きすぎます');
        default:
          throw new RuntimeException('その他のエラーが発生しました');
      }
      $type = @exif_imagetype($file['tmp_name']);
      if(!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)){
        throw new RuntimeException('画像形式が未対応です');
      }
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
      if(!move_uploaded_file($file['tmp_name'], $path)){
        throw new  RuntimeException('ファイル保存時にエラーが発生しました');
      }
      //パーミッション
      chmod($path, 0644);
      debug('ファイルは正常にアップロードされました');
      debug('ファイルパス:'.$path);
      return $path;

    }catch(RuntimeException $e){
      debug($e->getMessage());
      global $err_msg;
      //$err_msg['pic']にRuntimeExceptionのエラーを表示させる
      $err_msg[$key] = getMessage();
    }
  }
}
//Jsの投稿しました！のやつ
//セッションを1回だけも持たせる
function getSessionOnce($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    //セッションを持たせてから、空にする
    $_SESSION[$key] = '';
    return $data;
  }
}

//================================
// フォーム入力保持関数
//フォームに入寮する際にエラーが出たらフォームの中の入力保持はどうするか
//================================
function getFormData($key){
  global $dbFormData;

  if(!empty($dbFormData)){ //DBにでーたがある
    if(!empty($err_msg[$key])){//エラーがある
      debug('バリデーションに引っかかっています');
      if(!empty($_POST[$key])){ //DBにデータが合ってエラーもあるしPOSTもある
        return $_POST[$key];
      }
    }else{ //DBにデータが合ってエラーがない
      //特に定義しない
    }
  }else{//DBにデータがない
    if(!empty($_POST[$key])){//POSTがある
      if(!empty($err_msg)){ //DBにデータはないがPOSTが合ってエラーがある
        return $_POST[$key];
      }
    }
  }
}

//   if(!empty($dbFormData)){//DBにデータがある
//     if(!empty($err_msg)){//エラーメッセージがない
//
//       if(!empty($_POST)){//POST送信がある
//         return $_POST[$key];
//       }else{
//         return $dbFormData[$key];
//       }
//     }else{ //dbにデータがあってエラーはない時(通常の場合)
//       return $dbFormData[$key];
//     }
//   }else{// DBにデータがない
//     if(!empty($err_msg)){ //DBにデータがないがPOSTにエラーがある
//       return $_POST[$key];
//     }
//   }
// }

//ユーザーidを元に商品情報を取得 registProduct.php
function getProduct($u_id){
  debug('商品登録・編集のために商品情報を取得します');

  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM product WHERE id=:u_id AND delete_flg=0';
    $data = array(':u_id'=>$_SESSION['user_id']);
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetchAll();
    if(!empty($result)){
      debug('商品情報のフェッチ成功しました');
      return $result;
    }else{
      return 0;
    }
  }catch(Exception $e){
    error_log('エラー発生:'.$e->getMessage());
  }
}

//ユーザーIDを元にカテゴリ情報を取得
function getCategory(){
  debug('カテゴリ情報を取得します');
  try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM category';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetchAll();
    if(!empty($result)){
      debug('カテゴリ情報のフェッチに成功しました');
      return $result;
    }else{
      return 0;
    }
  }catch(Exception $e){
    error_log('エラー発生:'.$e->getMessage());

  }
}

//商品一覧表示用関数　全部のデータを取得
//DBにある全ての商品情報を取得してくるので、それを元に総ページ数を設定でき、ページネーションに使える
function getProductList($currentMinNum=1, $listSpan, $category, $sort){
  debug('商品情報(商品一覧)を開始します');

  try{
    $dbh  = dbConnect();

    //とってくるデータその1 件数　ページ数
    $sql = 'SELECT id FROM product'; //基本となるSQL①

    if(!empty($category)){
      //カテゴリで仕分けたい　条件としてカテゴリidはgetパラメータの番号
      $sql .= ' WHERE category_id =  '.$category;
    }
    //順番指定があれば
    if(!empty($sort)){
      switch($sort){
        case 1 :
          $sql .= ' ORDER BY price DESC';
          break;
        case 2 :
          $sql .= ' ORDER BY price ASC';
      }
    }

    $data = array();
    debug('SQL:'.$sql);
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $rst['total'] = $stmt->rowCount(); // 総レコード数
      $rst['total_page'] = ceil($rst['total']/$listSpan);//総ページ数
    }else{
      return false;
    }

    //とってくるデータその２　全ての商品情報
    $sql = 'SELECT * FROM product';//基本となるsql②
    if(!empty($category)){
      //カテゴリで仕分けたい　条件としてカテゴリidはgetパラメータの番号
      $sql .= ' WHERE category_id =  '.$category;
    }
    //順番指定があれば
    if(!empty($sort)){
      switch($sort){
        case 1 :
          $sql .= ' ORDER BY price DESC';
          break;
        case 2 :
          $sql .= ' ORDER BY price ASC';
      }
    }

    $sql .= ' LIMIT '.$listSpan.' OFFSET '.$currentMinNum;//くっつける

    $data = array();
    debug('データ：'.print_r($data, true));
    debug('SQL:'.$sql);//くっつけた結果のsqlを表示
    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      $rst['data'] = $stmt->fetchAll();

      /////////////////
      return $rst; //これを最終的に返してくる
      ////////////////
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生:'.$e->getMessage());
  }
}

//ページネーション
function pagenation($totalPageNum, $currentPageNum){
    debug('ページネーションのための値を取得します');

    global $dbProductData;
    $totalPageNum = $dbProductData['total_page'];

    //総ページ数が5以内の場合は全て表示
    if( $totalPageNum <= 5){
    $minPageNum = 1;
    $maxPageNum = $totalPageNum;
    //総ページ数が5以上かつ現在のページが3,2,1の場合は1〜5を表示
    }elseif( $currentPageNum <= 3){
    $minPageNum = 1;
    $maxPageNum = 5;
    //総ページ数が5以上かつ現在のページが総ページ-2,-1,-0の場合はラスト5個を表示
    }elseif( $currentPageNum >= $totalPageNum-2 ){
    $minPageNum = $totalPageNum-4;
    $maxPageNum = $totalPageNum;
    //それ以外の場合は現在ページの前後2つを表示
    }else{
    $minPageNum = $currentPageNum - 2;
    $maxPageNum = $currentPageNum +2;
    }
    debug('返した最小データmin:'.print_r($minPageNum, true));
    debug('返した最大データmax:'.print_r($maxPageNum, true));
    debug('最大値最小値の処理が成功しました。');

    echo'<div class="pagenation">';
      echo'<ul class="pagenation-list">';


        if($currentPageNum !== 1 ){
          echo'<li class="list-item"><a href="?p=1">&lt;</a></li>';
        }
        for($i = $minPageNum; $i <= $maxPageNum; $i++){
          echo'<li class="list-item"><a href=?p='.$i.'>'.$i.'</a></li>';
        }
        if($currentPageNum !== $maxPageNum && $maxPageNum > 1){
           echo'<li class="list-item"><a href="?p='.$maxPageNum.'">&gt;</a></li>';
         }

      echo'</ul>';
    echo'</div>';
  }

//商品詳細の商品情報
  function getProductOne($p_id){
    debug('商品詳細ための商品情報を取得します');
    try{
      $dbh = dbConnect();
      $sql = 'SELECT p.id, p.name, p.comment, p.price, p.pic1, p.pic2, p.pic3, p.user_id, p.create_date, c.name AS category FROM product AS p LEFT JOIN category AS c ON p.category_id=c.id WHERE p.id=:p_id AND p.delete_flg=0 AND c.delete_flg=0';
      //条件はp_id
      $data = array(':p_id'=>$p_id);
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        return $stmt->fetch(PDO::FETCH_ASSOC);
        debug('フェッチ成功しました');
      }else{
        return false;
      }
    }catch(Exception $e){
      error_log('エラー発生:'.$e->getMessage());
    }
  }

//商品詳細の画像関数
function showImg($img){
  if(empty($img)){
    return 'img/noimg.jpeg';
  }else{
    $img;
  }
}
// 基本のパラメータである?$key=　を生成 getパラメータの生成ができれば&以降はif文で追加することができる
function getParam($key){
  debug('GETパラメータを付与します');

  //in_arrayで引数と同じ$Keyなければ付与
  $str = '?';
  //get送信を$keyと$valに分解したいのでforeachを使う
  foreach($_GET as $key => $val):
    $param = $str.$key.'='.$val;
  endforeach;

  return $param;

  if(!empty($param)){
  }
}

//商品詳細で連絡掲示板へ移動するためのログイン認証関数
function isLogin(){
  debug('ログイン認証を確認します');

  if(!empty($_SESSION['login_date'])){
    debug('すでにログインしていますので新規掲示板を作成して連絡掲示板へ遷移します');
    return true;
  }else{
    debug('ログインしていないユーザーなのでログイン推奨を出します');
    return false;
  }
}
?>
