<?php
class product
{
	var $id;
	var $cat_id;
	var $name;
	
	function getList($params, $sort)
	{
		if( (isset($params['cid'])) && (isset($params['include_subcats'])))
		{
			$sub_ids = $this->subcategoryids($params['cid']);
			$products_from_subcaregories = $this->selectfromSubCategory($sub_ids, $params['include_subcats']);
		}
		else
		{
			$products_from_subcaregories = "";
		}
		
		$sorting_by_name = $this->sorting($sort);
		
		$allproducts = $this->getproducts($params['cid'], $products_from_subcaregories, $sorting_by_name);
			
		// echo '<pre>';print_r($allproducts);echo '</pre>';	
			
		return $allproducts;
	}
	
	function getproducts($cid, $products_from_subcaregories, $sorting_by_name)
	{
	
//		print_r('CatID='.$cid.'</br>products_from_subcaregories='.$products_from_subcaregories.'</br>sorting_by_name='.$sorting_by_name);
	
		$sql = "SELECT p.id, p.name AS prodname, c.name AS catname FROM product AS p
				LEFT JOIN category AS c ON c.id=p.cat_id";
				
		if($cid!=0) 
		{
			If($products_from_subcaregories!="")
			{
				$sql .= " WHERE p.cat_id=".$cid.$products_from_subcaregories;
			}
			else
			{
				$sql .= " WHERE p.cat_id=".$cid;
			}
		}	
					
		If($sorting_by_name!="")
		{
			$sql .= $sorting_by_name;
		}
			
		$query = mysql_query($sql);
		
		$i = 0;
		$allproducts = array();
		while ($row = mysql_fetch_array($query))
			$allproducts[$i++]=$row;
			
		return $allproducts;
	}
	
	function sorting($sort="")
	{
		if($sort!="" and ($sort=="ASC" or $sort=="DESC"))
				$sort = " ORDER BY prodname ".$sort;
		
		return $sort;
	}
	
	function selectfromSubCategory($sub_ids, $include_subcats)
	{
		$str = "";
					
			if($include_subcats)
			{
				$subids = explode("_", $sub_ids);
				$str = "";
                foreach($subids AS $subid)
					if($subid!="") $str.=" OR p.cat_id=".$subid;				
			}
				
		return $str;
	}
	
	function subcategoryids($cid=0)
	{
			
		$nav_query = mysql_query("SELECT * FROM category WHERE par_id='" . $cid . "' ORDER BY name");
		$subcategories = "";                    
		$depth = 1;                   
		$top_level_on = 1;            
		$exclude = array();            
		array_push($exclude, 0);    
						
		while ( $nav_row = mysql_fetch_array($nav_query) )
		{
			$continue = 1;            
			for($x = 0; $x < count($exclude); $x++ )        
			{
				if ( $exclude[$x] == $nav_row['id'] )
				{
					$continue = 0;
					break;                
				}
			}
			if ( $continue == 1 )
			{
				$subcategories .= "_" . $nav_row['id'];                
				array_push($exclude, $nav_row['id']);      
				if ( $nav_row['id'] < 6 )
				{ $top_level_on = $nav_row['id']; }
				
				$subcategories .= $this->build_child($nav_row['id']);        
			}
		}
					
		return $subcategories;
	
	}
		
	function build_child($oldID)           
	{
		$tempTree = "";
		global $exclude, $depth;           
		$child_query = mysql_query("SELECT * FROM category WHERE par_id=" . $oldID ." ORDER BY name");
		while ( $child = mysql_fetch_array($child_query) )
		{
					
			if ( $child['id'] != $child['par_id'] )
			{
				   
				for ( $c=0;$c<$depth;$c++ )            
				{ $tempTree .= ""; }
				$tempTree .= "_". $child['id'];
				$depth++;       
				$tempTree .= $this->build_child($child['id']);        
				$depth--;        
				@array_push($exclude, $child['id']);            
			}
		}
				
		return $tempTree;      
	}
	
	
}
?>
