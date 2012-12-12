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
						'city'=>'上线城市',
						'type1_name'=>'所属频道',
						'goods_id'=>'商品ID',
						'goods_name'=>'商品名称',
						'uv'=>'商品详情页UV',
						'频道UV'=>'频道UV',
						'type2_name'=>'二级分类',
						'type3_name'=>'三级分类',
						'order_num'=>'当天下单数',
						'goods_num'=>'当天下单商品数',
						'suc_order_num'=>'当天支付订单数',
						'suc_goods_num'=>'当天支付商品数',
						'user_num'=>'当天独立下单用户数',
						'suc_user_num'=>'当天独立购买用户数',
						'suc_total_price'=>'当天销售额',
						'maoli'=>'当天毛利额',
						'all_order_num'=>'累计下单数',
						'all_goods_num'=>'累计下单商品数',
						'all_suc_order_num'=>'累计支付订单数',
						'all_suc_goods_num'=>'累计支付商品数',
						'all_user_num'=>'累计独立下单用户数',
						'all_suc_user_num'=>'累计独立购买用户数',
						'all_suc_total_price'=>'累计销售额',
						'all_maoli'=>'累计毛利额',
						'avg_order_num'=>'日平均下单数',
						'avg_goods_num'=>'日平均下单商品数',
						'avg_suc_order_num'=>'日平均支付订单数',
						'avg_suc_goods_num'=>'日平均支付商品数',
						'avg_user_num'=>'日平均独立下单用户数',
						'avg_suc_user_num'=>'日平均独立购买用户数',
						'avg_suc_total_price'=>'日平均销售额',
						'avg_maoli'=>'日平均毛利额',
						'price'=>'商品销售单价',
						'jiesuan_price'=>'商品结算单价',
						'goods_maoli'=>'商品毛利',
						'start_time'=>'上线日期',
						'shengfen'=>'省份',
						'url'=>'URL'						
						);

$sql = "select
d.date,d.city,d.type1_name,d.goods_id,d.goods_name,p.uv,'频道UV',d.type2_name,d.type3_name,p.order_num,p.goods_num,p.suc_order_num,
p.suc_goods_num,p.user_num,p.suc_user_num,p.suc_total_price,p.maoli,p.all_order_num,p.all_goods_num,p.all_suc_order_num,
p.all_suc_goods_num,p.all_user_num,p.all_suc_user_num,p.all_suc_total_price,p.all_maoli,p.avg_order_num,p.avg_goods_num,p.avg_suc_order_num,
p.avg_suc_goods_num,p.avg_user_num,p.avg_suc_user_num,p.avg_suc_total_price,p.avg_maoli,d.price,d.jiesuan_price,
(d.price-d.jiesuan_price) as 'goods_maoli',d.start_time,gip.city_name as 'shengfen',gip.url
from dim_cat_goods d LEFT JOIN product_sort p on d.date=p.date and d.goods_id=p.goods_id,
goods_id_province gip
where 1=1 and p.goods_id=gip.goods_id ";

if($filter_date_start && $filter_date_end){
	$condition .= " and d.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
	$condition .= " and d.date = '$filter_date_start' ";
}elseif($filter_date_end){
	$condition .= " and d.date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and d.date between '$filter_date_start' and  '$filter_date_end' ";
}
$city_name = trim($_GET['city_name']);
$pindao = trim($_GET['pindao']);
$cate_second = trim($_GET['cate_second']);
$cate_third = trim($_GET['cate_third']);

if($city_name){
	$condition .= " and d.city ='".$city_name."' ";
}
if($pindao){
	$condition .= " and d.type1_name ='".$pindao."' ";
}
if($cate_second){
	$condition .= " and d.type2_name ='".$cate_second."' ";
}
if($cate_third){
	$condition .= " and d.type3_name ='".$cate_third."' ";
}

$sql .= $condition;

$datass = DB::GetQueryResult ( $sql." order by d.date ;", false );

//foreach($datass as $key=>$data ){
//	$datass[$key]['kedanjia'] = round($data['sale']/$data['user'],2);
//	$datass[$key]['zhuanhua_rate'] = round($data['order_num']*100/$data['total_uv'],2)."%";
//	$datass[$key]['pay_kedanjia'] = round($data['pay_sale']/$data['pay_user'],2);
//	$datass[$key]['pay_rate'] = round($data['pay_order']*100/$data['total_uv'],2)."%";
//}

$excel_name = "商品数据排序";

 export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>