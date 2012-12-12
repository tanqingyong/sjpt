<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = date("Y-m-d",strtotime($arr_var['filter_date_start'])+15*24*3600);
$filter_date_end = $arr_var['filter_date_end'];
$filter_mail_type = $arr_var['filter_mail_type'];
$filter_edm = $arr_var['filter_edm'];
$page_size = $arr_var['page_size'];
$filter_task_id = $arr_var['filter_task_id'];

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
	    $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/edm/task_data/send_task_data.php');
	    $("form:first").submit();
	}
}
function export_excel(){
	if(check_date()){
	    $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/edm/task_data/export_send_task_data.php');
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
<!-- search  -->
<div class="search-box">
<form action="" method="get">
<table>
    <tr>
        <td><label>查询从</label> <input type="text" style="width:90px"
            name="startdate" id="startdate" onfocus="WdatePicker()"
            value='<?php echo $filter_date_start;?>' /></td>
        <td><label>到</label> <input type="text" style="width:90px" name="enddate"
            id="enddate" onfocus="WdatePicker()"
            value='<?php echo $filter_date_end;?>' /></td>
        <td>
        <label>任务ID</label>
        <input type="text" style="width:90px" name="task_id"
            id="task_id" 
            value='<?php echo $filter_task_id;?>' />
        </td>
        <td>
        <label>邮件类型</label>
        <select id="mail_type" name="mail_type" >
            <option></option>
           <?php
                echo '' . Utility::Option ( $mail_types , $filter_mail_type ) . '';
           ?>
        </select>
        </td>
        <td><label>发送平台</label>
        <select id="edm" name="edm" >
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
<!-- search end  --> <!-- table -->
<div class="table-data-box">
<table cellpadding="0" cellspacing="0" style="width: 1400px">
    <thead>
        <tr>
            <td>发送日期</td>
            <td>任务ID</td>
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
        <tr>
            <td><?php echo $data['send_date'];?></td>
            <td><?php echo $data['task_id'];?></td>
            <td><?php echo $mail_array[$data['mail']];?></td>
            <td><?php echo $data['edm'];?></td>
            <td><?php echo $data['ip_sum'];?></td>
            <td><?php echo $data['uv_sum'];?></td>
            <td><?php echo $data['pv_sum'];?></td>
            <td><?php echo $data['register_sum'];?></td>
            <td><?php echo $data['order_sum'];?></td>
            <td><?php echo round($data['money_sum'],2);?></td>
            <td><?php echo $data['suc_order_sum'];?></td>
            <td><?php echo round($data['suc_money_sum'],2);?></td>
            <td><?php echo round($data['suc_order_sum']*100/$data['order_sum'],2);?>%</td>
            <td><?php echo round($data['order_sum']*100/$data['ip_sum'],2);?>%</td>
            <td><?php echo round($data['register_sum']*100/$data['ip_sum'],2);?>%</td>
            <?php if($data['cost']){?>
            <td><?php echo round($data['cost'],2);?></td>
            <td><?php echo round($data['cost']/$data['uv_sum'],2);?></td>
            <td><?php echo round($data['cost']/$data['register_sum'],2);?></td>
            <td><?php echo round($data['cost']/$data['order_sum'],2);?></td>
            <td><?php echo round($data['suc_money_sum']/$data['cost'],2);?></td>
            <?php }else{?>
            <td><?php echo "--";?></td>
            <td><?php echo "--";?></td>
            <td><?php echo "--";?></td>
            <td><?php echo "--";?></td>
            <td><?php echo $data['suc_money_sum']?">100":"--";?></td>
            <?php }?>
        </tr>
        <?php }?>
        <?php if(!$datas){?>
        <tr>
            <td colspan='17'>没有数据</td>
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

