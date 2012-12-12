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

$sumary_table = array(  'months'=>'日期',
                        'times1'=>'购买1次用户数',
                        'times2'=>'购买2次用户数',
                        'times3'=>'购买3次用户数',
                        'times4'=>'购买4次用户数',
						'times5'=>'购买5次用户数',
                        'times6'=>'购买6次用户数',
                        'times7'=>'购买7次用户数',
                        'times8'=>'购买8次用户数',
                        'times9'=>'购买9次用户数',
                        'times10'=>'购买10次用户数',
                        'times11'=>'购买10次以上用户数'
						
                      );
//wait for data
$sql = "SELECT * from buy_times where 1=1 ";

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
//$condition .= " and months  between '".$start1."-".$start2."' and '".$end1."-".$end2."' ";
$condition .=" and id >=(SELECT min(id) from buy_times where months>='".$start1."-".$start2."') AND id <=(SELECT max(id) from buy_times where months<='".$end1."-".$end2."')";

$sql .= $condition;


$datass = DB::GetQueryResult ( $sql."  ;", false ); 

//foreach($datass as $key=>$data ){
//	$datass[$key]['kedanjia'] = round($data['sale']/$data['user'],2);
//	$datass[$key]['zhuanhua_rate'] = round($data['order_num']*100/$data['total_uv'],2)."%";
//	$datass[$key]['pay_kedanjia'] = round($data['pay_sale']/$data['pay_user'],2);
//	$datass[$key]['pay_rate'] = round($data['pay_order']*100/$data['total_uv'],2)."%";
//}

$excel_name = "用户购买频次数据";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>