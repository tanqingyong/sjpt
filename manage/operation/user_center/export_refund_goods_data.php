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
                        'goods_id'=>'商品ID',
                        'refund_user'=>'退款用户数',
                        'refund_items'=>'退款笔数',
                        'refund_goods_num'=>'退款商品份数',
                        'refund_money'=>'退款金额',
                        'refund_rate'=>'用户退款率',
                        'refund_total_user'=>'累计退款用户数',
                        'refund_total_items'=>'累计退款笔数',
                        'refund_total_goods_num'=>'累计退款商品份数',
                        'refund_total_money'=>'累计退款金额'
						
                      );
//wait for data
$sql = "SELECT  * from operate_refund_goods where goods_id>0 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);

$condition = "";
if($filter_date_start){
	$condition .= " and date = '$filter_date_start' ";
}else{
	$filter_date_start = date('Y-m-d',time()-24*3600);
	$condition .= " and date = '$filter_date_start' ";
}

$sql .= $condition;

$datass = DB::GetQueryResult ( $sql." order by date ;", false ); 

foreach($datass as $key=>$data ){
	$datass[$key]['refund_rate'] = round($data['refund_user']*100/$data['buy_user'],2)."%";
}

$excel_name = "退款数据(按商品)";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>