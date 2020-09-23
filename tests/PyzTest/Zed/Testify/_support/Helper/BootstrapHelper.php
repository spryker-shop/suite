<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Testify\Helper;

use Codeception\Lib\Framework;
use Codeception\TestInterface;
use Pyz\Zed\Application\Communication\ZedBootstrap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

class BootstrapHelper extends Framework
{
    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $requestFactory = function (array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) {
            $request = new Request($query, $request, $attributes, $cookies, $files, $server, $content);
            $request->server->set('SERVER_NAME', 'localhost');

            return $request;
        };

        Request::setFactory($requestFactory);

        $application = new ZedBootstrap();
        $this->client = new HttpKernelBrowser($application->boot());
    }
}
