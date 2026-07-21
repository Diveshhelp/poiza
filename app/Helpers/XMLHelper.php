<?php

namespace App\Helpers;

use Exception;
use SimpleXMLElement;

class XMLHelper
{
    public static function getSpecifiedTag($content, $column = "product_title")
    {
        $extracted_content = NULL;

        switch ($column) {
            case "product_title":
            case "productTitle":
            case "title":
            case "n":
            case "name":
                // Try all possible title tag formats
                $patterns = [
                    '/<productTitle>(.*?)<\/productTitle>/s',
                    '/<product_title>(.*?)<\/product_title>/s',
                    '/<title>(.*?)<\/title>/s',
                    '/<n>(.*?)<\/n>/s',
                    '/<name>(.*?)<\/name>/s'
                ];

                foreach ($patterns as $pattern) {
                    preg_match($pattern, $content, $matches);
                    if (isset($matches[1])) {
                        $extracted_content = trim($matches[1]);
                        break;
                    }
                }
                break;
            case "dutch_description":
                preg_match('/<dutch>(.*?)<\/dutch>/s', $content, $matches);
                break;
            case "content":
                preg_match('/<content>(.*?)<\/content>/s', $content, $matches);
                break;
            case "product_description":
                preg_match('/<description>(.*?)<\/description>/s', $content, $matches);
                break;
            case "thumbnail_image_link":
                preg_match('/<thumbnail>(.*?)<\/thumbnail>/', $content, $matches);
                break;
            case "url":
                preg_match('/<url>(.*?)<\/url>/', $content, $matches);
                break;
            case "topic":
                preg_match('/<topic>(.*?)<\/topic>/s', $content, $matches);
                break;
            case "img-urls":
                preg_match('/<image_list>(.*?)<\/image_list>/s', $content, $matches);
                break;
            case "productType":
                preg_match('/<productType>(.*?)<\/productType>/s', $content, $matches);
                break;
            default:
                // Handle invalid column name
                break;
        }
        if (isset($matches[1])) {
            $extracted_content = $matches[1];

        }

        return $extracted_content;
    }
    public static function getAllTopicsTag($content)
    {
        $extracted_content = [];

        preg_match_all('/<topic>(.*?)<\/topic>/s', $content, $matches);

        if (isset($matches[1])) {
            $extracted_content = $matches[1];
        }

        return $extracted_content;
    }

    public static function getSpecificationTag($content)
    {
        $specifications = NULL;
        $dimensions=NULL;
        // Extract the specifications block, including the opening and closing tags
        preg_match('/<specifications>(.*?)<\/specifications>/s', $content, $matches);
        if (isset($matches[0])) {
            $specifications = $matches[0];

        }

        preg_match('/<dimensions>(.*?)<\/dimensions>/s', $content, $matches);
        if (isset($matches[0])) {
            $dimensions = $matches[0];
        }
        return $specifications.$dimensions;
    }
    public static function getTitleWithLangTag($content)
    {
        $extracted_content = NULL;
        // Extract the specifications block, including the opening and closing tags
        preg_match('/<title>(.*?)<\/title>/s', $content, $matches);
        if (isset($matches[0])) {
            $extracted_content = $matches[0];
        }
        return $extracted_content;
    }

    public static function getAllTagOfAi($content, $column = "product_title")
    {
        $extracted_content = NULL;

        // First try exact tag match
        preg_match('/<'.$column.'>(.*?)<\/'.$column.'>/s', $content, $matches);

        // If no match, try alternate tag formats
        if (!isset($matches[1])) {
            switch ($column) {
                case "product_title":
                case "productTitle":
                case "title":
                case "n":
                case "name":
                    $patterns = [
                        '/<productTitle>(.*?)<\/productTitle>/s',
                        '/<product_title>(.*?)<\/product_title>/s',
                        '/<title>(.*?)<\/title>/s',
                        '/<n>(.*?)<\/n>/s',
                        '/<name>(.*?)<\/name>/s'
                    ];

                    foreach ($patterns as $pattern) {
                        preg_match($pattern, $content, $matches);
                        if (isset($matches[1])) {
                            $extracted_content = trim($matches[1]);
                            break;
                        }
                    }
                    break;
            }
        }

        if (isset($matches[1])) {
            $extracted_content = trim($matches[1]);
        }

        return $extracted_content;
    }
    public static function getSpecifiedTagOfAi($content, $column = "product_title")
    {
        $extracted_content = NULL;

        switch ($column) {
            case "product_title":
            case "productTitle":
            case "title":
            case "n":
            case "name":
                // Try all possible title tag formats
                $patterns = [
                    '/<productTitle>(.*?)<\/productTitle>/',
                    '/<product_title>(.*?)<\/product_title>/',
                    '/<title>(.*?)<\/title>/',
                    '/<n>(.*?)<\/n>/',
                    '/<name>(.*?)<\/name>/'
                ];

                foreach ($patterns as $pattern) {
                    preg_match($pattern, $content, $matches);
                    if (isset($matches[1])) {
                        $extracted_content = trim($matches[1]);
                        break;
                    }
                }
                break;
            case "productLongDescription":
                preg_match('/<productLongDescription>(.*?)<\/productLongDescription>/s', $content, $matches);
                if (isset($matches[1])) {
                    $extracted_content = trim($matches[1]);
                }
                break;
            default:
                break;
        }

        return $extracted_content;
    }

