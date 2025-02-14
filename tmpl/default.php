<?php
/**
 * @package     EasyVMFilter
 * @version     1.1.0
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
// Add accordion 
$document->addScriptDeclaration('
    jQuery(document).ready(function($) {
		var styleFramework = "'. $style_framework .'"; 
        $(".filter-group").accordion({
            collapsible: true,
            active: 0,
            heightStyle: "content"
        });

        $(".show-all-button").click(function() {
            var container = $(this).prev(".filter-options-container");
            container.toggleClass("expanded");
            $(this).text(container.hasClass("expanded") ? "'.JText::_('MOD_EASY_VM_FILTER_FILTER_HIDE').'" : "'.JText::_('MOD_EASY_VM_FILTER_FILTER_SHOW_ALL').'");
            container.children().slice(5).toggle();
        });

        window.clearAllFilters = function() {
            var form = document.querySelector(".mod_easyvmfilter form");
            if (!form) return;
            
            var elements = form.elements;
            var url = form.action;
            var params = {};

            // Очистка фильтров
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

            // Формирование URL с выбранными параметрами
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

        // Add icons to buttons (but not for Bootstrap 5)
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
        <?php foreach ($custom_fields as $field): ?>
		
            <div class="filter-group">
			
                <h6 class="filter-title">
				        <?php echo htmlspecialchars($field->custom_title); ?>
						<span class="accordion-icon"></span>
				</h6>
		
                <div class="filter-content">
 
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