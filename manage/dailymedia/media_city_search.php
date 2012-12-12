<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_login();
need_page(); 
$filter_date_start = trim($_GET["startdate"]);
$filter_date_end = trim($_GET["enddate"]);
$media_name = trim($_GET["media_name"]);
$city_name = trim($_GET["city_name"]);
$order_type = trim($_GET["order_type"]);
$condition = "";
if( $filter_date_start && $filter_date_end ){
    $condition .= " and p.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif( $filter_date_start ){
    $condition .= " and p.date = '$filter_date_start' ";
}elseif( $filter_date_end ){
    $condition .= " and p.date = '$filter_date_end'";
}else{
    $filter_date_start = date("Y-m")."-01";
    $filter_date_end = date("Y-m-d");
    $condition .= " and p.date  between '$filter_date_start' and '$filter_date_end' ";
}
$user_auth = Session::Get('user_auth');
if($user_auth){
	foreach ($user_auth as $permission){
		if((!$media_name)&&$permission['grade']==1){
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
			$condition .= " and p.platform_id in $media ";
			break;
		}
		elseif($media_name){
			$condition .= " and p.platform_id = '$media_name' ";
			break;
		}
		break;
	}
}
//if($media_name){
//    $condition .= " and platform_id = '$media_name' ";
//}
if($city_name){
    $condition .= " and p.city like '%$city_name%' ";
}
if($order_type){
    $order_by = " p.suc_order_num $order_type ";
}else{
    $order_by = " p.date desc,p.suc_order_num desc ";
}
$sql_count = "select count(1) as count from platform_update_city p where p.platform_id!='合计' $condition ";
$result_count = DB::GetQueryResult($sql_count,true);
$count = $result_count['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql = "select p.*,round(t.refund_bishu/p.suc_order_num*100,2) as 'refund_bishu',round(t.refund_money/p.suc_total_price*100,2) as 'refund_money' 
from platform_update_city p left join media_refund t  on p.date=t.date and p.platform_id=t.media and p.city=t.city_name where p.platform_id!='合计' $condition order by $order_by limit $offset,$pagesize";

$datas = DB::GetQueryResult($sql,false);
$sql_sum = "select sum(pv) as pv_sum, sum(order_num) as order_sum, sum(act_num) as act_sum, sum(total_price) as total_sum, 
sum(register_num) as register_sum, sum(suc_order_num) as suc_order_sum, sum(suc_total_price) as suc_total_sum from platform_update_city p 
where p.platform_id!='合计'  $condition ";
$sum_array = DB::GetQueryResult($sql_sum,true);
echo template ( 'manage_dailymedia_media_city_search', 
                array ( 'datas'=>$datas,
                        'pagestring' => $pagestring,
                        'media_name' => $media_name,
                        'city_name' => $city_name,
                        'filter_date_start' => $filter_date_start,
                        'filter_date_end' => $filter_date_end,
                        'page_size' => $page_size,
                        'sum_array' => $sum_array,
                        'offset' => $offset
                       )
              );
?>