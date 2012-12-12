<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "SELECT * from product_account_recharge where 1=1 ";
$sql_count = "select count(1) as count from product_account_recharge where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$recharge_type  = $_GET['typename'];
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
	$condition .= " and date = '$filter_date_start' ";
}elseif($filter_date_end){
	$condition .= " and date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and date between '$filter_date_start' and  '$filter_date_end' ";
}

$sql .= $condition;
$sql_count .= $condition;
if($recharge_type){
	$sql .=" and recharge_type='".$recharge_type."' ";
	$sql_count .=" and recharge_type='".$recharge_type."' ";
}

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .=" order by date limit $offset,$pagesize;";

$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_recharge_type_data',
array ( 'datas'=>$datas,
                'pagestring'=>$pagestring,
				'recharge_type'=>$recharge_type,
                'filter_date_start'=>$filter_date_start,
                'filter_date_end'=>$filter_date_end,
                'page_size'=>$page_size) );

?>
