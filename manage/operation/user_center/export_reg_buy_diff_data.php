<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/export_excel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/PHPExcel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/DB.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/Cache.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/Config.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/PHPExcel/Writer/Excel2007.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/function/utility.php');

need_login();
$sumary_table = array(  'date'=>'日期',
                        'times1'=>'1天',
                        'times2'=>'2天',
                        'times3'=>'3天',
                        'times4_5'=>'4~5天',
						'times6_10'=>'6~10天',
                        'times11_30'=>'11~30天',
                        'times31_60'=>'31~60天',
                        'times61_120'=>'61~120天',
                        'times121_180'=>'121~180天',
                        'times181_360'=>'181~360天',
                        'times361up'=>'360天以上'
						
                      );
//wait for data
                      
$sql = "SELECT * from reg_buy_diff where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);

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


$datass = DB::GetQueryResult ( $sql." order by date ;", false ); 

//foreach($datass as $key=>$data ){
//	$datass[$key]['kedanjia'] = round($data['sale']/$data['user'],2);
//	$datass[$key]['zhuanhua_rate'] = round($data['order_num']*100/$data['total_uv'],2)."%";
//	$datass[$key]['pay_kedanjia'] = round($data['pay_sale']/$data['pay_user'],2);
//	$datass[$key]['pay_rate'] = round($data['pay_order']*100/$data['total_uv'],2)."%";
//}

$excel_name = "注册购买日期分布";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>