    public static function getAllTagOfAiExport($content)
    {
        $tags = [];

        // Load the XML content
        $xml = simplexml_load_string($content);

        if ($xml === false) {
            // Handle XML parsing error
            return $tags;
        }

        // Recursive function to get all tag names
        $getTagNames = function($element) use (&$getTagNames, &$tags) {
            $tags[] = $element->getName();
            foreach ($element->children() as $child) {
                $getTagNames($child);
            }
        };

        // Start the recursive process
        $getTagNames($xml);

        // Remove duplicates and reset array keys
        $tags = array_values(array_unique($tags));

        return $tags;
    }
    public static function getAllImages($response, bool $isSingle = false): string|array|null
    {
        // Extract image tags
        $imageTags = self::getSpecifiedTag($response, 'img-urls');
        if (empty($imageTags)) {
            return $isSingle ? null : [];
        }

        // Prepare XML for parsing
        $xmlContent = sprintf('<root>%s</root>', $imageTags);

        try {
            // Configure XML parsing
            libxml_use_internal_errors(true);
            $xml = new SimpleXMLElement($xmlContent, LIBXML_NOCDATA);

            // Convert SimpleXML to array
            $imageData = json_decode(json_encode($xml), true);
            if (is_string($imageData)) {
                return [
                    'url' => $imageData
                ];
            }

            // If image is already in correct format
            if (is_array($imageData) && isset($imageData['url'])) {
                return $imageData;
            }

            // If image is in array but direct 'image' key with URL
            if (is_array($imageData) && isset($imageData['image'])) {
                if (is_string($imageData['image'])) {
                    return [
                        'url' => $imageData['image']
                    ];
                }
                return $imageData['image'];
            }

            // Return empty array if no valid image data found
            return [
                'url' => ''
            ];

        } catch (Exception $e) {
            error_log(sprintf(
                "Error parsing images: %s. Response: %s",
                $e->getMessage(),
                substr($response, 0, 200)
            ));

            libxml_clear_errors();
            return $isSingle ? null : [];
        } finally {
            libxml_use_internal_errors(false);
        }
    }
    public static function parseAllImages($response, bool $isSingle = false): string|array|null
    {


        // Extract image tags
        $imageTags = self::getSpecifiedTag($response, 'img-urls');
        if (empty($imageTags)) {
            return $isSingle ? null : [];
        }

        // Prepare XML for parsing
        $xmlContent = sprintf('<root>%s</root>', $imageTags);

        try {
            // Configure XML parsing
            libxml_use_internal_errors(true);
            $xml = new SimpleXMLElement($xmlContent, LIBXML_NOCDATA);

            // Convert SimpleXML to array
            $imageData = json_decode(json_encode($xml), true);
            $imageLists = isset($imageData['image']) ? $imageData['image'] : [];
            if (empty($imageLists)) {
                return $isSingle ? null : [];
            }

            // Normalize the structure
            if (isset($imageLists['url'])) {
                // Single image case: ['url' => 'http://...']
                $urls = [$imageLists['url']];
            } else {
                // Multiple images case: [['url' => 'http://...'], ['url' => 'http://...']]
                $urls = array_map(function($image) {
                    return $image['url'] ?? null;
                }, $imageLists);
            }

            // Filter out any invalid URLs
            $urls = array_filter($urls, function($url) {
                return !empty($url) && filter_var($url, FILTER_VALIDATE_URL);
            });

            if (empty($urls)) {
                return $isSingle ? null : [];
            }

            // Return based on type requested
            if ($isSingle) {
                return reset($urls);  // Return first URL for single mode
            }

            return array_values($urls);  // Return all URLs as indexed array

        } catch (Exception $e) {
            error_log(sprintf(
                "Error parsing images: %s. Response: %s",
                $e->getMessage(),
                substr($response, 0, 200)
            ));

            libxml_clear_errors();
            return $isSingle ? null : [];
        } finally {
            libxml_use_internal_errors(false);
        }
    }
    public static function parseSingleImage($response, bool $isSingle = true): string|array|null
    {
        // Extract image tags
        $imageTags = self::getSpecifiedTag($response, 'img-urls');
        if (empty($imageTags)) {
            return $isSingle ? null : [];
        }

        // Prepare XML for parsing
        $xmlContent = sprintf('<root>%s</root>', $imageTags);

        try {
            // Configure XML parsing
            libxml_use_internal_errors(true);
            $xml = new SimpleXMLElement($xmlContent, LIBXML_NOCDATA);

            // Convert SimpleXML to array
            $imageData = json_decode(json_encode($xml), true);
            $imageLists = isset($imageData['image']) ? $imageData['image'] : [];

            if (empty($imageLists)) {
                return $isSingle ? null : [];
            }

            // Normalize the structure
            if (isset($imageLists['url'])) {
                // Single image case: ['url' => 'http://...']
                $urls = [$imageLists['url']];
            } else {
                // Handle various image formats
                $firstImage = null;

                if (is_array($imageLists)) {
                    // Case 1: Direct URL in first element
                    if (isset($imageLists[0]) && is_string($imageLists[0])) {
                        $firstImage = $imageLists[0];
                    }
                    // Case 2: URL in nested array
                    elseif (isset($imageLists[0]['url'])) {
                        $firstImage = $imageLists[0]['url'];
                    }
                    // Case 3: Array of arrays with URL
                    elseif (isset($imageLists[0]) && is_array($imageLists[0])) {
                        foreach ($imageLists[0] as $item) {
                            if (is_string($item)) {
                                $firstImage = $item;
                                break;
                            } elseif (is_array($item) && isset($item['url'])) {
                                $firstImage = $item['url'];
                                break;
                            }
                        }
                    }
                }

                $urls = $firstImage ? [$firstImage] : [];
            }

            // Filter out any invalid URLs
            $urls = array_filter($urls, function($url) {
                return !empty($url) && filter_var($url, FILTER_VALIDATE_URL);
            });

            if (empty($urls)) {
                return $isSingle ? null : [];
            }

            // Return based on type requested
            if ($isSingle) {
                return reset($urls); // Return first URL for single mode
            }

            return array_values($urls); // Return all URLs as indexed array

        } catch (Exception $e) {
            error_log(sprintf(
                "Error parsing images: %s. Response: %s",
                $e->getMessage(),
                substr($response, 0, 200)
            ));
            libxml_clear_errors();
            return $isSingle ? null : [];
        } finally {
            libxml_use_internal_errors(false);
        }
    }
    public static function removeSpecifiedTag(string $xml,$tag): string
    {
        $xml=XMLHelper::getWithoutMergeDataTag($xml);
        if($xml!=""){
            foreach($tag as $tag_name){
                // Pattern to match thumbnail tag and its content
                $pattern= '/<'.$tag_name.'>(.*?)<\/'.$tag_name.'>/s';
                // Remove the thumbnail tag
                $xml = preg_replace($pattern, '', $xml);
            }
        }
        // Return original string if preg_replace fails
        return $xml;
    }


