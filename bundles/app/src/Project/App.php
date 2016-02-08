<?php

namespace Project;

/**
 * Default application bundle
 */
class App extends \PHPixie\DefaultBundle
{
    /**
     * Build bundle builder
     * @param \PHPixie\BundleFramework\Builder $frameworkBuilder
     * @return App\Builder
     */
    protected function buildBuilder($frameworkBuilder)
    {
        return new App\Builder($frameworkBuilder);
    }
}