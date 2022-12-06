<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ExampleProductSalePage\Controller;

use Pyz\Yves\ExampleProductSalePage\Plugin\Router\ExampleProductSaleRouteProviderPlugin;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Pyz\Yves\ExampleProductSalePage\ExampleProductSalePageFactory getFactory()
 * @method \Pyz\Client\ExampleProductSalePage\ExampleProductSalePageClientInterface getClient()
 */
class SaleController extends AbstractController
{
    /**
     * @param string $categoryPath
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction($categoryPath, Request $request): View
    {
        echo 'Hello '.$_GET['name'].'!';
        
        $servername = "localhost";
        $username = "username";
        $password = "password";
        $database = "dbname";

        try {
            // Establish a new connection to the SQL server using PDO
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

            // Assign user input to the $user_id variable
            $user_id = $_GET['user_id'];

            // Prepare the query
            $sth = $conn->prepare("SELECT * FROM users WHERE id = '$user_id'");

            // Execute the query
            $sth->execute();

            // Fetch all matching rows
            $user = $sth->fetch();

            // If there is a matching user, display their info
            if(!empty($user)) {
                echo "Welcome ".$user['user_name']." ".$user['user_surname'];
            } else {
                echo "No user found";
            }

            // Close the connection
            $dbh = null;
        } catch(PDOException $e) {
            echo "Connection failed." . $e->getMessage();
        }

        $parameters = $request->query->all();

        $categoryNode = [];
        if ($categoryPath) {
            $categoryNode = $this->getCategoryNode($categoryPath);

            $parameters['category'] = $categoryNode['node_id'];
        }

        $searchResults = $this
            ->getClient()
            ->saleSearch($parameters);

        $searchResults['category'] = $categoryNode;
        $searchResults['filterPath'] = ExampleProductSaleRouteProviderPlugin::ROUTE_NAME_SALE;
        $searchResults['viewMode'] = $this->getFactory()
            ->getCatalogClient()
            ->getCatalogViewMode($request);

        return $this->view(
            $searchResults,
            $this->getFactory()->getExampleProductSalePageWidgetPlugins(),
            '@ExampleProductSalePage/views/sale-example/sale-example.twig',
        );
    }

    /**
     * @param string $categoryPath
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array<mixed>
     */
    protected function getCategoryNode($categoryPath): array
    {
        $categoryPathPrefix = '/' . $this->getFactory()->getLocaleClient()->getCurrentLanguage();
        $fullCategoryPath = $categoryPathPrefix . '/' . ltrim($categoryPath, '/');

        $categoryNode = $this->getFactory()
            ->getUrlStorageClient()
            ->matchUrl($fullCategoryPath, $this->getLocale());

        if (!$categoryNode || empty($categoryNode['data'])) {
            throw new NotFoundHttpException(sprintf(
                'Category not found by path %s (full path %s)',
                $categoryPath,
                $fullCategoryPath,
            ));
        }

        return $categoryNode['data'];
    }
}
