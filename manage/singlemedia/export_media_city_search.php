<?php
require_once(dirname(dirname(__FILE__)) . '/export_excel.php');
require_once(dirname(dirname(dirname(__FILE__))). '/include/library/PHPExcel.php');
require_once(dirname(dirname(dirname(__FILE__))). '/include/library/DB.class.php');
require_once(dirname(dirname(dirname(__FILE__))). '/include/library/Cache.class.php');
require_once(dirname(dirname(dirname(__FILE__))). '/include/library/Config.class.php');
require_once(dirname(dirname(dirname(__FILE__))). '/include/library/PHPExcel/Writer/Excel2007.php');
require_once(dirname(dirname(dirname(__FILE__))). '/include/function/utility.php');

need_login();
$order = trim($_GET['order']);
if(empty($order))
	$order = ' date ';
else
	$order = " suc_order_num $order ";
$media_text = mysql_escape_string(trim($_GET['media_text']));
$ad_text = mysql_escape_string(trim($_GET['ad_text']));
$city_text = mysql_escape_string(trim($_GET['city_text']));
$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);

$sumary_table = array(  'ad'=>'广告位',
						'date'=>'日期',
                        'city'=>'城市',
                        'uniq_ip'=>'IP',
                        'uv'=>'UV',
                        'pv'=>'PV',
                        'order_num'=>'订单数',
                        'total_price'=>'流水额',
                        'register_num'=>'注册量',
						'suc_order_num'=>'成单数',
                        'suc_total_price'=>'成单金额',
                        'order_rate'=>'订单转化率',
						'register_rate'=>'注册转化率',
                        'suc_order_rate'=>'成单率'
                      );

$sql = "select date, ad, city ,uniq_ip, uv, pv, order_num, total_price, register_num, suc_order_num, suc_total_price from adref_success_city where 1=1 ";
$sql_summary = "select sum(uniq_ip) as uniq_ip,
sum(uv) as uv,
sum(pv) as pv,
sum(order_num) as order_num,
sum(total_price) as total_price,
sum(register_num) as register_num,
sum(suc_order_num) as suc_order_num,
sum(suc_total_price) as suc_total_price 
from adref_success_city where 1=1 ";
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and date between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $condition .= " and date = '$filter_date_start' ";
}elseif($filter_date_end){
    $condition .= " and date = '$filter_date_end' ";
}else{
	$filter_date_start = date("Y-m")."-01";
    $filter_date_end = date("Y-m-d");
    $condition .= " and date  between '$filter_date_start' and '$filter_date_end' ";
}
//if(!empty($media_text)){
//	$condition .= " and 
//case when instr(ad,'-')=0 then ad 
//when instr(substr(ad,instr(ad,'-')+1),'-')=0 then ad
//else substr(ad,instr(ad,'-')+1,instr(substr(ad,instr(ad,'-')+1),'-')-1) 
//end = '".$media_text."'";
//}
$user_auth = Session::Get('user_auth');
if($user_auth){
	foreach ($user_auth as $permission){
		if(empty($media_text)&&$permission['grade']==1){
			$medialist = get_platforms_for_search();
			//变成sql识别的in("","","")
			$media = "(";
			foreach($medialist as $key=>$value){
				$media.="'";
				$media.= $value;
				$media.="',";
			}
			$media = substr($media,0,strlen($media)-1);
			$media.= ")";
			$condition .= " and 
				case when instr(ad,'-')=0 then ad 
				when instr(substr(ad,instr(ad,'-')+1),'-')=0 then ad
				else substr(ad,instr(ad,'-')+1,instr(substr(ad,instr(ad,'-')+1),'-')-1) 
				end in ".$media."";
			break;
		}
		else if(!empty($media_text)){
			$condition .= " and 
			case when instr(ad,'-')=0 then ad 
			when instr(substr(ad,instr(ad,'-')+1),'-')=0 then ad
			else substr(ad,instr(ad,'-')+1,instr(substr(ad,instr(ad,'-')+1),'-')-1) 
			end = '".$media_text."'";
			break;
		}
		break;
	}
}
if(!empty($ad_text)){
	$condition .= " and ad = '$ad_text' ";
}
if(!empty($city_text)){
	$condition .= " and city = '$city_text' ";
}
$datas = DB::GetQueryResult ( $sql.$condition." order by $order ;", false );

foreach($datas as $key=>$data ){
	$datas[$key]['order_rate'] = round($data ['order_num']*100/$data ['uv'],2).'%';
	$datas[$key]['register_rate'] = round($data ['register_num']*100/$data ['uv'],2).'%';
	$datas[$key]['suc_order_rate'] = round($data ['suc_order_num']*100/$data ['order_num'],2).'%';
}
$result = DB::Query( $sql_summary.$condition.";", false );
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$data_summary[] = '汇总';
$data_summary[] = '';
$data_summary[] = '';
$data_summary[] = $row['uniq_ip'];
$data_summary[] = $row['uv'];
$data_summary[] = $row['pv'];
$data_summary[] = $row['order_num'];
$data_summary[] = $row['total_price'];
$data_summary[] = $row['register_num'];
$data_summary[] = $row['suc_order_num'];
$data_summary[] = $row['suc_total_price'];

$excel_name = "每日媒体总数据";

export_excel($datas,$sumary_table,$data_summary,$excel_name);

?>