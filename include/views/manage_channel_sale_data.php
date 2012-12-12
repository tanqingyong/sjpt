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
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/channel_sale_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/export_channel_sale_data.php');
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
                                   <table cellpadding="0" cellspacing="0"   style="width:1300px" >
								    <thead>
								    	<tr><td>日期</td><td>上线时间</td><td>商品ID</td><td>商品短名称</td><td>二级分类</td>
								    	<td>三级分类</td><td>商品单价</td><td>商品数量</td><td>商品金额</td>
								    	<td>邮费</td><td>包邮数量</td><td>来源城市</td><td>CRM码</td>
								    	<td>物流码</td><td>专卖店ID</td><td>专卖店名称</td><td>专卖店上线时间</td>
								    	<td>销售人员</td><td>编辑人员</td><td>商品URL</td><td>专卖店URL</td></tr>
								    </thead>				 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['date'];?></td>
											<td><?php echo $data['start_date'];?></td>
											<td><?php echo $data['goods_id'];?></td>
											<td><?php echo $data['goods_name'];?></td>
											<td><?php echo $data['second_cate'];?></td>
											<td><?php echo $data['third_cate'];?></td>
											<td><?php echo $data['goods_price'];?></td>
											<td><?php echo $data['buy_num'];?></td>
											<td><?php echo $data['buy_money'];?></td>
											<td><?php echo $data['post_fee'];?></td>
											<td><?php echo $data['post_num'];?></td>
											<td><?php echo $data['from_city'];?></td>									
											<td><?php echo $data['crm_sn'];?></td>
											<td><?php echo $data['wms_sn'];?></td>
											<td><?php echo $data['store_id'];?></td>
											<td><?php echo $data['store_name'];?></td>
											<td><?php echo $data['store_start_time'];?></td>
											<td><?php echo $data['sale_user'];?></td>
											<td><?php echo $data['operate_user'];?></td>
											<td><?php echo $data['goods_url'];?></td>
											<td><?php echo $data['store_url'];?></td>
											
											
										</tr>
										<?php }?>
										<?php if(!$datas){?>
										<tr><td  colspan='13'>没有数据</td></tr>
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
  
