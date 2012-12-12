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

$sumary_table = array(  'mon'=>'月份',
                        'user_id'=>'用户ID',
						// 'city_name'=>'城市',
                        'refund_items'=>'退款笔数',
                        'refund_goods_num'=>'退款商品份数',
                        'refund_money'=>'退款金额',
                        'refund_rate'=>'订单退款率',


);
//wait for data
$sql = "SELECT  month(date) as 'mon',user_id,'-' as 'city_name',sum(refund_items) as 'refund_items',sum(refund_goods_num) as 'refund_goods_num',
sum(refund_money) as 'refund_money',sum(buy_user) as 'buy_user' from operate_refund_user where user_id>0 ";

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

$datass = DB::GetQueryResult ( $sql." group by mon,user_id order by date ;", false );

foreach($datass as $key=>$data ){
	$datass[$key]['refund_rate'] = round($data['refund_items']*100/$data['buy_user'],2)."%";
}

$excel_name = "退款数据(按月用户)";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>