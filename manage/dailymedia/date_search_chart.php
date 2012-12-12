<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_login();
need_page(); 
$filter_date_start = trim($_POST["startdate"]);
$media_name = trim($_POST["media_name"]);
$index = trim($_POST["index_name"]);

$condition ="";
if( !$filter_date_start  ){
    $filter_date_start = date("Y-m-d",time()-24*3600);
    $date0 = $filter_date_start;
}else{
$date0 = $filter_date_start;	
}
	
	$date1 = date("Y-m-d",strtotime($filter_date_start)-7*24*3600);
	$date2 = date("Y-m-d",strtotime($filter_date_start)-2*7*24*3600);
	$date3 = date("Y-m-d",strtotime($filter_date_start)-3*7*24*3600);
	$date4 = date("Y-m-d",strtotime($filter_date_start)-4*7*24*3600);
	
	$date=$date0."','".$date1."','".$date2."','".$date3."','".$date4;
	



$condition .= " and date in('".$date."') ";

if($media_name){
    $condition .= " and platform_id = '$media_name' ";
}



if($media_name){
	$sql = "select date as 'date',
 sum(ip) as 'ip',sum(uv) as 'uv',sum(pv) as 'pv',sum(suc_order_num) as 'suc_order_num',sum(suc_goods_num) as 'suc_goods_num',
sum(suc_total_price) as 'suc_total_price',sum(act_num) as 'act_num',sum(user_num) as 'user_num'
 from platform_update where platform_id!='合计' $condition group by date order by date ";
}else{
	
	$sql = "select date as 'date',
 sum(ip) as 'ip',sum(uv) as 'uv',sum(pv) as 'pv',sum(suc_order_num) as 'suc_order_num',sum(suc_goods_num) as 'suc_goods_num',
sum(suc_total_price) as 'suc_total_price',sum(act_num) as 'act_num',sum(user_num) as 'user_num'
 from platform_update where platform_id='合计' $condition group by date order by date ";
}

$datas = DB::GetQueryResult($sql,false);
$ip = array();
$uv = array();
$pv = array();
$suc_order_num = array();
$suc_total_price = array();
$act_num = array();
$user_num = array();

$datetime = array();
foreach($datas as $key=>$val){
	array_push($ip, round($val['ip']));
	array_push($uv, round($val['uv']));
	array_push($pv, round($val['pv']));
	array_push($suc_order_num, round($val['suc_order_num']));
	array_push($suc_goods_num, round($val['suc_goods_num']));
	array_push($suc_total_price, round($val['suc_total_price'],2));
	array_push($act_num, round($val['act_num']));
	array_push($user_num, round($val['user_num']));
	array_push($datetime, $val['date']);
}

$ip = json_encode($ip);
$uv = json_encode($uv);
$pv = json_encode($pv);
$suc_order_num = json_encode($suc_order_num);
$suc_goods_num = json_encode($suc_goods_num);
$suc_total_price = json_encode($suc_total_price);
$act_num = json_encode($act_num);
$user_num = json_encode($user_num);
$datetime = json_encode($datetime);

	
echo template ( 'manage_dailymedia_date_search_chart', 
                array ( 'date'=>$datetime,
                		'ip'=>$ip,
                		'uv'=>$uv,
                   		'pv'=>$pv,
                 		'suc_order_num'=>$suc_order_num,
                 		'suc_goods_num'=>$suc_goods_num,
                 		'suc_total_price'=>$suc_total_price,
                 		'act_num'=>$act_num,
                 		'user_num'=>$user_num,
                        'filter_date_start' => $filter_date_start,
                        'media_name' => $media_name
                       )
             );
?>