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
     * @var array|null
     */
    private $shippingMethods;

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
        if ($this->shippingMethods === null) {
            /** @var \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\Matrixrate\Collection $collection */
            $collection = $this->matrixRateCollectionFactory->create();
            $collection->addFieldToSelect('pk')
                       ->addFieldToSelect('shipping_method');

            $shippingMethods = [];
            foreach ($collection->getItems() as $item) {
                $code                   = 'matrixrate_' . $item['pk'];
                $label                  = $item['shipping_method'];
                $shippingMethods[$code] = $label;
            }

            $this->shippingMethods = $shippingMethods;
        }

        return array_merge($result, $this->shippingMethods);
    }
}
