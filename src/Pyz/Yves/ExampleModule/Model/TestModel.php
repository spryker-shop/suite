<?php

namespace Pyz\Yves\ExampleModule\Model;

class TestModel
{
    public function __construct(
        protected \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
    ) {
    }

    /**
     * @return array<string>
     */
    function returnTestWord(): array
    {
        $productAbstractStorageData = $this->productStorageClient
            ->findProductAbstractStorageData(124, 'en_US');

        $data = [
            'Hello World!',
            'This is a Symfony DI test page.',
            'Here you can see result of call to \Spryker\Client\ProductStorage\ProductStorageClient::findProductAbstractStorageData() method',
            'using Symfony container directly in TestModel',
            'This is a product with ID: 124' => [
                'sku' => $productAbstractStorageData['sku'],
                'name' => $productAbstractStorageData['name'],
            ],
        ];

        return $data;
    }
}
