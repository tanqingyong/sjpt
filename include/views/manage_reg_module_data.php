<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');
$reg_login=array('reg'=>'注册','login'=>'登陆');

$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$rl_tpye=$arr_var['type'];
if($rl_tpye=='reg'){
	$rl_name='注册';
}else{
	$rl_name='登陆';
}
?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/flow/reg_module_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/flow/export_reg_module_data.php');
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
                        <label>页面类型：</label>
                        <select id="type" name="type" class="se_ect1">
                            <option></option>
                             <?php  echo ''.Utility::Option($reg_login,$rl_tpye).''; ?>
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
                                   <table cellpadding="0" cellspacing="0"   style="width:100%" >
								    <thead>
								    <?php echo "	<tr><td>日期</td><td>申请".$rl_name."PV</td><td>申请".$rl_name."UV</td><td>申请".$rl_name."IP</td>
								    <td>提交".$rl_name."PV</td><td>提交".$rl_name."UV</td><td>提交".$rl_name."IP</td>
								    <td>成功提交".$rl_name."PV</td><td>成功提交".$rl_name."UV</td><td>成功提交".$rl_name."IP</td>
								    <td>".$rl_name."操作成功率</td></tr> ";?>
								    </thead>			 
									<tbody>		
							   <?php foreach($datas as $data){
							   		if($rl_tpye=='reg'){
							   	?>
										<tr><td><?php echo $data['date'];?></td>
											<td><?php echo $data['sq_pv'];?></td>
											<td><?php echo $data['sq_uv'];?></td>
											<td><?php echo $data['sq_ip'];?></td>
											<td><?php echo $data['tj_pv'];?></td>
											<td><?php echo $data['tj_uv'];?></td>
											<td><?php echo $data['tj_ip'];?></td>
											<td><?php echo $data['cgtj_pv'];?></td>
											<td><?php echo $data['cgtj_uv'];?></td>
											<td><?php echo $data['cgtj_ip'];?></td>
											<td><?php echo round($data['cgtj_uv']*100/$data['sq_uv'],2);echo "%";?></td>
										</tr>
									<?php }else{ ?>		
										<tr><td><?php echo $data['date'];?></td>
											<td><?php echo $data['loginsq_pv'];?></td>
											<td><?php echo $data['loginsq_uv'];?></td>
											<td><?php echo $data['loginsq_ip'];?></td>
											<td><?php echo $data['logintj_pv'];?></td>
											<td><?php echo $data['logintj_uv'];?></td>
											<td><?php echo $data['logintj_ip'];?></td>
											<td><?php echo $data['logincgtj_pv'];?></td>
											<td><?php echo $data['logincgtj_uv'];?></td>
											<td><?php echo $data['logincgtj_ip'];?></td>
											<td><?php echo round($data['logincgtj_uv']*100/$data['logintj_uv'],2);echo "%";?></td>
										</tr>
										
										
										<?php }}?>
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
  
