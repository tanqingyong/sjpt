<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$filter_mail_type = $arr_var['filter_mail_type'];
$filter_edm = $arr_var['filter_edm'];
$page_size = $arr_var['page_size'];
$sum_array = $arr_var['sum_array'];
$mail_summary = $arr_var['mail_summary'];
//获取平台和邮件类型及其对应关系 
global $edms_mail;
global $mail_array;
$edms =array();
$mail_types = $mail_array;
foreach($edms_mail as $edm=> $mails){
    $edms[$edm]=$edm;
}
?>
<script type="text/javascript">
    function cha_xun(){
    	if(check_date()){
	        $("form:first").attr('action', '<?php echo WEB_ROOT;?>/manage/edm/day_sales_data/day_sales_data.php');
	        $("form:first").submit();
    	}
    }
    
    function export_excel(){
        if(check_date()){
	        $("form:first").attr('action', '<?php echo WEB_ROOT;?>/manage/edm/day_sales_data/export_day_sales_data.php');
	        $("form:first").submit();
        }
    }
    function check_date(){
        var date_from = $('#startdate').val();
        var date_end = $('#enddate').val();
        if( date_from && date_end ){
            var from_date = date_from.split("-");
            var end_date = date_end.split("-");
            if(date_from>date_end){
                alert("开始时间不能比结束时间大!");
                return false;
            }
        }
        return true;
    }
</script>
<div class="right-data">
	<div class="top-info"><!-- today-monitor-->
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	    <tr>
	        <td>汇总信息为：</td>
	        <td>
	          <p>
		       <?php echo "
		                   IP:{$sum_array['summary']['ip_sum']}, 
                           UV:{$sum_array['summary']['uv_sum']}, 
                           PV:{$sum_array['summary']['pv_sum']}, 
                                                                        注册用户数:{$sum_array['summary']['register_sum']}, 
                                                                        订单量:{$sum_array['summary']['order_sum']}, 
                                                                        订单额:{$sum_array['summary']['money_sum']}, 
                                                                        成单量:{$sum_array['summary']['suc_order_sum']}, 
                                                                        成单额:{$sum_array['summary']['suc_money_sum']},
                                                                        成单率:{$sum_array['summary']['suc_order_rate']},
                                                                        订单转化率:{$sum_array['summary']['order_rate']}, 
                                                                        新用户转化率:{$sum_array['summary']['register_rate']}, 
                                                                        发送成本:{$sum_array['summary']['cost_sum']},
                           CPC:{$sum_array['summary']['CPC']}, 
                           CPA:{$sum_array['summary']['CPA']},
                           CPS:{$sum_array['summary']['CPS']},
                           ROI:{$sum_array['summary']['ROI']}.
	                      "; 
	           ?>
              </p>
             </td>
         </tr>
         
	        <?php 
	          foreach($edms_mail as $edm => $mails){
	          	  if(!empty($sum_array[$edm])){
		          	  echo "<tr><td>{$edm}的统计信息为:</td>";
		          	  echo "<td><p>
		          	            IP:{$sum_array[$edm]['ip_sum']},
		          	            UV:{$sum_array[$edm]['uv_sum']}, 
	                            PV:{$sum_array[$edm]['pv_sum']}, 
	                                                                           注册用户数:{$sum_array[$edm]['register_sum']}, 
	                                                                           订单量:{$sum_array[$edm]['order_sum']}, 
	                                                                           订单额:{$sum_array[$edm]['money_sum']},
	                                                                           成单量:{$sum_array[$edm]['suc_order_sum']}, 
                                                                                      成单额:{$sum_array[$edm]['suc_money_sum']},
                                                                                      成单率:{$sum_array[$edm]['suc_order_rate']},                                                
	                                                                           订单转化率:{$sum_array[$edm]['order_rate']}, 
	                                                                           新用户转化率:{$sum_array[$edm]['register_rate']}, 
	                                                                           发送成本:{$sum_array[$edm]['cost_sum']},
	                            CPC:{$sum_array[$edm]['CPC']}, 
	                            CPA:{$sum_array[$edm]['CPA']},
	                            CPS:{$sum_array[$edm]['CPS']},
	                            ROI:{$sum_array[$edm]['ROI']},
	                           </p>
	                         </td></tr>";
	          	  }
	          	  foreach($mails as $mail){
	          	  	  if(!empty($mail_summary[get_real_mailtype($mail)])){
		          	  	  echo "<tr><td>".get_mail_name($mail).":</td>";
			              echo "<td><p>IP:".$mail_summary[get_real_mailtype($mail)]["ip_sum"].",
			                           UV:".$mail_summary[get_real_mailtype($mail)]['uv_sum'].", 
			                           PV:".$mail_summary[get_real_mailtype($mail)]['pv_sum'].", 
			                                                                        注册用户数:".$mail_summary[get_real_mailtype($mail)]['register_sum'].", 
			                                                                        订单量:".$mail_summary[get_real_mailtype($mail)]['order_sum'].", 
			                                                                        订单额:".$mail_summary[get_real_mailtype($mail)]['money_sum'].",
			                                                                        成单量:".$mail_summary[get_real_mailtype($mail)]['suc_order_sum'].", 
                                                                                                        成单额:".$mail_summary[get_real_mailtype($mail)]['suc_money_sum'].",
                                                                                                        成单率:".$mail_summary[get_real_mailtype($mail)]['suc_order_rate'].",                                                                      
			                                                                        订单转化率:".$mail_summary[get_real_mailtype($mail)]['order_rate'].", 
			                                                                        新用户转化率:".$mail_summary[get_real_mailtype($mail)]['register_rate'].", 
			                                                                        发送成本:".$mail_summary[get_real_mailtype($mail)]['cost_sum'].",
			                           CPC:".$mail_summary[get_real_mailtype($mail)]['CPC'].", 
			                           CPA:".$mail_summary[get_real_mailtype($mail)]['CPA'].",
			                           CPS:".$mail_summary[get_real_mailtype($mail)]['CPS'].",
			                           ROI:".$mail_summary[get_real_mailtype($mail)]['ROI'].",
			                    </p></td></tr>";
	          	  	  }
	          	  }
	          }
            ?>
	</table>
    <!-- today-monitor end-->
   </div><!-- search  -->
