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

class ModEasyVirtuemartFilterHelper
{
    public static function getCustomFields($custom_fields_ids)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->quoteName(['virtuemart_custom_id', 'custom_title', 'field_type'])) // Добавляем 'field_type'
            ->from($db->quoteName('#__virtuemart_customs'))
            ->where($db->quoteName('virtuemart_custom_id') . ' IN (' . implode(',', array_map('intval', $custom_fields_ids)) . ')');

        $db->setQuery($query);

        try {
            $results = $db->loadObjectList();
        } catch (RuntimeException $e) {
            JLog::add($e->getMessage(), JLog::WARNING, 'jerror');
            return [];
        }

        return $results;
    }

    public static function getFilterValues($custom_field_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        
        $query->select($db->quoteName('custom_value'))
            ->from($db->quoteName('#__virtuemart_customs'))
            ->where($db->quoteName('virtuemart_custom_id') . ' = ' . $db->quote($custom_field_id));

        $db->setQuery($query);

        try {
            $custom_value_string = $db->loadResult(); 
        } catch (RuntimeException $e) {
            JLog::add($e->getMessage(), JLog::WARNING, 'jerror');
            return [];
        }

        $values = explode(';', $custom_value_string);
        $values = array_map('trim', $values);

        return $values;
    }

    public static function getProductCustomFieldValue($product_id, $custom_field_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->quoteName('customfield_value'))
            ->from($db->quoteName('#__virtuemart_product_customfields'))
            ->where($db->quoteName('virtuemart_product_id') . ' = ' . $db->quote($product_id))
            ->where($db->quoteName('virtuemart_custom_id') . ' = ' . $db->quote($custom_field_id));

        $db->setQuery($query);

        try {
            $result = $db->loadResult();
        } catch (RuntimeException $e) {
            JLog::add($e->getMessage(), JLog::WARNING, 'jerror');
            return '';
        }

        return $result;
    }
}