<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/manage/export_excel.php');
need_login();

$table_array = array( 
                      'date'=>'日期', 
                      'platform_id'=>'媒体名称',
                      'ip'=>'IP',
                      'uv'=>'UV',
                      'pv'=>'PV',
                      'order_num'=>'订单数',
                      'act_num' => '活动下单数',
                      'total_price'=>'流水额',
                      'register_num'=>'注册量',
                      'suc_order_num'=>'成单数',
                      'suc_total_price'=>'成单金额',
						'verify_money'=>'验证金额',
						'refund_money_2'=>'退款金额',
						'refund_bishu'=>'退款订单率',
						'refund_money'=>'退款金额占比',
                      'order_rate'=>'订单转化率',
                      'register_rate'=>'注册转化率',
                      'suc_order_rate'=>'成单率',
					  'user_num'=>'购买用户数',
                      'pay_rate'=>'购买转换率',
						'other_login'=>'第三方登录用户数'
                    );
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
//if($media_name){
//    $condition .= " and platform_id = '$media_name' ";
//}
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
if($order_type){
    $order_by = " p.suc_order_num $order_type ";
}else{
    $order_by = " p.date desc,p.suc_order_num desc ";
}
$sql = "select p.*,round(t.refund_bishu/p.suc_order_num*100,2) as 'refund_bishu',t.refund_money as 'refund_money_2',round(t.refund_money/p.suc_total_price*100,2) as 'refund_money',v.verify_money
 from platform_update_new p left join (SELECT date,media,sum(refund_bishu) as 'refund_bishu',sum(refund_money) as 'refund_money' 
 from media_refund p where 1=1 ".$condition." GROUP BY date,media) t on p.date=t.date and p.platform_id=t.media left join
  (SELECT date,media,sum(verify_money) as 'verify_money' 
 from media_verify p where 1=1 ".$condition." GROUP BY date,media) v on p.date=v.date and p.platform_id=v.media
  where p.platform_id!='合计' ".$condition.$condition_p." order by $order_by  ";

$datas = DB::GetQueryResult($sql,false);
foreach($datas as $key => $value){
    $datas[$key]['order_rate'] = round($value['order_num']*100/$value['ip'],2)."%";
    $datas[$key]['register_rate'] = round($value['register_num']*100/$value['ip'],2)."%";
    $datas[$key]['suc_order_rate'] = round($value['suc_order_num']*100/$value['order_num'],2)."%";
    $datas[$key]['pay_rate'] = round($value['user_num']*100/$value['ip'],2)."%";
}
$sql_sum = "select '总计','-' as a,sum(ip) as ip,sum(uv) as uv,sum(pv) as pv_sum, sum(order_num) as order_sum,sum(act_num) as act_sum, round(sum(total_price),2) as total_sum, 
sum(register_num) as register_sum, sum(suc_order_num) as suc_order_sum, round(sum(suc_total_price),2) as suc_total_sum,'-' as 'refund_bishu','-' as 'refund_money,
'-' as e,'-' as f, round(sum(suc_order_num)*100/sum(order_num),2) as suc_order_rate,sum(user_num) as user_num ,'-' as pay_rate 
from platform_update_new p
where p.platform_id!='合计'  $condition.$condition_p ";
$sum_array = DB::GetQueryResult($sql_sum,true);
$sum_array['suc_order_rate']=$sum_array['suc_order_rate']."%";
$excel_name = "每日媒体统计";
export_excel($datas,$table_array,$sum_array,$excel_name);
?>