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

$sumary_table = array(  'date'=>'日期',
                        'start_date'=>'上线时间',
                        'goods_id'=>'商品ID',
                        'goods_name'=>'商品短名称',
                        'second_cate'=>'二级分类',
                        'third_cate'=>'三级分类',
                        'goods_price'=>'商品单价',
						 'buy_num'=>'购买商品数量',
                        'buy_money'=>'支付金额',
                        'post_fee'=>'邮费',
						 'post_num'=>'包邮数量',
						'from_city'=>'来源城市',
						'crm_sn'=>'CRM码',
						'wms_sn'=>'物流吗',
						'store_id'=>'专卖店ID',
						'store_name'=>'专卖店名称',
						'store_start_time'=>'专卖店上线时间',
						'sale_user'=>'销售人员',
						'operate_user'=>'编辑人员',
						'goods_url'=>'商品URL',
						'store_url'=>'专卖店URL'
                      );

                      
$sql = "select goods_sale_detail.*,j.crm_sn,j.wms_sn,j.store_id,j.store_name,j.store_start_time,j.sale_user,j.operate_user,j.goods_url,j.store_url
 from goods_sale_detail left join goods_store_url j on goods_sale_detail.goods_id=j.goods_id where 1=1 ";
if(!$filter_date_start){
   $filter_date_start=date('Y-m-d',time()-24*3600);
}
 $sql .= " and date = '$filter_date_start' ";


$datass = DB::GetQueryResult ( $sql, false ); 



$excel_name = "频道销售数据";

export_excel($datass,$sumary_table,$data_field_array,$excel_name);

?>