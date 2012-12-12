<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "select * from (SELECT month(date) as 'mon',user_id,'-' as 'city_name',sum(refund_items) as 'refund_items',sum(refund_goods_num) as 'refund_goods_num',
sum(refund_money) as 'refund_money',sum(buy_user) as 'buy_user' from operate_refund_user where user_id>0 ";

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


$sql .= $condition;
$sql_count = "select count(1) as 'count' from operate_refund_user where user_id>0 ".$condition;
$result = DB::GetQueryResult($sql_count,true);


$sql .=" group by mon,user_id ORDER BY refund_items desc limit 100 ) a";

$sql_sum="SELECT mon,
'-' as 'user_id','-' as 'city_name',sum(refund_items) as 'refund_items',sum(refund_goods_num) as 'refund_goods_num',
sum(refund_money) as 'refund_money',sum(buy_user) as 'buy_user'
 from (select month(date) as 'mon',user_id,'-' as 'city_name',sum(refund_items) as 'refund_items',sum(refund_goods_num) as 'refund_goods_num',
sum(refund_money) as 'refund_money',sum(buy_user) as 'buy_user' FROM operate_refund_user where user_id>0 ".$condition." group by mon,user_id ORDER BY refund_items desc limit 101,".$result['count'].") o";
$datas = DB::GetQueryResult($sql,false);
$data_sum = DB::GetQueryResult($sql_sum,TRUE);

echo template ( 'manage_refund_user_mon_data',
array ( 'datas'=>$datas,
		'data_sum'=>$data_sum,
                'pagestring'=>$pagestring,
                'filter_date_start'=>$filter_date_start,
                'filter_date_end'=>$filter_date_end) );

?>
