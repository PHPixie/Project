<?php

namespace Project\Framework;

/**
 * Your projects bundle registry.
 * Every bundle you add must be registered here.
 */
class Bundles extends \PHPixie\BundleFramework\Bundles
{
    /**
     * Should return an array of Bundle instances
     * @return array
     */
    protected function buildBundles()
    {
        return array(
            new \Project\App($this->builder)
        );
    }
}