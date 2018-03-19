<?php
/**
 * LzpCache v2018.2 RC1 - Requires PHP >= 5.5
 *
 * @author André Posso <admin@lzptec.com>
 * @copyright 2018 Lzp Tec
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace LzpCache;

/**
 * Base LzpCacheClass
 */
 abstract class LzpCache
{
    /**
     * const string
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * @var array
     */
    protected $sync = array();

    /**
     * @var array
     */
    protected $cfg = array(
        'expire' => 600,
        'compress' => 0,
        'version' => null,
        'nameHash' => 'md5',
        'compressType' => 'gz',
        'sync' => false,
		'syncOnDestruct' => false
    );

    public function __destruct()
    {
		if($this->cfg['sync'] && $this->cfg['syncOnDestruct']) {
			$this->Sync();
		}
    }

    /**
     * Get Settings
     *
     * @return array Current settings for the cache.
     */
    public function GetSettings()
    {
        return $this->cfg;
    }

    public function GetConfig()
    {
        return $this->cfg;
    }

    /**
     * Apply Settings
     *
     * @param array $options Optional containing settings for the cache.
     */
    public function ApplySettings($options)
    {
        $this->cfg = $this->CustomSettings($options);
    }

    public function Config($options)
    {
        $this->cfg = $this->CustomSettings($options);
    }

    /**
     * Merge custom cache settings
     *
     * @param array $options Optional containing settings for the cache.
     * @return array containing the merged settings
     */
    abstract protected function CustomSettings($customConfiguration);

    /**
     * Checks if one or more caches exist
     *
     * @param array|string $names Names of the caches to be checked
     * @param array $settings Optional containing settings for the cache.
     * @return mixed
     */
    public function Exists($name, $settings = null)
    {
        $settings = $this->CustomSettings($settings);

        if (is_array($name)) {
            $exists = array();

            foreach ($name as $n) {
                $exists[$n] = $this->CacheExists($n, $settings);
            }
        } else {
            $exists = $this->CacheExists($name, $settings);
        }

        return $exists;
    }

    public function Check($names, $settings = null)
    {
        return $this->Exists($names, $settings);
    }

    abstract protected function CacheExists($name, $settings);

    /**
     * Create one or more caches
     *
     * @param array $datas Names and Data of the caches to be created
     * @param boolean $expire Optional Time for the cache expires
     * @param array $settings Optional containing settings for the cache.
     * @return array
     */
    public function Create($datas, $expire = null, $settings = null)
    {
        $settings = $this->CustomSettings($settings);
        $expire = is_int($expire) ? $expire : $settings['expire'];

        if ($expire > 0) {
            $settings['expire'] = $expire + time();
        }

        $complete = array();

        foreach ($datas as $name => $data) {
            $complete[$name] = $this->CacheWrite($name, $data, $settings);
        }

        return $complete;
    }

    public function Set($datas, $expire = null, $settings = null)
    {
        return $this->Create($datas, $expire, $settings);
    }

    abstract protected function CacheWrite($name, $data, $settings);

    /**
     * Get one or more caches
     *
     * @param array|string $names Names of the caches to be retrieved
     * @param boolean $ignoreExpired Optional ignores if the cache has already expired
     * @param array $settings Optional containing settings for the cache.
     * @return mixed
     */
    public function Get($name, $ignoreExpired = false, $settings = null)
    {
        $settings = $this->CustomSettings($settings);

        if (is_array($name)) {
            $data = array();
            foreach ($name as $n) {
                $data[$n] = $this->CacheRead($n, $ignoreExpired, $settings);
            }
            return $data;
        }

        return $this->CacheRead($name, $ignoreExpired, $settings);
    }

    public function Read($names, $expired = false, $settings = null)
    {
        return $this->Get($names, $expired, $settings);
    }

    abstract protected function CacheRead($name, $ignoreExpired, $settings);

    /**
     * Delete one or more caches
     *
     * @param array|string $names Names of the caches to be deleted
     * @param array $settings Optional containing settings for the caches to be deleted
     * @return mixed
     */
    abstract public function Delete($names, $settings = null);

    public function Remove($names, $settings = null)
    {
        return $this->Delete($names, $settings);
    }

