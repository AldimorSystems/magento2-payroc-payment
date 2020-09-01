<?php

namespace AldimorSystems\Payroc\Model\Config\Source;

class PaymentAction implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => true, 'label' => __('Authorize Payment')],
            ['value' => false, 'label' => __('Pre-Authorize Payment')],
        ];
    }
}
