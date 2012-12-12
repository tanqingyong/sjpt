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
$rl_type = $_GET['type'];
$sumary_table = array( 'date'=>'日期',
                        'pindao'=>'频道',
                        'pv'=>'频道PV',
                        'uv'=>'频道UV',
                        'ip'=>'频道IP',
                        'goods_pv'=>'商品详情页PV',
                        'goods_uv'=>'商品详情页UV',
                        'goods_ip'=>'商品详情页IP',
                        'ontheline_goods_num'=>'在售商品数',
                        'onthline_goods_click'=>'点击商品数',
                        'online_goods_num'=>'上线商品数',
                        'online_goods_click'=>'点击今日上线商品数',
                        'add_order_user_num'=>'下单用户数',
                        'add_order_goods_num'=>'下单商品数',
                        'add_order_num'=>'下单数',
                        'add_order_money'=>'下单总额',
						'add_kedanjia'=>'下单客单价',
                        'pay_order_user_num'=>'成单用户数',
                        'pay_order_goods_num'=>'成单商品数',
                        'pay_order_num'=>'成单数',
                        'pay_order_money'=>'成单总额',
                        'pay_kedanjia'=>'成单客单价'
                      );

//wait for data
$sql = "SELECT * from product_summary_visit where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$pay_type  = $_GET['type'];
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

if($rl_type){
	$condition .=" and pindao='".$rl_type."'";	
}

$sql .= $condition;

$datass = DB::GetQueryResult ( $sql." order by date asc ;", false ); 

foreach ($datass as $key=>$data){
	$datass[$key]['add_kedanjia']=round($datass[$key]['add_order_money']/$datass[$key]['add_order_user_num'],2);
	$datass[$key]['pay_kedanjia']=round($datass[$key]['pay_order_money']/$datass[$key]['pay_order_user_num'],2);

}
$excel_name = "销售流量数据(分频道)";	

 export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>