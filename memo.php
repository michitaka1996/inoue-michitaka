<?php

//foreachの回数制限
$i =0;
foreach($items as $key => $value){
	echo $value['price'].'<br>';
	$i++;
	if($i == 6){
		break;
	}
}


//get送信のgetパラメータを用いてurl生成
//$del_keyはキー
//そもそもは取り除くこと、
//!in_arrayで合致してない場合だけ$_GET送信した$keyと$valを付け加える
function appendGetParam($del_key){

	if(!empty($_GET)){
		$str = '?';
		foreach($_GET as $key => $value){
			//指定した引数に合致してなければ
			if(!in_array($key, $del_key, true)){
				$str .= $key. '=' .$val .'&';
			}
		}
		$str = mb_substr(str,0,-1, "UTF-8");
		return $str;
	}
}	


function appendGetParam($arr_key){
	if(!empty($_GET)){
		$str = '?';
		foreach($_GET as $key=>$val){
			if(!in_array($key, $arr_key, true)){
				$srt .= $key. '='. $val .'&';
			}
		}
		$str = mb_substr(str)
	}
}

?>
