<?php
/**
	* LzpDiskCache v2018.1 - Requires PHP >= 5.5
	*
	* @author André Posso <admin@lzptec.com>
	* @copyright 2018 Lzp Tec
	* @license http://www.opensource.org/licenses/mit-license.php MIT License
*/
namespace Lzp;

require('LzpCache.php');

class MemCache extends Cache
{
	private $memCfg = array(
		'dir' => (__DIR__).self::DS.'cache'.self::DS, 
		'ext' => '.lzp'
	);

	public function __construct($options = null)
	{
		$options = is_array($options) ? array_merge($this->memCfg, $options) : $this->memCfg;
		$this->ApplySettings($options);
	}

	/**
		* Merge custom cache settings
		*
		* @param array $options Optional containing settings for the cache.
		* @return array containing the merged settings
	*/
	protected function CustomSettings($customConfiguration){ }

	/**
		* Checks if a cache exist
		* 
		* @param array|string $names Name of the cache to be checked
		* @param array $settings Optional containing settings for the cache.
		* @return bool
	*/
	protected function CacheExists($name, $settings){ }

	/**
		* Create one or more caches
		* 
		* @param array $names Names of the caches to be created
		* @param boolean $expire Opcional Tempo para o cache expirar
		* @param array $settings Optional containing settings for the caches to be created
		* @return array
	*/
	public function Create($datas, $expire=null, $settings=null){ }

	/**
		* Get one or more caches
		* 
		* @param array|string $names Names of the caches to be retrieved
		* @param boolean $expired Optional ignores if the cache has already expired
		* @param array $settings Optional containing settings for the caches to be retrieved
		* @return mixed
	*/
	public function Get($names, $expired=false, $settings=null){ }

	/**
		* Delete one or more caches
		* 
		* @param array|string $names Names of the caches to be deleted
		* @param array $settings Optional containing settings for the caches to be deleted
		* @return mixed
	*/
	public function Delete($names, $settings=null){ }

	/**
		* Delete all caches
		* 
		* @param array $settings Optional containing settings for the caches to be deleted
		* @return mixed
	*/
	public function Clear($settings=null){ }

	/**
		* Reads and returns the cache directory size
		* 
		* @param string $dir Directory to read
		* @param array $settings Optional containing settings for the caches to be read
		* @return null|string
	*/
	public function Size($settings=null){ }

	/**
		* Sync all cache
	*/
	public function Sync(){ }


}