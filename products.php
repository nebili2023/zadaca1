<?php 
function productsList()
{
	$page = new page;
	$prod = new product;
	
	if(isset($_GET['sort']))
	{
		if($_GET['sort']=="ASC") 
		{
			$sort = "DESC";
		} 
		else 
		{
			$sort = "ASC";
		}
	}
	else
	{		
		$sort = "";
	}
	
	$params = array();
	
	$params = $page->setProductsParams();
	
	$products = $prod->getList($params, $sort);
	
	$page->displayProducts($products, $params, $sort);
}

include ("include/page.class.php");
productsList();
include("include/bottom.php"); 
?>