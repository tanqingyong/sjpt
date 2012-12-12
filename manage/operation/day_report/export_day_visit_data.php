<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/export_excel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/PHPExcel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/DB.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/Cache.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/Config.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/library/PHPExcel/Writer/Excel2007.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))). '/include/function/utility.php');
need_login();
$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);

$sumary_table = array(  'data_date'=>'日期',
                        'total_pv'=>'整站PV',
                        'total_uv'=>'整站UV',
                        'total_ip'=>'整站IP',
						'time_online_peruser'=>'平均停留时长',
						'goods_pv'=>'商品PV',
                        'goods_uv'=>'商品UV',
						'goods_ip'=>'商品IP',
                        'click_product'=>'点击商品数',
                        'click_zero_goodsnum'=>'0元商品数',
                        'push_rate'=>'推送率',
                        'login_user'=>'登录用户数',
                        'reg_user'=>'新注册用户数',
						 'buy_user'=>'购买用户数',
						 'union_nopay_user'=>'联合登陆未购买用户数',
						 'union_pay_user'=>'联合登陆购买用户数',
                        'reg_rate'=>'注册转化率',
						 'reg_total_user'=>'累计注册用户数',
						 'buy_total_user'=>'累计购买用户数'
                      );
//wait for data
$sql = "select a.data_date,a.total_ip,a.total_pv,a.total_uv,a.goods_pv,a.goods_uv,a.goods_ip,c.click_product,
pr.login_user,pr.reg_user,pr.reg_total_user,pb.buy_user,pb.buy_total_user,
c.time_online_peruser,c.click_zero_goodsnum,c.total_visit_times,b.union_nopay_user,b.union_pay_user 
from web_country_day_pvuvip a LEFT JOIN product_reg_login_tmp pr on a.data_date=pr.date left join product_buy_user pb on a.data_date=pb.date,
buss_country_day_order b,webbuss_country_day_other c 
where a.data_date=b.data_date and a.data_date=c.data_date  ";

if($filter_date_start && $filter_date_end){
	$sql .= " and a.data_date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
    $sql .= " and a.data_date = '$filter_date_start' ";
}elseif($filter_date_end){
    $sql .= " and a.data_date = '$filter_date_end' ";
}else{
	$date_start = date('Y-m-01',time());
	$date_end = date('Y-m-t',time());
	$sql .= " and a.data_date between '$date_start' and  '$date_end' ";
}
 function  pertime($aa){
		   	$h = intval($aa/3600);
		   	$m = intval(($aa-$h*3600)/60);
		   	$s = $aa-$h*3600-$m*60;
		   	if($h<10){
		   		$h = "0".$h;								   		
		   	}
		   if($m<10){
		   		$m = "0".$m;								   		
		   	}
		   if($s<10){
		   		$s = "0".$s;								   		
		   	}
		   	return  $h.":".$m.":".$s;
		   	
	}
$datass = DB::GetQueryResult ( $sql." order by a.data_date ;", false ); 


foreach($datass as $key=>$data ){
	$datass[$key]['time_online_peruser'] = pertime($data['time_online_peruser']);
	
	$datass[$key]['push_rate'] = round($data['goods_uv']*100/$data['total_uv'],2);
	$datass[$key]['push_rate'] .="%";

	$datass[$key]['reg_rate'] = round($data['reg_user']*100/$data['total_uv'],2);
	$datass[$key]['reg_rate'] .="%";
}

$excel_name = "日报流量访问数";
export_excel($datass,$sumary_table,$data_field_array,$excel_name);


?>
    