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
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/whole_site/user_visit_depth.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/whole_site/export_user_visit_depth.php');
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
						  <table cellpadding="0" cellspacing="0" >
						  <thead>
						  <tr><td>日期</td><td>深度1</td><td>深度2</td><td>深度3</td><td>深度4</td><td>深度5</td><td>深度6~10</td><td>深度10以上</td></tr> 
						   </thead>
						   <tbody>
												 
											   <?php foreach($datas as $data){?>			 
														<tr><td><?php echo $data['data_date'];?></td>
															<td><?php echo $data['user_click1'];?></td>
															<td><?php echo $data['user_click2'];?></td>
															<td><?php echo $data['user_click3'];?></td>
															<td><?php echo $data['user_click4'];?></td>
															<td><?php echo $data['user_click5'];?></td>
															<td><?php echo $data['user_click610'];?></td>
															<td><?php echo $data['user_click_up10'];?></td>
											
														</tr>
														<?php }?>
														<?php if(!$datas){?>
														<tr><td  colspan='8'>没有数据</td></tr>
														<?php }?>
													</tbody>
                                                </table>
                                            </div>

                      			   <div class="sect">
									<table style="width:100%;" id="orders-list" cellspacing="0" cellpadding="0"
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
  
