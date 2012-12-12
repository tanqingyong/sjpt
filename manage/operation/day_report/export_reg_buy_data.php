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
                        'reg_user'=>'注册用户数',
                        'reg_buy_user'=>'购买用户数',
                        'order_num'=>'支付订单数',
                        'buy_money'=>'支付金额'
                      );

$sql = "select l.date,p.reg_user,l.reg_buy_user,l.order_num,l.buy_money from lwj_reguser l,product_reg_login_tmp p 
where 1=1 and l.date=p.date ";
if($filter_date_start && $filter_date_end){
	$sql .= " and l.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $sql .= " and l.date = '$filter_date_start' ";
}elseif($filter_date_end){
    $sql .= " and l.date = '$filter_date_end' ";
}else{
	$date_start = date('Y-m-01',time());
	$date_end = date('Y-m-t',time());
	$sql .= " and l.date between '$date_start' and  '$date_end' ";
}

$datass = DB::GetQueryResult ( $sql." order by l.date ;", false ); 


$excel_name = "注册购买用户数";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>