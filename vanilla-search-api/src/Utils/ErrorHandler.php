<?php
/**
 * @author Adam Charron <adam.c@vanillaforums.com>
 * @copyright 2009-2020 Vanilla Forums Inc.
 * @license Proprietary
 */

namespace Vanilla\MockApiResponses\SearchApi\Utils;

/**
 * Error handling.
 */
final class ErrorHandler {

    /**
     * Apply the error handler.
     */
    public static function apply() {
        set_error_handler([ErrorHandler::class, 'handleError']);
        set_exception_handler([ErrorHandler::class, 'handleException']);
    }

    /**
     * Error handler that logs to error, then exits.
     *
     * @param \Exception $e
     */
    public static function handleException(\Exception $e) {
        fwrite(STDERR, "\n" . $e->getMessage() . "\n" . $e->getTraceAsString());
        exit(500);
    }

    /**
     * Error handler that logs to error, then exits.
     *
     * @param $severity
     * @param $message
     * @param $file
     * @param $line
     */
    public static function handleError($severity, $message, $file, $line) {
        fwrite(STDERR, "\n" . $message . "\n" . $file . ":" . $line);
        exit(500);
    }
}
