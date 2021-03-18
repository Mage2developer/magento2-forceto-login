<?php
/**
 * Mage2developer
 * Copyright (C) 2021 Mage2developer
 *
 * @category Mage2developer
 * @package Mage2_Login
 * @copyright Copyright (c) 2021 Mage2developer
 * @author Mage2developer <mage2developer@gmail.com>
 */

declare(strict_types=1);

namespace Mage2\Login\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * @package Mage2\Login\Model\Config\Source
 */
class AllowedPages implements OptionSourceInterface
{
    /**
     * Retrieve allowed pages options
     *
     * @return array[]
     */
    public function toOptionArray()
    {
        $optionArray = [
            ['value' => 'cms_index_index', 'label' => __('Home Page')],
            ['value' => 'catalog_category_view', 'label' => __('Category Page')],
            ['value' => 'catalog_product_view', 'label' => __('Product Page')],
            ['value' => 'catalogsearch_result_index', 'label' => __('Catalog Search Result')]
        ];

        return $optionArray;
    }
}
