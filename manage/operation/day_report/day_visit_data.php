<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$sql = "select a.data_date,a.total_ip,a.total_pv,a.total_uv,a.goods_pv,a.goods_uv,a.goods_ip,c.click_product,
pr.login_user,pr.reg_user,pr.reg_total_user,pb.buy_user,pb.buy_total_user,
c.time_online_peruser,c.click_zero_goodsnum,c.total_visit_times,b.union_nopay_user,b.union_pay_user 
from web_country_day_pvuvip a LEFT JOIN product_reg_login_tmp pr on a.data_date=pr.date left join product_buy_user pb on a.data_date=pb.date,
buss_country_day_order b,webbuss_country_day_other c 
where a.data_date=b.data_date and a.data_date=c.data_date  ";

$sql_count = "select count(1) as count from web_country_day_pvuvip a,buss_country_day_order b,webbuss_country_day_other c where a.data_date=b.data_date and a.data_date=c.data_date and 1=1 ";

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
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):31;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .="order by a.data_date  limit $offset,$pagesize;";
$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_day_visit_data', 
                array ( 'datas'=>$datas,'pagestring'=>$pagestring,'filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'page_size'=>$page_size) );

?>
