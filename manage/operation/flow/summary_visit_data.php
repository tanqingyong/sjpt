<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();

$rl_type=$_GET['type'];

$sql = "SELECT * from product_summary_visit where 1=1 ";
$sql_count = "select count(1) as count from product_summary_visit where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);

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

if($rl_type){
	$condition .=" and pindao='".$rl_type."'";	
}

$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .=" order by date limit $offset,$pagesize;";
$datas = DB::GetQueryResult($sql,false);


echo template ( 'manage_summary_visit_data',
array ( 'datas'=>$datas,
				'type'=>$rl_type,
                'pagestring'=>$pagestring,
                'filter_date_start'=>$filter_date_start,
                'filter_date_end'=>$filter_date_end,
                'page_size'=>$page_size) );

?>
