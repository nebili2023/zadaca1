<?php 
function categoryList()
{
	
	$page = new page;
	$categ = new category;
	
	$get_list = $categ->getAllList();
	
	$page->printCategories($get_list);
	
}

include ("include/page.class.php");
CategoryList();
include("include/bottom.php"); 
?>