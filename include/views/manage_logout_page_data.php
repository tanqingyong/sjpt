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
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/flow/logout_page_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/flow/export_logout_page_data.php');
    $("form:first").submit();
}

</script>

                        <div class="right-data"  >

                            
                            <!-- search  -->
                            <div class="search-box">
							<form action="" method="get">
							<table>
								<tr><td>
                        <label>频道类型：</label>
                        <select id="type" name="type" class="se_ect1">
                            <option></option>
                             <?php  echo ''.Utility::Option(get_paytype_option('product_logout_page','pindao'),$pay_type).''; ?>
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
								    	<tr><td>日期</td><td>一级页面</td><td>二级页面</td><td>pv</td><td>uv</td><td>ip</td><td>跳出率</td></tr>
								    </thead>			 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['date'];?></td>
											<td><?php echo $data['pindao'];?></td>
											<td><?php echo $data['list_page'];?></td>
											<td><?php echo $data['pv'];?></td>
											<td><?php echo $data['uv'];?></td>
											<td><?php echo $data['ip'];?></td>
											<td><?php echo $data['logout_rate']*100;echo "%";?></td>
										</tr>
										<?php }?>
										<?php if(!$datas){?>
										<tr><td  colspan='7'>没有数据</td></tr>
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
  
