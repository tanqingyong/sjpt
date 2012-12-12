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

$sumary_table = array( 'date'=>'日期',
                        'pindao'=>'一级页面',
                        'list_page'=>'二级页面',
                        'pv'=>'PV',
                        'uv'=>'uv',
						'ip'=>'ip',
						'login_rate'=>'跳出率'
						
                      );
//wait for data
$sql = "SELECT date,pindao,list_page,pv,uv,ip,zhuolu_uv/uv as 'login_rate' from product_login_page where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$pay_type  = $_GET['type'];
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
	$condition .= " and date = '$filter_date_start' ";
}elseif($filter_date_end){
	$condition .= " and date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and date between '$filter_date_start' and  '$filter_date_end' ";
}

$sql .= $condition;
if($pay_type){
	$sql .=" and pindao='".$pay_type."' ";
}

$datass = DB::GetQueryResult ( $sql." order by date,pindao,list_page desc ;", false ); 



$excel_name = "着陆页统计";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>