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
if($filter_date_start && $filter_date_end && $filter_date_start != $filter_date_end){
$sumary_table = array(
                        'keyword'=>'关键词',
                        'uv'=>'UV',
                        'ip'=>'独立IP',
                        'pv'=>'PV',
						'order_num'=>'订单数',
                        'totalmoney'=>'订单额',
						'regnum'=>'注册用户数'
                      );

											
$sql = "SELECT keyword,
sum(uv) AS 'uv',
sum(uniq_ip) AS 'ip',
sum(pv) AS 'pv',
sum(order_num) AS 'order_num',
round(sum(total_price),2) AS 'totalmoney',
sum(register_num) AS 'regnum'
FROM baiduseo where 1=1 ";
if($filter_date_start && $filter_date_end){
    $condition .= " and date between '$filter_date_start' and  '$filter_date_end'  ";
}else{
	$filter_date_start = date('Y-m-d',time()-24*3600);
	$filter_date_end = date('Y-m-d',time()-24*3600*2);
	$condition .= " and date between '$filter_date_end' and  '$filter_date_end' ";
}
$sql .= $condition;

$datass = DB::GetQueryResult ( $sql."  group by keyword ORDER BY ip desc limit 0,200;", false ); 


$keys= count($datass);
 
foreach($datass as $data){
	$sumip +=$data['ip'];
	$sumuv +=$data['uv'];
	$sumpv +=$data['pv'];
	$sumorder +=$data['order_num'];
	$summoney +=$data['totalmoney'];
    $sumreg +=$data['regnum'];
}

	
//汇总数据
	$datass[$keys+1]['keyword']="求和";
	$datass[$keys+1]['ip']=$sumip;
	$datass[$keys+1]['uv']=$sumuv;
	$datass[$keys+1]['pv']=$sumpv;
	$datass[$keys+1]['order_num']=$sumorder;
	$datass[$keys+1]['totalmoney']=round($summoney,2);
	$datass[$keys+1]['regnum']=$sumreg;

		
	
	
}else{
$sumary_table = array(  'date'=>'日期',
                        'keyword'=>'关键词',
                        'uv'=>'UV',
                        'ip'=>'独立IP',
                        'pv'=>'PV',
						'order_num'=>'订单数',
                        'totalmoney'=>'订单额',
						'regnum'=>'注册用户数'
                      );

											
$sql = "SELECT date,
keyword,
sum(uv) AS 'uv',
sum(uniq_ip) AS 'ip',
sum(pv) AS 'pv',
sum(order_num) AS 'order_num',
round(sum(total_price),2) AS 'totalmoney',
sum(register_num) AS 'regnum'
FROM baiduseo where 1=1 ";
if(!$filter_date_start){
	$filter_date_start = date('Y-m-d',time()-24*3600);
    $condition .= " and date = '$filter_date_start' ";
}else{
	$condition .= " and date = '$filter_date_start' ";

}
$sql .= $condition;

$datass = DB::GetQueryResult ( $sql."  group by keyword ORDER BY ip desc;", false ); 


$keys= count($datass);
 
foreach($datass as $data){
	$sumip +=$data['ip'];
	$sumuv +=$data['uv'];
	$sumpv +=$data['pv'];
	$sumorder +=$data['order_num'];
	$summoney +=$data['totalmoney'];
    $sumreg +=$data['regnum'];
}

	
//汇总数据
	$datass[$keys+1]['date']="求和";
	$datass[$keys+1]['keyword']="-";
	$datass[$keys+1]['ip']=$sumip;
	$datass[$keys+1]['uv']=$sumuv;
	$datass[$keys+1]['pv']=$sumpv;
	$datass[$keys+1]['order_num']=$sumorder;
	$datass[$keys+1]['totalmoney']=round($summoney,2);
	$datass[$keys+1]['regnum']=$sumreg;

	
}
	


// 公共部分
$result = mysql_query($sql." limit 1"); 

$fields = mysql_num_fields($result);

$excel_name = "SEO来源关键词".$filter_date_start;

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>