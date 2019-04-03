<?php
require('function.php');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');
debug('お気に入り登録機能');
debug('『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『『');

debugLogStart();

//trueのみを返したいのでisLogin 

if(isset($_POST['productId']) && isset($_SESSION['user_id']) && isLogin()){

	debug('AjaxによりPOSTさせました。ログイン認証okです');
	$p_id = $_POST['productId'];
	debug('Ajaxの商品id確認:'.print_r($p_id, true));

	try{
	 	$dbh = dbConnect();

	 	$sql = 'SELECT * FROM `like` WHERE product_id=:p_id AND user_id=:u_id';
	 	$data = array(':p_id'=>$p_id, ':u_id'=>$_SESSION['user_id']);
	 	$stmt = queryPost($dbh, $sql, $data);
	 	$result = $stmt->rowCount();

	 	if(!empty($result)){
	 		debug('すでにお気に入りをDBに登録していたので削除します');

	 		$sql = 'DELETE FROM `like` WHERE product_id=:p_id AND user_id=:u_id';
	 		$data = array(':p_id'=>$p_id, ':u_id'=>$_SESSION['user_id']);
	 		$stmt = queryPost($dbh, $sql, $data);

	 	}else{
	 		$sql = 'INSERT INTO `like`(product_id, user_id, create_date) VALUES(:p_id, :u_id, :date)';
		 	$data = array(':p_id'=>$p_id, ':u_id'=>$_SESSION['user_id'], ':date'=>date('Y-m-d H:i:s'));
		 	debug('流し込みデータ:'.print_r($data, true));
		 	$stmt = queryPost($dbh, $sql, $data);

		 	if($stmt){
		 		debug('お気に入り登録しました');
		 	}else{
		 		debug('お気に入り登録できませんでした');
		 	}
	 	}
	}catch(Exception $e){
		error_log('エラー発生:'.$e->getMessage());
	}
}


debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面商事処理終了');
?>