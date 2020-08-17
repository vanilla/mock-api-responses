<?php
/**
 * @author Adam Charron <adam.c@vanillaforums.com>
 * @copyright 2009-2020 Vanilla Forums Inc.
 * @license gpl-2.0-only
 */

function errorHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("errorHandler");

$validator = new Validator();
$validator->run();

class Validator {

    /** @var array[] */
    private $sites;

    /** @var string[] */
    private $errors = [];

    public function run() {
        $sitesFilePath = realpath(__DIR__ . '/../vanilla-search-api/sites.json');
        $textContent = file_get_contents($sitesFilePath);
        $sites = json_decode($textContent, true);
        $this->sites = $sites;

        foreach ($sites as $site) {
            $this->ensureVarExists($site, 'siteId');
            $this->ensureVarExists($site, 'accountId');
            $this->ensureVarExists($site, 'meta');
            $this->ensureVarUnique($site, 'siteId');
            $this->ensureVarUnique($site, 'meta');
            $this->ensureVarInt($site, 'siteId');
            $this->ensureVarInt($site, 'accountId');
        }

        if (count($this->errors) > 0) {
            foreach ($this->errors as $error) {
                fwrite(STDERR, $error);
            }
            throw new Exception('Failed to validate configuration');
        }
    }

    private function ensureVarExists(array $site, string $key) {
        if (!isset($site[$key])) {
            $this->errors[] = "\nEncountered site without a $key: \n" . json_encode($site, JSON_PRETTY_PRINT);
        }
    }

    private function ensureVarUnique(array $site, string $key) {
        if (isset($site[$key])) {
            $foundCount = 0;
            $ownVal = $site[$key];
            foreach ($this->sites as $otherSite) {
                // Loose equality because this may have ints or strings for ints.
                if ($ownVal == $otherSite[$key]) {
                    $foundCount++;
                }
            }
            if ($foundCount > 1) {
                $this->errors[] = "\nEncountered site duplicate value for siteKey $key in: \n" . json_encode($site, JSON_PRETTY_PRINT);
            }
        }
    }

    private function ensureVarInt(array $site, string $key) {
        if (isset($site[$key])) {
            $val = $site[$key];
            $validated = filter_var($val, FILTER_VALIDATE_INT);
            if ($validated === false) {
                $this->errors[] = "\nExpected integer value for key $key in site: \n" . json_encode($site, JSON_PRETTY_PRINT);
            }
        }
    }

}