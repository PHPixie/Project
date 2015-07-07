<?php

class Project extends \PHPixie\BundleFramework
{
    protected function buildBuilder()
    {
        return new Project\Builder();
    }
}