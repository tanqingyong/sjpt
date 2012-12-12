<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$chechbox = $arr_var['chechbox'];
$count = $arr_var['count'];

foreach($datas as $data){
	$sumip +=$data['ip'];
	$sumuv +=$data['uv'];
	$sumorder +=$data['order_num'];
	$summoney +=$data['total_price'];
    $sumreg +=$data['reg_num'];
}

?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/seo/jixiao/seo_jixiao_data.php');
    $("form:first").submit();
}
function check_box2(){
	alert($("#checkbox").val())
	 $("#checkbox").attr('action','<?php echo WEB_ROOT;?>/manage/seo/jixiao/seo_jixiao_data.php');
	 $("#checkbox").submit();
}
function check_box(){
	var a='';
	  var checkboxa = jQuery("input[name='checkbox']:checked").each(function(){
	   a += '\''+jQuery(this).val()+'\',';
	 })
	 var v = a.substring(0,a.length-1);
	//  window.location.href='<?php echo WEB_ROOT;?>/manage/seo/jixiao/seo_jixiao_data.php?checkbox='+v;
  window.open('<?php echo WEB_ROOT;?>/manage/seo/jixiao/seo_jixiao_data.php?checkbox='+v);
	}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/seo/jixiao/export_seo_jixiao_data.php');
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
									     echo "";
										}
									?>
								  
									</td>
									<td>
									<a href="javascript:void(0);" onclick="cha_xun();" class="search-btn">查询</a>
									</td>
									<?php if(!$chechbox){?>
									<td>
									<a href="javascript:void(0);" onclick="export_excel();"  class="export-btn">导出数据</a>
									</td>
									<?php }?>
									
								</tr>
							</table>
		
							</form>
                               </div>     
                         <!-- search end  -->
                           <!-- table -->
                         <div class="table-data-box">
                                   <table cellpadding="0" cellspacing="0"   style="width:100%px" >
								    <thead>
								    	<tr><td><a href="javascript:void(0);" onclick="check_box();">对比</a></td><td>日期</td><td>星期</td><td>独立IP</td><td>UV</td><td>订单量</td><td>订单额</td>
								    	<td>注册用户</td><td>订单转化率</td><td>新用户转化率</td><td>客单价</td><td>roi</td></tr>
								    </thead>				 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><input id="checkbox" type="checkbox"  name="checkbox" value="<?php echo $data['date'];?>"/></td>
										    <td><?php echo $data['date'];?></td>
											<td><?php echo $data['weeks'];?></td>
											<td><?php echo $data['ip'];?></td>
											<td><?php echo $data['uv'];?></td>
											<td><?php echo $data['order_num'];?></td>
											<td><?php echo round($data['total_price'],2);?></td>
											<td><?php echo $data['reg_num'];?></td>
											<td><?php echo round($data['order_num']/$data['ip']*100,2);echo "%";?></td>
											<td><?php echo round($data['reg_num']/$data['ip']*100,2); echo "%";?></td>
											<td><?php echo round($data['total_price']/$data['order_num'],2);?></td>
											<td><?php echo round($data['total_price']/695,2);?></td> 
										</tr>
										<?php }?>
										
										<tr><td colspan="3" style="background-color:#eeddee"><?php echo "求平均";?></td>
											<td><?php echo round($sumip/$count);?></td>
											<td><?php echo round($sumuv/$count);?></td>
											<td><?php echo round($sumorder/$count);?></td>
											<td><?php echo round($summoney/$count,2);?></td>
											<td><?php echo round($sumreg/$count);?></td>
											<td><?php echo round($sumorder/$sumip*100,2); echo "%";?></td>
											<td><?php echo round($sumreg/$sumip*100,2); echo "%";?></td>
											<td><?php echo round($summoney/$sumorder,2);?></td>
											<td><?php echo round($summoney/695/$count,2);?></td> 
										</tr>
										
										<tr><td colspan="3" style="background-color:#eeddee"><?php echo "求和";?></td>
											<td><?php echo $sumip;?></td>
											<td><?php echo $sumuv;?></td>
											<td><?php echo $sumorder;?></td>
											<td><?php echo round($summoney,2);?></td>
											<td><?php echo $sumreg;?></td>
											<td><?php echo "/";?></td>
											<td><?php echo  "/";?></td>
											<td><?php echo "/";?></td>
											<td><?php echo "/";?></td> 
										</tr>
										
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
  
