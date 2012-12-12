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
                        'url'=>'URL',
                        'pv'=>'PV',
                        'uv'=>'UV',
                        'ip'=>'IP',
                        'click_onlyone_ratio'=>'跳出率'
                      );
//wait for data#网站访问数据

$sql = "select data_date,url,pv,uv,ip,click_onlyone_ratio from country_day_outfromurl a where 1=1 ";
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

$excel_name = "外站来源URL";
export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>