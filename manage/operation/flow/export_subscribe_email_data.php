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

$sumary_table = array( 'date'=>'日期',
                        'reg_user'=>'注册用户数',
                        'email_user'=>'email订阅用户数',
						'emaildy_pv'=>'email订阅连接点击数'
                      );
//wait for data
$sql = "SELECT p.date,p.reg_user,r.email_user,r.emaildy_pv from product_reg_login p left join reg_login_pvuvip r on p.date=r.date where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$pay_type  = $_GET['type'];
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and p.date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
	$condition .= " and p.date = '$filter_date_start' ";
}elseif($filter_date_end){
	$condition .= " and p.date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and p.date between '$filter_date_start' and  '$filter_date_end' ";
}

$sql .= $condition;

$datass = DB::GetQueryResult ( $sql." order by p.date asc ;", false ); 

$excel_name = "用户注册并订阅数据";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>