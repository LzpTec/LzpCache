<?php
/**
	* LzpCache v1.5 - Requer PHP >= 5.5
	*
	* @author André Posso <admin@lzptec.com>
	* @copyright 2017 Lzp Tec
	* @license http://www.opensource.org/licenses/mit-license.php MIT License
*/
namespace Lzp
{
	class Cache
	{
		#######
		# CFG #
		#######
		protected $cfg;
		
		#############
		# CONSTRUCT #
		#############
		public function __construct($config = false)
		{
			$configDefault = array(
				'dir' => __DIR__.'/cache/',
				'expire' => 600, 'version' => null,
				'compress' => 0,
				'namePrefix' => '%name%_',
				'storageHash' => 'md5',
				'ext' => '.lzp'
			);
			if(is_array($config))
				$this->cfg = array_merge($configDefault, $config);
			if(!is_dir($this->cfg['dir']))
				mkdir($this->cfg['dir'], 0777, true);
		}

		##########
		# CONFIG #
		##########
		public function Config($config)
		{
			if(is_array($config))
			{
				$this->cfg = array_merge($this->cfg, $config);
				if(!is_dir($this->cfg['dir']))
					mkdir($this->cfg['dir'], 0777, true);
			}
		}
		
		##########
		# EXISTS #
		##########
		public function Exists($name, $version=false)
		{
			$file = $this->cfg['dir'].$this->GetVersion($version).$this->Name($name).$this->cfg['ext'];
			return (is_file($file) && is_readable($file));
		}
		
		####################
		# EXISTS MULTIPLES #
		####################
		public function ExistsMultiples($names, $version=false)
		{
			$version = $this->GetVersion($version);
			foreach($names as $name){
				$file = $this->cfg['dir'].$version.$this->Name($name).$this->cfg['ext'];
				$exists[$name] = (is_file($file) && is_readable($file));
			}
			return $exists;
		}
		
		##########
		# CREATE #
		##########
		public function Create($name, $data, $version=false, $config=false)
		{
			if(!is_writeable($this->cfg['dir']))
				die('Direrório não diponível ou sem permissão para escrita');
			
			$name = $this->Name($name);
			$data = $this->Encode($data);
			$version = $this->GetVersion($version);
			$path = $this->cfg['dir'].$version;
			
			$expire = isset($config['expire'])?$config['expire']:$this->cfg['expire'];
			$expire = ($expire!=0)?(time() + (Int)$expire):0;
			$compress = isset($config['compress'])?$config['compress']:$this->cfg['compress'];
			$compress = ($compress > 0 && $compress < 10)?$compress:0;
			
			$cacheData = array(
				'compress' => $compress,
				'expire' => $expire,
				'data' => ($compress > 0)?$this->Compress($data, $compress):$data
			);
			
			$cacheData = $this->Encode($cacheData);
			
			if(!is_dir($path))
				mkdir($path, 0775, true);
			
			return file_put_contents($path.$name.$this->cfg['ext'], $cacheData);
		}
		
		####################
		# CREATE MULTIPLES #
		####################
		public function CreateMultiples($values, $version=false)
		{
			if(!is_writeable($this->cfg['dir']))
				die('Direrório não diponível ou sem permissão para escrita');
			
			$compress = ($this->cfg['compress'] > 0 && $this->cfg['compress'] < 10) ? $this->cfg['compress'] : 0;
			$expire = ($this->cfg['expire']!=0) ? (time() + (Int)$this->cfg['expire']) : 0;
			$version = $this->GetVersion($version);
			
			foreach($values as $name=>$data){
				$name = $this->Name($name);
				$data = $this->Encode($data);
				
				$cacheData = array(
				'compress' => $compress,
				'expire' => $expire,
				'data' => ($compress > 0)?$this->Compress($data, $compress):$data
				);
				
				$cacheData = $this->Encode($cacheData);
				$values[$name] = file_put_contents($this->cfg['dir'].$version.$name.$this->cfg['ext'], $cacheData);
			}
			return $values;
		}
		
