<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_login();
need_page();


$sql_count = "select count(1) as count from adref_success p where 1=1 ";

$order = trim($_GET['order']);
if(empty($order))
	$order = ' p.date ';
else
	$order = " p.suc_order_num $order ";
$media_text = mysql_escape_string(trim($_GET['media_text']));
$ad_text = mysql_escape_string(trim($_GET['ad_text']));
$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and p.date between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $condition .= " and p.date = '$filter_date_start' ";
}elseif($filter_date_end){
    $condition .= " and p.date = '$filter_date_end' ";
}else{
	$filter_date_start = date("Y-m")."-01";
    $filter_date_end = date("Y-m-d");
    $condition .= " and p.date  between '$filter_date_start' and '$filter_date_end' ";
}

$sql = "select p.date, p.ad ,p.uniq_ip, p.uv, p.pv, p.order_num, p.total_price, p.register_num, p.suc_order_num, p.suc_total_price,round(t.refund_bishu/p.suc_order_num*100,2) as 'refund_bishu',round(t.refund_money/p.suc_total_price*100,2) as 'refund_money'
from adref_success p left join (select p.date,sum(p.refund_bishu) as 'refund_bishu',sum(p.refund_money) as 'refund_money',p.single_media from media_refund p where 1=1 $condition group by p.date,p.single_media)
 t  on p.date=t.date and p.ad=t.single_media  where 1=1 ";
//if(!empty($media_text)){
//	$condition .= " and 
//case when instr(ad,'-')=0 then ad 
//when instr(substr(ad,instr(ad,'-')+1),'-')=0 then ad
//else substr(ad,instr(ad,'-')+1,instr(substr(ad,instr(ad,'-')+1),'-')-1) 
//end = '".$media_text."'";
//}
$user_auth = Session::Get('user_auth');
if($user_auth){
	foreach ($user_auth as $permission){
		if(empty($media_text)&&$permission['grade']==1){
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
			$condition .= " and 
				case when instr(p.ad,'-')=0 then p.ad 
				when instr(substr(p.ad,instr(ad,'-')+1),'-')=0 then p.ad
				else substr(p.ad,instr(p.ad,'-')+1,instr(substr(p.ad,instr(p.ad,'-')+1),'-')-1) 
				end in ".$media."";
			break;
		}
		else if(!empty($media_text)){
			$condition .= " and 
			case when instr(p.ad,'-')=0 then p.ad 
			when instr(substr(p.ad,instr(p.ad,'-')+1),'-')=0 then p.ad
			else substr(p.ad,instr(p.ad,'-')+1,instr(substr(p.ad,instr(p.ad,'-')+1),'-')-1) 
			end = '".$media_text."'";
			break;
		}
		break;
	}
}
if(!empty($ad_text)){
    $condition .= " and p.ad like '%$ad_text%' ";
}
$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .="order by $order limit $offset,$pagesize;";

 $datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_date_search', array ( 'datas'=>$datas,'pagestring'=>$pagestring,'filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'page_size'=>$page_size,'media_text'=>$media_text,'ad_text'=>$ad_text) );

?>