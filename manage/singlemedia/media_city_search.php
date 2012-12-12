<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_login();
need_page();

$sql = "select date, ad ,city ,uniq_ip, uv, pv, order_num, total_price, register_num, suc_order_num, suc_total_price from adref_success_city where 1=1 ";
$sql_count = "select count(1) as count from adref_success_city where 1=1 ";

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
	$condition .= " and ad like '%$ad_text%' ";
}
if(!empty($city_text)){
	$condition .= " and city like '%$city_text%' ";
}
$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .="order by $order limit $offset,$pagesize;";

$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_media_city_search', array ( 'datas'=>$datas,'pagestring'=>$pagestring,'filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'page_size'=>$page_size,'media_text'=>$media_text,'ad_text'=>$ad_text,'city_text'=>$city_text) );

?>