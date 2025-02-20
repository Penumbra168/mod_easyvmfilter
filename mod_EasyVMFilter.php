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

require_once __DIR__ . '/helper.php';

JFactory::getLanguage()->load('mod_easyvmfilter', JPATH_SITE, null, true);
$custom_fields = $params->get('custom_fields', []);
$display_type = $params->get('display_type', 'select'); 
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

$app = JFactory::getApplication();
$template = $app->getTemplate();


$enable_price_filter = $params->get('enable_price_filter', 0);
$price_filter_label = $params->get('price_filter_label', 'Цена');

$menu = $app->getMenu();
$active = $menu->getActive();
$categoryId = 0;
if ($active && isset($active->query['virtuemart_category_id'])) {
    $categoryId = (int)$active->query['virtuemart_category_id'];
}

$minMaxPrice = ModEasyVirtuemartFilterHelper::getMinMaxPrice($categoryId);
$minPrice = floor($minMaxPrice->min_price);
$maxPrice = ceil($minMaxPrice->max_price);




$layout = $params->get('layout', '');

if (empty($layout)) {
    if ($template == 'cassiopeia') {
        $layout = 'default'; 
    } elseif (strpos($template, 'bootstrap') !== false || strpos($template, 'bs5') !== false) {
        $layout = 'bs5_default'; 
    } else {
        $layout = 'default'; 
    }
}

$document = JFactory::getDocument();
$document->addStyleSheet(JUri::base() . 'modules/mod_EasyVMFilter/css/style.css');

require JModuleHelper::getLayoutPath('mod_EasyVMFilter', $layout);