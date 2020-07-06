# Data stucture

- `siteId` is requires and it must be unique across all records. 
- `accountId` is required. Multiple sites can have the same one. 
- `multiSiteId` is optional. It must be unique across different `accountId`. It can be the same accross multiple `siteId`. This is the "hub" id.
- `meta` is optional and can be used to describe what the record is used for. 
