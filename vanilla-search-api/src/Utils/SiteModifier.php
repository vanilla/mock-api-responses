<?php
/**
 * @author Adam Charron <adam.c@vanillaforums.com>
 * @copyright 2009-2020 Vanilla Forums Inc.
 * @license Proprietary
 */

namespace Vanilla\MockApiResponses\SearchApi\Utils;

use Exception;

/**
 * Small utility for modifying the sites.json.
 */
class SiteModifier {

    const META_CI = "CI";
    const META_EMPLOYEE = "Employee";
    const CI_ENTRY_COUNT = 300;
    const ROOT_CI_ID = 13310000;


    public function rebuild() {
        $this->rebuildEmployeeEntries();
        $this->rebuildCIEntries();
    }

    /**
     * Rebuild all of the CI entries.
     */
    private function rebuildEmployeeEntries() {
        $data = $this->getSiteData();

        // Strip all existing employee entries.
        $strippedData = $this->remoteEntriesWithMeta($data, self::META_EMPLOYEE);
        $allEntries = [$strippedData];

        foreach (Employees::INFO as $id => $name) {
            $allEntries[] = $this->generateEmployeeEntries($name, $id);
        }

        $newData = array_merge(...$allEntries);
        $this->writeSiteData($newData);
    }

    /**
     * Rebuild all of the CI entries.
     */
    private function rebuildCIEntries() {
        $data = $this->getSiteData();
        $strippedData = $this->remoteEntriesWithMeta($data, self::META_CI);
        $newData = array_merge($strippedData, $this->generateCIEntries());
        $this->writeSiteData($newData);
    }

    /**
     * Generate the CI entries.
     *
     * @param string $employeeName The name of the employee.
     * @param int $idPrefix Some number to prefix the various IDs with.
     *
     * @return array
     */
    private function generateEmployeeEntries(string $employeeName, int $idPrefix): array {
        if ($idPrefix < 1000) {
            throw new Exception('ID Prefix should be greater than 1000');
        }

        $remainder = $idPrefix % 1000;
        if ($remainder !== 0) {
            throw new Exception("ID Prefix must be a multiple of 1000");
        }

        $result = [];

        $siteAccountID = $idPrefix + 100;
        $testAccountID = $idPrefix + 900;

        // Local hub/node.
        $result[] = [
            'meta' => self::META_EMPLOYEE . " $employeeName - Hub",
            'siteId' => $siteAccountID,
            'accountId' => $siteAccountID,
            'multiSiteId' => $siteAccountID,
        ];
        $result[] = [
            'meta' => self::META_EMPLOYEE . " $employeeName - Node 1",
            'siteId' => $siteAccountID + 1,
            'accountId' => $siteAccountID,
            'multiSiteId' => $siteAccountID,
        ];
        $result[] = [
            'meta' => self::META_EMPLOYEE . " $employeeName - Node 2",
            'siteId' => $siteAccountID + 2,
            'accountId' => $siteAccountID,
            'multiSiteId' => $siteAccountID,
        ];
        $result[] = [
            'meta' => self::META_EMPLOYEE . " $employeeName - Node 3",
            'siteId' => $siteAccountID + 3,
            'accountId' => $siteAccountID,
            'multiSiteId' => $siteAccountID,
        ];

        // Local tests
        $result[] = [
            'meta' => self::META_EMPLOYEE . " $employeeName - Test Hub",
            'siteId' => $testAccountID,
            'accountId' => $testAccountID,
            'multiSiteId' => $testAccountID,
        ];
        $result[] = [
            'meta' => self::META_EMPLOYEE . " $employeeName - Test Node 1",
            'siteId' => $testAccountID + 1,
            'accountId' => $testAccountID,
            'multiSiteId' => $testAccountID,
        ];
        $result[] = [
            'meta' => self::META_EMPLOYEE . " $employeeName - Test Node 2",
            'siteId' => $testAccountID + 2,
            'accountId' => $testAccountID,
            'multiSiteId' => $testAccountID,
        ];
        $result[] = [
            'meta' => self::META_EMPLOYEE . " $employeeName - Test Node 3",
            'siteId' => $testAccountID + 3,
            'accountId' => $testAccountID,
            'multiSiteId' => $testAccountID,
        ];

        return $result;
    }

    /**
     * Generate the CI entries.
     *
     * @return array
     */
    private function generateCIEntries(): array {
        $result = [];
        for ($i = 0; $i < self::CI_ENTRY_COUNT;  $i++) {

            $siteAccountID = self::ROOT_CI_ID + $i;

            $result[] = [
                'meta' => 'CI Hub - ' . $i,
                'siteId' => $siteAccountID,
                'accountId' => $siteAccountID,
                'multiSiteId' => $siteAccountID,
            ];

            // Add the 2 nodes.

            $result[] = [
                'meta' => 'CI Node - ' . $i . ' - 1',
                'siteId' => $siteAccountID * 10 + 1,
                'accountId' => $siteAccountID,
                'multiSiteId' => $siteAccountID,
            ];
            $result[] = [
                'meta' => 'CI Node - ' . $i . ' - 2',
                'siteId' => $siteAccountID * 10 + 2,
                'accountId' => $siteAccountID,
                'multiSiteId' => $siteAccountID,
            ];
        }

        return $result;
    }

    /**
     * Remove all entries for containing some string in their meta.
     *
     * @param array $sites
     * @param string $search Some string to look for in the meta.
     *
     * @return array
     */
    private function remoteEntriesWithMeta(array $sites, string $search): array {
        $result = [];
        foreach ($sites as $site) {
            if (strpos($site['meta'], $search) !== false) {
                continue;
            }
            $result[] = $site;
        }
        return $result;
    }

    /**
     * Write site data.
     */
    private function writeSiteData(array $data) {
        $validator = new SiteValidator();
        $validator->validateArray($data);

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents(Paths::sitesJson(), $json);
    }

    /**
     * Load the site data.
     *
     * @return array
     */
    private function getSiteData(): array {
        $textContent = file_get_contents(Paths::sitesJson());
        $sites = json_decode($textContent, true);
        return $sites;
    }
}
