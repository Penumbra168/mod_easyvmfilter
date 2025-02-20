<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
/**
 * @package     EasyVMFilter
 * @version     1.2.1
 * @date        2025-01-28
 * @author      Penumbra168 
 * @license     GNU General Public License v3; see LICENSE.txt
 *
 * This file is part of the Easy VM Filter for VirtueMart.
 *
 * Easy VM Filter for VirtueMart is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Easy VM Filter for VirtueMart is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Easy VM Filter for VirtueMart. If not, see <http://www.gnu.org/licenses/>.
 */

defined('_JEXEC') or die;




$custom_fields = ModEasyVirtuemartFilterHelper::getCustomFields($custom_fields);
$display_type = $params->get('display_type', 'select');
$style_framework = $params->get('style_framework', 'bootstrap5');

$document->addScriptDeclaration('
	document.addEventListener("DOMContentLoaded", function() {
	  var acc = document.querySelectorAll(".filter-group .filter-title");

	  acc.forEach(function(title) {
		title.addEventListener("click", function() {
		  this.classList.toggle("active");
		  var content = this.nextElementSibling;
		  const isOpen = content.classList.contains("open");

		  content.classList.toggle("open");

		  if (content.style.maxHeight && content.style.maxHeight !== "0px") {
			content.style.maxHeight = "0px";
		  } else {
			 content.style.maxHeight = null; 
			 setTimeout(() => {
			  content.style.maxHeight = content.scrollHeight + "px";
			}, 0); 
		  }
		});
	  });

	  var initialOpen = document.querySelectorAll(".filter-content.open");
	  for (var i = 0; i < initialOpen.length; i++) {
		initialOpen[i].style.maxHeight = initialOpen[i].scrollHeight + "px";
		initialOpen[i].previousElementSibling.classList.add("active");
	  }
	 });	 

	 document.addEventListener("DOMContentLoaded", function () {
	  var showAllButtons = document.querySelectorAll(".show-all-button");

	  showAllButtons.forEach(function (button) {
		var filterContent = button.closest(".filter-content");
		if (!filterContent) return;

		var accordion = filterContent.closest(".accordion"); 
		var checkboxes = filterContent.querySelectorAll(`input[type="checkbox"]`);
		var hiddenCheckboxes = Array.from(checkboxes).slice(5);

		hiddenCheckboxes.forEach(checkbox => {
		  var label = checkbox.closest("label") || checkbox.parentNode;
		  if (label) label.style.display = "block";
		});

		var fullHeight = filterContent.scrollHeight;

		hiddenCheckboxes.forEach(checkbox => {
		  var label = checkbox.closest("label") || checkbox.parentNode;
		  if (label) label.style.display = "none";
		});

		var collapsedHeight = filterContent.scrollHeight; 
		filterContent.style.maxHeight = collapsedHeight + "px";
		filterContent.style.overflow = "hidden";
		filterContent.style.transition = "max-height 0.3s ease-out";

		function updateAccordionHeight() {
		  if (accordion) {
			requestAnimationFrame(() => {
			  accordion.style.height = "auto"; 
			});
		  }
		}

		button.addEventListener("click", function (e) {
		  e.preventDefault();
		  var isShowingAll = button.dataset.showingAll === "true";

		  if (isShowingAll) {

			hiddenCheckboxes.forEach(checkbox => {
			  var label = checkbox.closest("label") || checkbox.parentNode;
			  if (label) label.style.display = "none";
			});

			button.textContent = "'.JText::_('MOD_EASY_VM_FILTER_FILTER_SHOW_ALL').'";
			button.dataset.showingAll = "false";
			filterContent.style.maxHeight = collapsedHeight + "px";

		  } else {
		   
			hiddenCheckboxes.forEach(checkbox => {
			  var label = checkbox.closest("label") || checkbox.parentNode;
			  if (label) label.style.display = "block";
			});

			button.textContent = "'.JText::_('MOD_EASY_VM_FILTER_FILTER_HIDE').'";
			button.dataset.showingAll = "true";
			filterContent.style.maxHeight = fullHeight + "px";
		  }

		  updateAccordionHeight(); 
		});

		button.dataset.showingAll = "false";
	  });
	});


    jQuery(document).ready(function($) {
		var styleFramework = "'. $style_framework .'"; 


        window.clearAllFilters = function() {
            var form = document.querySelector(".mod_easyvmfilter form");
            if (!form) return;
            
            var elements = form.elements;
            var url = form.action;
            var params = {};

            for (var i = 0; i < elements.length; i++) {
                var el = elements[i];
                if (el.name.startsWith("filter[")) {
                    if (el.type === "checkbox" || el.type === "radio") {
                        el.checked = false;
                    } else if (el.tagName === "SELECT") {
                        el.selectedIndex = 0;
                    }
                }
            }

            for (var i = 0; i < elements.length; i++) {
                var el = elements[i];
                if (el.name.startsWith("filter[") && el.value) {
                    if (el.type === "checkbox" && el.checked) {
                        if (!params[el.name]) params[el.name] = [];
                        params[el.name].push(el.value);
                    } else if (el.type === "radio" && el.checked) {
                        params[el.name] = el.value;
                    } else if (el.tagName === "SELECT") {
                        params[el.name] = el.value;
                    }
                }
            }

            // Построение URL
            var paramStrings = Object.keys(params).map(function(name) {
                return encodeURIComponent(name) + "=" + encodeURIComponent(params[name]);
            });

            if (paramStrings.length > 0) {
                url += "?" + paramStrings.join("&");
            }

            window.location.href = url;
        };

		console.log("jQuery UI Loaded:", $.ui); // Проверяем, загружен ли jQuery UI

		var minPrice = '.$minPrice.';
		var maxPrice = '.$maxPrice.';
		var minLimit = Math.ceil(minPrice * 0.80);   
		var maxLimit = Math.ceil(maxPrice * 1.10); 

		console.log("Min Price:", minPrice, "Max Price:", maxPrice);

		$("#price-slider").slider({
			range: true,
			min: minLimit,
			max: maxLimit,
			values: [minPrice, maxPrice],
			slide: function(event, ui) {
				console.log("New Values:", ui.values[0], ui.values[1]);
				$("#min_price").val(ui.values[0]);
				$("#max_price").val(ui.values[1]);
			}
		});

		$("#min_price, #max_price").on("change", function() {
			var minVal = parseInt($("#min_price").val(), 10) || minLimit;
			var maxVal = parseInt($("#max_price").val(), 10) || maxLimit;

			if (minVal < minLimit) minVal = minLimit;
			if (maxVal > maxLimit) maxVal = maxLimit;
			if (minVal > maxVal) minVal = maxVal;

			console.log("Updated Input Values:", minVal, maxVal);
			$("#price-slider").slider("values", [minVal, maxVal]);
		});


		console.log("styleFramework: " + styleFramework);
        if (styleFramework !== "bootstrap5") {
            $(".mod_easyvmfilter form button[type=\'submit\'], .mod_easyvmfilter form button[onclick=\'clearAllFilters()\']").each(function() {
                var iconClass = "";
                if ($(this).text().indexOf("'.JText::_('JSELECT').'") >= 0) {
                    iconClass = "fa fa-check-square-o";
                } else if ($(this).text().indexOf("'.JText::_('JSEARCH_FILTER_CLEAR').'") >= 0) {
                    iconClass = "fas fa-times";
                }
                if (iconClass) {
                    $(this).prepend(\'<i class="\' + iconClass + \'" aria-hidden="true"></i> \');
                }
            });
        }		
    });
');

?>






<div class="mod_easyvmfilter<?php echo $moduleclass_sfx; ?>">
    <form action="<?php echo JUri::current(); ?>" method="get">
	
	
	
	
		<?php if ($enable_price_filter): ?>
            <div class="filter-group">
                <h6 class="filter-title"><?php echo htmlspecialchars($price_filter_label); ?>
				 <span class="accordion-icon"></span>
				</h6>
                <div class="filter-content open">
				<div class="easy-vm-ui-slider-horizontal">
				<div id="price-slider"  class="easy-vm-ui-slider-horizontal"></div></div>
                    <div class="range-inputs">
						
							<input type="number" name="min_price" placeholder="Min"  id="min_price" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : $minPrice; ?>">
							<input type="number" name="max_price" placeholder="Max"  id="max_price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : $maxPrice; ?>">
						
						<div class="range-values">
							<?php echo JText::_('MOD_EASY_VM_FILTER_PRICE_FROM'); ?> <span id="min-price-label"><?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : $minPrice; ?></span><br>
							<?php echo JText::_('MOD_EASY_VM_FILTER_PRICE_TO'); ?> <span id="max-price-label"><?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : $maxPrice; ?></span>
						</div>
                    </div>
                </div>
            </div>
        <?php endif; ?>		
	
	
	
	
	
        <?php foreach ($custom_fields as $field): ?>
		
            <div class="filter-group">
			

			
                <h6 class="filter-title">
				        <?php echo htmlspecialchars($field->custom_title); ?>
						<span class="accordion-icon"></span>
				</h6>
		
                <div class="filter-content open">
 
					 <?php if ($display_type == 'select'): ?>
					    <?php echo ($style_framework == 'bootstrap5') ? '<div class="form-floating">' : ''; ?>
						<select class="<?php echo ($style_framework == 'bootstrap5') ? 'form-select' : 'easy-vm-form-select-sm'; ?>" name="filter[<?php echo $field->virtuemart_custom_id; ?>]" id="filter_<?php echo $field->virtuemart_custom_id; ?>">
							<option value=""><?php echo JText::_('JALL'); ?></option>
							<?php
							  $filter_values = ModEasyVirtuemartFilterHelper::getFilterValues($field->virtuemart_custom_id);
							  $selectedValue = isset($_GET['filter'][$field->virtuemart_custom_id]) ? $_GET['filter'][$field->virtuemart_custom_id] : '';
							  foreach ($filter_values as $value): ?>
								<option value="<?php echo htmlspecialchars($value); ?>" <?php if ($selectedValue == $value) echo 'selected="selected"'; ?>><?php echo htmlspecialchars($value); ?></option>
							  <?php endforeach; ?>
						</select>
						<?php echo ($style_framework == 'bootstrap5') ? '<label for="floatingSelect">' . JText::_('MOD_EASY_VM_FILTER_SELECT') . '</label></div>' : ''; ?>
					 <?php elseif ($display_type == 'radio'): ?>
                            <?php
                              $filter_values = ModEasyVirtuemartFilterHelper::getFilterValues($field->virtuemart_custom_id);
                              $selectedValue = isset($_GET['filter'][$field->virtuemart_custom_id]) ? $_GET['filter'][$field->virtuemart_custom_id] : '';
                              foreach ($filter_values as $value): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="filter[<?php echo $field->virtuemart_custom_id; ?>]" id="radio_<?php echo $field->virtuemart_custom_id; ?>_<?php echo htmlspecialchars($value); ?>" value="<?php echo htmlspecialchars($value); ?>" <?php if ($selectedValue == $value) echo 'checked="checked"'; ?>>
                                    <label class="form-check-label" for="radio_<?php echo $field->virtuemart_custom_id; ?>_<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></label>
                                </div>
                              <?php endforeach; ?>
					 <?php elseif ($display_type == 'checkbox'): ?>
                            <?php
                              $filter_values = ModEasyVirtuemartFilterHelper::getFilterValues($field->virtuemart_custom_id);
                              $selectedValue = isset($_GET['filter'][$field->virtuemart_custom_id]) ? $_GET['filter'][$field->virtuemart_custom_id] : array();
                              ?>
                                <div class="filter-options-container">
                                    <?php
                                    $i = 0;
                                    foreach ($filter_values as $value):
                                        $i++;
                                        $style = ($i > 5) ? 'style="display:none;"' : '';
                                        ?>
                                        <div class="<?php echo ($style_framework == 'bootstrap5') ? 'form-check' : 'easy-vm-form-check'; ?>" <?php echo $style; ?>>
                                            <input class="<?php echo ($style_framework == 'bootstrap5') ? 'form-check-input' : 'easy-vm-form-check-input'; ?>" type="checkbox" name="filter[<?php echo $field->virtuemart_custom_id; ?>][]" id="checkbox_<?php echo $field->virtuemart_custom_id; ?>_<?php echo htmlspecialchars($value); ?>" value="<?php echo htmlspecialchars($value); ?>"
											<?php 
											$selected_values = isset($_GET['filter'][$field->virtuemart_custom_id]) 
												? $_GET['filter'][$field->virtuemart_custom_id] 
												: [];

											   if (!is_array($selected_values)) {
												   $selected_values = explode(',', $selected_values);
											   }

											   if (in_array($value, $selected_values)) {
												   echo 'checked="checked"';
											   }
											   ?>>
																			
											
                                            <label class="<?php echo ($style_framework == 'bootstrap5') ? 'form-check-label' : 'easy-vm-form-check-label'; ?>" for="checkbox_<?php echo $field->virtuemart_custom_id; ?>_<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
							<?php if (count($filter_values) > 5): ?>
								<button type="button" class="show-all-button btn btn-sm btn-link"><?php echo JText::_('MOD_EASY_VM_FILTER_FILTER_SHOW_ALL'); ?></button>
							<?php endif; ?>
					<?php endif; ?>

 
                </div>
			</div>
			
            <?php endforeach; ?>
            <button class="<?php echo ($style_framework == 'bootstrap5') ? 'btn btn-secondary w-100 mt-3' : 'easy-vm-btn'; ?>" type="submit">
			

			<?php echo JText::_('JSELECT'); ?>
			</button>
			
				<?php
				$showClearButton = false;
				foreach ($custom_fields as $field) {
					if (isset($_GET['filter'][$field->virtuemart_custom_id]) && !empty($_GET['filter'][$field->virtuemart_custom_id])) {
						$showClearButton = true;
						break;
					}
				}
				?>
			
			<?php if ($showClearButton): ?>
				<button class="<?php echo ($style_framework == 'bootstrap5') ? 'btn btn-outline-secondary w-100 mt-3' : 'btn easy-vm-btn'; ?>" type="button" onclick="clearAllFilters()">
					 <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
				</button>
			<?php endif; ?>
        </form>
</div>