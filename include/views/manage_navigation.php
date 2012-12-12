<?php
need_login ();
?>
<div id="content">
<div>
<!-- 暂时注释掉该部分，因为现在只有窝窝团的数据，等以后有55.com的数据后再做调整 -->
<!--  div  class="tag-menu"><span class="tag3"><a id="one3"
	onclick="setTab('one',3,3)" class="help"><cite>help</cite></a></span> <span
	class="tag2"><a id="one2" onclick="setTab('one',2,3)" class="ww"><cite>55.com</cite></a></span>
<span class="tag1"><a id="one1" onclick="setTab('one',1,3)"
	class="wwtuan hover"><cite>55tuan.com</cite></a></span></div>
</div-->
<div class="data-content"><!-- tag1 wwtuan  -->
<div id="con_one_1" class="data-wwtuan">

<div class="left-menu">
<p><a>主体分析</a></p>
<ul id="menu-tree">
<?php
$arr_auth = Session::Get ( 'user_auth' );

//查看所有数据源数据

if('data_resource_member'==get_data_resource_role($arr_auth)){
	if('department_member'==get_department_role($arr_auth)){
		$arr_menu_ids = get_curr_user_menu($arr_auth);
		echo TreeRender::tree_node_reader($arr_menu_ids);
	}else{
		$arr_menu_ids = get_menu_by_data_resource_id(get_data_resource_id($arr_auth));
		echo TreeRender::tree_node_reader($arr_menu_ids);
	}
}else{
	$top_cate = new Category ( 1 );
	$top = $top_cate->get_children ();
	for($i = 0; $i < count ( $top ); $i ++){
		echo TreeRender::tree_render ( $top [$i]);
	}
}

?>
</ul>
<script type="text/javascript">
 function addEvent(el,name,fn){//绑定事件
  if(el.addEventListener) return el.addEventListener(name,fn,false);
  return el.attachEvent('on'+name,fn);
 } 
 function nextnode(node){//寻找下一个兄弟并剔除空的文本节点
  if(!node)return ;
  if(node.nodeType == 1)
   return node;
  if(node.nextSibling)
   return nextnode(node.nextSibling);
 }
 function prevnode(node){//寻找上一个兄弟并剔除空的文本节点
  if(!node)return ;
  if(node.nodeType == 1)
   return node;
  if(node.previousSibling)
   return prevnode(node.previousSibling);
 } 
 function parcheck(self,checked){//递归寻找父亲元素，并找到input元素进行操作
  var par =  prevnode(self.parentNode.parentNode.parentNode.previousSibling),parspar;
  if(par&&par.getElementsByTagName('input')[0]){
   par.getElementsByTagName('input')[0].checked = checked;
   parcheck(par.getElementsByTagName('input')[0],sibcheck(par.getElementsByTagName('input')[0]));
  }
 } 
 function sibcheck(self){//判断兄弟节点是否已经全部选中
  var sbi = self.parentNode.parentNode.parentNode.childNodes,n=0;
  for(var i=0;i<sbi.length;i++){
   if(sbi[i].nodeType != 1)//由于孩子结点中包括空的文本节点，所以这里累计长度的时候也要算上去
    n++;
   else if(sbi[i].getElementsByTagName('input')[0].checked)
    n++;
  }
  return n==sbi.length?true:false;
 }
 addEvent(document.getElementById('menu-tree'),'click',function(e){//绑定input点击事件，使用menu-tree根元素代理
  e = e||window.event;
  var target = e.target||e.srcElement;
  var tp = nextnode(target.parentNode.nextSibling);
  switch(target.nodeName){
   case 'A'://点击A标签展开和收缩树形目录，并改变其样式会选中checkbox
    if(tp&&tp.nodeName == 'UL'){
     if(tp.style.display != 'block' ){
      tp.style.display = 'block';
      prevnode(target.parentNode.previousSibling).className = 'ren'
     }else{
      tp.style.display = 'none';
      prevnode(target.parentNode.previousSibling).className = 'add'
     }
    }
    break;
   case 'SPAN'://点击图标只展开或者收缩
    var ap = nextnode(nextnode(target.nextSibling).nextSibling);
    if(ap.style.display != 'block' ){
     ap.style.display = 'block';
     target.className = 'ren'
    }else{
     ap.style.display = 'none';
     target.className = 'add'
    }
    break;
   case 'INPUT'://点击checkbox，父亲元素选中，则孩子节点中的checkbox也同时选中，孩子结点取消父元素随之取消
    if(target.checked){
     if(tp){
      var checkbox = tp.getElementsByTagName('input');
      for(var i=0;i<checkbox.length;i++)
       checkbox[i].checked = true;
     }
    }else{
     if(tp){
      var checkbox = tp.getElementsByTagName('input');
      for(var i=0;i<checkbox.length;i++)
       checkbox[i].checked = false;
     }
    }
    parcheck(target,sibcheck(target));//当孩子结点取消选中的时候调用该方法递归其父节点的checkbox逐一取消选中
    break;
  }
 });
 window.onload = function(){//页面加载时给有孩子结点的元素动态添加图标
  var labels = document.getElementById('menu-tree').getElementsByTagName('label');
  for(var i=0;i<labels.length;i++){
   var span = document.createElement('span');
   span.style.cssText ='display:inline-block;height:18px;vertical-align:middle;width:16px;cursor:pointer;';
   span.innerHTML = ' '
   span.className = 'add';
   if(nextnode(labels[i].nextSibling)&&nextnode(labels[i].nextSibling).nodeName == 'UL')
    labels[i].parentNode.insertBefore(span,labels[i]);
   else
    labels[i].className = 'rem'
  }
  $('.current_menu').parent().parent().parent().css('display','block');
  spanren = $('.current_menu').parent().parent().parent().parent().children().get(0);
  $(spanren).addClass('ren');
 } 
</script>
<?php if(is_manager()){;?>
<p><a>系统管理</a></p>
<ul id="menu-tree">
	 <li>
	  <label>
	  	<?php if(get_url()=='/manage/user/create_user.php'){?>
	  		<a class="current_menu" href="/manage/user/create_user.php">
	  	<?php }else{?>
	  		<a href="/manage/user/create_user.php">	
	  	<?php }?>
	  		新建用户</a></label>
	 </li>
	 <li>
      <label>
      	<?php if(get_url()=='/manage/user/manage.php'){?>
	  		<a class="current_menu" href="/manage/user/manage.php">
	  	<?php }else{?>
	  		<a href="/manage/user/manage.php">	
	  	<?php }?>
      	用户管理</a></label>
	 </li>
	 <li>
      <label>
      	<?php if(get_url()=='/manage/menulog/menulog_search.php'){?>
	  		<a class="current_menu" href="/manage/menulog/menulog_search.php">
	  	<?php }else{?>
	  		<a href="/manage/menulog/menulog_search.php">	
	  	<?php }?>
      	菜单访问日志</a></label>
	 </li>	 
	 <!-- 
	 <li>
      <label>
      	<?php if(get_url()=='/manage/onlineuser/onlineuser_search.php'){?>
	  		<a class="current_menu" href="/manage/onlineuser/onlineuser_search.php">
	  	<?php }else{?>
	  		<a href="/manage/onlineuser/onlineuser_search.php">	
	  	<?php }?>
      	当前在线用户</a></label>
	 </li>	 
	  -->	 
</ul>
<?php }?>
<p><a href="/manage/user/update_password.php">修改密码</a></p>
<p><a href="/doc/">数据项定义</a></p>
</div>