<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$pay_type = $arr_var['pay_type'];
?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/user_center/act_loss_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/user_center/export_act_loss_data.php');
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
									<label>城市:</label>
								<input type="text" width="10px" name="city_name" id="city_name" 
									value='<?php echo $city_name;?>' />
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
                                   <table cellpadding="0" cellspacing="0"   style="width:1100px" >
								    <thead>
								    	<tr><td>日期</td><td>城市</td><td>新增注册用户数</td><td>累计注册用户数</td><td>购买用户数</td>
								    	<td>回访用户数</td><td>流失用户数</td><td>全部用户流失率</td><td>新用户流失数</td><td>新用户流失率</td><td>新用户占活跃用户的比例</td></tr>
								    </thead>		 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['date'];?></td>
											<td><?php echo $data['city_name'];?></td>
											<td><?php echo $data['reg_user'];?></td>
											<td><?php echo $data['reg_total_user'];?></td>
											<td><?php echo $data['buy_user'];?></td>
											<td><?php echo $data['return_visit_user'];?></td>
											<td><?php echo $data['loss_user'];?></td>
											<td><?php echo $data['loss_rate'];?></td>
											<td><?php echo $data['new_loss_user'];?></td>
											<td><?php echo $data['new_loss_rate'];?></td>
											<td><?php echo $data['new_user_rate'];?></td>
										</tr>
										<?php }?>
										<?php if(!$datas){?>
										<tr><td  colspan='11'>没有数据</td></tr>
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
  
