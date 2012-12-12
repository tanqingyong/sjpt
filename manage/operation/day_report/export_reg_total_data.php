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

$sumary_table = array(  'date'=>'日期',
                        'city'=>'城市',
                        'reg_user'=>'注册用户数',
                        'buy_user'=>'购买用户数',
                        'total_reg_user'=>'累计注册用户数'
                      );

$sql = "SELECT date,city,reg_user,buy_user,total_reg_user from jy_reg_buy_user  where 1=1 ";

if(!$filter_date_start){
   $filter_date_start=date('Y-m-d',time()-24*3600);
}
 $sql .= " and date = '$filter_date_start' ";

$datass = DB::GetQueryResult ( $sql." order by date ;", false ); 


$excel_name = "分城市注册-购买用户";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>