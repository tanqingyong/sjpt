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
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/whole_site/site_visit_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/whole_site/export_site_visit_data.php');
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
							  <tr><td>日期</td><td>整站PV</td><td>整站UV</td><td>整站IP</td><td>人均访问时长</td><td>首页PV</td><td>首页UV</td><td>首页IP</td><td>整站跳出率</td></tr> 
								</thead>
									<tbody>	 
								   <?php foreach($datas as $data){?>			 
											<tr><td><?php echo $data['data_date'];?></td>
												<td><?php echo $data['total_pv'];?></td>
												<td><?php echo $data['total_uv'];?></td>
												<td><?php echo $data['total_ip'];?></td>
												<td><?php echo $data['time_online_peruser'];?></td>
												<td><?php echo $data['index_pv'];?></td>
												<td><?php echo $data['index_uv'];?></td>
												<td><?php echo $data['index_ip'];?></td>
												<td><?php echo round($data['click_onlyone_ratio'],4)*100;echo "%";?></td>
											</tr>
											<?php }?>
											<?php if(!$datas){?>
											<tr><td  colspan='9'>没有数据</td></tr>
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
  
