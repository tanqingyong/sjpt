<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/day_sale_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/export_day_sale_data.php');
    $("form:first").submit();
}

</script>

 
                        <div class="right-data"  >

                            
                            <!-- search  -->
                            <div class="search-box">
							<form action="" method="get">
							<table>
								<tr>
								   <td>
									<label>查询从</label>
								<input type="text" width="10px" name="stratdate" id="stratdate" onfocus="WdatePicker()"
									value='<?php echo $filter_date_start;?>' />
									</td>
									<td>
									<label>到</label>
								<input type="text" width="10px" name="enddate" id="enddate" onfocus="WdatePicker()"
									value='<?php echo $filter_date_end;?>' />
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
                                   <table cellpadding="0" cellspacing="0"   style="width:1400px" >
								    <thead>
								    	<tr><td>日期</td><td>下单数</td><td>下单用户数</td><td>下单商品数</td><td>下单总额</td><td>下单客单价</td><td>订单转化率</td><td>付款订单数</td><td>付款用户数</td><td>付款商品数</td><td>付款订单总额</td><td>付款客单价</td><td>付款转化率</td><td>支付0元订单数</td><td>支付0元用户数</td><td>支付0元商品数</td><td>毛利</td></tr>
								    </thead>				 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['data_date'];?></td>
											<td><?php echo $data['order_num'];?></td>
											<td><?php echo $data['user'];?></td>
											<td><?php echo $data['product'];?></td>
											<td><?php echo $data['sale'];?></td>
											<td><?php echo round($data['sale']/$data['user'],2);?></td>
											<td><?php echo round($data['order_num']*100/$data['total_uv'],2);echo "%";?></td>
											<td><?php echo $data['pay_order'];?></td>
											<td><?php echo $data['pay_user'];?></td>
											<td><?php echo $data['pay_product'];?></td>
											<td><?php echo $data['pay_sale'];?></td>
											<td><?php echo round($data['pay_sale']/$data['pay_user'],2);?></td>
											<td><?php echo round($data['pay_order']*100/$data['total_uv'],2);echo "%";?></td>
											<td><?php echo $data['pay_order_num_zero'];?></td>
											<td><?php echo $data['pay_user_zero'];?></td>
											<td><?php echo $data['pay_product_zero'];?></td>
											<td><?php echo $data['gross_profit'];?></td>
										</tr>
										<?php }?>
										<?php if(!$datas){?>
										<tr><td  colspan='17'>没有数据</td></tr>
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
  
