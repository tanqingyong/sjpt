<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$start1 = trim($_GET['start1']);
$end1 = trim($_GET['end1']);

$condition = "";
if(!$start1 || !$end1 ){
	$start1 = date('Y',strtotime("-1 week"));
	$end1 = date('W',strtotime("-1 week"));
}
$d= date('D');
if($d=="Mon"){
$nowdate=date('Y-m-d',strtotime("-0 week Monday"));
}else{
	$nowdate=date('Y-m-d',strtotime("-1 week Monday"));
}


$sql = "SELECT year,week_num,user_id,cate_num,ord_num,round(g_money/ord_num,2) as 'avg_money',datediff('$nowdate',max_date) as 'shijiancha',
max_money from operate_user_week where 1=1 ";
$sql_count = "select count(1) as count from operate_user_week where 1=1 ";
$condition .= " and year = '".$start1."' and week_num= '".$end1."' ";
$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .=" limit $offset,$pagesize;";
$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_user_values_week_data',
array ( 'datas'=>$datas,
                'pagestring'=>$pagestring,
				'start1'=>$start1,
				'end1'=>$end1,
                'page_size'=>$page_size) );

?>
