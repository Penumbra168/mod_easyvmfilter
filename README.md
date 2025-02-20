# Easy VM custom fields Filter for VirtueMart

## Description

This Joomla module provides advanced product filtering for VirtueMart based on custom fields. 

## Features

*   **Custom Field Filtering:** Filter products based on any custom fields defined in VirtueMart.
*   **Multiple Filter Types:** Supports filter display as select lists, radio buttons, and checkboxes.
*   **AJAX Filtering (Planned):** Filter products without page reload (in future versions).
*   **Adaptive Design:** The module automatically adapts to different Joomla templates.
*   **Bootstrap 5 and Joomla Default Styles Support:** Allows selecting the styling framework in the module settings.
*   **Clear All Button:** Easily clear all selected filters.
*   **Collapsible Filter Groups:** Improved organization of filters on the page using jQuery UI Accordion.
*   **Multilingual Support:** Full support for Joomla's multilingual capabilities.

## Requirements

*   Joomla 5.x
*   VirtueMart 4.x
*   PHP 7.4 or higher

## Installation

1.  Download the ZIP archive of the module.
2.  Log in to the Joomla administrator panel.
3.  Go to "Extensions" -> "Install".
4.  Upload the ZIP archive and install the module.
5.  Go to "Extensions" -> "Modules" and find the "Easy VM Filter" module.
6.  Enable the module.
7.  Configure the module:
    *   Select the custom fields to use for filtering.
    *   Select the filter display type (select list, radio buttons, or checkboxes).
    *   Select the styling framework (Bootstrap 5 or Joomla Default).
    *   Assign the module to the VirtueMart category pages.
8.  **IMPORTANT:** Copy the `default.php` and `bs5_default.php` files from the folder `modules/mod_easyvmfilter/tmpl/` to the folder `templates/your_template/html/com_virtuemart/category/`. If the folder `templates/your_template/html/com_virtuemart/category/` does not exist, create it.
9.  Save the module settings.

## Usage

1.  Open a VirtueMart category page on your website.
2.  The filter module should be displayed in the selected position.
3.  Use the filters to find the desired products.
4.  Click the "Apply" button to apply the filters.
5.  Click the "Clear All" button to clear all selected filters.

## Configuration

In the module settings, you can:

*   **Select Custom Fields for Filtering:** Choose which custom fields will be used to filter products.
*   **Select Filter Display Type:** Choose how the filters will be displayed (select list, radio buttons, or checkboxes).
*   **Select Styling Framework:** Choose which styling framework to use (Bootstrap 5 or Joomla Default).
*   **Customize Module Appearance:** Use the module's CSS classes to customize its appearance to match your website's design.

## Planned Enhancements

*   Implement AJAX filtering for faster and smoother product filtering.
*   Add the ability to filter by price range.
*   Add the ability to sort products by various parameters.
*   Improve the module's responsiveness for mobile devices.

## Support

If you have any questions or issues, please create an issue on GitHub.

## License

This module is distributed under the GNU GPL v3 license.

## Authors

*   Penumbra168

## Acknowledgements

*   VirtueMart Team for the excellent e-commerce component.
*   Joomla Team for the powerful and flexible CMS.
