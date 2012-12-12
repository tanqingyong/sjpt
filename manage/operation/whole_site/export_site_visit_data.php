<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/export_excel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/PHPExcel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/DB.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/Cache.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/Config.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/PHPExcel/Writer/Excel2007.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/function/utility.php');

need_login();
$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);

$sumary_table = array(  'data_date'=>'日期',
                        'total_pv'=>'整站PV',
                        'total_uv'=>'整站UV',
                        'total_ip'=>'整站IP',
                        'time_online_peruser'=>'人均访问时长',
                        'index_pv'=>'首页PV',
                        'index_uv'=>'首页UV',
                        'index_ip'=>'首页IP',
						 'click_onlyone_ratio'=>'整站跳出率'
                      );
//wait for data#网站访问数据(日期，整站PV，整站UV，整站IP，人均访问时长，首页PV，首页UV,首页IP，整站跳出率化率)

$sql = "select a.data_date,a.total_pv,a.total_uv,a.total_ip,b.time_online_peruser,a.index_pv,a.index_uv,a.index_ip,b.click_onlyone_ratio from web_country_day_pvuvip a join webbuss_country_day_other b on a.data_date=b.data_date where 1=1 ";
if($filter_date_start && $filter_date_end){
	$sql .= " and a.data_date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $sql .= " and a.data_date = '$filter_date_start' ";
}elseif($filter_date_end){
    $sql .= " and a.data_date = '$filter_date_end' ";
}else{
	$date_start = date('Y-m-01',time());
	$date_end = date('Y-m-t',time());
	$sql .= " and a.data_date between '$date_start' and  '$date_end' ";
}

$datass = DB::GetQueryResult ( $sql." order by a.data_date ;", false ); 

foreach($datass as $key=>$data ){
	$datass[$key]['click_onlyone_ratio'] = ($data['click_onlyone_ratio']*100);
	$datass[$key]['click_onlyone_ratio'] .="%";
}

$excel_name = "网站访问数据";
export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>