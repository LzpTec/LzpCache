# LzpCache
**LzpCache** Improves performance in php applications.

## Current stable version
2018.1 - 02/01/2018

## Current dev version
2018.2 - 2018

**LzpCache 2018.1** Caches from previous versions does not work with the new version

## Resources
- Cache compression
- Custom name for cache and user choice hash
- Custom cache file extension
- Create / Retrieve / Delete multiple caches at once
- It is possible to get a cache even if it has expired
- LZF and Gzip compression
-Support for MemCache and MemCached


## How to use(Disk Cache)
Initialize:
```php
// With settings
	$Cache = new Lzp\DiskCache($config);
// Without settings
	$Cache = new Lzp\DiskCache;
```

To get current settings:
```php
	$cache->GetSettings();
//Returns
	array('dir', 'expire', 'compress', 'version', 'nameHash', 'ext', 'compressType', 'syncOnCall');
```


To configure:
```php
//Settings values
	$config = array('dir', 'expire', 'compress', 'version', 'nameHash', 'ext', 'compressType', 'syncOnCall');
//Parameters( = default/demonstration):
	//Directory path where the cache will be stored
	$config['dir'] = __DIR__.'/cache/';
	//0 for infinity - Value Accepted int (Optional)
	$config['expire'] = 600;
	//0 disables - Accepted values: int from 0 to 9 (Optional)
	$config['compress'] = 0;
	//Null disables - Accepted values: float, string and int (Optional)
	$config['version'] = null;
	//Custom Hash to generate cache name (Optional)
	$config['nameHash'] = 'md5';
	//Cache file extension (Optional)
	$config['ext'] = '.lzp';
	//Cache Compression - supported: gz and lzf (Optional)
	$config['compressType'] = 'gz';
	//Write cache only when call function Sync(Optional)
	$config['syncOnCall'] = false;
//Apply Settings:
	$cache->ApplySettings($config);
```

To get a single cache:
```php
$cache->Get($cacheName, $getExpired, $settings);
$cache->Read($cacheName, $getExpired, $settings);
//Parameters( = default/demonstration):
	// Cache Name (Required)
	$cacheName = 'cacheName';
	//Ignore if cache has expired (Optional)
	$getExpired = false;
	//Settings for the cache to be obtained(Optional)
	$settings = null;
//Returns
	$cacheData;
```

To get multiple caches:
```php
$cache->Get($cachesNames, $getExpired, $settings);
$cache->Read($cachesNames, $getExpired, $settings);
//Parameters( = default/demonstration):
	//Array containing the Name of each cache (Required)
	$cachesNames = array('cacheName00', ...);
	//Ignore if cache has already expired (Optional)
	$getExpired = false;
	//Settings for the cache to be obtained(Optional)
	$settings = null;
//Returns
	array($cacheName=>$value);
```

To create one or more caches:
```php
$cache->Create($namesAndValues, $expire, $settings);
$cache->Set($namesAndValues, $expire, $settings);
//Parameters( = default):
	//Array containing the Names and values of the caches to create (Required)
	$namesAndValues = array('cacheName00' => $value);
	//Cache time / 0 for infinity - Accepted value int (Optional)
	$expire = 0;
	//Settings for the cache to be created(Optional)
	$settings = null;
//Returns
	//true on success
```

To delete one or more caches:
```php
$cache->Delete($cachesNames, $settings);
$cache->Remove($cachesNames, $settings);
//Parameters( = default):
	//Array containing the Name of each cache (Required)
	$cachesNames = array('cacheName00', ...);
	//Settings for the cache to be deleted(Optional)
	$settings = null;
//Returns
	//$wasDeleted = true, false(fail) or null(Cache does not exist)
	array($cacheName=>$wasDeleted);
```

To delete all caches:
```php
$cache->Clear($settings);
//Parameters( = default):
	//Settings for the caches to be deleted(Optional)
	$settings = null;
//Returns
	//true if caches are deleted
```

To check if a cache exists:
```php
$cache->Exists($cacheName, $settings);
$cache->Check($cacheName, $settings);
//Parameters( = default):
	//Cache Name(Required)
	$cacheName = 'cacheName';
	//Settings for the cache to be checked(Optional)
	$settings = null;
//Returns
	//true if the cache exists
```

To check if multiple caches exist:
```php
$cache->Exists($cachesNames, $settings);
//Parameters( = default):
	//Array containing the Name of each cache (Required)
	$cachesNames = array('cacheName00', ...);
	//Settings for the caches to be checked(Optional)
	$settings = null;
//Returns
	array($cacheName=>$exists);
```

To check the cache directory size:
```php
$cache->Size($settings);
//Parameters( = default):
	//$round Rounds values to B, KB, MB, GB, TB...(Optional)
	
	//Settings for the cache size(Optional)
	$settings = null;
//Returns
	//directory size or null(Empty directory or not exist)
```

To sync all caches:
```php
$cache->Sync();
```

To get settings for version:
```php
//Added for Backwards compatibility
$cache->GetVersion($version);
//Parameters( = default):
	//Version to get (Required)
	$version;
//Returns
	$settings array containing settings for that version
	
//EXAMPLE:
	$cache->Get($cacheName, $getExpired, $cache->GetVersion($version));
//OR
	$cache->Size($cache->GetVersion($version));
```