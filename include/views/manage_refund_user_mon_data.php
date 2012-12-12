<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$data_sum = $arr_var['data_sum'];

?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/user_center/refund_user_mon_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/user_center/export_refund_user_mon_data.php');
    $("form:first").submit();
}

</script>

 
                        <div class="right-data"  >

                            
                            <!-- search  -->
                            <div class="search-box">
							<form action="" method="get">
							<table>
								<tr>
								<!--<td>
                       		 <label>支付类型：</label>
                       		 <select id="type" name="type" class="se_ect1">
                            <option></option>
                             <?php // echo ''.Utility::Option(get_paytype_option('product_pay_money','pay_type'),$pay_type).''; ?>
                       </select>
                    </td>
								   --><td>
									<label>查询</label>
								<input type="text" width="10px" name="stratdate" id="stratdate" onfocus="WdatePicker()"
									value='<?php echo $filter_date_start;?>' />
									</td>
									<td>
									<label>到</label>
								<input type="text" width="10px" name="enddate" id="enddate" onfocus="WdatePicker()"
									value='<?php  echo $filter_date_end;?>' />
									</td>
									<td>
									<a href="javascript:void(0);" onclick="cha_xun();" class="search-btn">查询</a>
									</td>
									<td>
									<a href="javascript:void(0);" onclick="export_excel();"  class="export-btn">导出数据</a>
									</td>
								</tr>
							</table>
		
							</form>
                               </div>     
                         <!-- search end  -->
                           <!-- table -->
                         <div class="table-data-box">
                                   <table cellpadding="0" cellspacing="0"   style="width:100%" >
								    <thead>
								    	<tr><td>月份</td><td>用户ID</td><td>退款笔数</td>
								    	<td>退款商品份数</td><td>退款金额</td><td>订单退款率</td></tr>
								    </thead>			 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['mon'];?></td>
											<td><?php echo $data['user_id'];?></td>
											<!--<td><?php echo $data['city_name'];?></td>
											--><td><?php echo $data['refund_items'];?></td>
											<td><?php echo $data['refund_goods_num'];?></td>
											<td><?php echo $data['refund_money'];?></td>
											<td><?php echo round($data['refund_user']/$data['buy_user']*100,2);?>%</td>
										</tr>
										<?php }
										if($data_sum){
										?>
										<tr><td><?php echo $data_sum['mon'];?></td>
											<td><?php echo $data_sum['user_id'];?></td>
											<!--<td><?php echo $data_sum['city_name'];?></td>
											--><td><?php echo $data_sum['refund_items'];?></td>
											<td><?php echo $data_sum['refund_goods_num'];?></td>
											<td><?php echo $data_sum['refund_money'];?></td>
											<td><?php echo round($data_sum['refund_items']/$data_sum['buy_user']*100,2);?>%</td>
										<?php }
										if(!$datas){?>
										<tr><td  colspan='6'>没有数据</td></tr>
										<?php }?>
										</tbody>
                                      </table>
                                 
                         </div>
									<div class="sect">
									<table   width="100%" id="orders-list" cellspacing="0" cellpadding="0"
										border="0" class="coupons-table">
									
										<tr>
											<td><?php
											echo $pagestring;
											?>
										
										</tr>
									</table>
									</div>
                         <!-- table end -->
                        </div>
  
