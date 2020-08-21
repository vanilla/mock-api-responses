<?php
/**
 * @author Adam Charron <adam.c@vanillaforums.com>
 * @copyright 2009-2020 Vanilla Forums Inc.
 * @license Proprietary
 */

namespace Vanilla\MockApiResponses\SearchApi\Utils;

/**
 * Constants for employees.
 */
final class Employees {

    /**
     * An array of baseID => Employee name
     *
     * ID MUST ALL BE MULTIPLES OF 1000.
     */
    const INFO = [
        1000 => 'AlexK',
        2000 => 'TuanN',
        3000 => 'AdamC',
        4000 => 'StéphaneL',
        5000 => 'ToddB',
        6000 => 'DominicL',
        7000 => 'RichardF',
        8000 => 'DaniM',
        9000 => 'IsisG',
        12000 => 'ChrisC',
    ];
}
