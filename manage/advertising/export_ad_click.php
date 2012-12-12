<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/manage/export_excel.php');
need_login();
$table_array = array( 'date'=>'日期', 
                      'city_name'=>'城市',
                      'ads_name'=>'广告名称',
                      'ads_type'=>'类型',
                      'ads_id'=>'广告ID',
                      'ads_url'=>'广告URL',
                      'pv_sum'=>'PV',                     
                      'uv_sum'=>'UV',
                      'ip_sum'=>'IP',
                    );
$filter_date_start = trim($_GET["startdate"]);
$filter_date_end = trim($_GET["enddate"]);
$ad_type = trim($_GET["ad_type"]);
$ad_id = trim($_GET["ad_id"]);
$city = trim($_GET["city"]);
$condition ="";
if( $filter_date_start && $filter_date_end ){
    $condition .= " and date  between '$filter_date_start' and '$filter_date_end' ";
}elseif( $filter_date_start ){
    $condition .= " and date = '$filter_date_start' ";
}elseif( $filter_date_end ){
    $condition .= " and date = '$filter_date_end'";
}else{
    $filter_date_start = date("Y-m")."-01";
    $filter_date_end = date("Y-m-d");
    $condition .= " and date  between '$filter_date_start' and '$filter_date_end' ";
}
if( $ad_type ){
    $condition .= " and ads_type = '$ad_type' ";
}
if( $ad_id ){
    $condition .= " and ads_id like '%$ad_id%' ";
}
if( $city ){
    $condition .= " and city_name = '$city' ";
}

$sql = "select date,ads_name,ads_type,ads_id,ads_url,city_name,sum(ip) as ip_sum,sum(uv) as uv_sum,sum(pv) as pv_sum from ads_source_data where 1=1 $condition  group by ads_id order by ip_sum desc ";

$excel_name = "广告位点击";

export_excel($sql,$table_array,$sum_array,$excel_name);
?>