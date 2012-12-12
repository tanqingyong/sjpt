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
$city_name = trim($_GET['city_name']);
$sumary_table = array(  'date'=>'日期',
                        'goods_id'=>'商品ID',
                        'city_name'=>'城市',
                        'first_cate_name'=>'用一级分类',
                        'second_cate_name'=>'二级分类',
                        'goods_sname'=>'商品名称'
						
                      );
//wait for data#日报销售数据(日期,下单数,下单用户数,下单商品数,下单总额,下单客单价,订单转化率,付款订单数,付款用户数,付款商品数,付款订单总额,付款客单价,付款转化率)

$sql = "select * from zaixian_sale where 1=1 ";

if($filter_date_start && $filter_date_end){
	$sql .= " and date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $sql .= " and date = '$filter_date_start' ";
}elseif($filter_date_end){
    $sql .= " and date = '$filter_date_end' ";
}else{
	$date_start = date('Y-m-01',time());
	$date_end = date('Y-m-t',time());
	$sql .= " and date between '$date_start' and  '$date_end' ";
}
if($city_name){
	$sql .= " and city_name='$city_name'";	
}

$datass = DB::GetQueryResult ( $sql." order by date ;", false ); 

//foreach($datass as $key=>$data ){
//	$datass[$key]['kedanjia'] = round($data['sale']/$data['user'],2);
//	$datass[$key]['zhuanhua_rate'] = round($data['order_num']*100/$data['total_uv'],2)."%";
//	$datass[$key]['pay_kedanjia'] = round($data['pay_sale']/$data['pay_user'],2);
//	$datass[$key]['pay_rate'] = round($data['pay_order']*100/$data['total_uv'],2)."%";
//}

$excel_name = "在线商品";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>