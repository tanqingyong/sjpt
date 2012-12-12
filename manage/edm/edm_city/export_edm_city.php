<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/manage/export_excel.php');

need_login();

$filter_date_start = trim($_GET['startdate']);
$filter_date_end = trim($_GET['enddate']);
$filter_city = trim($_GET['city']);

$table_array = array( 'date'=>'日期', 
                      'city'=>'城市',
                      'ip_sum'=>'IP',
                      'uv_sum'=>'UV',
                      'pv_sum'=>'PV',
                      'register_sum'=>'注册用户数',
                      'order_sum'=>'订单量',
                      'money_sum'=>'订单额',
                      'suc_order_sum'=>'成单量',
                      'suc_money_sum'=>'成单额',
                      'suc_order_rate'=>'成单率(%)',
                      'order_rate'=>'订单转化率(%)',
                      'register_rate'=>'注册转化率(%)'
);

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

$excel_name = "EDM城市数据";

export_excel($sql,$table_array,$data_field_array,$excel_name);
