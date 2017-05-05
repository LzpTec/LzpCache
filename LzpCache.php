<?php
	/**
		* LzpCache v2.1.3 - Requires PHP >= 5.5
		*
		* @author André Posso <admin@lzptec.com>
		* @copyright 2017 Lzp Tec
		* @license http://www.opensource.org/licenses/mit-license.php MIT License
	*/
	namespace Lzp;
	
	class Cache
	{
		private const DS = DIRECTORY_SEPARATOR;
		
		/**
			* Array containing the settings.
			* 
			* @var array
		*/
		private $cfg;
		
		/**
			* Variable for the Size function.
			* 
			* @var array
		*/
		private $tempFileSize = null;
		
		/**
			* Constructor
			* 
			* @param array $options Optional containing settings for the cache.
			* @return void
		*/
		public function __construct($options = null)
		{
			//Array padrão
			$default = array(
			'dir' => (__DIR__).self::DS.'cache'.self::DS, 
			'expire' => 600, 
			'compress' => 0, 
			'version' => null, 
			'nameHash' => 'sha1', 
			'ext' => '.lzp',
			'compressType' => 'gz'
			);
			
			$newOptions = $defaults;
			
			if(is_array($options))
			{
				foreach($options as $k => $v)
				{
					if(array_key_exists($k, $newOptions))
					{
						$newOptions[$k] = $options[$k];
					}
				}
			}
			
			$this->cfg = $newOptions;
			$this->CreateDir($this->cfg['dir']);
		}
		
		/**
			* Configure the cache
			* 
			* @param array $options Contains settings for the cache.
			* @return void
		*/
		public function Config($options)
		{
			$newOptions = $this->cfg;
			
			if(is_array($options))
			{
				foreach($options as $k => $v)
				{
					if(array_key_exists($k, $newOptions))
					{
						$newOptions[$k] = $options[$k];
					}
				}
			}
			
			$this->cfg = $newOptions;
			
			$this->CreateDir($this->cfg['dir']);
		}
		
		/**
			* Checks if one or more caches exist
			* 
			* @param array|string $names Names of the caches to be checked
			* @param null|float|int|string $version Optional Version of the caches to be checked
			* @return mixed
		*/
		public function Exists($names, $version=null){
			$path = $this->cfg['dir'];
			$path .= $this->GetVersion($version);
			
			if(is_array($names))
			{
				$exists = array();
				
				foreach($names as $name)
				{
					$newName = $this->Name($name);
					$newName = implode(self::DS, $newName);
					$file = $path.$newName.$this->cfg['ext'];
					$exists[$name] = is_file($file);
				}
			}
			else
			{
				$name = $this->Name($names);
				$name = implode(self::DS, $name);
				$file = $path.$name.$this->cfg['ext'];
				$exists = is_file($file);
			}
			
			return $exists;
		}
		
		/**
			* Cria o(s) cache(s)
			* 
			* @param array $names Names of the caches to be created
			* @param boolean $expire Opcional Tempo para o cache expirar
			* @param null|float|int|string $version Opcional Version of the caches to be created
			* @return array
		*/
		public function Create($datas, $expire=null, $version=null)
		{
			$path = $this->cfg['dir'];
			$path .= $this->GetVersion($version);
			
			$compress = $this->cfg['compress'];
			$expire = !is_null($expire) ? $expire : $this->cfg['expire'];
			
			if($expire!=0){
				$expire += time();
			}
			
			$complete = array();
			
			foreach($datas as $name=>$data)
			{
				$newName = $this->Name($name);
				$path .= $newName[0].self::DS;
				
				$this->CreateDir($path);
				
				$cacheData = array(
				'compress' => ($compress > 0) ? $compress.'|'.$this->cfg['compressType'] : null,
				'expire' => (int)$expire,
				'data' => ($compress > 0) ? $this->Compress($data, $compress) : $data
				);
				
				$complete[$name] = $this->Write($path.$newName[1].$this->cfg['ext'], $cacheData);
			}
			
			return $complete;
		}
		
		public function Set($datas, $expire=null, $version=null)
		{
			return $this->Create($datas, $expire, $version);
		}
		
		/**
			* Obtém o(s) cache(s)
			* 
			* @param array|string $names Names of the caches to be retrieved
			* @param boolean $expired Optional ignores if the cache has already expired
			* @param null|float|int|string $version Optional Version of the caches to be retrieved
			* @return mixed
		*/
		public function Get($names, $expired=false, $version=null)
		{
			$path  = $this->cfg['dir'];
			$path .= $this->GetVersion($version);
			
			if(is_array($names))
			{
				$data = array();
				foreach($names as $name)
				{
					$newName = $this->Name($name);
					$newName = implode(self::DS, $newName);
					$cache = $this->Open($path.$newName.$this->cfg['ext']);
					
					if($cache['expire'] == 0 || time() < $cache['expire'] || $expired)
					{
						$cacheData = $cache['data'];
						$compress = explode('|', $cache['compress']);
						$data[$name] = ($compress[0] > 0) ? $this->Uncompress($cacheData, $compress[1]) : $cacheData;
					}
				}
				return $data;
			}
			
			$names = $this->Name($names);
			$names = implode(self::DS, $names);
			$cache = $this->Open($path.$names.$this->cfg['ext']);
			
			if($cache['expire'] == 0 || time() < $cache['expire'] || $expired)
			{
				$data = $cache['data'];
				$compress = explode('|', $cache['compress']);
				return ($compress[0] > 0) ? $this->Uncompress($data, $compress[1]) : $data;
			}
		}
		
		public function Read($names, $expired=false, $version=null)
		{
			return $this->Get($names, $expired, $version);
		}
		
		/**
			* Deleta o(s) cache(s)
			* 
			* @param array|string $names Names of the caches to be deleted
			* @param null|float|int|string $version Optional Version of the caches to be deleted
			* @return mixed
		*/
		public function Delete($names, $version=null)
		{
			if(!is_writeable($this->cfg['dir']))
			{
				die('Direrório não diponível ou sem permissão para escrita');
			}
			
			$path = $this->cfg['dir'];
			$path .= $this->GetVersion($version);
			
			$del = array();
			foreach($names as $name)
			{
				$newName = $this->Name($names);
				$newName = implode(self::DS, $newName);
				$file = $path.$newName.$this->cfg['ext'];
				$del[$name] = is_file($file) ? @unlink($file) : null;
			}
			return $del;
		}
		
		public function Remove($names, $version=null)
		{
			return $this->Delete($names, $version);
		}
		
		/**
			* Deleta todos os caches
			* 
			* @param null|float|int|string $version Optional Version of the caches to be deleted
			* @return mixed
		*/
		public function Clear($version=null)
		{
			if(!is_writeable($this->cfg['dir']))
			{
				die('Direrório não diponível ou sem permissão para escrita');
			}
			
			$del = array();
			
			$files = glob($this->cfg['dir'].$this->GetVersion($version).'/*', GLOB_NOSORT);
			foreach($files as $file)
			{
				if(is_file($file))
				{
					$del[] = @unlink($file);
				}
			}
			return (!is_null($del) && !in_array(false, $del));
		}
		
		/**
			* Reads and returns the cache directory size
			* 
			* @param string $dir Directory to read
			* @param null|float|int|string $version Optional Version of the caches to be read
			* @return null|string
		*/
		public function Size($version=null)
		{
			$path = !is_null($this->tempFileSize) ? $this->tempFileSize : $this->cfg['dir'];
			$path .= $this->GetVersion($version);
			
			if(!is_readable($path))
			{
				die('Direrório não diponível ou sem permissão para leitura');
			}
			
			$size = 0;
			$files = glob(rtrim($path, '/').'/*', GLOB_NOSORT);
			foreach($files as $file)
			{
				$this->tempFileSize = $file;
				$size += is_file($file) ? filesize($file) : $this->Size($version);
			}
			
			$this->tempFileSize = null;
			
			$extSize = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];
			
			return ($size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $extSize[$i] : null);
		}
		
		/**
			* Reads and returns data from a file
			* 
			* @param string $file File to read
			* @return mixed
		*/
		private function Open($file)
		{
			if(!is_file($file))
			{
				return null;
			}
			
			$file = file_get_contents($file);
			
			return $this->Decode($file);
		}
		
		/**
			* Create a file and add data to it
			* 
			* @param string $file File to create
			* @param mixed $data Data to be added to the file
			* @return mixed
		*/
		private function Write($file, $data)
		{
			$data = $this->Encode($data);
			return file_put_contents($file, $data);
		}
		
		/**
			* Filter a string
			* 
			* @param string $data String to be filtered by removing any invalid characters
			* @return string
		*/
		private function Filter($data)
		{
			return preg_replace("/[^a-zA-Z0-9_.-]/", "", $data);
		}
		
		/**
			* Returns the final cache name
			* 
			* @param string $name Cache name
			* @return array
		*/
		private function Name($name, $size=18)
		{
			$name = $this->Filter($name);
			
			$nameHash = hash($this->cfg['nameHash'], $name, true);
			
			$nameHash = $this->base32($nameHash.$name);
			
			$path = str_split(strrev($nameHash), 3);
			
			$path = $path[0].self::DS.$path[1];
			
			return array($path, strtolower(substr($nameHash, 0, $size)));
		}
		
		/**
			* Create a directory
			* 
			* @param string $path Directory path
			* @return string
		*/
		private function CreateDir($path)
		{
			if(!file_exists($path)){
				mkdir($path, 0777, true);
			}
		}
		
		/**
			* Encodes for base32
			* 
			* @param string $data Data to be coded
			* @return string
		*/
		private function base32($data)
		{
			$dataSize = strlen($data);
			$result = '';
			$remainder = 0;
			$remainderSize = 0;
			$chars = '0123456789abcdefghijklmnopqrstuv';
			
			for($i = 0; $i < $dataSize; $i++)
			{
				$b = ord($data[$i]);
				$remainder = ($remainder << 8) | $b;
				$remainderSize += 8;
				while ($remainderSize > 4)
				{
					$remainderSize -= 5;
					$c = $remainder & (31 << $remainderSize);
					$c >>= $remainderSize;
					$result .= $chars[$c];
				}
			}
			if ($remainderSize > 0)
			{
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
		private function Encode($data)
		{
			return (is_array($data) || is_object($data)) ? serialize($data) : $data;
		}
		
		/**
			* Decode the cache
			* 
			* @param mixed $data Data that will be decoded.
			* @return mixed
		*/
		private function Decode($data)
		{
			if(is_null($data)){
				return null;
			}
			
			
			$x = @unserialize($data);
			return ($x === 'b:0;' || $x !== false) ? $x : $data;
		}
		
		/**
			* Compress the cache
			* 
			* @param string $data Data that will be compressed.
			* @param int $compressLevel Compression level from 1 to 9.
			* @return string
		*/
		private function Compress($data, $compressLevel)
		{
			$data = $this->Encode($data);
			
			if($this->cfg['compressType'] == 'gz' && function_exists('gzdeflate'))
			{
				return gzdeflate($data, $compressLevel);
			}
			elseif($this->cfg['compressType'] == 'bz' && function_exists('bzcompress'))
			{
				return bzcompress($data, $compressLevel);
			}
			elseif($this->cfg['compressType'] == 'lzf' && function_exists('lzf_compress'))
			{
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
		private function Uncompress($data, $type)
		{
			$data = $this->Decode($data);
			
			if($type == 'bz' && function_exists('bzdecompress'))
			{
				return bzdecompress($data);
			}
			elseif($type == 'lzf' && function_exists('lzf_decompress')){
				return lzf_decompress($data);
			}
			elseif($type == 'gz' && function_exists('gzinflate')){
				return gzinflate($data);
			}
			
			return $data;
		}
		
		/**
			* Return the filtered cache version
			* 
			* @param mixed $version Version of the cache.
			* @return string
		*/
		private function GetVersion($version){
			$version = !is_null($version) ? $version : $this->cfg['version'];
			return !is_null($version) ? $this->Filter($version).self::DS : '';
		}
	}						