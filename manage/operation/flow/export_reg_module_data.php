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
$rl_type = $_GET['type'];

if($rl_type=='reg'){
$sumary_table = array( 'date'=>'日期',
                        'sq_pv'=>'申请注册PV',
                        'sq_uv'=>'申请注册UV',
                        'sq_ip'=>'申请注册IP',
                        'tj_pv'=>'提交注册PV',
                        'tj_uv'=>'提交注册UV',
                        'tj_ip'=>'提交注册IP',
                        'cgtj_pv'=>'成功提交注册PV',
                        'cgtj_uv'=>'成功提交注册UV',
                        'cgtj_ip'=>'成功提交注册IP',
                        'rate'=>'成功操作率'
                      );
}else{
	$sumary_table = array( 'date'=>'日期',
                        'loginsq_pv'=>'申请登陆PV',
                        'loginsq_uv'=>'申请登陆UV',
                        'loginsq_ip'=>'申请登陆IP',
                        'logintj_pv'=>'提交登陆PV',
                        'logintj_uv'=>'提交登陆UV',
                        'logintj_ip'=>'提交登陆IP',
                        'logincgtj_pv'=>'成功提交登陆PV',
                        'logincgtj_uv'=>'成功提交登陆UV',
                        'logincgtj_ip'=>'成功提交登陆IP',
                        'rate'=>'成功操作率'
                      );
	
}
//wait for data
$sql = "SELECT * from reg_login_pvuvip where 1=1 ";

$filter_date_start = trim($_GET['stratdate']);
$filter_date_end = trim($_GET['enddate']);
$pay_type  = $_GET['type'];
$condition = "";
if($filter_date_start && $filter_date_end){
	$condition .= " and date  between '$filter_date_start' and '$filter_date_end' ";
}elseif($filter_date_start){
	$condition .= " and date = '$filter_date_start' ";
}elseif($filter_date_end){
	$condition .= " and date = '$filter_date_end' ";
}else{
	$filter_date_start = date('Y-m-01',time());
	$filter_date_end = date('Y-m-t',time());
	$condition .= " and date between '$filter_date_start' and  '$filter_date_end' ";
}

$sql .= $condition;

$datass = DB::GetQueryResult ( $sql." order by date asc ;", false ); 

foreach ($datass as $key=>$data){
	if($rl_type=='reg'){
	$datass[$key]['rate']=round($datass[$key]['cgtj_uv']*100/$datass[$key]['sq_uv'],2);
	$datass[$key]['rate'].="%";
	}else{
	$datass[$key]['rate']=round($datass[$key]['logincgtj_uv']*100/$datass[$key]['logintj_uv'],2);
	$datass[$key]['rate'].="%";
	}
}
if($rl_type=='reg'){
$excel_name = "用户注册流量数据";
}else{
$excel_name = "用户登陆流量数据";	
}
 export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>