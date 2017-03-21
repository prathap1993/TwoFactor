<?php
/**
 * My own options
 *
 */
namespace Commerceshop\TwoFactor\Model\Config\Source;
class phoneemail implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'phone', 'label' => __('Phone')],
            ['value' => 'email', 'label' => __('E-Mail')]
        ];
    }
}
 
?>