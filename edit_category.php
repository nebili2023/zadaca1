<?php 
function categoryEdit()
{
	$page = new page;
	$categ = new category;
	
	if(isset($_GET['cid']) AND $_GET['cid'] !=0 )
	{
		$id = $_GET['cid']; 		
	}
	else
	{
		$id = 0;
	}
	
	$editthis = $categ->get($id);
	
	$msg = $page->processCategory();
	
	$get_all_categories = $categ->getAlltree($editthis['par_id']);
	
	$page->printCategoryForm($get_all_categories, $editthis['name'], $id, $msg);
	
}

include ("include/page.class.php");
categoryEdit(); 
include("include/bottom.php");
?>
