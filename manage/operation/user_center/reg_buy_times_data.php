<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "SELECT o.* from operate_not_buy o where 1=1 ";
$sql_count = "select count(1) as count from operate_not_buy o where 1=1  ";

$start1 = trim($_GET['start1']);
$end1 = trim($_GET['end1']);
$start2 = trim($_GET['start2']);
$end2 = trim($_GET['end2']);

$condition = "";
if(!$start1 || !$start2 || !$end1 || !$end2){
	$start1 = "2010";
	$start2 = "01";
	$end1 = date('Y',time());
	$end2 = date('m',time());
}

 $condition .= " and months  between '".$start1."-".$start2."' and '".$end1."-".$end2."' ";
//$condition .=" and b.id >=(SELECT if(id>=1,min(id),1) from buy_times where months='".$start1."-".$start2."') AND b.id <=(SELECT max(id) from buy_times where months<='".$end1."-".$end2."')";
$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .=" order by months  limit $offset,$pagesize;";

$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_reg_buy_times_data',
array ( 'datas'=>$datas,
                'pagestring'=>$pagestring,
				'start1'=>$start1,
				'start2'=>$start2,
				'end1'=>$end1,
				'end2'=>$end2,
                'page_size'=>$page_size) );

?>
