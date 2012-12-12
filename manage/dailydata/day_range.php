<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_login();
need_page(); 
$filter_date_start = $_GET["startdate"];
$filter_date_end = $_GET["enddate"];
$condition ="";
$compare_condition = "";
if( $filter_date_start && $filter_date_end ){
	$compare_date = date("Y-m-d",strtotime($filter_date_start)-7*3600*24);
    $condition .= " and p.date  between '$filter_date_start' and '$filter_date_end' ";
    $compare_condition = " and date  between '$compare_date' and '$filter_date_end' ";
}elseif( $filter_date_start ){
	$compare_date = date("Y-m-d",strtotime($filter_date_start)-7*3600*24);
    $condition .= " and p.date = '$filter_date_start' ";
    $compare_condition = " and date = '$compare_date' ";
}elseif( $filter_date_end ){
    $compare_date = date("Y-m-d",strtotime($filter_date_end)-7*3600*24);
    $condition .= " and p.date = '$filter_date_end'";
    $compare_condition = " and date = '$compare_date' ";
}else{
    $filter_date_start = date("Y-m")."-01";
    $filter_date_end = date("Y-m-d");
    $compare_date = date("Y-m-d",strtotime($filter_date_start)-7*3600*24);
    $condition .= " and p.date  between '$filter_date_start' and '$filter_date_end' ";
    $compare_condition = " and date  between '$compare_date' and '$filter_date_end' ";
}
$sql_count = "select count(1) as count from platform_update_new where platform_id='合计' ".$condition;
$result_count = DB::GetQueryResult($sql_count,true);
$count = $result_count['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql = "select p.*,round(t.refund_bishu/p.suc_order_num*100,2) as 'refund_bishu',round(t.refund_money/p.suc_total_price*100,2) as 'refund_money' from platform_update_new p left join (SELECT date,sum(refund_bishu) as 'refund_bishu',sum(refund_money) as 'refund_money' 
 from media_refund p where 1=1 ".$condition." GROUP BY date) t on p.date=t.date  where p.platform_id='合计' $condition order by p.date asc limit $offset,$pagesize ";

$sql_compare = "select * from platform_update_new p where p.platform_id='合计' $compare_condition order by p.date asc limit $offset,$pagesize ";
$sql_sum = "select sum(pv) as pv_sum, sum(order_num) as order_sum,sum(act_num) as act_sum, sum(total_price) as total_sum, 
sum(register_num) as register_sum, sum(suc_order_num) as suc_order_sum, sum(suc_total_price) as suc_total_sum,sum(user_num) as user_num 
from platform_update_new p
where platform_id='合计' $condition order by date asc ";

$datas = DB::GetQueryResult($sql,false);
$compare_datas = DB::GetQueryResult($sql_compare,false);
$sum_array = DB::GetQueryResult($sql_sum,true);
$day_data = array();
foreach($compare_datas as $key => $value){
    $day_data[$value["date"]] = $value;
}
foreach($datas as $key => $value){
    $day_last_week = date("Y-m-d",strtotime($value["date"])-7*24*3600);
    if($value["suc_total_price"] > $day_data[$day_last_week]["suc_total_price"]){
        $datas[$key]["trend"] = "↑";
    }else if($value["suc_total_price"] < $day_data[$day_last_week]["suc_total_price"]){
        $datas[$key]["trend"] = "↓";
    }else{
        $datas[$key]["trend"] = "-";
    }
    $datas[$key]["rate"] = round(abs($day_data[$day_last_week]["suc_total_price"]-$value["suc_total_price"])*100/$day_data[$day_last_week]["suc_total_price"],2)."%";
}

echo template ( 'manage_dailydata_day_range', 
                array ( 'datas'=>$datas,
                        'pagestring' => $pagestring,
                        'filter_date_start' => $filter_date_start,
                        'filter_date_end' => $filter_date_end,
                        'page_size' => $page_size,
                        'sum_array' => $sum_array
                       )
              );
?>