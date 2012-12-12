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
                        'city_name'=>'城市',
                        'reg_user'=>'新增注册用户数',
                        'reg_total_user'=>'累计注册用户数',
                        'buy_user'=>'购买用户数',
						'return_visit_user'=>'回访用户数',
                        'loss_user'=>'流失用户数',
						'loss_rate'=>'全部用户流失率',
                        'new_loss_user'=>'新用户流失数',
						'new_loss_rate'=>'新用户流失率',
                        'new_user_rate'=>'新用户占活跃用户的比例'
                      );
//wait for data 

$sql = "SELECT r.date,r.city_name,r.reg_user,r.reg_total_user,r.buy_user,r.return_visit_user,r.loss_user,
concat(round(r.loss_user/r.reg_total_user*100,2),'%') as 'loss_rate',
r.new_loss_user,concat(round(r.new_loss_user/r.reg_user*100,2),'%') as 'new_loss_rate',
concat(round(r.reg_user/r.return_visit_user*100,2),'%') as 'new_user_rate'
 from reg_user_city r where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$city_name=trim($_GET['city_name']);
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and r.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
	$condition .= " and r.date = '$filter_date_start' ";
}elseif($filter_date_end){
	$condition .= " and r.date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and r.date between '$filter_date_start' and  '$filter_date_end' ";
}


if($city_name){	
	$condition .= " and r.city_name='$city_name' ";
}
$sql .= $condition;
$datass = DB::GetQueryResult ( $sql." order by r.date ;", false ); 

//foreach($datass as $key=>$data ){
//	$datass[$key]['kedanjia'] = round($data['sale']/$data['user'],2);
//	$datass[$key]['zhuanhua_rate'] = round($data['order_num']*100/$data['total_uv'],2)."%";
//	$datass[$key]['pay_kedanjia'] = round($data['pay_sale']/$data['pay_user'],2);
//	$datass[$key]['pay_rate'] = round($data['pay_order']*100/$data['total_uv'],2)."%";
//}

$excel_name = "用户活跃_流失(按日)";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>