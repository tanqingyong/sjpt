<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];

foreach($datas as $data){
	$sumip +=$data['ip'];
	$sumuv +=$data['uv'];
	$sumorder +=$data['order_num'];
	$summoney +=$data['totalmoney'];
    $sumreg +=$data['regnum'];
    $sumpv +=$data['pv'];
    $sumgoods +=$data['goods_num'];
}

?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/seo/jixiao/seo_keyword_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/seo/jixiao/export_seo_keyword_data.php');
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
									<label>日期从</label>
								<input type="text" width="10px" name="stratdate" id="stratdate" onfocus="WdatePicker()"
									value='<?php echo $filter_date_start;?>' />
									</td>
									<td>
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
                                   <table cellpadding="0" cellspacing="0"   style="width:100%px" >
								    <thead>
								    	<tr><td>日期</td><td>关键词</td><td>UV</td><td>独立IP</td><td>PV</td><td>订单数</td>
								    	<td>订单额</td><td>注册用户数</td></tr>
								    </thead>				 
									<tbody>		
							   <?php foreach($datas as $data){?>
							   <tr>
										    <td><?php echo $data['date'];?></td>
											<td><?php echo $data['keyword'];?></td>
											<td><?php echo $data['uv'];?></td>
											<td><?php echo $data['ip'];?></td>
											<td><?php echo $data['pv'];?></td>
											<td><?php echo $data['order_num'];?></td>
											<td><?php echo round($data['totalmoney'],2);?></td>
											<td><?php echo $data['regnum'];?></td>
										</tr>
										<?php }?>
										
										
										<tr><td colspan="2" style="background-color:#eeddee"><?php echo "求和";?></td>
											<td><?php echo $sumuv;?></td>
											<td><?php echo $sumip;?></td>
											<td><?php echo $sumpv;?></td>
											<td><?php echo $sumorder;?></td>
											<td><?php echo round($summoney,2);?></td>
											<td><?php echo  $sumreg;?></td>

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
  
