# Data structure

- `siteId` is requires and it must be unique across all records. 
- `accountId` is required. Multiple sites can have the same one. 
- `multiSiteId` is optional. It must be unique across different `accountId`. It can be the same accross multiple `siteId`. This is the "hub" id.
- `meta` is optional and can be used to describe what the record is used for. 

## How to add your own

1. Create a new git branch within this repo based on `master`
2. Go to `vanilla-search-api/src/Utils/Employees.php`
3. Add an entry for yourself. Your ID must be a multiple of 1000.
4. Run the following

    ```sh
    cd /path/to/mock-api-responses
    composer install
    ./bin/build-sites
    ```

5. Make a PR to this repo with the resulting changes.

## Notes on uniqueness

- All `siteId`s must be globally unique. Make sure you are not re-using an existing one.
- Try to pick a full range of 1000 for your account/siteIDs. If another employee is already using the "4000"s for example, use "5000"s for your own IDs.

## Example Hub/Node Setup

```
[
    {
        "meta": "Adam Local Hub",
        "accountId": 3000,
        "siteId": "3100",
        "multiSiteId": "3100"
    },
    {
        "meta": "Adam Local Node 1",
        "accountId": 3000,
        "siteId": "3101",
        "multiSiteId": "3100"
    },
    {
        "meta": "Adam Local Node 2",
        "accountId": 3000,
        "siteId": "3102",
        "multiSiteId": "3100"
    }
]
```
