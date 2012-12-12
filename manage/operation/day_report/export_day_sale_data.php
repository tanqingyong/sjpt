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

$sumary_table = array(  'data_date'=>'日期',
                        'order_num'=>'下单数',
                        'user'=>'下单用户数',
                        'product'=>'下单商品数',
                        'sale'=>'下单总额',
                        'kedanjia'=>'下单客单价',
                        'zhuanhua_rate'=>'订单转化率',
                        'pay_order'=>'付款订单数',
						 'pay_user'=>'付款用户数',
                        'pay_product'=>'付款商品数',
                        'pay_sale'=>'付款订单总额',
						 'pay_kedanjia'=>'付款客单价',
                        'pay_rate'=>'付款转化率',
						'pay_order_num_zero'=>'支付0元单数',
						'pay_user_zero'=>'支付0元用户数',
						'pay_product_zero'=>'支付0元商品数'
						
                      );
//wait for data#日报销售数据(日期,下单数,下单用户数,下单商品数,下单总额,下单客单价,订单转化率,付款订单数,付款用户数,付款商品数,付款订单总额,付款客单价,付款转化率)

$sql = "select a.data_date,a.order_num,a.user,a.product,a.sale,a.pay_order,a.pay_user,a.pay_product,a.pay_sale,b.total_uv,a.pay_order_num_zero,a.pay_user_zero,a.pay_product_zero,a.product_zero from buss_country_day_order a join web_country_day_pvuvip b on a.data_date=b.data_date where 1=1 ";
if($filter_date_start && $filter_date_end){
	$sql .= " and a.data_date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $sql .= " and a.data_date = '$filter_date_start' ";
}elseif($filter_date_end){
    $sql .= " and a.data_date = '$filter_date_end' ";
}else{
	$date_start = date('Y-m-01',time());
	$date_end = date('Y-m-t',time());
	$sql .= " and a.data_date between '$date_start' and  '$date_end' ";
}

$datass = DB::GetQueryResult ( $sql." order by a.data_date ;", false ); 

foreach($datass as $key=>$data ){
	$datass[$key]['kedanjia'] = round($data['sale']/$data['user'],2);
	$datass[$key]['zhuanhua_rate'] = round($data['order_num']*100/$data['total_uv'],2)."%";
	$datass[$key]['pay_kedanjia'] = round($data['pay_sale']/$data['pay_user'],2);
	$datass[$key]['pay_rate'] = round($data['pay_order']*100/$data['total_uv'],2)."%";
}

$excel_name = "日报销售数据";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>