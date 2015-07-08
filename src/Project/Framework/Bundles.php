<?php

namespace Project\Framework;

class Bundles extends \PHPixie\BundleFramework\Bundles
{
    protected function buildBundles()
    {
        return array(
            new \Project\App($this->builder)
        );
    }
    
    protected function getRootFolder()
    {
        return realpath(__DIR__.'/../../');
    }
}