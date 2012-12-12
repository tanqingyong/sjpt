<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$filter_city = $arr_var['filter_city'];
$page_size = $arr_var['page_size'];

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
        $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/edm/edm_city/edm_city.php');
        $("form:first").submit();
    }
}
function export_excel(){
    if(check_date()){
        $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/edm/edm_city/export_edm_city.php');
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
        <td><label>查询从</label> <input type="text" 
            name="startdate" id="startdate" onfocus="WdatePicker()"
            value='<?php echo $filter_date_start;?>' /></td>
        <td><label>到</label> <input type="text" name="enddate"
            id="enddate" onfocus="WdatePicker()"
            value='<?php echo $filter_date_end;?>' /></td>
        <td>
        <td><label>城市</label>
            <input type="text" id="city" name="city" style="width:80px" value='<?php echo $filter_city;?>'/>
        </td>
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
<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <td>日期</td>
            <td>城市名称</td>
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
        </tr>
    </thead>
    <tbody>
    <?php foreach($datas as $data){?>
        <tr>
            <td><?php echo $data['date'];?></td>
            <td><?php echo $data['city'];?></td>
            <td><?php echo $data['ip_sum'];?></td>
            <td><?php echo $data['uv_sum'];?></td>
            <td><?php echo $data['pv_sum'];?></td>
            <td><?php echo $data['register_sum'];?></td>
            <td><?php echo $data['order_sum'];?></td>
            <td><?php echo $data['money_sum'];?></td>
            <td><?php echo $data['suc_order_sum'];?></td>
            <td><?php echo $data['suc_money_sum'];?></td>
            <td><?php echo $data['suc_order_rate']?>%</td>
            <td><?php echo $data['order_rate']?>%</td>
            <td><?php echo $data['register_rate'];?>%</td>
        </tr>
        <?php }?>
        <?php if(!$datas){?>
        <tr>
            <td colspan='13'>没有数据</td>
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