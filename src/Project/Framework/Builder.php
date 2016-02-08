<?php

namespace Project\Framework;

/**
 * Your projects main factory, usually referenced as $frameworkBuilder.
 *
 * You can use it to override and customize the framework.
 */
class Builder extends \PHPixie\BundleFramework\Builder
{
    /**
     * Your Bundles registry
     * @return Bundles
     */
    protected function buildBundles()
    {
        return new Bundles($this);
    }

    /**
     * Your extension registry registry
     * @return Bundles
     */
    protected function buildExtensions()
    {
        return new Extensions($this);
    }

    /**
     * Projects root directory
     * @return Bundles
     */
    protected function getRootDirectory()
    {
        return realpath(__DIR__.'/../../../');
    }
}