    /**
     * Delete all caches
     *
     * @param array $settings Optional containing settings for the caches to be deleted
     * @return mixed
     */
    abstract public function Clear($settings = null);

    /**
     * Reads and returns the cache directory size
     *
     * @param bool $round Optional Rounds values to B, KB, MB, GB, TB...
     * @param array $settings Optional containing settings for the caches to be read
     * @return null|string
     */
    abstract public function Size($round = false, $settings = null);

    /**
     * Sync all cache
     */
    abstract public function Sync();

    /**
     * Returns the final cache name
     *
     * @param string $name Cache name
     * @return array
     */
    protected function Name($name)
    {
        $nameHash = hash($this->cfg['nameHash'], $name, true);

        $nameHash = $this->Base32($nameHash . $name);

        $path = str_split(strrev($nameHash), 3);

        $path = $path[0] . self::DS . $path[1];

        return array($path, strtolower($nameHash));
    }

    /**
     * Filter a string
     *
     * @param string $data String to be filtered by removing any invalid characters
     * @return string
     */
    protected function Filter($data)
    {
        return preg_replace("/[^a-zA-Z0-9_.-]/", "", $data);
    }

    /**
     * Encodes for base32
     *
     * @param string $data Data to be coded
     * @return string
     */
    protected function Base32($data)
    {
        $dataSize = strlen($data);
        $result = '';
        $remainder = 0;
        $remainderSize = 0;
        $chars = '0123456789abcdefghijklmnopqrstuv';

        for ($i = 0; $i < $dataSize; $i++) {
            $b = ord($data[$i]);
            $remainder = ($remainder << 8) | $b;
            $remainderSize += 8;
            while ($remainderSize > 4) {
                $remainderSize -= 5;
                $c = $remainder & (31 << $remainderSize);
                $c >>= $remainderSize;
                $result .= $chars[$c];
            }
        }
        if ($remainderSize > 0) {
            $remainder <<= (5 - $remainderSize);
            $c = $remainder & 31;
            $result .= $chars[$c];
        }

        return $result;
    }

    /**
     * Encode the cache
     *
     * @param mixed $data Array or Object that will be converted to string.
     * @return string
     */
    protected function Encode($data)
    {
        return (is_array($data) || is_object($data)) ? serialize($data) : $data;
    }

    /**
     * Decode the cache
     *
     * @param mixed $data Data that will be decoded.
     * @return mixed
     */
    protected function Decode($data)
    {
        if (is_null($data)) {
            return null;
        }

        $x = @unserialize($data);
        return ($x === 'b:0;' || $x !== false) ? $x : $data;
    }

    /**
     * Compress the cache
     *
     * @param string $data Data that will be compressed.
     * @param array $settings General Settings array
     * @return string
     */
    protected function Compress($data, $settings)
    {
        $data = $this->Encode($data);

        if ($settings['compressType'] == 'gz' && function_exists('gzdeflate')) {
            return gzdeflate($data, $settings['compress']);
        } elseif ($settings['compressType'] == 'bz' && function_exists('bzcompress')) {
            return bzcompress($data, $settings['compress']);
        } elseif ($settings['compressType'] == 'lzf' && function_exists('lzf_compress')) {
            return lzf_compress($data);
        }

        return $data;
    }

    /**
     * Uncompress the cache
     *
     * @param string $data Data that will be decompressed.
     * @return string
     */
    protected function Uncompress($data, $settings)
    {
        if ($settings['compressType'] == 'bz' && function_exists('bzdecompress')) {
            $data = bzdecompress($data);
        } elseif ($settings['compressType'] == 'lzf' && function_exists('lzf_decompress')) {
            $data = lzf_decompress($data);
        } elseif ($settings['compressType'] == 'gz' && function_exists('gzinflate')) {
            $data = gzinflate($data);
        }

        $data = $this->Decode($data);

        return $data;
    }

    /**
     * Return the filtered cache version
     *
     * @param mixed $version Version of the cache.
     * @return string
     */
    protected function GetFilteredVersion($version)
    {
        $version = !is_null($version) ? $version : $this->cfg['version'];
        return !is_null($version) ? $this->Filter($version) . self::DS : '';
    }

    public function GetVersion($version)
    {
        return array('version' => $version);
    }
}