<div class="search-box">
<form action="" method="get">
<table>
	<tr>
		<td><label> 查询从 </label> <input type="text" style="width: 90px"
			name="startdate" id="startdate" onfocus="WdatePicker()"
			value='<?php echo $filter_date_start;?>' /></td>
		<td><label> 到 </label> <input type="text" style="width: 90px"
			name="enddate" id="enddate" onfocus="WdatePicker()"
			value='<?php echo $filter_date_end;?>' /></td>
		<td><label> 邮件类型 </label> <select id="mail_type" name="mail_type">
			<option></option>
			<?php
			echo '' . Utility::Option ( $mail_types , $filter_mail_type ) . '';
			?>
		</select></td>
		<td><label> 发送平台 </label> <select id="edm" name="edm">
			<option></option>
			<?php
			echo '' . Utility::Option ( $edms , $filter_edm ) . '';
			?>
		</select></td>
		<td><a href="javascript:void(0);" onclick="cha_xun();"
			class="search-btn">查询</a></td>
		<td><a href="javascript:void(0);" onclick="export_excel();"
			class="export-btn">导出数据</a></td>
	</tr>
</table>
</form>
</div>
<!-- search end  --><!-- table -->
<div class="table-data-box">
<table cellpadding="0" cellspacing="0" style="width: 1400px">
	<thead>
		<tr>
			<td>日期</td>
			<td>邮件类型</td>
			<td>发送平台</td>
			<td>IP</td>
			<td>UV</td>
			<td>PV</td>
			<td>注册用户数</td>
			<td>订单量</td>
			<td>订单额</td>
			<td>成单量</td>
			<td>成单额</td>
			<td>成单率</td>
			<td>订单转化率</td>
			<td>新用户转化率</td>
			<td>发送成本</td>
			<td>CPC</td>
			<td>CPA</td>
			<td>CPS</td>
			<td>ROI</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach($datas as $data){?>
	   <?php if( date("w",strtotime($data['date']))==0 || date("w",strtotime($data['date']))==6){?>
		<tr class="weekend">
			<td><?php echo $data['date'];?></td>
			<td><?php 
			         echo get_mail_name($data['edm_id']);
				?></td>
			<td><?php echo $data['platform'];?></td>
			<td><?php echo $data['ip'];?></td>
			<td><?php echo $data['uv'];?></td>
			<td><?php echo $data['pv'];?></td>
			<td><?php echo $data['register_num'];?></td>
			<td><?php echo $data['order_num'];?></td>
			<td><?php echo $data['total_price'];?></td>
			<td><?php echo $data['suc_order_num'];?></td>
            <td><?php echo $data['suc_total_price'];?></td>
            <td><?php echo round($data['suc_order_num']*100/$data['order_num'],2);?>%</td>
			<td><?php echo round($data['order_num']*100/$data['ip'],2);?>%</td>
			<td><?php echo round($data['register_num']*100/$data['ip'],2);?>%</td>
			<?php if($data['sum_cost']){?>
			<td><?php echo round($data['sum_cost'],2);?></td>
            <td><?php echo round($data['sum_cost']/$data['uv'],2);?></td>
            <td><?php echo round($data['sum_cost']/$data['register_num'],2);?></td>
            <td><?php echo round($data['sum_cost']/$data['order_num'],2);?></td>
            <td><?php echo round($data['suc_total_price']/$data['sum_cost'],2);?></td>
			<?php }else{?>
			<td><?php echo "--";?></td>
            <td><?php echo "--";?></td>
            <td><?php echo "--";?></td>
            <td><?php echo "--";?></td>
            <td><?php echo $data['suc_total_price']?">100":"--"?></td>
			<?php }?>
		</tr>
		<?php }else{?>
		<tr>
            <td><?php echo $data['date'];?></td>
            <td><?php 
                     echo get_mail_name($data['edm_id']);
                ?></td>
            <td><?php echo $data['platform'];?></td>
            <td><?php echo $data['ip'];?></td>
            <td><?php echo $data['uv'];?></td>
            <td><?php echo $data['pv'];?></td>
            <td><?php echo $data['register_num'];?></td>
            <td><?php echo $data['order_num'];?></td>
            <td><?php echo $data['total_price'];?></td>
            <td><?php echo $data['suc_order_num'];?></td>
            <td><?php echo $data['suc_total_price'];?></td>
            <td><?php echo round($data['suc_order_num']*100/$data['order_num'],2);?>%</td>
            <td><?php echo round($data['order_num']*100/$data['ip'],2);?>%</td>
            <td><?php echo round($data['register_num']*100/$data['ip'],2);?>%</td>
            <?php if($data['sum_cost']){?>
            <td><?php echo round($data['sum_cost'],2);?></td>
            <td><?php echo round($data['sum_cost']/$data['uv'],2);?></td>
            <td><?php echo round($data['sum_cost']/$data['register_num'],2);?></td>
            <td><?php echo round($data['sum_cost']/$data['order_num'],2);?></td>
            <td><?php echo round($data['suc_total_price']/$data['sum_cost'],2);?></td>
            <?php }else{?>
            <td><?php echo "--";?></td>
            <td><?php echo "--";?></td>
            <td><?php echo "--";?></td>
            <td><?php echo "--";?></td>
            <td><?php echo $data['suc_total_price']?">100":"--"?></td>
            <?php }?>
        </tr>     
		<?php }?>
    <?php }?>
		<?php if(!$datas){?>
		<tr>
			<td colspan='16'>没有数据</td>
		</tr>
		<?php }?>
	</tbody>
</table>
</div>
<div class="sect">
<table width="100%" id="orders-list" cellspacing="0" cellpadding="0"
	border="0" class="coupons-table">
	<tr>
		<td><?php
		echo $pagestring;
		?>
	
	</tr>
</table>
</div>
<!-- table end --></div>
