<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Rector\Container;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;

class ContainerArrayAccessToSetterRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param \PhpParser\Node\Expr\Assign $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->var instanceof ArrayDimFetch && $node->var->var->name === 'container') {
            // Update self to static
            if ($node->var->dim->class->parts[0] === 'self') {
                $node->var->dim->class->parts[0] = 'static';
            }

            $arguments = [
                $node->var->dim,
                $node->expr,
            ];
            $methodCallNode = new MethodCall($node->var->var, new Identifier('set'), $arguments);

            return $methodCallNode;
        }

        return null;
    }

    /**
     * @return \Rector\Core\RectorDefinition\RectorDefinition
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Replaces ContainerInterface array access with setter method.',
            [
                new CodeSample(
                    '$container[FooBar::CONST] = function () {
                        return new ZipZap();
                    };',
                    '$container->set(FooBar::CONST, function () {
                        return new ZipZap();
                    });'
                ),
            ]
        );
    }
}
