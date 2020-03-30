<?php

use Spryker\Shared\Kernel\CodeBucket\Context\AbstractCodeBucketContext;
use Spryker\Shared\Kernel\CodeBucket\Context\CodeBucketContextInterface;

class SprykerCodeBucketContext extends AbstractCodeBucketContext implements CodeBucketContextInterface
{
    /**
     * @return string[]
     */
    public function getCodeBuckets(): array
    {
        return [
            'DE',
            'AT',
            'US',
            '',
        ];
    }

    /**
     * @deprecated This method implementation will be removed when environment configs are cleaned up.
     *
     * @return string
     */
    public function getDefaultCodeBucket(): string
    {
        return APPLICATION_STORE;
    }
}