    public static function getWithoutMergeDataTag($content)
    {
        $extracted_content = NULL;
        // Extract the specifications block, including the opening and closing tags
        preg_match('/<merged_data>(.*?)<\/merged_data>/s', $content, $matches);
        if (isset($matches[1])) {
            $extracted_content = $matches[1];
        }
        return $extracted_content;
    }
    public static function extractOutputTagFromContent($content): ?string
    {
        preg_match('/<outputTag>(.*?)<\/outputTag>/s', $content, $matches);

        if (isset($matches[1])) {
            $extracted_content = $matches[0];
            return $extracted_content;
        } else {
            return NULL;
        }
    }
    public static function convertToXml($simpleXmlObject) {
        // Create a new DOMDocument
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;  // For nice formatting

        // Create root element
        $root = $dom->createElement('outputTag');
        $dom->appendChild($root);

        // Convert each element
        foreach ($simpleXmlObject as $key => $value) {
            $element = $dom->createElement($key);
            $root->appendChild($element);

            // Create text node with preserved formatting
            $text = $dom->createTextNode((string)$value);
            $element->appendChild($text);
        }

        return $dom->saveXML();
    }
    public static function getSeparatedPromptContent($content, $column = "productTitle")
    {
        $extracted_content = NULL;

        switch ($column) {
            case "productTitle":
                preg_match('/<productTitle>(.*?)<\/productTitle>/', $content, $matches);
                break;
            case "productLongDescription":
                preg_match('/<productLongDescription>(.*?)<\/productLongDescription>/s', $content, $matches);
                break;
            case "productShortDescription":
                preg_match('/<productShortDescription>(.*?)<\/productShortDescription>/s', $content, $matches);
                break;
            default:
                break;
        }
        if (isset($matches[1])) {
            $extracted_content = $matches[1];

        }
        return $extracted_content;
    }

    public static function removeSpecificSpecialCharacter($response): array|string
    {
        $search=['&'];
        $replace=['&amp;'];
        return $response = str_replace($search,$replace, $response);
    }


    public static function getProductTitle($dataObject){
        if (!empty(XMLHelper::getSpecifiedTag($dataObject->merged_data, "product_title"))) {
            return mb_convert_encoding(XMLHelper::getSpecifiedTag($dataObject->merged_data, "product_title"), 'UTF-8');
        } elseif (!empty(XMLHelper::getSpecifiedTag($dataObject->merged_data, 'title'))) {
            return mb_convert_encoding(XMLHelper::getSpecifiedTag($dataObject->merged_data, "title"), 'UTF-8');
        }elseif(!empty($dataObject->product_title)) {
            return mb_convert_encoding($dataObject->product_title, 'UTF-8');
        }else{
            return '';
        }
    }

}
