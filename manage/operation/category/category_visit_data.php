<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page();
$cate = array("1"=>"lvyou","2"=>"jiudian","3"=>"shenghuoguan","4"=>"huazhuangpin","5"=>"shop");

	$channel = get_curr_user_channel();
	if(strlen(trim($_GET['channel']))>0){
	$channel = trim($_GET['channel']);
	}
	foreach ($cate as $k=>$v){
	if($channel==$k){
	$ind =  $v;
	}
	}
	foreach ($arr_channel as $key=>$value){
		if($channel==$key){
		$catename =  $value;
		}
		}

$sql = "select date,ind,ip,uv,pv,goods_ip,goods_uv,goods_pv,order_num,user_num,suc_order_num,suc_user_num from industry a where a.ind = '".$ind."' and 1=1 ";
$sql_count = "select count(1) as count from industry a where a.ind = '".$ind."' and 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);

$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and a.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $condition .= " and a.date = '$filter_date_start' ";
}elseif($filter_date_end){
    $condition .= " and a.date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and a.date between '$filter_date_start' and  '$filter_date_end' ";
}

$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .="order by a.date  limit $offset,$pagesize;";
$datas = DB::GetQueryResult($sql,false);



echo template ( 'manage_category_visit_data', 
                array ( 'datas'=>$datas,'pagestring'=>$pagestring,'filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'page_size'=>$page_size,'arr_channel'=>$arr_channel,'channel'=>$channel,'catename'=>$catename) );

?>


