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

$sumary_table = array(  'year'=>'年',
                        'week_num'=>'周',
                        'user_id'=>'用户ID',
                        'shijiancha'=>'最近购买时间',
                        'ord_num'=>'购买频率',
						'cate_num'=>'购买商品种类',
                        'avg_money'=>'平均每次消费额',
                        'max_money'=>'单次最高消费额'
                      );

$start1 = trim($_GET['start1']);
$end1 = trim($_GET['end1']);
$condition = "";
if(!$start1 || !$end1 ){
	$start1 = date('Y',strtotime("-1 week"));
	$end1 = date('W',strtotime("-1 week"));
}

$d= date('D');
if($d=="Mon"){
$nowdate=date('Y-m-d',strtotime("-0 week Monday"));
}else{
	$nowdate=date('Y-m-d',strtotime("-1 week Monday"));
}

$sql = "SELECT year,week_num,user_id,cate_num,ord_num,g_money/ord_num as 'avg_money',datediff('$nowdate',max_date) as 'shijiancha',
max_money from operate_user_week where 1=1 ";
$condition .= " and year = '".$start1."' and week_num= '".$end1."' ";
$sql .= $condition;


$datass = DB::GetQueryResult ( $sql."  ;", false ); 

//foreach($datass as $key=>$data ){
//	$datass[$key]['kedanjia'] = round($data['sale']/$data['user'],2);
//	$datass[$key]['zhuanhua_rate'] = round($data['order_num']*100/$data['total_uv'],2)."%";
//	$datass[$key]['pay_kedanjia'] = round($data['pay_sale']/$data['pay_user'],2);
//	$datass[$key]['pay_rate'] = round($data['pay_order']*100/$data['total_uv'],2)."%";
//}

$excel_name = "用户价值(按周)";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>