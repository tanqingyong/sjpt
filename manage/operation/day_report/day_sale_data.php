<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "select a.data_date,a.order_num,a.user,a.product,a.sale,a.pay_order,a.pay_user,a.pay_product,a.pay_sale,b.total_uv,
a.pay_order_num_zero,a.pay_user_zero,a.pay_product_zero,a.product_zero,a.gross_profit 
from buss_country_day_order a join web_country_day_pvuvip b on a.data_date=b.data_date where 1=1 ";
$sql_count = "select count(1) as count from buss_country_day_order a join web_country_day_pvuvip b on a.data_date=b.data_date where 1=1 ";

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

echo template ( 'manage_day_sale_data', 
                array ( 'datas'=>$datas,'pagestring'=>$pagestring,'filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'page_size'=>$page_size) );

?>
