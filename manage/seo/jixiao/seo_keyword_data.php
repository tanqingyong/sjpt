<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();


$chechbox = $_GET['checkbox'];
$sql_day = "select distinct date FROM baiduseo where  1=1 ";

$sql = "SELECT date,keyword,sum(uv) AS 'uv',sum(uniq_ip) AS 'ip',sum(pv) AS 'pv',sum(order_num) AS 'order_num',sum(goods_num) AS 'goods_num',sum(total_price) AS 'totalmoney',sum(register_num) AS 'regnum'
FROM baiduseo where 1=1 ";
$sql_count = "select count( 1) as count FROM baiduseo where  1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);


$condition = "";

if($filter_date_start && $filter_date_end){
	$condition .= " and date between '$filter_date_start' and  '$filter_date_end' ";
	
}elseif($filter_date_start){
    $condition .= " and date = '$filter_date_start' ";
}elseif($filter_date_end){
	$condition .= " and date = '$filter_date_end' ";
}else{
	$filter_date_end = date('Y-m-d',time()-24*3600);
	$filter_date_start = date('Y-m-d',time()-24*3600*2);
	$condition .= " and date between '$filter_date_start' and  '$filter_date_end' ";
}

$sql .= $condition;
$sql_count .= $condition;
$sql_day .=$condition;
$sql_day .=" group by date order by date desc;";

$dayresult=DB::GetQueryResult($sql_day,false);
$page = 0;
	if($_GET['page']){
	$page = $_GET['page']-1;
	}
	$filter_date_starts = $dayresult[$page]['date'];
	
	
$daycount= count($dayresult);

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
if($count>400){
$count=$daycount*200	;
}
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):200;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);
// $sql .=" group by date order by date desc limit $offset,$pagesize;";

$sql .=" and date='".$filter_date_starts."' group by keyword ORDER BY ip desc LIMIT 0,200 ;";

$datas = DB::GetQueryResult($sql,false);

//打印显示页面
echo template ( 'manage_seo_keyword_data', 
       array ( 'datas'=>$datas,'pagestring'=>$pagestring,'filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,
 			   'page_size'=>$page_size ) );

?>


