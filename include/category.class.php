<?php
class category
{
	var $id;
	var $par_id;
	var $name;
	
	function get($cid=0)
	{
		if($cid!=0)
		{
			$sql = "SELECT * FROM category WHERE id=".$cid;
			$category_array = @mysql_fetch_array(mysql_query($sql));
		}		
		else
		{
			 $category_array = array('name' => '', 'par_id' => 0);
		}
		
		return $category_array;
	}
	
	function getAlltree($cid=0)
	{
	
		$nav_query = mysql_query("SELECT * FROM category WHERE par_id=0 ORDER BY name");
		$tree = "";                    
		$depth = 1;                  
		$top_level_on = 1;           
		$exclude = array();          
		array_push($exclude, 0);   
		
	    function build_child($oldID, $cid)           
		{
			$indent = "";
			$tempTree = "";
			global $exclude, $depth;           
			$child_query = mysql_query("SELECT * FROM category WHERE par_id=" . $oldID ." ORDER BY name");
			while ( $child = mysql_fetch_array($child_query) )
			{
				$indent="";
				if ( $child['id'] != $child['par_id'] )
				{
				   
					for ( $c=0;$c<$depth;$c++ )        
					{ $tempTree .= "";$indent .= "&nbsp;&nbsp;"; }
					if($child['id']==$cid) $select_p="selected=selected"; else $select_p="";
					$tempTree .= "<option value=". $child['id'] . " ".$select_p.">" . $indent . $child['name'] ."</option><br />";
					$depth++;      
					$tempTree .= build_child($child['id'], $cid);    
					$depth--;    
					@array_push($exclude, $child['id']);   
				}
			}
			
			return $tempTree;      
		}
		

		
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
				if($nav_row['id']==$cid) $select="selected=selected"; else $select="";
				$tree .= "<option value=" . $nav_row['id'] . " ".$select.">" . $nav_row['name'] . "</option><br/>";       
				array_push($exclude, $nav_row['id']);
				if ( $nav_row['id'] < 6 )
				{ $top_level_on = $nav_row['id']; }
				
				$tree .= build_child($nav_row['id'], $cid);     
			}
		}
		
		
		return $tree;

	}
	
	function getAllList()
	{
		
		$nav_query = mysql_query("SELECT * FROM category WHERE par_id=0 ORDER BY name");
		$tree = "";           
		$depth = 1;               
		$top_level_on = 1;
		$exclude = array();           
		array_push($exclude, 0);   
		
	    function build_child($oldID)           
		{	$indent = ""; 
			$tempTree = "";
			global $exclude, $depth;           
			$child_query = mysql_query("SELECT * FROM category WHERE par_id=" . $oldID ." ORDER BY name");
			while ( $child = mysql_fetch_array($child_query) )
			{
				
				if ( $child['id'] != $child['par_id'] )
				{    
					for ( $c=0;$c<$depth;$c++ )          
					{ 
						$tempTree .= ""; 
						$indent .= "&nbsp;&nbsp;"; 
					}
					$tempTree .= "<tr><td> " . $indent . $child['name'] . "</td><td><a href='edit_category.php?cid=".$child['id']."'>edit</a></td><td><a href='categories.php?cid=".$child['id']."&act=del'>delete</a></td></tr>";
					$indent = "";
					$depth++;        
					$tempTree .= build_child($child['id']);       
					$depth--;       
					@array_push($exclude, $child['id']);       
				}
			}
			
			return $tempTree;     
		}
		
		
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
				$tree .= "<tr><td> " . $nav_row['name'] . "</td><td><a href='edit_category.php?cid=".$nav_row['id']."'>edit</a></td><td><a href='categories.php?cid=".$nav_row['id']."&act=del'>delete</a></td></tr>";               
				array_push($exclude, $nav_row['id']);        
				if ( $nav_row['id'] < 6 )
				{ $top_level_on = $nav_row['id']; }
				
				$tree .= build_child($nav_row['id']);        
			}
		}
		
		
		return $tree;

	}
	
	function count($id, $category_name)
	{
		$sql = "SELECT COUNT(*) FROM category WHERE name='".$category_name."' AND id<>".$id;
		$count = mysql_fetch_array(mysql_query($sql));
		
		if($count[0]>0)
		{
			$category_name_exist = 1;
		}
		else
		{
			$category_name_exist = 0;
		}
		
		return $category_name_exist;
	}
	
	function sqlCategory($id, $category_name, $parent_id, $action)
	{
		if($action == "update")
		{
			$sql = "UPDATE category SET par_id=" . $parent_id . ", name='" . $category_name . "' WHERE id=" . $id . ";";
		}
		else
		{
			$sql = "INSERT INTO category (par_id, name) VALUES (" . $parent_id . ", '" . $category_name . "');";
		}
		mysql_query($sql);
	}
	
	function insertCategory($id, $category_name, $parent_id)
	{
		$message = "";
		if(!isset($category_name) or $category_name == "")
		{
			$message = "please fill the required fields!";
		}
		else
		{
			if($id == $parent_id and $id != 0)
			{
				$message = "refers to same category!"; 
			}
			else
			{	
				$category_name_exist = $this->count($id, $category_name);
				
				if( !$category_name_exist )	
				{
					if($id!=0)
					{
						$this->sqlCategory($id, $category_name, $parent_id, "update");
						$message = "category updated";
					}
					else
					{
						$this->sqlCategory($id, $category_name, $parent_id, "insert");
						$message = "new category created";
					}
				}		
				else
				{
						$message = "category with the same name alredy exists. please try with another category name.";
				}			
			}
		}
			
		return $message;
	}
	
	function deleteCategory($id)
	{
		$sql = "SELECT COUNT(id) FROM category WHERE par_id=".$id;
		$countsubcategs = @mysql_fetch_array(mysql_query($sql));
		
		$sql = "SELECT COUNT(id) FROM product WHERE cat_id=".$id;
		$countproducts = @mysql_fetch_array(mysql_query($sql));
		
		if($countsubcategs[0]==0 && $countproducts[0]==0)
		{
			$sql = "DELETE 	FROM category WHERE id=".$id;
			mysql_query($sql);
			return "category deleted";
		}
		else
		 return $countsubcategs[0]. " subcategories and " . $countproducts[0] . " products!";

	}
	
}
?>
