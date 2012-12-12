<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');
need_login();
$aa = opendir(dirname(dirname(__FILE__)).'/doc/');
$i=1;
$temp = "<table >";
while($bb=readdir($aa)){
	if(strlen($bb)>4 && $bb!='index.php'){
		$temp .= "<tr><td>".$i."、<a href=\"".$bb."\" target=_blank>".$bb."</a></td></tr>";
		$i++;
	}

}

$temp = $temp."</table>";

echo template ( 'manage_doc',array ( 'temp'=>$temp));
?>