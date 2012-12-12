<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "select
d.date,d.city,d.type1_name,d.goods_id,d.goods_name,p.uv,'频道UV',d.type2_name,d.type3_name,p.order_num,p.goods_num,p.suc_order_num,
p.suc_goods_num,p.user_num,p.suc_user_num,p.suc_total_price,p.maoli,p.all_order_num,p.all_goods_num,p.all_suc_order_num,
p.all_suc_goods_num,p.all_user_num,p.all_suc_user_num,p.all_suc_total_price,p.all_maoli,p.avg_order_num,p.avg_goods_num,p.avg_suc_order_num,
p.avg_suc_goods_num,p.avg_user_num,p.avg_suc_user_num,p.avg_suc_total_price,p.avg_maoli,d.price,d.jiesuan_price,
(d.price-d.jiesuan_price) as 'goods_maoli',d.start_time,gip.city_name as 'shengfen',gip.url
from dim_cat_goods d LEFT JOIN product_sort p on d.date=p.date and d.goods_id=p.goods_id,
goods_id_province gip
where 1=1 and p.goods_id=gip.goods_id ";
$sql_count = "select count(1) as count  from dim_cat_goods d LEFT JOIN product_sort p on d.date=p.date and d.goods_id=p.goods_id where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and d.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
	$condition .= " and d.date = '$filter_date_start' ";
}elseif($filter_date_end){
	$condition .= " and d.date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and d.date between '$filter_date_start' and  '$filter_date_end' ";
}

$city_name = trim($_GET['city_name']);
$pindao = trim($_GET['pindao']);
$cate_second = trim($_GET['cate_second']);
$cate_third = trim($_GET['cate_third']);

if($city_name){
	$condition .= " and d.city ='".$city_name."' ";
}
if($pindao){
	$condition .= " and d.type1_name ='".$pindao."' ";
}
if($cate_second){
	$condition .= " and d.type2_name ='".$cate_second."' ";
}
if($cate_third){
	$condition .= " and d.type3_name ='".$cate_third."' ";
}

$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .=" limit $offset,$pagesize;";

$datas = DB::GetQueryResult($sql,false);




echo template ( 'manage_goods_sort_data',
				array ( 'datas'=>$datas,
				'pagestring'=>$pagestring,
				'filter_date_start'=>$filter_date_start,
				'filter_date_end'=>$filter_date_end,
				'city_name'=>$city_name,
				'pindao'=>$pindao,
				'cate_second'=>$cate_second,
				'cate_third'=>$cate_third,
				'page_size'=>$page_size)
 );

?>
