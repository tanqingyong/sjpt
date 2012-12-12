<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_login();
need_page(); 
$filter_date_start = trim($_GET["startdate"]);
$filter_date_end = trim($_GET["enddate"]);
$order_type = trim($_GET["order_type"]);
$media_name = trim($_GET["media_name"]);
$condition ="";
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
$condition_p='';
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
			$condition_p .= " and p.platform_id in $media ";
			break;
		}
		elseif($media_name) {
			$condition_p .= " and p.platform_id = '$media_name' ";
			break;
		}
		break;
	}
}
//if($media_name&&Session::Get('grade')!=1){
//    $condition .= " and platform_id = '$media_name' ";
//}

if($order_type){
    $order_by = " p.suc_order_num $order_type ";
}else{
    $order_by = " p.date desc,p.suc_order_num desc ";
}

$sql_count = "select count(1) as count from platform_update_new p where p.platform_id!='合计' ".$condition.$condition_p;
$result_count = DB::GetQueryResult($sql_count,true);
$count = $result_count['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql = "select p.*,round(t.refund_bishu/p.suc_order_num*100,2) as 'refund_bishu',t.refund_money as 'refund_money_2',round(t.refund_money/p.suc_total_price*100,2) as 'refund_money',v.verify_money
 from platform_update_new p left join (SELECT date,media,sum(refund_bishu) as 'refund_bishu',sum(refund_money) as 'refund_money' 
 from media_refund p where 1=1 ".$condition." GROUP BY date,media) t on p.date=t.date and p.platform_id=t.media left join
  (SELECT date,media,sum(verify_money) as 'verify_money' 
 from media_verify p where 1=1 ".$condition." GROUP BY date,media) v on p.date=v.date and p.platform_id=v.media
  where p.platform_id!='合计' ".$condition.$condition_p." order by $order_by limit $offset,$pagesize ";

$datas = DB::GetQueryResult($sql,false);
$sql_sum = "select sum(ip) as ip_sum,sum(uv) as uv_sum,sum(pv) as pv_sum, sum(order_num) as order_sum,sum(act_num) as act_sum, sum(total_price) as total_sum, 
sum(register_num) as register_sum, sum(suc_order_num) as suc_order_sum, sum(suc_total_price) as suc_total_sum,sum(user_num) as user_num 
from platform_update_new p
where p.platform_id!='合计'  $condition.$condition_p ";
$sum_array = DB::GetQueryResult($sql_sum,true);
echo template ( 'manage_dailymedia_date_search', 
                array ( 'datas'=>$datas,
                        'pagestring' => $pagestring,
                        'filter_date_start' => $filter_date_start,
                        'filter_date_end' => $filter_date_end,
                        'media_name' => $media_name,
                        'page_size' => $page_size,
                        'sum_array' => $sum_array,
                        'offset' => $offset
                       )
              );
?>