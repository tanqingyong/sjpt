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
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/refundment_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/export_refundment_data.php');
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
									<label>查询</label>
								<input type="text" width="10px" name="stratdate" id="stratdate" onfocus="WdatePicker()"
									value='<?php echo $filter_date_start;?>' />
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
								    	<tr><td>日期</td><td>商品ID</td><td>上线城市</td><td>上线时间</td><td>下线时间</td><td>支付时间</td>
								    	<td>商品名称</td><td>单价</td><td>成本单价</td><td>用户名</td><td>购买数量</td><td>退款数量</td>
								    	<td>退款金额</td><td>退款时间</td><td>退款原因</td></tr>
								    </thead>				 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['date'];?></td>
											<td><?php echo $data['goods_id'];?></td>
											<td><?php echo $data['city'];?></td>
											<td><?php echo $data['start_time'];?></td>
											<td><?php echo $data['end_time'];?></td>
											
											<td><?php echo $data['pay_time'];?></td>
											<td><?php echo $data['goods_name'];?></td>
											<td><?php echo $data['goods_price'];?></td>
											<td><?php echo $data['cost_price'];?></td>
											<td><?php echo $data['user_name'];?></td>
											<td><?php echo $data['buy_num'];?></td>
											<td><?php echo $data['refundment_num'];?></td>
											<td><?php echo $data['refundment_money'];?></td>
											<td><?php echo $data['refundment_time'];?></td>
											<td><?php echo $data['refundment_reason'];?></td>
										</tr>
										<?php }?>
										<?php if(!$datas){?>
										<tr><td  colspan='15'>没有数据</td></tr>
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
  
