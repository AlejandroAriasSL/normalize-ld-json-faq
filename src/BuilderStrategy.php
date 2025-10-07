<?php
namespace NormalizeLdJsonFAQ;

use NormalizeLdJsonFAQ\Adapters\ElementorAdapter;

class BuilderStrategy {

    public static function getActiveAdapter(): ?Adapter
    {
        switch(true)
        {
            case defined('ELEMENTOR_VERSION'): return new ElementorAdapter();
            default: return null;
        }
    }
}