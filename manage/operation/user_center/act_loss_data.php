<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "SELECT r.date,r.city_name,r.reg_user,r.reg_total_user,r.buy_user,r.return_visit_user,r.loss_user,
concat(round(r.loss_user/r.reg_total_user*100,2),'%') as 'loss_rate',
r.new_loss_user,concat(round(r.new_loss_user/r.reg_user*100,2),'%') as 'new_loss_rate',
concat(round(r.reg_user/r.return_visit_user*100,2),'%') as 'new_user_rate'
 from reg_user_city r where 1=1 ";
$sql_count = "select count(1) as count from reg_user_city r where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$city_name=trim($_GET['city_name']);
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and r.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
	$condition .= " and r.date = '$filter_date_start' ";
}elseif($filter_date_end){
	$condition .= " and r.date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and r.date between '$filter_date_start' and  '$filter_date_end' ";
}
if($city_name){	
	$condition .= " and r.city_name='$city_name' ";
}

$sql .= $condition;
$sql_count .= $condition;


$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .=" order by r.date limit $offset,$pagesize;";

$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_act_loss_data',
array ( 'datas'=>$datas,
                'pagestring'=>$pagestring,
				'city_name'=>$city_name,
                'filter_date_start'=>$filter_date_start,
                'filter_date_end'=>$filter_date_end,
                'page_size'=>$page_size) );

?>
