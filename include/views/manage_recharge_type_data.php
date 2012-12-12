<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$recharge_type = $arr_var['recharge_type'];
?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/user_center/recharge_type_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/user_center/export_recharge_type_data.php');
    $("form:first").submit();
}

</script>

 
                        <div class="right-data"  >

                            
                            <!-- search  -->
                            <div class="search-box">
							<form action="" method="get">
							<table>
								<tr><td>
                        <label>充值类型：</label>
                        <select id="typename" name="typename" class="se_ect1">
                            <option></option>
                             <?php  echo ''.Utility::Option(get_paytype_option('product_account_recharge','recharge_type'),$recharge_type).''; ?>
                       </select>
                    </td>
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
                                   <table cellpadding="0" cellspacing="0"   style="width:800px" >
								    <thead>
								    	<tr><td>日期</td><td>充值类型</td><td>充值订单数</td><td>充值用户数</td><td>充值金额</td></tr>
								    </thead>			 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['date'];?></td>
											<td><?php echo $data['recharge_type'];?></td>
											<td><?php echo $data['recharge_order'];?></td>
											<td><?php echo $data['recharge_user'];?></td>
											<td><?php echo $data['recharge_money'];?></td>
										</tr>
										<?php }?>
										<?php if(!$datas){?>
										<tr><td  colspan='5'>没有数据</td></tr>
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
  
