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
                        'goods_name'=>'商品短名称',
                        'mid_title'=>'商品中标题',
                        'first_cate'=>'一级分类',
                        'second_cate'=>'二级分类',
                        'pindao_name'=>'频道',
                        'city'=>'所在城市',
						 'from_city'=>'来源城市',
                        'start_time'=>'上线时间',
                        'end_time'=>'下线时间'
                      );


$sql = "SELECT date,goods_id,goods_name,mid_title,first_cate,second_cate,pindao_name,city,from_city,start_time,end_time from ontheline_goods where 1=1 ";
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

$datass = DB::GetQueryResult ( $sql." order by date ;", false ); 



$excel_name = "今日上线商品";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>