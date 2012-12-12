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
                        'pay_rate'=>'付款转化率'
                      );
//wait for data#日报销售数据(日期,下单数,下单用户数,下单商品数,下单总额,下单客单价,订单转化率,付款订单数,付款用户数,付款商品数,付款订单总额,付款客单价,付款转化率)

$sql = "select a.data_date,a.order_num,a.user,a.product,a.sale,a.pay_order,a.pay_user,a.pay_product,a.pay_sale,b.total_uv from buss_country_day_order a join web_country_day_pvuvip b on a.data_date=b.data_date where 1=1 ";
if($filter_date_start && $filter_date_end){
	$sql .= " and a.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $sql .= " and a.date = '$filter_date_start' ";
}elseif($filter_date_end){
    $sql .= " and a.date = '$filter_date_end' ";
}else{
	$date_start = date('Y-m-01',time());
	$date_end = date('Y-m-t',time());
	$sql .= " and a.date between '$date_start' and  '$date_end' ";
}

$datass = DB::GetQueryResult ( $sql." order by a.date ;", false ); 


$excel_name = "注册用户访问数据";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>