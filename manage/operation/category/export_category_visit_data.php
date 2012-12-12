<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/export_excel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/PHPExcel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/DB.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/Cache.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/Config.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/PHPExcel/Writer/Excel2007.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/function/utility.php');

need_login();
$cate = array("1"=>"lvyou","2"=>"jiudian","3"=>"shenghuoguan","4"=>"huazhuangpin","5"=>"shop");
$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$channel = get_curr_user_channel();
	if(strlen(trim($_GET['channel']))>0){
	$channel = trim($_GET['channel']);
	}
	foreach ($cate as $k=>$v){
	if($channel==$k){
	$ind =  $v;
	}
	}
	foreach ($arr_channel as $key=>$value){
		if($channel==$key){
		$catename =  $value;
		}
		}
		
$sumary_table = array(  'date'=>'日期',
                        'ind'=>'行业',
                        'pv'=>'频道PV',
                        'uv'=>'频道UV',
                        'ip'=>'频道IP',
						'goods_pv'=>'商品详情PV',
                        'goods_uv'=>'商品详情UV',
                        'goods_ip'=>'商品详情IP',
						'order_num'=>'订单数',
                        'user_num'=>'订单人数'
                      );

$sql = "select date,ind,ip,uv,pv,goods_ip,goods_uv,goods_pv,order_num,user_num,suc_order_num,suc_user_num from industry a where a.ind = '".$ind."' and 1=1 ";
if($filter_date_start && $filter_date_end){
	$sql .= " and a.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $condition .= " and a.date = '$filter_date_start' ";
}elseif($filter_date_end){
    $condition .= " and a.date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$sql .= " and a.date between '$date_start' and  '$date_end' ";
}

$datass = DB::GetQueryResult ( $sql." order by a.date ;", false ); 

foreach($datass as $key=>$data ){
	$datass[$key]['ind'] = $catename;
}

$excel_name = "频道数据";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>