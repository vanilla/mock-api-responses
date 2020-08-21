<?php
/**
 * @author Adam Charron <adam.c@vanillaforums.com>
 * @copyright 2009-2020 Vanilla Forums Inc.
 * @license Proprietary
 */

namespace Vanilla\MockApiResponses\SearchApi\Utils;

/**
 * Validator of the sites JSON.
 */
class SiteValidator {

    /** @var array[] */
    private $sites;

    /** @var string[] */
    private $errors = [];

    /**
     * Validate an array of sites.
     *
     * @param array $sites
     *
     * @return void
     */
    public function validateArray(array $sites) {
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
            throw new \Exception('Failed to validate configuration');
        }
    }

    /**
     * Validate sites loaded from the json file.
     *
     * @return void
     */
    public function validateJson() {
        $textContent = file_get_contents(Paths::sitesJson());
        $sites = json_decode($textContent, true);
        $this->validateArray($sites);
    }

    /**
     * Ensure an error exists in a site.
     *
     * @param array $site
     * @param string $key
     *
     * @return void
     */
    private function ensureVarExists(array $site, string $key) {
        if (!isset($site[$key])) {
            $this->errors[] = "\nEncountered site without a $key: \n" . json_encode($site, JSON_PRETTY_PRINT);
        }
    }

    /**
     * Ensure a variable is unique among all sites.
     *
     * @param array $site
     * @param string $key
     *
     * @return void
     */
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

    /**
     * Ensure a variable in a site is an integer.
     *
     * @param array $site
     * @param string $key
     *
     * @return void
     */
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
