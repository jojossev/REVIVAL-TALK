<?php

namespace App\Services;

class XMLService
{
    /**
     * Clean XML content to handle malformed HTML/XML tags
     *
     * @param string $xmlContent
     * @return string
     */
    public static function cleanXmlContent($xmlContent)
    {
        // Remove or fix common malformed HTML/XML issues
        // Remove script and style tags with their content (they can contain invalid XML)
        $xmlContent = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/is', '', $xmlContent);
        $xmlContent = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/is', '', $xmlContent);

        // Fix common unclosed tags in descriptions/content
        // Remove noscript tags that might be malformed
        $xmlContent = preg_replace('/<noscript[^>]*>.*?<\/noscript>/is', '', $xmlContent);
        $xmlContent = preg_replace('/<noscript[^>]*>/i', '', $xmlContent);
        $xmlContent = preg_replace('/<\/noscript>/i', '', $xmlContent);

        // Remove invalid XML characters
        $xmlContent = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $xmlContent);

        // Try to fix unclosed meta tags (common issue)
        $xmlContent = preg_replace('/<meta([^>]*?)(?<!\/)>/i', '<meta$1 />', $xmlContent);

        // Fix CDATA sections that might contain malformed HTML
        // Preserve CDATA but clean its content
        $xmlContent = preg_replace_callback(
            '/<!\[CDATA\[(.*?)\]\]>/is',
            function($matches) {
                // Clean the CDATA content
                $content = $matches[1];
                // Remove script and style tags from CDATA
                $content = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/is', '', $content);
                $content = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/is', '', $content);
                // Escape XML special characters if needed
                $content = htmlspecialchars($content, ENT_XML1 | ENT_QUOTES, 'UTF-8', false);
                return $content;
            },
            $xmlContent
        );

        return $xmlContent;
    }
}
