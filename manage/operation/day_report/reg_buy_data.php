<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');
// 当天注册，购买用户的数据
need_login();
need_page();
$sql = "select l.date,p.reg_user,l.reg_buy_user,l.order_num,l.buy_money from lwj_reguser l,product_reg_login_tmp p 
where 1=1 and l.date=p.date ";
$sql_count = "select count(1) as count from lwj_reguser where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and l.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
	$condition .= " and l.date = '$filter_date_start' ";
}elseif($filter_date_end){
	$condition .= " and l.date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and l.date between '$filter_date_start' and  '$filter_date_end' ";
}

$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .=" order by l.date  limit $offset,$pagesize;";
$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_reg_buy_data',
array ( 'datas'=>$datas,
                'pagestring'=>$pagestring,
                'filter_date_start'=>$filter_date_start,
                'filter_date_end'=>$filter_date_end,
                'page_size'=>$page_size) );

?>
