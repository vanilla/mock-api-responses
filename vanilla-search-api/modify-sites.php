<?php

$modifier = new SitesModifier();
$modifier->rebuildCIEntries();

/**
 * Small utility for modifying the sites.json.
 */
class SitesModifier {

    const CI_ENTRY_COUNT = 300;

    const ROOT_CI_ID = 13310000;

    /**
     * Rebuild all of the CI entries.
     */
    public function rebuildCIEntries() {
        $data = $this->getSiteData();
        $strippedData = $this->removeCIEntries($data);
        $newData = array_merge($strippedData, $this->generateCIEntries());
        $this->writeSiteData($newData);
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
                'accountID' => $siteAccountID,
                'multisiteId' => $siteAccountID,
            ];

            // Add the 2 nodes.

            $result[] = [
                'meta' => 'CI Node - ' . $i . ' - 1',
                'siteId' => $siteAccountID * 10 + 1,
                'accountID' => $siteAccountID,
                'multisiteId' => $siteAccountID,
            ];
            $result[] = [
                'meta' => 'CI Node - ' . $i . ' - 2',
                'siteId' => $siteAccountID * 10 + 2,
                'accountID' => $siteAccountID,
                'multisiteId' => $siteAccountID,
            ];
        }

        return $result;
    }

    /**
     * Remove all entries for CI from the array.
     *
     * @param array $sites
     *
     * @return array
     */
    private function removeCIEntries(array $sites): array {
        $result = [];
        foreach ($sites as $site) {
            if (strpos($site['meta'], 'CI') !== false) {
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
        $sitesFilePath = realpath(__DIR__ . '/sites.json');
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($sitesFilePath, $json);
    }

    /**
     * Load the site data.
     *
     * @return array
     */
    private function getSiteData(): array {
        $sitesFilePath = realpath(__DIR__ . '/sites.json');
        $textContent = file_get_contents($sitesFilePath);
        $sites = json_decode($textContent, true);
        return $sites;
    }
}
