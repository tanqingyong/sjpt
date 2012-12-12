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
                        'city'=>'上线城市',
                        'start_time'=>'上线时间',
                        'end_time'=>'下线时间',
						'pay_time'=>'支付时间',
                        'goods_name'=>'商品名称',
                        'goods_price'=>'单价',
                        'cost_price'=>'成本单价',
						 'user_name'=>'用户名',
                        'buy_num'=>'购买数量',
                        'refundment_num'=>'退款数量',
						'refundment_money'=>'退款金额',
						'refundment_time'=>'退款时间',
						'refundment_reason'=>'退款原因'
                      );

$sql = "select date,goods_id,buy_num,refundment_num,goods_name,user_name,refundment_reason,city,goods_price,cost_price,
refundment_money,pay_time,start_time,end_time,refundment_time from refundment where 1=1 ";
if(!$filter_date_start){
    $filter_date_start=date('Y-m-d',time()-24*3600);
}
$sql .= " and date = '$filter_date_start' ";

$datass = DB::GetQueryResult ( $sql, false ); 



$excel_name = "每日退款数据";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>