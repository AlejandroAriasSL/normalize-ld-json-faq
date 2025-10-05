<?php
namespace NormalizeLdJsonFAQ\Utils;

class HTMLExtractor {
    public static function extractLastParagraph(string $html) : string
    {
        if (preg_match_all('#<p>(.*?)</p>#is', $html, $matches)) {
            $lastParagraph = end($matches[1]);
            return trim($lastParagraph);
        } 
            
        return trim(strip_tags($html));
    }
}
?>