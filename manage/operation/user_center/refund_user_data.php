<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "select * from (SELECT * from operate_refund_user where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);


$condition = "";

if($filter_date_start){
	$condition .= " and date = '$filter_date_start' ";
}else{
	$filter_date_start = date('Y-m-d',time()-24*3600);
	$condition .= " and date = '$filter_date_start' ";
}


$sql .= $condition;
$sql_count = "select count(1) as 'count' from operate_refund_user where 1=1 ".$condition;
$result = DB::GetQueryResult($sql_count,true);


$sql .=" ORDER BY refund_items desc limit 100 ) a";

$sql_sum="SELECT date,
'-' as 'user_id','-' as 'city_name',sum(refund_items) as 'refund_items',sum(refund_goods_num) as 'refund_goods_num',
sum(refund_money) as 'refund_money',sum(buy_user) as 'buy_user'
 from (select * FROM operate_refund_user where 1=1 ".$condition." ORDER BY refund_items desc limit 101,".$result['count'].") o";
$datas = DB::GetQueryResult($sql,false);
$data_sum = DB::GetQueryResult($sql_sum,TRUE);

echo template ( 'manage_refund_user_data',
array ( 'datas'=>$datas,
		'data_sum'=>$data_sum,
                'pagestring'=>$pagestring,
                'filter_date_start'=>$filter_date_start,
                'filter_date_end'=>$filter_date_end) );

?>