		#######
		# GET #
		#######
		public function Get($name, $expired=false, $version=false)
		{
			if(!is_readable($this->cfg['dir']))
				die('Direrório não diponível ou sem permissão para leitura');
			
			$name = $this->Name($name);
			$version = $this->GetVersion($version);
			$file = $this->cfg['dir'].$version.$name.$this->cfg['ext'];
			
			if(is_readable($file))
			{
				$cacheData = $this->Decode(file_get_contents($file));
				if($cacheData['expire'] == 0 || time() <= $cacheData['expire'] || $expired===true)
				{
					$data = $cacheData['data'];
					$data = ($cacheData['compress'] > 0)?$this->Uncompress($data):$data;
					$data = $this->Decode($data);
					
					return $data;
				}
			}
			return null;
		}
		
		#################
		# GET MULTIPLES #
		#################
		public function GetMultiples($names, $expired=false, $version=false)
		{
			if(!is_readable($this->cfg['dir']))
				die('Direrório não diponível ou sem permissão para leitura');
			
			foreach($names as $name)
			{
				$data[$name] = $this->Get($name, $expired, $version);
			}
			return $data;
		}
		
		##########
		# DELETE #
		##########
		public function Delete($name, $version=false)
		{
			if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))
				return;
			
			$name = $this->Name($name);
			$version = $this->GetVersion($version);
			$file = $this->cfg['dir'].$version.$name.$this->cfg['ext'];
			
			if(is_file($file))
				return unlink($file);
			else
				return null;
			
			return false;
		}
		
		####################
		# DELETE MULTIPLES #
		####################
		public function DeleteMultiples($names, $version = false)
		{
			if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))
				return;
			
			$del = array();
			foreach($names as $name)
			{
				$del[$name] = $this->Delete($name, $version);
			}
			return $del;
		}
		
		##############
		# DELETE ALL #
		##############
		public function DeleteAll($version=false)
		{
			if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))
				return;
			
			$del = array();
			$files = glob($this->cfg['dir'].$this->GetVersion($version).'/*', GLOB_NOSORT);
			foreach($files as $file)
			{
				if(is_file($file)){$del[] = @unlink($file);}
			}
			return !in_array(false, $del);
		}
		
		##################
		# CACHE DIR SIZE #
		##################
		public function DirSize($dir=null, $version=false)
		{
			$dir = (($dir!=null)?$dir:$this->cfg['dir']).$this->GetVersion($version);
			if(!is_readable($dir))
				die('Direrório não diponível ou sem permissão para leitura');
			
			$size = 0;
			$extSize = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];
			$files = glob(rtrim($dir, '/').'/*', GLOB_NOSORT);
			foreach($files as $file)
			{
				$size += is_file($file) ? filesize($file) : $this->DirSize($file, $version);
			}
			
			return ($size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $extSize[$i] : 'Vazio');
		}
		
		##########
		# Filter #
		##########
		private function Filter($name)
		{
			return preg_replace("/[^a-zA-Z0-9_.-]/", "", $name);
		}
		
		########
		# Name #
		########
		private function Name($name)
		{
			$newName = strtolower($name);
			$newName = $this->Filter($newName);
			
			if(strpos($this->cfg['namePrefix'], '%name%') !== false)
				$newName = str_replace('%name%', $newName, $this->cfg['namePrefix']);

			$newName .= hash($this->cfg['storageHash'], $name);
			return $newName;
		}
		
		##########
		# Encode #
		##########
		private function Encode($data)
		{
			return (is_array($data) || is_object($data))?serialize($data):$data;
		}
		
		##########
		# Decode #
		##########
		private function Decode($data)
		{
			$x = @unserialize($data);
			return ($x === 'b:0;' || $x !== false)?$x:$data;
		}
		
		############
		# Compress #
		############
		private function Compress($data, $compress)
		{
			return function_exists('gzdeflate') ? gzdeflate($data, $compress) : $data;
		}
		
		##############
		# Uncompress #
		##############
		private function Uncompress($data)
		{
			return function_exists('gzinflate') ? gzinflate($data) : $data;
		}
		
		##############
		# GetVersion #
		##############
		private function GetVersion($version)
		{
			return strlen($version) > 0 ? $this->Filter($version) : strlen($this->cfg['version']) > 0 ? $this->Filter($this->cfg['version']) : '';
		}
	}
}	