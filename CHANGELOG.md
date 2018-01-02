﻿# LzpCache Changelog

## Current stable version
2018.1 - 02/01/2018

## Current dev version
2018.2 - 2018

## 2018.2

-Support for MemCache and MemCached.

## 2018.1

-Fixed Typo

-Remove parameter $version in functions Size and Clear

-Added parameter $setting in functions Size and Clear

-Updated Documentation

-Improved performance

-Default hash pattern changed to MD5

-Added for Backwards compatibility: GetVersion function

-Fixed UTF-8 namespace bug

**Delayed**

-Support for MemCache and MemCached.

## 2017.1

-New version numbering system

-New Cache mode: Sync on call

-New function: GetSettings && GetConfig (Get current settings)

-New function: Check (Same thing as Exists)

-Pass custom settings to the cache

-New function: ApplySettings (Same thing as Config)

-Remove parameter $version in all functions(except Size and Clear)

-Added parameter $setting in all functions(except Size and Clear)

-Re-written code

**Delayed**

-Support for MemCache and MemCached.

## 2.1.5
-Small performance improvement

## 2.1.4
-Code improvements

-Small performance improvement

-Clean code

## V2.1.3b
-Bugs fixed

## V2.1.3 [YANKED]
-English Content

-Code improvements

-Documentation updated

-Small performance improvement

## V2.1.2
-Fixed configuration bug

-Cache configuration improvements

-Small performance improvement

## V2.1.1
-Fixed bugs in compression and decompression functions

-Enhanced compression

-Improved code

-Small performance improvement

-New option to configure compression (compressType)

## V2.1.0
-New Name System (Does not work with v2.0)

-Names are now case-sensitive

-Performance Optimized

-Removed the useNewNameSystem configuration

-Bugs fixed

-Hash pattern changed to SHA1

## V2.0.3
- Fixed the Bzip2 compression option

## V2.0.2 [YANKED]
-New compression options (Lzf and Bzip2)

## V2.0.1
-Removed the cacheNameCfg configuration

-New nameHash setting

-New Name System (BETA) - useNewNameSystem configuration

-Performance Optimized

-Improvements in documentation

-Removed dir argument of Size function

-Fixed a bug in the cache version system

-Fixed bug in delete function

## V2.0.0
-New parameter for Create ($expire)

-Modified Delete and Create Function

-Function Delete All renamed to Clear

-New function: Set (Same thing as Create)

-New function: Read (Same thing as Get)

-New function: Remove (Same thing as Delete)

-Directories are now created with permission 0777

-Documented Code

-Re-written code

-Performance Optimized

-Documentation updated

-CacheNameType setting renamed to cacheNameCfg

-Removed Create($config) parameter

-Removed Cache Encryption

-Remove DirSize for Size

-United Function ExistsMultiples -> Exists

-United Function CreateMultiples -> Create

-United Function DeleteMultiples -> Delete

-United Function GetMultiples -> Get

-Removed code changelog

## V1.3.0
-Performance Optimized

-Documentation updated

-Cache encryption (Consume performance - Need to remove comments from code)

-Function CacheDirSize renamed to DirSize

-Bugs in the DirSize function fixed

-New parameter for ExistsMultiples ($version)

-New parameter for CreateMultiples ($version)

-New parameter for GetMultiples ($version)

-New parameter for DeleteMultiples ($version)

-New parameter for DirSize ($version)

-Extended default extension for (.lzp)

## V1.2.1
-Added argument added in Settings ('version')

-Improved Performance

## V1.2.0
-Documentation updated

-Improved Performance

-Modified Lozep\LzpCache to Lzp\Cache

## V1.1.1
-Performance Optimized

-Improved Code Organization

-Improved Documentation

-New parameter for DeleteAll ($version)

-New parameter for Create ($config)

-Modification in functions (see more in the examples):

DeleteMultiples -> Returns an array ($cachename => [bool $has_been_deleted])

CreateMultiples -> Returns an array ($cachename => [bool $has_been_created])

Delete -> Returns true, false, or null(does not exist)


## V1.1.0
-New optional argument ($cacheVersion)

-Improved Documentation

-Improved Performance

-New function (cacheDirSize)

## V1.0.0
- Release of the code for free use (MIT License)