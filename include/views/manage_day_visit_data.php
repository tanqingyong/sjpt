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
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/day_visit_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/export_day_visit_data.php');
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
                                    <table cellpadding="0" cellspacing="0" style="width:1550px">
									<thead>
                                          <tr><td>日期</td><td>整站PV</td><td>整站UV</td><td>整站IP</td><td>平均停留时长</td><td>商品PV</td>
                                          <td>商品UV</td><td>商品IP</td><td>点击商品数</td><td>0元商品数</td><td>推送率</td><td>登录用户数</td>
                                          <td>新注册用户数</td><td>购买用户数</td><td>联合登陆未购买用户数</td><td>联合登陆购买用户数</td>
                                          <td>注册转化率</td><td>累计注册用户数</td><td>累计购买用户数</td></tr> 	   									  </thead>
	 								<tbody>
								   <?php 
								   function  pertime($aa){
								   	$h = intval($aa/3600);
								   	$m = intval(($aa-$h*3600)/60);
								   	$s = $aa-$h*3600-$m*60;
								   	if($h<10){
								   		$h = "0".$h;								   		
								   	}
								   if($m<10){
								   		$m = "0".$m;								   		
								   	}
								   if($s<10){
								   		$s = "0".$s;								   		
								   	}
								   	return  $h.":".$m.":".$s;
								   	
								   }
								   foreach($datas as $data){?>
											<tr><td><?php echo $data['data_date'];?></td>
												<td><?php echo $data['total_pv'];?></td>
												<td><?php echo $data['total_uv'];?></td>
												<td><?php echo $data['total_ip'];?></td>											
												<td><?php echo pertime($data['time_online_peruser']);?></td>
												<td><?php echo $data['goods_pv'];?></td>
												<td><?php echo $data['goods_uv'];?></td>
												<td><?php echo $data['goods_ip'];?></td>
												<td><?php echo $data['click_product'];?></td>
												<td><?php echo $data['click_zero_goodsnum'];?></td>
												<td><?php echo round($data['goods_uv']*100/$data['total_uv'],2);echo "%";?></td>
												<td><?php echo $data['login_user'];?></td>
												<td><?php echo $data['reg_user'];?></td>
												<td><?php echo $data['buy_user'];?></td>
												<td><?php echo $data['union_nopay_user'];?></td>
												<td><?php echo $data['union_pay_user'];?></td>
												<td><?php echo round($data['reg_user']*100/$data['total_uv'],2);echo "%";?></td>
												<td><?php echo $data['reg_total_user'];?></td>
												<td><?php echo $data['buy_total_user'];?></td>
											</tr>
											<?php }?>
											<?php if(!$datas){?>
											<tr><td  colspan='15'>没有数据</td></tr>
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