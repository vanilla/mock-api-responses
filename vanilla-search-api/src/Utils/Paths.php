<?php
/**
 * @author Adam Charron <adam.c@vanillaforums.com>
 * @copyright 2009-2020 Vanilla Forums Inc.
 * @license Proprietary
 */

namespace Vanilla\MockApiResponses\SearchApi\Utils;

/**
 * Paths used in the utils.
 */
final class Paths {

    /**
     * Get the path to the sites json.
     *
     * @return string
     */
    public static function sitesJson(): string {
        return realpath(__DIR__ . "/../../sites.json");
    }
}
