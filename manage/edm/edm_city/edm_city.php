<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page(); 

$filter_date_start = trim($_GET['startdate']);
$filter_date_end = trim($_GET['enddate']);
$filter_city = trim($_GET['city']);

if( $filter_date_start && $filter_date_end ){
    $condition .= " and edm_city.date between '$filter_date_start' and '$filter_date_end' ";
}elseif( $filter_date_start ){
    $condition .= " and edm_city.date between '$filter_date_start' and '$filter_date_start' ";
}elseif( $filter_date_end ){
    $condition .= " and edm_city.date between '$filter_date_end' and '$filter_date_end' ";
}else{
    $filter_date_start = date("Y-m")."-01";
    $filter_date_end = date("Y-m-d");
    $condition .= " and edm_city.date between '$filter_date_start' and '$filter_date_end' ";
}
if( $filter_city ){
	$condition .= " and f_edm.targe_city='$filter_city' ";
}

$sql = " select edm_city.date as date,f_edm.targe_city as city,sum(ip) as ip_sum,sum(uv) as uv_sum,sum(pv) as pv_sum,
sum(order_num) as order_sum,round(sum(total_price),2) as money_sum,sum(register_num) as register_sum,
sum(suc_order_num) as suc_order_sum,round(sum(suc_total_price),2) as suc_money_sum,
round(sum(suc_order_num)*100/sum(order_num),2) as suc_order_rate,
round(sum(order_num)*100/sum(ip),2) as order_rate,round(sum(register_num)*100/sum(ip),2) as register_rate
from edm_city 
left join f_edm on f_edm.source_city=edm_city.city
where 1=1 $condition  
group by edm_city.date,f_edm.targe_city order by edm_city.date desc,sum(ip) desc";
$sql_count = " select count(1) as count from ( select 1 from edm_city 
left join f_edm on f_edm.source_city=edm_city.city
where 1=1 $condition  
group by edm_city.date,f_edm.targe_city) as a ";
$result_count = DB::GetQueryResult($sql_count,true);

$count = $result_count['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);
$sql .= " limit $offset,$pagesize";

$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_edm_edm_city', 
                array ( 'datas'=>$datas,
                        'pagestring'=>$pagestring,
                        'filter_date_start'=>$filter_date_start,
                        'filter_date_end'=>$filter_date_end,
                        'page_size'=>$page_size,
                        'filter_city' => $filter_city,
                       )
              );