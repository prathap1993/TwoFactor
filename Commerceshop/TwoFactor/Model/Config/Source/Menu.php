<?php
/**
 * My own options
 *
 */
namespace Commerceshop\TwoFactor\Model\Config\Source;
class Menu implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'enable', 'label' => __('Enable')],
            ['value' => 'disable', 'label' => __('Disable')]
        ];
    }
}
 
?>