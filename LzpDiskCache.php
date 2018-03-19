<?php
/**
 * DiskCache v2018.2 RC1 - Requires PHP >= 5.5
 *
 * @author André Posso <admin@lzptec.com>
 * @copyright 2018 Lzp Tec
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace LzpCache;

class DiskCache extends LzpCache
{
    private $diskCfg = array(
        'dir' => (__DIR__) . self::DS . 'cache' . self::DS,
        'ext' => '.lzp',
    );

    public function __construct($options = null)
    {
        $options = is_array($options) ? array_merge($this->diskCfg, $options) : $this->diskCfg;
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
        if (is_array($customConfiguration)) {
            $mergedCfg = array_merge($this->cfg, $customConfiguration);

            $mergedCfg['version'] = $this->GetFilteredVersion($mergedCfg['version']);

            $this->ValidateDirectory($mergedCfg['dir']);

            return $mergedCfg;
        }

        return $this->cfg;
    }

    /**
     * Checks if a cache exist
     *
     * @param string $name Name of the cache to be checked
     * @param array $settings Optional containing settings for the cache.
     * @return bool
     */
    protected function CacheExists($name, $settings)
    {
        $path = $this->GetDirectoryWithVersion($settings);
        $name = implode(self::DS, $this->Name($name));

        return is_file($path . $name . $settings['ext']);
    }

    /**
     * Create one or more caches
     *
     * @param array $name Name of the cache to be created
     * @param boolean $expire Optional Time for the cache expires
     * @param array $settings Optional containing settings for the cache.
     * @return array
     */
    protected function CacheWrite($name, $data, $settings)
    {
        $path = $this->GetDirectoryWithVersion($settings);

        $file = $path . implode(self::DS, $this->Name($name)) . $settings['ext'];

        $cacheData = array(
            'settings' => $settings,
            'data' => ($settings['compress'] > 0) ? $this->Compress($data, $settings) : $data,
        );

        $data = $this->Encode($cacheData);

        if ($settings['sync']) {
            $this->sync[$file] = $data;
            return true;
        }

        $this->ValidateDirectory($path);

        return file_put_contents($file, $data);
    }

    /**
     * Get one or more caches
     *
     * @param array|string $name Name of the cache to be retrieved
     * @param boolean $ignoreExpired Optional ignores if the cache has already expired
     * @param array $settings Optional containing settings for the cache.
     * @return mixed
     */
    protected function CacheRead($name, $ignoreExpired, $settings)
    {
        $path = $this->GetDirectoryWithVersion($settings);

        $settings = $this->CustomSettings($settings);
        $cache = $path . implode(self::DS, $this->Name($name));
        $cache .= $settings['ext'];

        if ($settings['sync'] && array_key_exists($cache, $this->sync)) {
            $cache = $this->sync[$cache];
        } else {
            if (!is_file($cache)) {
                return null;
            }

            $cache = file_get_contents($cache);
        }

        $cache = $this->Decode($cache);

        if ($cache != null) {
            $cacheSettings = $cache['settings'];

            if ($cacheSettings['expire'] == 0 || time() < $cacheSettings['expire'] || $ignoreExpired) {
                $cacheData = $cache['data'];
                return ($cacheSettings['compress'] > 0) ? $this->Uncompress($cacheData, $cacheSettings) : $cacheData;
            }
        }
        return null;
    }

    /**
     * Delete one or more caches
     *
     * @param array|string $names Names of the caches to be deleted
     * @param array $settings Optional containing settings for the cache to be deleted
     * @return mixed
     */
    public function Delete($names, $settings = null)
    {
        $settings = $this->CustomSettings($settings);
        $path = $this->GetDirectoryWithVersion($settings);

        if (!is_writeable($path)) {
            die('Directory not available');
        }

        $del = array();
        foreach ($names as $name) {
            $newName = implode(self::DS, $this->Name($names));
            $file = $path . $newName . $settings['ext'];

            $del[$name] = is_file($file) ? @unlink($file) : null;

            if ($settings['sync']) {
                if (array_key_exists($file, $this->sync)) {
                    unset($this->sync[$file]);
                    $del[$name] = true;
                }
            }
        }
        return $del;
    }

    /**
     * Delete all caches
     *
     * @param array $settings Optional containing settings for the caches to be deleted
     * @return mixed
     */
    public function Clear($settings = null)
    {
        $settings = $this->CustomSettings($settings);
        $path = !is_null($this->tempFileSize) ? $this->tempFileSize : $this->GetDirectoryWithVersion($settings);

        if (!is_writeable($path)) {
            die('Directory not available');
        }

        $del = array();

        $it = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isFile() || $file->isLink()) {
                $del[] = @unlink($file->getRealPath());
            } else {
                $del[] = @rmdir($file->getRealPath());
            }
        }
        rmdir($path);

        return (!is_null($del) && !in_array(false, $del));
    }

    /**
     * Reads and returns the cache directory size
     *
     * @param bool $round Optional Rounds values to B, KB, MB, GB, TB...
     * @param null|float|int|string $version Optional Version of the caches to be read
     * @return int
     */
    public function Size($round = false, $settings = null)
    {
        $settings = $this->CustomSettings($settings);

        $path = $this->GetDirectoryWithVersion($settings);

        if (!is_readable($path)) {
            die('Directory not available');
        }

        $size = 0;

        $it = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isFile() || $file->isLink()) {
                $size += $file->getSize();
            }
        }

        $extSize = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];

        return $round && $size ? round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $extSize[$i] : $size;
    }

    /**
     * Sync all cache
     */
    public function Sync()
    {
        $sync = $this->sync;
        foreach ($sync as $k => $v) {
            $this->ValidateDirectory(pathinfo($k)['dirname']);
            file_put_contents($k, $v);
        }
        $this->sync = array();
    }

    /**
     * GetDirectoryWithVersion
     *
     * @return string
     */
    private function GetDirectoryWithVersion($settings)
    {
        return $settings['dir'] . $settings['version'];
    }

    /**
     * Create a directory
     *
     * @param string $path Directory path
     * @return string
     */
    protected function ValidateDirectory($path)
    {
		if(!is_dir($path))
		{
			mkdir($path, 0777, true);
		}
    }
}
