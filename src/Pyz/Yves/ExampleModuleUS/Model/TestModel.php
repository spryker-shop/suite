<?php

namespace Pyz\Yves\ExampleModuleUS\Model;

class TestModel extends \Pyz\Yves\ExampleModule\Model\TestModel
{
    function returnTestWord(): array
    {
        $data = [
            'Hello World!',
            'This is a Symfony DI test page.',
            'This page shows capabilities of using Codebuckets in Spryker',
            'As you are using US Codebucket now you can see only this data with no addition product data.'
        ];

        return $data;
    }
}
