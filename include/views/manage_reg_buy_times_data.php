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
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/user_center/reg_buy_times_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/user_center/export_reg_buy_times_data.php');
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
								    	<tr><td>月份</td><td>注册未购买用户</td><td>注册购买用户</td><td>购买1次用户数</td><td>购买2次用户数</td><td>购买3次用户数</td>
								    	<td>购买4次用户数</td><td>购买5次用户数</td><td>购买6次用户数</td><td>购买7次用户数</td>
								    	<td>购买8次用户数</td><td>购买9次用户数</td><td>购买10次用户数</td><td>购买10次以上用户数</td></tr>
								    </thead>			 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['months'];?></td>
											<td><?php echo $data['reg_nobuy'];?></td>
											<td><?php echo $data['reg_buy'];?></td>
											<td><?php echo $data['times1'];?></td>
											<td><?php echo $data['times2'];?></td>
											<td><?php echo $data['times3'];?></td>
											<td><?php echo $data['times4'];?></td>
											<td><?php echo $data['times5'];?></td>
											<td><?php echo $data['times6'];?></td>
											<td><?php echo $data['times7'];?></td>
											<td><?php echo $data['times8'];?></td>
											<td><?php echo $data['times9'];?></td>
											<td><?php echo $data['times10'];?></td>
											<td><?php echo $data['times11'];?></td>
										</tr>
										<?php }?>
										<?php if(!$datas){?>
										<tr><td  colspan='12'>没有数据</td></tr>
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
  
