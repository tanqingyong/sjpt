<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/export_excel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/PHPExcel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/DB.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/Cache.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/Config.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/PHPExcel/Writer/Excel2007.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/function/utility.php');

need_login();
$week = array("0"=>"星期日","1"=>"星期一","2"=>"星期二","3"=>"星期三","4"=>"星期四","5"=>"星期五","6"=>"星期六");

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);

$sumary_table = array(  'date'=>'日期',
                        'weeks'=>'星期',
                        'ip'=>'独立IP',
                        'uv'=>'UV',
                        'order_num'=>'订单量',
						'total_price'=>'订单额',
                        'reg_num'=>'注册用户',
                        'order_rate'=>'订单转化率',
						'reg_rate'=>'新用户转化率',
                        'kedanjia'=>'客单价',
						'roi'=>'ROI'
                      );

$sql = "SELECT date,sum(uniq_ip) as ip,sum(uv) as uv,sum(pv) as pv,sum(order_num) as order_num,sum(total_price) as total_price,sum(register_num) as reg_num FROM baiduseo where 1=1 ";
$sql_count = "select count(1) as count FROM baiduseo where  1=1 ";


if($filter_date_start && $filter_date_end){
	$condition .= " and date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $condition .= " and date = '$filter_date_start' ";
}elseif($filter_date_end){
    $condition .= " and date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and date between '$date_start' and  '$date_end' ";
}
$sql_count .= $condition;
$sql_count .= "  group by date order by date desc;";
$result = DB::Query($sql_count);
$count = mysql_num_rows($result);
error_log($sql_count);
$datass = DB::GetQueryResult ( $sql.$condition."  group by date order by date desc;", false ); 

//取得星期几
function returnweek($date,$week){
	$w = date('w',strtotime($date));
	foreach ($week as $k=>$v){
		if($w==$k){
		$weekres  = $v;
		}
	}
	return  $weekres;
}

// 取得
foreach ($datass as  $key=>$data){
	$datass[$key]['total_price'] = round($data['total_price'],2);
	$datass[$key]['order_rate'] = round($data['order_num']/$data['ip']*100,2);
	$datass[$key]['order_rate'] .="%";
	$datass[$key]['reg_rate'] = round($data['reg_num']/$data['ip']*100,2);
	$datass[$key]['reg_rate'] .="%";
	$datass[$key]['kedanjia'] = round($data['total_price']/$data['order_num'],2);
	$datass[$key]['weeks'] = returnweek($data['date'],$week);
	$datass[$key]['roi'] = round($data['total_price']/695,2);
}
$keys= count($datass);
 
foreach($datass as $data){
	$sumip +=$data['ip'];
	$sumuv +=$data['uv'];
	$sumorder +=$data['order_num'];
	$summoney +=$data['total_price'];
    $sumreg +=$data['reg_num'];

}
// 平均数据 
$datass[$keys]['date']="求平均";
	$datass[$keys]['weeks']="-";
	$datass[$keys]['ip']=round($sumip/$count);
	$datass[$keys]['uv']=round($sumuv/$count);
	$datass[$keys]['order_num']=round($sumorder/$count);
	$datass[$keys]['total_price']=round($summoney/$count,2);
	$datass[$keys]['reg_num']=round($sumreg/$count);
	$datass[$keys]['order_rate']=round($sumorder/$sumip*100,2);
	$datass[$keys]['order_rate'] .="%";
	$datass[$keys]['reg_rate']=round($sumreg/$sumip*100,2);
	$datass[$keys]['reg_rate'] .="%";
	$datass[$keys]['kedanjia']=round($summoney/$sumorder,2);
	$datass[$keys]['roi']=round($summoney/695/$count,2);
	
//汇总数据
	$datass[$keys+1]['date']="求和";
	$datass[$keys+1]['weeks']="-";
	$datass[$keys+1]['ip']=$sumip;
	$datass[$keys+1]['uv']=$sumuv;
	$datass[$keys+1]['order_num']=$sumorder;
	$datass[$keys+1]['total_price']=round($summoney,2);
	$datass[$keys+1]['reg_num']=$sumreg;
	$datass[$keys+1]['order_rate']="/";
	$datass[$keys+1]['reg_rate']="/";
	$datass[$keys+1]['kedanjia']="/";
	$datass[$keys+1]['roi']="/";
	
$result = mysql_query($sql." limit 1"); 

$fields = mysql_num_fields($result);

$excel_name = "SEO流量绩效汇总统计";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>