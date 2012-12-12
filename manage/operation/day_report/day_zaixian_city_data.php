<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "select date,city_name,count(goods_id) as 'zaixiandan',sum(sale_num) as 'sale_num',sum(sale_money) as 'sale_money' from zaixian_sale where 1=1 ";
$sql_count = "select count(1) as count from (select date,city_name from zaixian_sale  where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$city_name = trim($_GET['city_name']);
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
if($city_name){
	$condition .= " and city_name='$city_name'";	
}

$sql .= $condition;
$sql_count .= $condition;

$sql_count .= " group by date,city_name) a";

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .=" group by date,city_name order by date asc,sale_money desc limit $offset,$pagesize;";

$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_day_zaixian_city_data', 
                array ( 'datas'=>$datas,'pagestring'=>$pagestring,'filter_date_start'=>$filter_date_start,
          'city_name'=>$city_name,     'filter_date_end'=>$filter_date_end,'page_size'=>$page_size) );

?>
