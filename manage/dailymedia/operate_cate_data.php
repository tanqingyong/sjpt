<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_login();
need_page(); 
$filter_date_start = trim($_GET["startdate"]);
$filter_date_end = trim($_GET["enddate"]);
$order_type = trim($_GET["order_type"]);
$media_name = trim($_GET["media_name"]);
$city_name = trim($_GET["city_name"]);
$cate_name = trim($_GET["cate_name"]);

$condition ="";

if( $filter_date_start ){
    $condition .= " and date = '$filter_date_start' ";
}else{
    $filter_date_start = date("Y-m-d",strtotime("-1 day"));
    $condition .= " and date = '$filter_date_start'  ";
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
			$condition .= " and media in $media ";
			break;
		}
		elseif($media_name) {
			$condition .= " and media = '$media_name' ";
			break;
		}
		break;
	}
}

if($city_name){
    $condition .= "  and city='".$city_name."' ";
}
if($cate_name){
    $condition .= "  and city='".$cate_name."' ";
}

//$sql_count = "select count(1) as count from operate_cate_data where 1=1 ".$condition;
//$result_count = DB::GetQueryResult($sql_count,true);
//$count = $result_count['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):52;
list($pagesize, $offset, $pagestring) = pagestring(51, $page_size);

$sql = "select date,city,media,category,sum(o.g_uv) as 'g_uv',sum(o.old_g_uv) as 'old_g_uv',sum(o.new_g_uv) as 'new_g_uv',sum(o.add_user) as 'add_user',
sum(o.old_add_user) as 'old_add_user',sum(o.new_add_user) as 'new_add_user',sum(o.pay_user) as 'pay_user',
sum(o.old_pay_user) as 'old_pay_user',sum(o.new_pay_user) as 'new_pay_user',sum(o.pay_money) as 'pay_money',sum(o.old_pay_money) as 'old_pay_money',
sum(o.new_pay_money) as 'new_pay_money',sum(o.add_order) as 'add_order',sum(o.pay_order) as 'pay_order',sum(o.pay_goods) as 'pay_goods',sum(o.g_pv) as 'g_pv'
 from operate_cate_data o where 1=1 $condition  GROUP BY city,media,category ORDER BY pay_money desc limit 50 ";

$datas = DB::GetQueryResult($sql,false);
////////////////////////////////////////////////////////////////////////////////////
$sql_num = "select sum(num) as 'num' from(select count(1) as num from operate_cate_data where 1=1 ".$condition." GROUP BY city,media,category ) a";
$result_num = DB::GetQueryResult($sql_num,true);
$num = $result_num['num'];

$all_sql = "
select date,sum(o.g_uv) as 'g_uv',sum(o.old_g_uv) as 'old_g_uv',sum(o.new_g_uv) as 'new_g_uv',sum(o.add_user) as 'add_user',
sum(o.old_add_user) as 'old_add_user',sum(o.new_add_user) as 'new_add_user',sum(o.pay_user) as 'pay_user',
sum(o.old_pay_user) as 'old_pay_user',sum(o.new_pay_user) as 'new_pay_user',sum(o.pay_money) as 'pay_money',sum(o.old_pay_money) as 'old_pay_money',
sum(o.new_pay_money) as 'new_pay_money',sum(o.add_order) as 'add_order',sum(o.pay_order) as 'pay_order',sum(o.pay_goods) as 'pay_goods',sum(o.g_pv) as 'g_pv'
from
(select date,city,media,category,sum(o.g_uv) as 'g_uv',sum(o.old_g_uv) as 'old_g_uv',sum(o.new_g_uv) as 'new_g_uv',sum(o.add_user) as 'add_user',
sum(o.old_add_user) as 'old_add_user',sum(o.new_add_user) as 'new_add_user',sum(o.pay_user) as 'pay_user',
sum(o.old_pay_user) as 'old_pay_user',sum(o.new_pay_user) as 'new_pay_user',sum(o.pay_money) as 'pay_money',sum(o.old_pay_money) as 'old_pay_money',
sum(o.new_pay_money) as 'new_pay_money',sum(o.add_order) as 'add_order',sum(o.pay_order) as 'pay_order',sum(o.pay_goods) as 'pay_goods',sum(o.g_pv) as 'g_pv'
 from operate_cate_data o where 1=1 $condition  GROUP BY city,media,category ORDER BY pay_money desc limit 51, $num) o";


$all_datas = DB::GetQueryResult($all_sql,false);


echo template ( 'manage_operate_cate_data',
                array ('datas'=>$datas,
                		'all_datas'=>$all_datas,
                        'pagestring' => $pagestring,
                        'filter_date_start' => $filter_date_start,
                        'filter_date_end' => $filter_date_end,
                        'media_name' => $media_name,
                        'page_size' => $page_size
                       )
              );
?>