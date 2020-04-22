<?php

namespace SprykerConfig;

use Spryker\Shared\Kernel\CodeBucket\Config\AbstractCodeBucketConfig;

class CodeBucketConfig extends AbstractCodeBucketConfig
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
