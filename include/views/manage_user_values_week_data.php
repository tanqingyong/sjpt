<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$page_size = $arr_var['page_size'];
$start1 = $arr_var['start1'];
$end1 = $arr_var['end1'];

?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/user_center/user_values_week_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/user_center/export_user_values_week_data.php');
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
								<select id="end1" name="end1" class="se_ect1">
                            <option></option>
                             <?php global $option_week; echo ''.Utility::Option($option_week,$end1).''; ?>
                       </select>
									周</td>
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
								    	<tr><td>年</td><td>周</td><td>用户ID</td><td>最近购买时间</td><td>购买频率</td>
								    	<td>购买商品种类</td><td>平均每次消费额</td><td>单次最高消费额</td></tr>
								    </thead>		 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['year'];?></td>
											<td><?php echo $data['week_num'];?></td>
											<td><?php echo $data['user_id'];?></td>
											<td><?php echo $data['shijiancha'];?></td>
											<td><?php echo $data['ord_num'];?></td>
											<td><?php echo $data['cate_num'];?></td>
											<td><?php echo $data['avg_money'];?></td>
											<td><?php echo $data['max_money'];?></td>
										</tr>
										<?php }?>
										<?php if(!$datas){?>
										<tr><td  colspan='8'>没有数据</td></tr>
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
  
