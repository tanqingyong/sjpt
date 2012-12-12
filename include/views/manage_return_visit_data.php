<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$page_size = $arr_var['page_size'];
$start1 = $arr_var['start1'];
$start2 = $arr_var['start2'];
$end1 = $arr_var['end1'];
$end2 = $arr_var['end2'];
?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/flow/return_visit_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/flow/export_return_visit_data.php');
    $("form:first").submit();
}

</script>

 
                        <div class="right-data"  >

                            
                            <!-- search  -->
                            <div class="search-box">
							<form action="" method="get">
							<table>
								<tr><td>
                        <label>查询从：</label>
                        <select id="start1" name="start1" class="se_ect1">
                            <option></option>
                             <?php global $option_year; echo ''.Utility::Option($option_year,$start1).''; ?>
                       </select>
                    </td>
									<td>
									<label>年</label>
								<select id="start2" name="start2" class="se_ect1">
                            <option></option>
                             <?php global $option_month; echo ''.Utility::Option($option_month,$start2).''; ?>
                       </select>
									</td><td>
									<label>月-到：</label>
                        <select id="end1" name="end1" class="se_ect1">
                            <option></option>
                             <?php global $option_year; echo ''.Utility::Option($option_year,$end1).''; ?>
                       </select>
                    </td>
									<td>
									<label>年</label>
								<select id="end2" name="end2" class="se_ect1">
                            <option></option>
                             <?php global $option_month; echo ''.Utility::Option($option_month,$end2).''; ?>
                       </select>月
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
								    	<tr><td>月份</td><td>只访问1天</td><td>回访1天</td><td>回访2天</td><td>回访3天</td>
								    	<td>回访4天</td><td>回访5天</td><td>回访6天</td><td>回访7天</td>
								    	<td>回访8天</td><td>回访9天</td><td>回访10天</td><td>回访10-15天</td><td>回访大于15天</td></tr>
								    </thead>			 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['months'];?></td>
											<td><?php echo $data['days0'];?></td>
											<td><?php echo $data['days1'];?></td>
											<td><?php echo $data['days2'];?></td>
											<td><?php echo $data['days3'];?></td>
											<td><?php echo $data['days4'];?></td>
											<td><?php echo $data['days5'];?></td>
											<td><?php echo $data['days6'];?></td>
											<td><?php echo $data['days7'];?></td>
											<td><?php echo $data['days8'];?></td>
											<td><?php echo $data['days9'];?></td>
											<td><?php echo $data['days10'];?></td>
											<td><?php echo $data['days1115'];?></td>
											<td><?php echo $data['days1630'];?></td>
										</tr>
										<?php }?>
										<?php if(!$datas){?>
										<tr><td  colspan='14'>没有数据</td></tr>
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
  
