<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$arr_channel = $arr_var['arr_channel'];
$channel = $arr_var['channel'];
$catename = $arr_var['catename'];
?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/category/category_visit_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/category/export_category_visit_data.php');
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
							  
									<?php
									  if(is_manager()){
									     echo "频道  <select name=\"channel\" id=\"channel\" >";
                               			 echo ''.Utility::getChannel($arr_channel,$channel).'';
										 echo " </select>";
										}
									?>
								  
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
								    	<tr><td>日期</td><td>行业</td><td>频道PV</td><td>频道UV</td><td>频道IP</td><td>商品详情PV</td><td>商品详情UV</td><td>商品详情IP</td><td>订单数</td><td>订单人数</td></tr>
								    </thead>				 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['date'];?></td>
											<td><?php echo $catename;?></td>
											<td><?php echo $data['pv'];?></td>
											<td><?php echo $data['uv'];?></td>
											<td><?php echo $data['ip'];?></td>
											<td><?php echo $data['goods_pv'];?></td>
											<td><?php echo $data['goods_uv'];?></td>
											<td><?php echo $data['goods_ip'];?></td>
											<td><?php echo $data['order_num'];?></td>
											<td><?php echo $data['user_num'];?></td>
											  
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
  
