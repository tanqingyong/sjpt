<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_login();
need_page(); 
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
$sql_count = "select count(1) as count from (select ads_id from ads_source_data where 1=1 ".$condition." group by ads_id) as ads";
$result_count = DB::GetQueryResult($sql_count,true);
$count = $result_count['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql = "select date,ads_name,ads_type,ads_id,ads_url,city_name,sum(ip) as ip_sum,sum(uv) as uv_sum,sum(pv) as pv_sum from ads_source_data where 1=1 $condition  group by ads_id order by ip_sum desc limit $offset,$pagesize ";
$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_advertising_ad_click', 
                array ( 'datas'=>$datas,
                        'pagestring' => $pagestring,
                        'filter_date_start' => $filter_date_start,
                        'filter_date_end' => $filter_date_end,
                        'ad_type' => $ad_type,
                        'ad_id' => $ad_id,
                        'city' => $city,
                        'page_size' => $page_size,
                        'offset' => $offset
                       )
              );
?>