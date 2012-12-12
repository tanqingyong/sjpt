<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$week = array("0"=>"星期日","1"=>"星期一","2"=>"星期二","3"=>"星期三","4"=>"星期四","5"=>"星期五","6"=>"星期六");

$sql = "SELECT date,sum(uniq_ip) as ip,sum(uv) as uv,sum(pv) as pv,sum(order_num) as order_num,sum(total_price) as total_price,sum(register_num) as reg_num FROM baiduseo where 1=1 ";
$sql_count = "select count(1) as count FROM baiduseo where  1=1 ";

$chechbox = $_GET['checkbox'];
$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);

$condition = "";
if(!$chechbox){
	if($filter_date_start && $filter_date_end){
		$condition .= " and date  between '$filter_date_start' and '$filter_date_end' ";
	}elseif($filter_date_start){
	    $condition .= " and date = '$filter_date_start' ";
	}elseif($filter_date_end){
	    $condition .= " and date = '$filter_date_end' ";
	}else{
		$filter_date_start = date('Y-m-01',strtotime("last month"));
		$filter_date_end = date('Y-m-t',time());
		$condition .= " and date between '$filter_date_start' and  '$filter_date_end' ";
	}
}else{
	$condition .= " and date in(".$chechbox.") ";
}

$sql .= $condition;
$sql_count .= $condition;
$sql_count .=" group by date order by date desc ;";

$allresult = DB::Query($sql_count);
$count = mysql_num_rows($allresult);

$page_size = $_GET['pagesize']?intval($_GET['pagesize']):9999;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);
 	
$sql .=" group by date order by date desc ;";

$datas = DB::GetQueryResult($sql,false);


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

foreach ($datas as $key=>$val) {
	$datas[$key]['weeks'] = returnweek($datas[$key]['date'],$week);
}

//打印显示页面
echo template ( 'manage_seo_jixiao_data', 
                array ( 'datas'=>$datas,'pagestring'=>$pagestring,'filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'page_size'=>$page_size,'count'=>$count,'chechbox'=>$chechbox
                ) );

?>


