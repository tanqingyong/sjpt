<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "select data_date,url,pv,uv,ip,click_onlyone_ratio from country_day_outfromurl a where 1=1 ";
$sql_count = "select count(1) as count from country_day_outfromurl a where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and a.data_date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $condition .= " and a.data_date = '$filter_date_start' ";
}elseif($filter_date_end){
    $condition .= " and a.data_date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and a.data_date between '$filter_date_start' and  '$filter_date_end' ";
}


$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):50;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .="order by a.data_date  limit $offset,$pagesize;";

$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_out_visit_url', 
                array ( 'datas'=>$datas,'pagestring'=>$pagestring,'filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'page_size'=>$page_size) );

?>
