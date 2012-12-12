<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "select a.data_date,a.total_pv,a.total_uv,a.total_ip,b.time_online_peruser,a.index_pv,a.index_uv,a.index_ip,b.click_onlyone_ratio from web_country_day_pvuvip a join webbuss_country_day_other b on a.data_date=b.data_date where 1=1 ";
$sql_count = "select count(1) as count from web_country_day_pvuvip a join webbuss_country_day_other b on a.data_date=b.data_date where 1=1 ";

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
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .="order by a.data_date  limit $offset,$pagesize;";

$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_site_visit_data', 
                array ( 'datas'=>$datas,'pagestring'=>$pagestring,'filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'page_size'=>$page_size) );

?>
