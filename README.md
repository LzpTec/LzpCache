# LzpCache
**LzpCache** é uma classe escrita em php para cache.

## Versão Atual
2.1.2 - 02/10/2016

**LzpCache V2.1.0** Caches from previous versions do not work with new versions

## Resources
- Cache compression
- Custom name for cache and user choice hash
- Custom cache file extension
- Create / Retrieve / Delete multiple caches at once
- It is possible to get a cache even if it has expired
- LZF and Bzip2 compression


## Resources that may be introduced
-Support for MemCache and MemCached.


## How to use
Initialize:
```php
// With settings
	$Cache = new Lzp\Cache($config);
// Without settings
	$Cache = new Lzp\Cache;
```

To configure:
```php
//Settings
	$config = array('dir', 'expire', 'version', 'compress', 'nameHash', 'ext', 'useLZF', 'useBZ');
//Parameters( = default/demonstration):
	$config['dir'] = __DIR__.'/cache/'; 	//Directory path where the cache will be stored
	$config['expire'] = 600; 				//0 for infinity - Value Accepted int (Optional)
	$config['version'] = null; 				//Null disables - Accepted values: float, string and int (Optional)
	$config['compress'] = 0;				//0 disables - Accepted values: int from 0 to 9 (Optional)
	$config['nameHash'] = 'sha1'			//Custom Hash to generate cache name (Optional)
	$config['ext'] = '.lzp'; 					//Cache file extension (Optional)
	$config['compressType'] = 'gz'; 		//Cache Compression - supported: gz, lzf, and bz (Optional)
//Apply Settings:
	$cache->Config($config);
```

To get a single cache:
```php
$cache->Get($cacheName, $getExpired, $version);
$cache->Read($cacheName, $getExpired, $version);
//Parameters( = default/demonstration):
	$cacheName = 'cacheName'; 	// Cache Name (Required)
	$getExpired = false;				//Ignore if cache has expired (Optional)
	$version = null;					//Version of the cache to be obtained - Accepted values: float, string and int (Optional)
```

To get multiple caches:
```php
$cache->Get($cachesNames, $getExpired, $version);		//Returns an array($cacheName=>$value)
$cache->Read($cachesNames, $getExpired, $version);
//Parameters( = default/demonstration):
	$cachesNames = array('cacheName00', ...);				//Array containing the Name of each cache (Required)
	$getExpired = false;												//Ignore if cache has already expired (opcional)
	$version = null;													//Version of the cache to be obtained - Accepted values: float, string and int (Optional)
```

To create one or more caches:
```php
$cache->Create($namesAndValues, $expire, $version); 		//Returns true on success
$cache->Set($namesAndValues, $expire, $version); 			//Returns true on success
//Parameters( = default):
	$namesAndValues = array('cacheName00' => $value); 	//Array containing the Names and values of the caches to create (Required)
	$expire = 0;															//Cache time / 0 for infinity - Accepted value int (opcional)
	$version = null;														//Version of the cache to be created - Accepted values: float, string and int (Optional)
```

To delete one or more caches:
```php
$cache->Delete($cachesNames, $version); 			//Returns an array($cacheName=>$itWasDeleted), $itWasDeleted = true, false(fail) or null(Cache does not exist)
$cache->Remove($cachesNames, $version);
//Parameters( = default):
	$cachesNames = array('cacheName00', ...); 		//Array containing the Name of each cache (Required)
	$version = null; 											//Version of the cache to be deleted - Accepted values: float, string and int (Optional)
```

To delete all caches:
```php
$cache->Clear($version);		//Returns true if caches are deleted
//Parameters( = default):
	$version = null;				//Delete the caches of a certain version - Accepted values: float, string and int(Optional)
```

To check if a cache exists:
```php
$cache->Exists($cacheName, $version);		//Returns true if the cache exists
//Parameters( = default):
	$cacheName = 'cacheName';					//Cache Name(Required)
	$version = null;									//Version to be checked - Accepted values: float, string and int (Opcional)
```

To check if multiple caches exist:
```php
$cache->Exists($cachesNames, $version);			//Returns an array($cacheName=>$exists)
//Parameters( = default):
	$cachesNames = array('cacheName00', ...); 		//Array contendo o Nome de cada cache (Required)
	$version = null;											//Version to be checked - Accepted values: float, string and int (Opcional)
```

To check the cache directory size:
```php
$cache->Size($version);		//Returns directory size or null(Empty directory)
//Parameters( = default):
	$version = null;				//Returns the cache size of a certain version - Accepted values: float, string and int (Opcional)
```