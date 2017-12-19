# LzpCache
**LzpCache** Improves performance in php applications.

## Current stable version
2017.1 - 19/12/2017

## Current dev version
2017.1 - 19/12/2017

**LzpCache 2017.1** Caches from previous versions do not work with new versions

## Resources
- Cache compression
- Custom name for cache and user choice hash
- Custom cache file extension
- Create / Retrieve / Delete multiple caches at once
- It is possible to get a cache even if it has expired
- LZF, Bzip2 and Gzip compression


## Resources that may be introduced
-Support for MemCache and MemCached.


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
	$cache->GetConfig();
//Returns
	array('dir', 'expire', 'version', 'compress', 'nameHash', 'ext', 'compressType');
```

To configure:
```php
//Settings values
	$config = array('dir', 'expire', 'version', 'compress', 'nameHash', 'ext', 'compressType');
//Parameters( = default/demonstration):
	//Directory path where the cache will be stored
	$config['dir'] = __DIR__.'/cache/';
	//0 for infinity - Value Accepted int (Optional)
	$config['expire'] = 600;
	//Null disables - Accepted values: float, string and int (Optional)
	$config['version'] = null;
	//0 disables - Accepted values: int from 0 to 9 (Optional)
	$config['compress'] = 0;
	//Custom Hash to generate cache name (Optional)
	$config['nameHash'] = 'sha1';
	//Cache file extension (Optional)
	$config['ext'] = '.lzp';
	//Cache Compression - supported: gz, lzf, and bz (Optional)
	$config['compressType'] = 'gz';
//Apply Settings:
	$cache->Config($config);
```

To get a single cache:
```php
$cache->Get($cacheName, $getExpired, $version);
$cache->Read($cacheName, $getExpired, $version);
//Parameters( = default/demonstration):
	// Cache Name (Required)
	$cacheName = 'cacheName';
	//Ignore if cache has expired (Optional)
	$getExpired = false;
	//Version of the cache to be obtained - Accepted values: float, string and int (Optional)
	$version = null;
//Returns
	$cacheData;
```

To get multiple caches:
```php
$cache->Get($cachesNames, $getExpired, $version);
$cache->Read($cachesNames, $getExpired, $version);
//Parameters( = default/demonstration):
	//Array containing the Name of each cache (Required)
	$cachesNames = array('cacheName00', ...);
	//Ignore if cache has already expired (opcional)
	$getExpired = false;
	//Version of the cache to be obtained - Accepted values: float, string and int (Optional)
	$version = null;
//Returns
	array($cacheName=>$value);
```

To create one or more caches:
```php
$cache->Create($namesAndValues, $expire, $version);
$cache->Set($namesAndValues, $expire, $version);
//Parameters( = default):
	//Array containing the Names and values of the caches to create (Required)
	$namesAndValues = array('cacheName00' => $value);
	//Cache time / 0 for infinity - Accepted value int (opcional)
	$expire = 0;
	//Version of the cache to be created - Accepted values: float, string and int (Optional)
	$version = null;
//Returns
	//true on success
```

To delete one or more caches:
```php
$cache->Delete($cachesNames, $version);
$cache->Remove($cachesNames, $version);
//Parameters( = default):
	//Array containing the Name of each cache (Required)
	$cachesNames = array('cacheName00', ...);
	//Version of the cache to be deleted - Accepted values: float, string and int (Optional)
	$version = null;
//Returns
	//$wasDeleted = true, false(fail) or null(Cache does not exist)
	array($cacheName=>$wasDeleted);
```

To delete all caches:
```php
$cache->Clear($version);
//Parameters( = default):
	//Delete the caches of a certain version - Accepted values: float, string and int(Optional)
	$version = null;
//Returns
	//true if caches are deleted
```

To check if a cache exists:
```php
$cache->Exists($cacheName, $version);
$cache->Check($cacheName, $version);
//Parameters( = default):
	//Cache Name(Required)
	$cacheName = 'cacheName';
	//Version to be checked - Accepted values: float, string and int (Opcional)
	$version = null;
//Returns
	//true if the cache exists
```

To check if multiple caches exist:
```php
$cache->Exists($cachesNames, $version);
//Parameters( = default):
	//Array containing the Name of each cache (Required)
	$cachesNames = array('cacheName00', ...);
	//Version to be checked - Accepted values: float, string and int (Opcional)
	$version = null;
//Returns
	array($cacheName=>$exists);
```

To check the cache directory size:
```php
$cache->Size($version);
//Parameters( = default):
	//Returns the cache size of a certain version - Accepted values: float, string and int (Opcional)
	$version = null;
//Returns
	//directory size or null(Empty directory or not exist)
```