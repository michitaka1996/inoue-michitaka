<?php

$i =0;
foreach($items as $key => $value){
	echo $value['price'].'<br>';
	$i++;
	if($i == 6){
		break;
	}
}

?>
