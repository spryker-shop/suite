<?php

namespace Pyz\Yves\ExampleModule\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;

class IndexController extends AbstractController
{
    public function __construct(
        protected \Pyz\Yves\ExampleModule\Model\TestModel $testModel
    ) {
    }

    public function indexAction(): View
    {
        $data = $this->testModel->returnTestWord();

        return $this->view(
            ['data' => $data],
            [],
            '@ExampleModule/views/index/index.twig',
        );
    }
}
