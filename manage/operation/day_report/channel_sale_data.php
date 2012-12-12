<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');
// 累计注册用户 姜莹的数据
need_login();
need_page();
$sql = "select goods_sale_detail.*,j.crm_sn,j.wms_sn,j.store_id,j.store_name,j.store_start_time,j.sale_user,j.operate_user,j.goods_url,j.store_url
 from goods_sale_detail left join goods_store_url j on goods_sale_detail.goods_id=j.goods_id  where 1=1 ";
$sql_count = "select count(1) as count from goods_sale_detail where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);

$condition = "";
if(!$filter_date_start){
   $filter_date_start=date('Y-m-d',time()-24*3600);
}
 $condition .= " and date = '$filter_date_start' ";
$sql .= $condition;
$sql_count .= $condition;

$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql .=" limit $offset,$pagesize;";

$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_channel_sale_data', 
                array ( 'datas'=>$datas,
                'pagestring'=>$pagestring,
                'filter_date_start'=>$filter_date_start,
                'page_size'=>$page_size) );

?>
