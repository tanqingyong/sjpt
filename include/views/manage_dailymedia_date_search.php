<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');
$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$offset = $arr_var['offset'];
$summary = $arr_var['sum_array'];
$media_name = $arr_var['media_name'];

?>
<script type="text/javascript">
function GetParams(type) {
    var url = location.search; 
    var theRequest = new Array();
    var return_str = "";
    var order_type = 0;
    if (url.indexOf("?") != -1) {
       var str = url.substr(1);
       strs = str.split("&");
       for(var i = 0; i < strs.length; i ++) {
          theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
          if(strs[i].split("=")[0]=='order_type'){
              theRequest[strs[i].split("=")[0]]=type; 
              order_type=1;
          }
          if(i==strs.length-1){
              return_str +=strs[i].split("=")[0]+'='+theRequest[strs[i].split("=")[0]];
          }else{
              return_str +=strs[i].split("=")[0]+'='+theRequest[strs[i].split("=")[0]]+'&';
          }
       }
    }
    if(order_type==0){
        if(return_str)
            return_str +="&order_type="+type; 
        else 
            return_str +="order_type="+type; 
    }
    return return_str;
 }

function change_order_type(type){
    var url_arr = window.location.href.split('?');
    var url = url_arr[0];
    window.location.href=url+'?'+GetParams(type);
 
}
function cha_xun(){
    $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/dailymedia/date_search.php');
    $("form:first").submit();
}
function export_excel(){
    $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/dailymedia/export_date_search.php');
    $("form:first").submit();
}

</script>

<div
    class="right-data"><!-- search  -->
    <div class="search-box">
        <form action="" method="get">
            <table>
                <tr>
                    <td>
                        <label>媒体名称</label>
                        <select id="media_name" name="media_name" class="se_ect1">
                        <option></option>
                             <?php  echo ''.Utility::Option(get_platforms_for_search(),$media_name).''; ?>
                       </select>
                    </td>
                    <td><label>查询从</label> <input type="text" width="10px"
                        name="startdate" id="stratdate" onfocus="WdatePicker()"
                        value='<?php echo $filter_date_start;?>' /></td>
                    <td><label>到</label> <input type="text" width="10px" name="enddate"
                        id="enddate" onfocus="WdatePicker()"
                        value='<?php echo $filter_date_end;?>' /></td>
                    <td><a href="javascript:void(0);" onclick="cha_xun();"
                        class="search-btn">查询</a></td>
                    <td><a href="javascript:void(0);" onclick="export_excel();"
                        class="export-btn">导出数据</a></td>
                    <td><a href="javascript:void(0)" onclick="change_order_type('asc')">↑升序</a>/<a href="javascript:void(0)" onclick="change_order_type('desc')">↓降序</a></td>
                </tr>
            </table>
        </form>
        
    </div>
    <!-- search end  --> <!-- table -->
    <div class="table-data-box">
    <table cellpadding="0" cellspacing="0" style="width: 1400px">
        <thead>
            <tr>
                <td>序号</td>
                <td>日期</td>
                <td>媒体名称</td>
                <td>IP</td>
                <td>UV</td>
                <td>PV</td>
                <td>订单数</td>
                <td>活动下单数</td>
                <td>流水额</td>
                <td>注册量</td>
                <td>成单数</td>
                <td>成单金额</td>
                <td>验证金额</td>
                <td>退款金额</td>
                <td>退款订单率</td>
				<td>退款金额占比</td>
                <td>订单转化率</td>
                <td>注册转化率</td>
                <td>成单率</td>
                <td>购买用户数</td>
                <td>购买转化率</td>
                <td>第三方登录用户数</td>
            </tr>
        </thead>
        <tbody>
        <?php foreach($datas as $data){?>
            <tr>
                <td><?php echo ++$offset;?></td>
                <td><?php echo $data['date'];?></td>
                <td><?php echo $data['platform_id']?></td>
                <td><?php echo $data['ip'];?></td>
                <td><?php echo $data['uv'];?></td>
                <td><?php echo $data['pv'];?></td>
                <td><?php echo $data['order_num'];?></td>
                <td><?php echo $data['act_num'];?></td>
                <td><?php echo $data['total_price'];?></td>
                <td><?php echo $data['register_num']?></td>
                <td><?php echo $data['suc_order_num'];?></td>
                <td><?php echo $data['suc_total_price'];?></td>
                <td><?php echo $data['verify_money'];?></td>
                <td><?php echo $data['refund_money_2'];?></td>
                <td><?php if($data['refund_bishu']>0)echo $data['refund_bishu']."%";?></td>
				<td><?php if($data['refund_money']>0)echo $data['refund_money']."%";?></td>
                <td><?php echo round($data['order_num']*100/$data['ip'],2);?>%</td>
                <td><?php echo round($data['register_num']*100/$data['ip'],2);?>%</td>
                <td><?php echo round($data['suc_order_num']*100/$data['order_num'],2);?>%</td>
                <td><?php echo $data['user_num'];?></td>
                <td><?php echo round($data['user_num']*100/$data['ip'],2);?>%</td>
                <td><?php echo $data['other_login'];?></td>
                
            </tr>
            <?php } if($datas){?>
            <tr>
                <td>总计</td>
                <td>-</td>
                <td>-</td>
                <td><?php echo $summary['ip_sum'];?></td>
                <td><?php echo $summary['uv_sum'];?></td>
                <td><?php echo $summary['pv_sum'];?></td>
                <td><?php echo $summary['order_sum'];?></td>
                <td><?php echo $summary['act_sum'];?></td>
                <td><?php echo round($summary['total_sum'],2);?></td>
                <td><?php echo $summary['register_sum']?></td>
                <td><?php echo $summary['suc_order_sum'];?></td>
                <td><?php echo round($summary['suc_total_sum'],2);?></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td><?php echo round($summary['suc_order_sum']*100/$summary['order_sum'],2);?>%</td>
                <td><?php echo $summary['user_num'];?></td>
                <td>-</td><td>-</td>
            </tr>
            <?php }if(!$datas){?>
            <tr>
                <td colspan='19'>没有数据</td>
            </tr>
            <?php }?>
        </tbody>
    </table>
    </div>
    <div class="sect">
       <table   width="100%" id="orders-list" cellspacing="0" cellpadding="0" border="0" class="coupons-table">
           <tr>
               <td><?php
                        echo $pagestring;
                   ?>
               </td>                         
          </tr>
      </table>
    </div>
<!-- table end -->
</div>

