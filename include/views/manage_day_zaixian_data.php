<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$city_name = $arr_var['city_name'];
?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/day_zaixian_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/export_day_zaixian_data.php');
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
									<label>城市</label>
								<input type="text" width="10px" name="city_name" id="city_name"
									value='<?php echo $city_name;?>' />
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
								    	<tr><td>日期</td><td>商品ID</td><td>城市</td><td>一级分类</td><td>二级分类</td><td>商品名称</td></tr>
								    </thead>				 
									<tbody>		
							   <?php foreach($datas as $data){?>
										<tr><td><?php echo $data['date'];?></td>
											<td><?php echo $data['goods_id'];?></td>
											<td><?php echo $data['city_name'];?></td>
											<td><?php echo $data['first_cate_name'];?></td>
											<td><?php echo $data['second_cate_name'];?></td>
											<td><?php echo $data['goods_sname'];?></td>
										</tr>
										<?php }?>
										<?php if(!$datas){?>
										<tr><td  colspan='6'>没有数据</td></tr>
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
  
