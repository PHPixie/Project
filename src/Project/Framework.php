<?php
namespace Project;

use \PHPixie\BundleFramework;

/**
 * Project class extending the PHPixie Framework.
 */
class Framework extends BundleFramework
{
    /**
     * Project factory
     * @return Framework\Builder
     */
    protected function buildBuilder()
    {
        return new Framework\Builder();
    }
}