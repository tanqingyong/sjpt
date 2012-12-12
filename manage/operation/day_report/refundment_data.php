<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');
// 当天退款数据的数据
need_login();
need_page();
$sql = "select date,goods_id,buy_num,refundment_num,goods_name,user_name,refundment_reason,city,goods_price,cost_price,
refundment_money,pay_time,start_time,end_time,refundment_time from refundment where 1=1 ";
$sql_count = "select count(1) as count from refundment where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);

$condition = "";
if(!$filter_date_start){
    $filter_date_start=date('Y-m-d',time()-24*3600);
}
$condition .= " and date = '$filter_date_start' ";
$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .="  limit $offset,$pagesize;";

$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_refundment_data', 
                array ( 'datas'=>$datas,
                'pagestring'=>$pagestring,
                'filter_date_start'=>$filter_date_start,
                'page_size'=>$page_size) );

?>
