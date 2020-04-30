<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\MatrixRateCompatibility\Plugin;

class AddAllowedMethodsToMatrixRateCarrier
{
    /**
     * @var \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\Matrixrate\CollectionFactory
     */
    private $matrixRateCollectionFactory;

    /**
     * @var array
     */
    private $shippingMethods = [];

    /**
     * AddAllowedMethodsToMatrixRateCarrier constructor.
     *
     * @param \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\Matrixrate\CollectionFactory $matrixRateCollectionFactory
     */
    public function __construct(
        \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\Matrixrate\CollectionFactory $matrixRateCollectionFactory
    ) {
        $this->matrixRateCollectionFactory = $matrixRateCollectionFactory;
    }

    /**
     * @param \WebShopApps\MatrixRate\Model\Carrier\Matrixrate $subject
     * @param array $result
     * @return array
     */
    public function afterGetAllowedMethods(
        \WebShopApps\MatrixRate\Model\Carrier\Matrixrate $subject,
        array $result = []
    ): array {

        if (empty($this->shippingMethods)) {
            /** @var \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\Matrixrate\Collection $collection */
            $collection = $this->matrixRateCollectionFactory->create();
            $collection->addFieldToSelect('pk')
                       ->addFieldToSelect('shipping_method');

            $items           = $collection->getItems();
            $shippingMethods = [];
            foreach ($items as $item) {
                $code                   = 'matrixrate_' . $item['pk'];
                $label                  = $item['shipping_method'];
                $shippingMethods[$code] = $label;
            }

            $this->shippingMethods = $shippingMethods;
        }

        $result = array_merge($result, $this->shippingMethods);

        return $result;
    }
}
