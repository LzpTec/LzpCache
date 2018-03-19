<?php
/**
 * MemCache v2018.2 RC1 - Requires PHP >= 5.5
 *
 * @author André Posso <admin@lzptec.com>
 * @copyright 2018 Lzp Tec
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace LzpCache;

class MemCache extends LzpCache
{
    private $memCfg = array(
        'host' => '127.0.0.1',
        'port' => '11211',
        'type' => 'memcached',
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
    protected function CustomSettings($customConfiguration)
    {

    }

    /**
     * Checks if a cache exist
     *
     * @param string $name Name of the cache to be checked
     * @param array $settings Optional containing settings for the cache.
     * @return bool
     */
    protected function CacheExists($name, $settings)
    {}

    /**
     * Create one or more caches
     *
     * @param array $name Name of the cache to be created
     * @param boolean $expire Optional Time for the cache expires
     * @param array $settings Optional containing settings for the cache.
     * @return array
     */
    public function CacheWrite($name, $data, $settings)
    {}

    /**
     * Get one or more caches
     *
     * @param array|string $name Name of the cache to be retrieved
     * @param boolean $ignoreExpired Optional ignores if the cache has already expired
     * @param array $settings Optional containing settings for the cache.
     * @return mixed
     */
    public function GetCacheRead($name, $ignoreExpired, $settings)
    {}

    /**
     * Delete one or more caches
     *
     * @param array|string $names Names of the caches to be deleted
     * @param array $settings Optional containing settings for the caches to be deleted
     * @return mixed
     */
    public function Delete($names, $settings = null)
    {}

    /**
     * Delete all caches
     *
     * @param array $settings Optional containing settings for the caches to be deleted
     * @return mixed
     */
    public function Clear($settings = null)
    {}

    /**
     * Reads and returns the cache directory size
     *
     * @param bool $round Optional Rounds values to B, KB, MB, GB, TB...
     * @param array $settings Optional containing settings for the caches to be read
     * @return null|string
     */
    public function Size($round = false, $settings = null)
    {}

    /**
     * Sync all cache
     */
    public function Sync()
    {}

}