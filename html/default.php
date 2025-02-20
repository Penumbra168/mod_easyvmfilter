<?php
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 10982 2024-03-18 08:58:44Z  $
 */


defined ('_JEXEC') or die('Restricted access');
$helperPath = JPATH_ROOT . '/modules/mod_EasyVMFilter/helper.php';
// Загружаем языковой файл модуля вручную
JFactory::getLanguage()->load('mod_easyvmfilter', JPATH_SITE . '/modules/mod_easyvmfilter');
require_once $helperPath;



if (vRequest::getInt('dynamic',false) and vRequest::getInt('virtuemart_product_id',false)) {
	if (!empty($this->products)) {
if($this->fallback){
			$p = $this->products;
			$this->products = array();
			$this->products[0] = $p;
			vmdebug('Refallback');
		}

		echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));

	}

	return ;
}
?> <div class="category-view"> <?php
$js = "
jQuery(document).ready(function ($) {
	$('.orderlistcontainer').hover(
		function() { $(this).find('.orderlist').stop().show()},
		function() { $(this).find('.orderlist').stop().hide()}
	)
});
";
vmJsApi::addJScript('vm-hover',$js);

if ($this->show_store_desc and !empty($this->vendor->vendor_store_desc)) { ?>
	<div class="vendor-store-desc">
		<?php echo $this->vendor->vendor_store_desc; ?>
	</div>
<?php }

if (!empty($this->showcategory_desc) and empty($this->keyword)){
	if(!empty($this->category)) {
	?>
<div class="category_description">
	<?php echo $this->category->category_description; ?>
</div>
<?php }
	if(!empty($this->manu_descr)) {
		?>
        <div class="manufacturer-description">
			<?php echo $this->manu_descr; ?>
        </div>
	<?php }
}

// Show child categories
if ($this->showcategory and empty($this->keyword)) {
	if (!empty($this->category->has_children)) {
		echo ShopFunctionsF::renderVmSubLayout('categories',array('categories'=>$this->category->children, 'categories_per_row'=>$this->categories_per_row));
	}
}

if (!empty($this->products) or ($this->showsearch or $this->keyword !== false)) {
?>
<div class="browse-view">
<?php

if ($this->showsearch or $this->keyword !== false) {
	//id taken in the view.html.php could be modified
	$category_id  = vRequest::getInt ('virtuemart_category_id', 0); ?>

	<!--BEGIN Search Box -->
	<div class="virtuemart_search">
		<form action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=category&limitstart=0', FALSE); ?>" method="get">
			<?php if(!empty($this->searchCustomList)) { ?>
			<div class="vm-search-custom-list">
				<?php echo $this->searchCustomList ?>
			</div>
			<?php } ?>

			<?php if(!empty($this->searchCustomValuesAr)) { ?>
			<div class="vm-search-custom-values">
				<?php
                echo ShopFunctionsF::renderVmSubLayoutAsGrid(
                    'searchcustomvalues',
                    array (
                        'searchcustomvalues' => $this->searchCustomValuesAr,
                        'options' => array (
                            'items_per_row' => array (
                                'xs' => 2,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                            ),
                        ),
                    )
                );
                ?>
			</div>
                <?php if(count($this->searchCustomValuesAr)>1){
                    ?>
                    <div><?php echo vmText::_('COM_VM_COMBINETAGS');
						echo VmHtml::checkbox ('combineTags', $this->combineTags, 1, 0, '', 'combineTags'); ?></div>
                <?php }

			 } ?>
			<div class="vm-search-custom-search-input">
				<input name="keyword" class="inputbox" type="text" size="40" value="<?php echo $this->keyword ?>"/>
				<input type="submit" value="<?php echo vmText::_ ('COM_VIRTUEMART_SEARCH') ?>" class="button" onclick="this.form.keyword.focus();"/>
				<?php //echo VmHtml::checkbox ('searchAllCats', (int)$this->searchAllCats, 1, 0, 'class="changeSendForm"'); ?>
				<span class="vm-search-descr"> <?php echo vmText::_('COM_VM_SEARCH_DESC') ?></span>
			</div>

			<!-- input type="hidden" name="showsearch" value="true"/ -->
			<input type="hidden" name="view" value="category"/>
			<input type="hidden" name="option" value="com_virtuemart"/>
			<input type="hidden" name="virtuemart_category_id" value="<?php echo $category_id; ?>"/>
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>"/>
		</form>
	</div>
	<!-- End Search Box -->
<?php
	/*if($this->keyword !== false){
		?><h3><?php echo vmText::sprintf('COM_VM_SEARCH_KEYWORD_FOR', $this->keyword); ?></h3><?php
	}*/
	$j = 'jQuery(document).ready(function($) {
$(".changeSendForm")
	.off("change",Virtuemart.sendCurrForm)
    .on("change",Virtuemart.sendCurrForm);
})';
	vmJsApi::addJScript('sendFormChange',$j);
} ?>

<?php // Show child categories

if(!empty($this->orderByList)) { ?>
<div class="orderby-displaynumber">
	<div class="floatleft vm-order-list">
		<?php echo $this->orderByList['orderby']; ?>
		<?php echo $this->orderByList['manufacturer']; ?>
	</div>
	<div class="vm-pagination vm-pagination-top">
		<?php echo $this->vmPagination->getPagesLinks (); ?>
		<span class="vm-page-counter"><?php echo $this->vmPagination->getPagesCounter (); ?></span>
	</div>
	<div class="floatright display-number"><?php echo $this->vmPagination->getResultsCounter ();?><br/><?php echo $this->vmPagination->getLimitBox ($this->category->limit_list_step); ?></div>

	<div class="clear"></div>
</div> <!-- end of orderby-displaynumber -->
<?php } ?>

