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

$sumary_table = array(  'date'=>'日期',
                        'url'=>'URL',
                        'pv'=>'PV',
                        'uv'=>'UV',
                        'ip'=>'IP',
                        'out_rate'=>'跳出率'
                      );

$sql = "select date,url,pv,uv,ip,out_rate from goods_detail a where 1=1 ";
if($filter_date_start && $filter_date_end){
	$sql .= " and a.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $sql .= " and a.date = '$filter_date_start' ";
}elseif($filter_date_end){
    $sql .= " and a.date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$sql .= " and a.date between '$date_start' and  '$date_end' ";
}

$datass = DB::GetQueryResult ( $sql." order by a.date desc,a.pv desc;", false ); 
foreach($datass as $key=>$data ){
	$datass[$key]['out_rate'] = ($data['out_rate']*100);
	$datass[$key]['out_rate'] .="%";
}


$excel_name = "商品详情页来源url";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>