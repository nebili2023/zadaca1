<?php
include ("include/top.php"); 
include ("include/config.php"); 
include ("include/product.class.php");
include ("include/category.class.php");

class page{

	function displayProducts($products, $params, $sort){
		
		if(isset($_GET['msg']))
			echo $_GET['msg'];
	
		echo '<form action="products.php" method="post" />
		Category
		<select style="width:169px;" name="category">
		<option value="0">All</option>'
		. $params['categories'] .
		'</select>
		<br/>
		Include Products In Subcategories
		<input type="checkbox" name="includesubcat" ' . $params['checked'] . ' /><br/>
		<input type="submit" name="search" value="search" />
		</form>
		<br/>';
		echo '<table cellspacing="0" cellpadding="0" border="1">
		<tr><td>#</td><td><a href="products.php?sort=' . $sort . '&cid=' . $params['cid'] . '&inclsub=' . $params['inclsub'] . '" >Product Name</a></td><td>Category Name</td></tr>';
	
		if(count($products)){
			$i=1;
			foreach($products AS $product=>$v)
				echo "<tr><td>".$i++."</td><td>".$v['prodname']."</td><td>".$v['catname']."</td></tr>";
		}

		echo '</table>';
	}
	
	function setProductsParams(){
		
		$categ = new category;
	
		if(isset($_POST['search']))
		{
				if(isset($_POST['category']))
				{
					$params['cid'] = $_POST['category'];
				}
					
				if(isset($_POST['includesubcat']))
				{
					$params['checked'] = "checked";
					$params['inclsub'] = "yes";
					$params['include_subcats'] = 1;
				} 
				else 
				{
					$params['checked'] = "";
					$params['inclsub'] = "no";
					$params['include_subcats'] = 0;
				}
				
		} 
		else
		{
				$params['checked'] = "";
				$params['inclsub'] = "no";
			
				if(isset($_GET['cid']))
				{
					$params['cid'] = $_GET['cid'];
				}
				else
				{
					$params['cid'] = 0;
				}
				
					
				if(isset($_GET['inclsub']))
				{
					$params['inclsub'] = $_GET['inclsub'];
					$params['checked'] = "";
					if($params['inclsub']=="yes")
					{
						$params['checked'] = "checked";
						$params['include_subcats'] = 1;
					}
					else
					{
						$params['include_subcats'] = 0;
					}
				}	
				else
				{
					$params['include_subcats'] = 0;
				}	
			}
		
		$params['categories'] = $categ->getAlltree($params['cid']);
		
		return $params;
	}
	
	function printCategories($get_list)
	{
		$categ = new category;
		if(isset($_GET['act']) && $_GET['act']=="del")
		{
			$status = $categ->deleteCategory($_GET['cid']);
			header("location: categories.php?msg=".$status);
		}
			
		if(isset($_GET['msg']))
			echo '<span style="color:blue;">'.$_GET['msg'].'</span>';
		
		
		echo '<p><a href="edit_category.php">new category</a></p><br/>';
		echo '<table cellspacing="0" cellpadding="0" border="1">';
		echo '<tr><td>Category Name</td><td colspan="2"></td></tr>';
		echo $get_list;
		echo "</table>";
	}
	
	function printCategoryForm($get_all_categories, $editthis, $id, $msg)
	{
	
		$categ = new category;
		
		if($msg!="")	
		{
			echo "<span style='color:red;'>".$msg."</span><br/><br/>";
			if(isset($_POST['category_name']))
			{
				$editthis = $_POST['category_name'];
			}
			else
			{
				$editthis = "";
			}
		}
		
		if($id==0) 
		{
			echo "<b>NEW CATEGORY</b>";
		} 
		else 
		{
			echo "<b>EDIT CATEGORY</b>";
		}

		echo '<form action="edit_category.php" method="post">
		Parent Category <br/>
		<select size="10" style="width:148px;" name="parent_category">';

		echo $get_all_categories;

		echo '</select>
		<br/>
		Name *<br/>
		<input type="text" name="category_name" value="'.$editthis.'"/>
		<input type="hidden" name="id" value="'.$id.'" />
		<br/>
		<input type="submit" name="submit" value="submit"/>
		</form>';
	}
	
	function processCategory()
	{
	
		$categ = new category;
		
		$msg = "";
	
		if(isset($_POST['submit']))
		{
			if(isset($_POST['parent_category']))
			{	
				$parent_category = $_POST['parent_category'];
			}
			else
			{
				$parent_category = 0;
			}
				
			$msg = $categ->insertCategory($_POST['id'], $_POST['category_name'], $parent_category);
			if($msg == "new category created" OR $msg == "category updated")
				header("location: categories.php?msg=".$msg);
		}
		
		return $msg;
		
	}

}

?>
