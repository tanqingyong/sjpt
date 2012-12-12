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
                        'user_click1'=>'深度1',
                        'user_click2'=>'深度2',
                        'user_click3'=>'深度3',
                        'user_click4'=>'深度4',
                        'user_click5'=>'深度5',
                        'user_click610'=>'深度6~10',
                        'user_click_up10'=>'深度10以上'
                      );
//wait for data#网站访问数据(日期，整站PV，整站UV，整站IP，人均访问时长，首页PV，首页UV,首页IP，整站跳出率化率)

$sql = "select data_date,user_click1,user_click2,user_click3,user_click4,user_click5,user_click6+user_click7+user_click8+user_click9+user_click10 as user_click610,user_click_up10 from country_day_frequser a where 1=1 ";
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

$excel_name = "用户访问深度分布";
export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>