<?php if (!empty($this->category->category_name)) { ?>
<h1><?php echo vmText::_($this->category->category_name); ?></h1>
<?php } ?>




			<?php // ************************************************
			// Get the current URL
			$uri = JUri::getInstance();
			$currentUrl = $uri->toString();


			// Получаемс список товаров из Virtuemart
			$products = $this->products;

			// Проверяем, что $products[1] существует и является массивом
			if (isset($products['products']) && is_array($products['products'])) {
				$productArray = $products['products'];

			} else {

				$productArray = array(); 
			}


			$prd=0;
			$pro=0;
			// Получаем выбранные фильтры
			$filters = isset($_GET['filter']) ? $_GET['filter'] : array();


			// Get min and max price from request
			$minPrice = JFactory::getApplication()->input->get('min_price', 0, 'FLOAT');
			$maxPrice = JFactory::getApplication()->input->get('max_price', 0, 'FLOAT');




			// Фильтруемс товары
			if (!empty($filters)) {
				$filtered_products = array();
				
				foreach ($productArray as $product) {
					$addProduct = true;

				
					//Filter by Price
							// Получаем цену товара
					$productPrice = isset($product->prices['product_price']) ? (float)$product->prices['product_price'] : 0;
					if (($minPrice > 0 && $productPrice < $minPrice) || ($maxPrice > 0 && $productPrice > $maxPrice)) {
						$addProduct = false;
					}	
				
				
				
				
					foreach ($filters as $custom_field_id => $filter_value) {
				
						$productId = isset($product->virtuemart_product_id) ? $product->virtuemart_product_id : (isset($product->product_id) ? $product->product_id : (isset($product->virtuemartProductId) ? $product->virtuemartProductId : (isset($product->id) ? $product->id : 0)));
						$product_customfield_value = ModEasyVirtuemartFilterHelper::getProductCustomFieldValue($productId, $custom_field_id);

						if (!empty($filter_value)) {
							if (is_array($filter_value)) {
								if (!in_array($product_customfield_value, $filter_value)) {
									$addProduct = false;
									break;
								}
							} else {
								if ($product_customfield_value != $filter_value) {
									$addProduct = false;
									break;
								}
							}
						}
					}
					if ($addProduct) {
						$filtered_products[] = $product;
					}
				}
				$productArray = $filtered_products;
				unset($products['products']);
				$products['products'] = $productArray;
				$this->products = $products;

			}

// Display the applied filters
if (!empty($filters)|| ($minPrice > 0 || $maxPrice > 0)): ?>
    <div class="applied-filters">
	
	
		 <?php  if ($minPrice > 0 || $maxPrice > 0):  ?>
			<div class="applied-filter"><strong><?php echo  JText::_('MOD_EASY_VM_FILTER_FILTER_SELECT'); ?></strong><?php
			echo  JText::_('MOD_EASY_VM_FILTER_PRICE_FROM') . ' ' . $minPrice . '  ' . JText::_('MOD_EASY_VM_FILTER_PRICE_TO') . '  ' . $maxPrice; ?></div>
		<?php endif; ?>	
		
	
        <?php foreach ($filters as $custom_field_id => $filter_value): ?>
            <?php
            // Get the custom field title
            $custom_field = ModEasyVirtuemartFilterHelper::getCustomFields(array($custom_field_id));
            if (!empty($custom_field)) {
                $custom_field_title = htmlspecialchars($custom_field[0]->custom_title);
            } else {
                $custom_field_title = 'Unknown Filter';
            }
            
            if (is_array($filter_value)){
              foreach ($filter_value as $single_filter_value):
                // Create the remove filter URL
                $removeFilterUrl =  ModEasyVirtuemartFilterHelper::removeURLParameter($uri,'filter[' . $custom_field_id . '][]=' . $single_filter_value);

                ?>
				<button class="btn btn-outline-secondary" type="button" onclick="window.location.href='<?php echo $removeFilterUrl; ?>'" class="applied-filter">
					<?php echo htmlspecialchars($single_filter_value); ?>
					<span class="remove-filter"><i class="fas fa-times-circle"></i></span>
				</button>
              <?php endforeach;
            } else {
                // Create the remove filter URL
                $removeFilterUrl =  ModEasyVirtuemartFilterHelper::removeURLParameter($uri,'filter[' . $custom_field_id . ']=' . $filter_value);
                ?>
				<button type="button" onclick="window.location.href='<?php echo $removeFilterUrl; ?>'" class="applied-filter">
					<?php echo htmlspecialchars($filter_value); ?>
					<span class="remove-filter"><i class="fas fa-times-circle"></i></span>
				</button>
            <?php } ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


	<?php
	if (!empty($this->products)) {
		//revert of the fallback in the view.html.php, will be removed vm3.2
		if($this->fallback){
			$p = $this->products;
			$this->products = array();
			$this->products[0] = $p;
			vmdebug('Refallback');
		}
	echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));
	if(!empty($this->orderByList)) { ?>
		<div class="vm-pagination vm-pagination-bottom"><?php echo $this->vmPagination->getPagesLinks (); ?><span class="vm-page-counter"><?php echo $this->vmPagination->getPagesCounter (); ?></span></div>
	<?php }
} elseif ($this->keyword !== false) {
	echo vmText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
}
?>
</div>

<?php }
	echo vmJsApi::writeJS();
?>
</div>

<!-- end browse-view -->

