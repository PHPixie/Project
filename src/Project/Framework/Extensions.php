<?php

namespace Project\Framework;

/**
 * Extensions registry.
  */
class Extensions extends \PHPixie\Framework\Extensions
{
    /**
     * Extensions for the Template component
     * @return array
     */
    public function templateExtensions()
    {
        return array_merge(parent::templateExtensions(), array(
           //add your Template Extensions here
        ));
    }

    /**
     * Format compilers for the Template component
     * @return array
     */
    public function templateFormats()
    {
        return array_merge(parent::templateFormats(), array(
            //add your Template Formats here
        ));
    }

    /**
     * Provider Builders for the Auth component
     * @return array
     */
    public function authProviderBuilders()
    {
        return array_merge(parent::authProviderBuilders(), array(
            //add your Auth Provider Builders here
        ));
    }
}