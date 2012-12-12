<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/manage/export_excel.php');
need_login();

$table_array = array( 'date'=>'日期', 
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
                      'suc_order_rate'=>'成单率',
                      'trend'=>'数据趋势',
                      'rate'=>'同比上周',
					  'user_num'=>'购买用户数',
					  'other_login'=>'第三方登录用户数',
                      'pay_rate'=>'购买转换率'
                    );
$filter_date_start = $_GET["startdate"];
$filter_date_end = $_GET["enddate"];
$condition ="";
$compare_condition = "";
if( $filter_date_start && $filter_date_end ){
    $compare_date = date("Y-m-d",strtotime($filter_date_start)-7*3600*24);
    $condition .= " and p.date  between '$filter_date_start' and '$filter_date_end' ";
    $compare_condition = " and date  between '$compare_date' and '$filter_date_end' ";
}elseif( $filter_date_start ){
    $compare_date = date("Y-m-d",strtotime($filter_date_start)-7*3600*24);
    $condition .= " and p.date = '$filter_date_start' ";
    $compare_condition = " and date = '$compare_date' ";
}elseif( $filter_date_end ){
    $compare_date = date("Y-m-d",strtotime($filter_date_end)-7*3600*24);
    $condition .= " and p.date = '$filter_date_end'";
    $compare_condition = " and date = '$compare_date' ";
}else{
    $filter_date_start = date("Y-m")."-01";
    $filter_date_end = date("Y-m-d");
    $compare_date = date("Y-m-d",strtotime($filter_date_start)-7*3600*24);
    $condition .= " and p.date  between '$filter_date_start' and '$filter_date_end' ";
    $compare_condition = " and date  between '$compare_date' and '$filter_date_end' ";
}

$sql = "select p.*,round(t.refund_bishu/p.suc_order_num,4) as 'refund_bishu',round(t.refund_money/p.suc_total_price,4) as 'refund_money' from platform_update_new p left join (SELECT date,sum(refund_bishu) as 'refund_bishu',sum(refund_money) as 'refund_money' 
 from media_refund p where 1=1 ".$condition." GROUP BY date) t on p.date=t.date  where p.platform_id='合计' $condition order by p.date asc";

$sql_compare = "select * from platform_update_new p where platform_id='合计' $compare_condition order by p.date asc ";
$sql_sum = "select '总计','-' as 'ip','-' as 'uv',sum(pv) as pv_sum, sum(order_num) as order_sum,sum(act_num) as act_sum, round(sum(total_price),2) as total_sum, 
sum(register_num) as register_sum, sum(suc_order_num) as suc_order_sum, round(sum(suc_total_price),2) as suc_total_sum,'-' as 'refund_bishu','-' as 'refund_money', 
'-' as c,'-' as d,round( sum(suc_order_num)*100/sum(order_num),2) as rate,'-' as e,'-' as f,sum(user_num) as user_num ,'-' as pay_rate 
from platform_update_new p
where platform_id='合计' $condition order by date asc ";



$datas = DB::GetQueryResult($sql,false);
$compare_datas = DB::GetQueryResult($sql_compare,false);
$sum_array = DB::GetQueryResult($sql_sum,true);
$sum_array['rate']=$sum_array['rate']."%";
$day_data = array();
foreach($compare_datas as $key => $value){
    $day_data[$value["date"]] = $value;
}
foreach($datas as $key => $value){
	$datas[$key]['order_rate'] = round($value['order_num']*100/$value['ip'],2)."%";
    $datas[$key]['register_rate'] = round($value['register_num']*100/$value['ip'],2)."%";
    $datas[$key]['suc_order_rate'] = round($value['suc_order_num']*100/$value['order_num'],2)."%";
    $day_last_week = date("Y-m-d",strtotime($value["date"])-7*24*3600);
    if($value["suc_total_price"] > $day_date[$day_last_week]["suc_total_price"]){
        $datas[$key]["trend"] = "↑";
    }else if($value["suc_total_price"] > $day_date[$day_last_week]["suc_total_price"]){
        $datas[$key]["trend"] = "↓";
    }else{
        $datas[$key]["trend"] = "-";
    }
    
    $datas[$key]['pay_rate'] = round($value['user_num']*100/$value['ip'],2)."%";
    $datas[$key]["rate"] = round(abs($day_date[$day_last_week]["suc_total_price"]-$value["suc_total_price"])*100/$day_date[$day_last_week]["suc_total_price"],2);

}
$excel_name = "每日数据统计";

export_excel($datas,$table_array,$sum_array,$excel_name);
?>