<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/manage/export_excel.php');

need_login();


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
$table_array = array( 
                      'platform_id'=>'媒体名称',
                      'date'=>'日期', 
                      'city'=>'城市',
                      'ip'=>'IP',
                      'uv'=>'UV',
                      'pv'=>'PV',
                      'order_num'=>'订单数',
                      'act_num' => '活动下单数',
                      'total_price'=>'流水额',
                      'register_num'=>'注册量',
                      'suc_order_num'=>'成单数',
                      'suc_total_price'=>'成单金额',
						'refund_bishu'=>'退款订单率',
						'refund_money'=>'退款金额占比',
                      'order_rate'=>'订单转化率',
                      'register_rate'=>'注册转化率',
                      'suc_order_rate'=>'成单率'
                    );

$sql = "select p.*,round(t.refund_bishu/p.suc_order_num,4) as 'refund_bishu',round(t.refund_money/p.suc_total_price,4) as 'refund_money' 
from platform_update_city p left join media_refund t  on p.date=t.date and p.platform_id=t.media and p.city=t.city_name where p.platform_id!='合计' $condition order by $order_by";
$datas = DB::GetQueryResult($sql,false);
echo $sql;
foreach($datas as $key => $value){
    $datas[$key]['order_rate'] = round($value['order_num']*100/$value['ip'],2)."%";
    $datas[$key]['register_rate'] = round($value['register_num']*100/$value['ip'],2)."%";
    $datas[$key]['suc_order_rate'] = round($value['suc_order_num']*100/$value['order_num'],2)."%";
}
$sql_sum = "select '总计','-' as a,'-' as b,'-' as c,'-' as d,sum(pv) as pv_sum, sum(order_num) as order_sum, sum(act_num) as act_sum,round(sum(total_price),2) as total_sum, 
sum(register_num) as register_sum, sum(suc_order_num) as suc_order_sum, round(sum(suc_total_price),2) as suc_total_sum ,
'-' as e,'-' as f, round(sum(suc_order_num)*100/sum(order_num),2) as suc_order_rate from platform_update_city p
where p.platform_id!='合计'  $condition ";
$sum_array = DB::GetQueryResult($sql_sum,true);
$sum_array["suc_order_rate"] = $sum_array["suc_order_rate"]."%";
$excel_name = "每日媒体统计(媒体，城市)";
export_excel($datas,$table_array,$sum_array,$excel_name);
?>