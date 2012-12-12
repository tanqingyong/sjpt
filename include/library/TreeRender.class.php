<?php
/**
 *菜单数量少
 */
class TreeRender {
	/*
	 * 获得指定节点的所有子节点，生成菜单树
	 */
	static public function tree_render($category,$checkbox=false, $viewed=false) {
		/*
		 <li>
		  <label><a href="javascript:;">站长特效一级菜单</a></label>
		  <ul class="two">
		   <li>
		    <label><a href="javascript:;">站长特效二级菜单</a></label>
		   </li>
		  </ul>
		 </li>
		 */

		$str_tree = '<li id="'.$category->getId().'">';
		if($checkbox){
			$str_tree .= '<input type="checkbox" id="'.$category->getId().'" name="'.$category->getMenu_name().'">';
		}
		$str_tree .= '<label>';
	    if($category->getMenu_grade()>1&&!$checkbox){
	    	if(get_url()==$category->getUrl()){
	    		$str_tree.= '<a class="current_menu" href="'.$category->getUrl().'">';
	    	}else{
	    		$str_tree.= '<a href="'.$category->getUrl().'">';
	    	}
	    }
	    $str_tree.= $category->getMenu_name();
		if($category->getMenu_grade()>1&&!$checkbox){
	    	$str_tree.= '</a>';
	    }
	    $str_tree.= '</label>';
	    
		if ($category->can_expand()) {
			$str_tree.= '<ul class="two"';
			if($checkbox)
				$str_tree .= ' style="display: block;"';
			$str_tree.= '>';
			$children = $category->get_children();//获得此结点的子节点
			
			for ($i=0;$i<count($children);$i++){
				$str_tree.= TreeRender::tree_render($children[$i],$checkbox, $viewed);//递归调用显示子节点
			}
			
			$str_tree.= '</ul>';
		}
		$str_tree.= '</li>';
		
		return $str_tree;
	}
	
	/*
	 * 根据给定的菜单ID生成菜单列表
	 * @para $arr_menu_ids  array('一级菜单ID'=>'二级菜单ID数组',...)
	 */
	static public function tree_node_reader($arr_menu_ids,$checkbox=false, $viewed=false){
		$str_tree = '';
		foreach($arr_menu_ids as $menu_id=>$menus){
			$category = new Category ($menu_id);

			$str_tree .= '<li id="'.$category->getId().'">';
			if($checkbox){
				$str_tree .= '<input type="checkbox" id="'.$category->getId().'" name="'.$category->getMenu_name().'">';
			}
			$str_tree .= '<label>';
		    if($category->getMenu_grade()>1&&!$checkbox){
			    if(get_url()==$category->getUrl()){
		    		$str_tree.= '<a class="current_menu" href="'.$category->getUrl().'">';
		    	}else{
		    		$str_tree.= '<a href="'.$category->getUrl().'">';
		    	}
		    	
		    }
		    $str_tree.= $category->getMenu_name();
			if($category->getMenu_grade()>1&&!$checkbox){
		    	$str_tree.= '</a>';
		    }
		    $str_tree.= '</label>';
		    
			if ($category->can_expand()) {
				$str_tree.= '<ul class="two"';
				if($checkbox)
					$str_tree .= ' style="display: block;"';
				$str_tree.= '>';
				foreach($menus as $id){
					$category = new Category ($id);
					
					$str_tree .= '<li id="'.$category->getId().'">';
					if($checkbox){
						$str_tree .= '<input type="checkbox" id="'.$category->getId().'" name="'.$category->getMenu_name().'">';
					}
					$str_tree .= '<label>';
				    if($category->getMenu_grade()>1&&!$checkbox){
					    if(get_url()==$category->getUrl()){
			    			$str_tree.= '<a class="current_menu" href="'.$category->getUrl().'">';
				    	}else{
				    		$str_tree.= '<a href="'.$category->getUrl().'">';
				    	}
				    }
				    $str_tree.= $category->getMenu_name();
					if($category->getMenu_grade()>1&&!$checkbox){
				    	$str_tree.= '</a>';
				    }
				    $str_tree.= '</label></li>';
				}
				
				$str_tree.= '</ul>';
			}
			$str_tree.= '</li>';
		}

		return $str_tree;
	}
}
?>