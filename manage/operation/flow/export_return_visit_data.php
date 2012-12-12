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
// 1天	2天	3天	4天	5天	6天	7天	8天	9天	10天	10-15天	15-30(31)天
$sumary_table = array(  'months'=>'日期',
						'days0'=>'只访问1天',
                        'days1'=>'回访1天',
                        'days2'=>'回访2天',
                        'days3'=>'回访3天',
                        'days4'=>'回访4天',
						'days5'=>'回访5天',
                        'days6'=>'回访6天',
                        'days7'=>'回访7天',
                        'days8'=>'回访8天',
                        'days9'=>'回访9天',
                        'days10'=>'回访10天',
                        'days1115'=>'回访11-15天',
                        'days1630'=>'回访大于15天'
						
                      );
//wait for data
$sql = "SELECT * from product_user_return_visit where 1=1 ";

$start1 = trim($_GET['start1']);
$end1 = trim($_GET['end1']);
$start2 = trim($_GET['start2']);
$end2 = trim($_GET['end2']);

$condition = "";
if(!$start1 || !$start2 || !$end1 || !$end2){
	$start1 = "2010";
	$start2 = "01";
	$end1 = date('Y',time());
	$end2 = date('m',time());
}

$condition .=" and id >=(SELECT min(id) from product_user_return_visit where months>='".$start1."-".$start2."') AND id <=(SELECT max(id) from product_user_return_visit where months<='".$end1."-".$end2."')";

$sql .= $condition;


$datass = DB::GetQueryResult ( $sql."  ;", false ); 

//foreach($datass as $key=>$data ){
//	$datass[$key]['kedanjia'] = round($data['sale']/$data['user'],2);
//	$datass[$key]['zhuanhua_rate'] = round($data['order_num']*100/$data['total_uv'],2)."%";
//	$datass[$key]['pay_kedanjia'] = round($data['pay_sale']/$data['pay_user'],2);
//	$datass[$key]['pay_rate'] = round($data['pay_order']*100/$data['total_uv'],2)."%";
//}

$excel_name = "用户回访天数统计";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>