<?php
/**
 * LzpCache v2.0.0 - Requer PHP >= 5.5
 *
 * @author André Posso <andre.posso@lzptec.com>
 * @copyright 2016 Lzp Tec
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
	namespace Lzp;
/*
*
*
* Resources:
* - Cache Compression
* - Custom name for the cache and custom hash
* - Custom cache extension
* - Create/Get/Delete multiples caches
* - Ignore cache expires
*
*
* Resources that may be introduced:
* - Support for MemCached and MemCached.
* - Custom configuration to create the cache($cache->Create($name, $data, $version, $config))
*
*
****Como Usar****
*
* Inicializar:
*	//Without settings
*		$cache = new Lzp\Cache;
*	//With settings
*		$cache = new Lzp\Cache($config);
*
*
*
* Configuration:
*	//Configs
*		$config = array('dir', 'expire', 'version', 'compress', 'cacheNameType', 'ext');
*	//Parameters( = Default):
*		$config['dir'] = __DIR__.'/cache/'; 										//Directory where the cache is stored
*		$config['expire'] = 600; 													//0 to infinity - Values Accepted: int(Optional)
*		$config['version'] = null; 													//null to disable - Values Accepted: float, string and int(Optional)
*		$config['compress'] = 0;													//0 disable - Accepted Values: int 0-9(Optional)
*		$config['cacheNameType'] = array('hash' => 'md5', 'prefix' => '%name%_'); 	//Use %name% to put the name of the cache in the prefix(Optional)
*		$config['ext'] = '.lzp'; 													//Cache file extension(Optional)
*	//Apply Configuration:
*		$cache->Config($config);
*
*
*
* Para obter um único cache:
*	$cache->Get($cacheName, $getExpired, $cacheVersion);
*	//Parametros( = Padrão):
* 		$cacheName = 'cache_name'; 										//Cache name(Required)
* 		$getExpired = false;											//Ignores cache expiration(Optional)
* 		$cacheVersion = false;											//Cache version to be obtained - Values Accepted: float, string and int(Optional)
*
* Para obter múltiplos caches:
* 	$cache->Get($cachesNames, $getExpired, $cacheVersion);				//Returns an array($cacheName => $value)
* 	//Parametros( = Padrão):
*		$cachesNames = array('nome_do_cache00', 'nome_do_cache01');		//Array containing the name of each cache(Required)
*		$getExpired = false;											//Ignores cache expiration(Optional)
* 		$cacheVersion = false;											//Cache version to be obtained - Values Accepted: float, string and int(Optional)
*
*
*
* Para criar um cache:
* 	$cache->Create($cacheName, $data, $cacheVersion, $config); 								//Returns true if successful
*	//Parametros( = Padrão):
* 		$cacheName = 'nome_do_cache';														//Cache name(Required)
* 		$data = 'dadosDoCache';																//Cache data(Required)
* 		$cacheVersion = false;																//Cache version to be created - Values Accepted: float, string and int(Optional)
*		$config = false;																	//Settings for the cache: array('expire', 'compress')
*
* Para criar múltiplos caches:
*  	$cache->CreateMultiples($namesValues, $cacheVersion); 									//Returns an array($nomecache=>$isCreated)
*	//Parametros( = Padrão):
*		$namesValues = array('nome_do_cache00' => $value, 'nome_do_cache01' => $value); 	//Array containing the name of each cache to be created(Required)
*		$cacheVersion = false; 																//Cache version to be created - Values Accepted: float, string and int(Optional)
*
*
*
* Para deletar um cache
* 	$cache->Delete($cacheName, $cacheVersion); 							//Retorna true se o cache for excluido, false em caso de falha e null caso o cache não exista
* 	//Parametros( = Padrão):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Required)
* 		$cacheVersion = false; 											//Versão do cache a ser deletado - Valores Aceitos float, string e int(Optional)
*
* Para Deletar Múltiplos caches
* 	$cache->Delete($cachesNames, $cacheVersion); 						//Retorna um array($nomecache=>$foiDeletado), $foiDeletado = true(sucesso), false(falha) ou null(cache não existe)
* 	//Parametros( = Padrão):
* 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Required)
*		$cacheVersion = false; 											//Versão dos caches a serem deletados - Valores Aceitos float, string e int(Optional)
*
* Para deletar todos os caches
* 	$cache->DeleteAll($cacheVersion); 									//Retorna true se os caches forem excluídos
* 	//Parametros( = Padrão):
* 		$cacheVersion = false; 											//Deleta os caches de uma certa versão - Valores Aceitos float, string e int(Parametro opicional)
*
*
* Para verificar se um cache existe
* 	$cache->Exists($cacheName, $cacheVersion);							//Retorna true se o cache existe
* 	//Parametros( = Padrão):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Required)
*		$cacheVersion = false; 											//Versão a ser verificada - Valores Aceitos float, string e int(Optional)
*
* Para verificar se vários caches existem
* 	$cache->Exists($cachesNames, $cacheVersion);						//Retorna um array($nomecache=>$exists)
* 	//Parametros( = Padrão):
* 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Required)
*		$cacheVersion = false; 											//Versão a ser verificada - Valores Aceitos float, string e int(Optional)
*
*
*
* Para verificar o tamanho do diretório de cache
*	$cache->Size($dir, $version);
*	//Parametros( = Padrão):
*		$dir = null;								//Diretório a ser verificado(Optional)	
*		$cacheVersion = false; 						//Retorna o tamanho do cache de uma certa versão - Valores Aceitos float, string e int(Optional)
*
*
*
*** ChangeLog ***
#####################################################################
# V 2.0.0															#
# -Código Reescrito													#
# -Performance Otimizada											#
# -Removida Criptografia do cache									#
# -Documentação atualizada											#
# -Modificado DirSize -> Size										#
# -Função Unida ExistsMultiples -> Exists							#
# -Função Unida GetMultiples -> Get									#
# -Função Unida DeleteMultiples -> Delete							#
#####################################################################
# V 1.3.0															#
# -Performance Otimizada											#
# -Documentação atualizada											#
# -Criptografia do cache											#
# -Modificado CacheDirSize -> DirSize								#
# -Bugs na função DirSize corrigidos								#
# -Novo parametro para ExistsMultiples($version)					#
# -Novo parametro para CreateMultiples($version)					#
# -Novo parametro para GetMultiples($version)						#
# -Novo parametro para DeleteMultiples($version)					#
# -Novo parametro para DirSize($version)							#
# -Extensão padrão modificada para(.lzp)							#
#####################################################################
# V 1.2.1															#
# -Argumento Optional adicionado nas Configurações('version')		#
# -Performance Otimizada											#
#####################################################################
# V 1.2.0															#
# -Documentação atualizada											#
# -Performance Otimizada											#
# -Modificado LzpCache -> Cache e Lozep -> Lzp						#
# -Modificado new Lozep\LzpCache -> new Lzp\Cache					#
#####################################################################
# V 1.1.1															#
# -Performance Otimizada											#
# -Melhor Organização do código										#
# -Melhor documentação												#
# -Novo parametro para DeleteAll($version)							#
# -Novo parametro para Create($config)								#
# -Modificação nas funções(veja mais nos exemplos):					#
# DeleteMultiples -> Retorna um array($nomecache=>$foiDeletado)		#
# CreateMultiples -> Retorna um array($nomecache=>$foiCriado)		#
# Delete -> Retorna true(sucesso), false(falha) ou null(não existe)	#
#####################################################################
# V 1.1.0															#
# -Novo argumento Optional($cacheVersion)							#
# -Melhor documentação												#
# -Performance Otimizada											#
# -Nova função(cacheDirSize)										#
#####################################################################
# V 1.0.0															#
# -Lançamento do código para uso livre(MIT License)					#
#####################################################################
*/
class Cache{
	const VERSION = '2.0.0';
	const DS = DIRECTORY_SEPARATOR;
	
	#######
	# CFG #
	#######
	protected $cfg;

	#############
	# CONSTRUCT #
	#############
	function __construct($config = false){
		$configDefault = array(
			'dir' => (__DIR__).self::DS.'cache'.self::DS, 
			'expire' => 600, 
			'compress' => 0, 
			'memcache' => false,
			'version' => null, 
			'cacheNameType' => array('hash' => 'md5', 'prefix' => '%name%_'), 
			'ext' => '.lzp'
		);
		if(is_array($config))
			$this->cfg = array_replace($configDefault, $config);
		else
			$this->cfg = $configDefault;

		if(!is_dir($this->cfg['dir'])){
			mkdir($this->cfg['dir'], 0755, true);
		}
	}

	#################################################
	# CONFIG/NAME/ENCODE/DECODE/COMPRESS/UNCOMPRESS #
	#################################################
	public function Config($config){
		$this->cfg = array_replace($this->cfg, $config);
		if(!is_dir($this->cfg['dir'])){
			mkdir($this->cfg['dir'], 0755, true);
		}
	}

	private function Filter($name){
		return preg_replace("/[^a-zA-Z0-9_.-]/", "", $name);
	}

	private function Name($name){
		$name = $this->Filter(strtolower($name));
		$name = str_ireplace('%name%', $name, $this->cfg['cacheNameType']['prefix']).hash($this->cfg['cacheNameType']['hash'], $name);

		return $name;
	}

	private function Encode($data){
		return (is_array($data) || is_object($data)) ? serialize($data) : $data;
	}

	private function Decode($data){
		$x = @unserialize($data);
		return ($x === 'b:0;' || $x !== false) ? $x : $data;
	}

	private function Compress($data, $compressLevel){
		return (function_exists('gzdeflate') && function_exists('gzinflate')) ? gzdeflate($data, $compressLevel) : $data;
	}

	private function Uncompress($data){
		return function_exists('gzinflate') ? gzinflate($data) : $data;
	}

	private function GetVersion($version){
		$version = !is_null($version) ? $version : $this->cfg['version'];
		return !is_null($version) ? $this->Filter($version) : '';
	}

	##########
	# EXISTS #
	##########
	public function Exists($names, $version=null){
		$version = $this->GetVersion($version);

		if(is_array($names)){
			foreach($names as $name){
				$file = $this->cfg['dir'].$version.$this->Name($name).$this->cfg['ext'];
				$exists[$name] = (is_file($file) && is_readable($file));
			}
		}else{
			$file = $this->cfg['dir'].$this->GetVersion($version).$this->Name($names).$this->cfg['ext'];
			$exists = (is_file($file) && is_readable($file));
		}

		return $exists;
	}

	##########
	# CREATE #
	##########
	public function Create($name, $data, $version=null, $config=null){
		if(!is_writeable($this->cfg['dir'])){
			die('Direrório não diponível ou sem permissão para escrita');
		}
		$name = $this->Name($name);
		$data = $this->Encode($data);
		$version = $this->GetVersion($version);
		$path = $this->cfg['dir'].$version;

		$expire = isset($config['expire']) ? $config['expire'] : $this->cfg['expire'];
		$expire = ($expire!=0) ? (time() + (Int)$expire) : 0;
		$compress = isset($config['compress']) ? $config['compress'] : $this->cfg['compress'];
		$compress = ($compress > 0 && $compress < 10) ? $compress : 0;

		$cacheData = array(
			'compress' => $compress,
			'expire' => $expire,
			'data' => ($compress > 0) ? $this->Compress($data, $compress) : $data
		);

		$cacheData = $this->Encode($cacheData);

		if(!is_dir($path)){
			mkdir($path, 0775, true);
		}

		return file_put_contents($path.$name.$this->cfg['ext'], $cacheData);
	}

	####################
	# CREATE MULTIPLES #
	####################
	public function CreateMultiples($values, $version=null){
		if(!is_writeable($this->cfg['dir'])){
			die('Direrório não diponível ou sem permissão para escrita');
		}
		$compress = ($this->cfg['compress'] > 0 && $this->cfg['compress'] < 10) ? $this->cfg['compress'] : 0;
		$expire = ($this->cfg['expire']!=0) ? (time() + (Int)$this->cfg['expire']) : 0;
		$version = $this->GetVersion($version);

		foreach($values as $name=>$data){
			$name = $this->Name($name);
			$data = $this->Encode($data);

			$cacheData = array(
				'compress' => $compress,
				'expire' => $expire,
				'data' => ($compress > 0) ? $this->Compress($data, $compress) : $data
			);

			$cacheData = $this->Encode($cacheData);
			$values[$name] = file_put_contents($this->cfg['dir'].$version.$name.$this->cfg['ext'], $cacheData);
		}
		return $values;
	}

	#######
	# GET #
	#######
	public function Get($names, $expired=false, $version=null){
		$data = null;
		$version = $this->GetVersion($version);

		if(!is_readable($this->cfg['dir'])){
			die('Direrório não diponível ou sem permissão para leitura');
		}

		if(is_array($names)){
			foreach($names as $name){
				$file = $this->cfg['dir'].$version.$this->Name($name).$this->cfg['ext'];
				if(is_readable($file)){
					$file = file_get_contents($file);
					$cacheData = $this->Decode($file);
					if($cacheData['expire'] == 0 || time() <= $cacheData['expire'] || $expired===true){
						$data = $cacheData['data'];
						$data = ($cacheData['compress'] > 0) ? $this->Uncompress($data) : $data;
						$data[$name] = $this->Decode($data);
					}
				}
			}
		}else{
			$file = $this->cfg['dir'].$version.$this->Name($names).$this->cfg['ext'];

			if(is_readable($file)){
				$cacheData = $this->Decode(file_get_contents($file));
				if($cacheData['expire'] == 0 || time() <= $cacheData['expire'] || $expired===true){
					$data = $cacheData['data'];
					$data = ($cacheData['compress'] > 0) ? $this->Uncompress($data) : $data;
					$data = $this->Decode($data);
				}
			}
		}
		
		return $data;
	}

	##########
	# DELETE #
	##########
	public function Delete($names, $version=null){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))return;
		$del = null;
		$version = $this->GetVersion($version);

		if(is_array($names)){
			foreach($names as $name){
				$file = $this->cfg['dir'].$version.$this->Name($name).$this->cfg['ext'];
				$del[$name] = is_file($file) ? @unlink($file) : null;
			}
		}else{
			$file = $this->cfg['dir'].$version.$this->Name($name).$this->cfg['ext'];
			if(is_file($file)){
				$del = @unlink($file);
			}
		}
		return $del;
	}

	##############
	# DELETE ALL #
	##############
	public function DeleteAll($version=null){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))return;
		$del = null;

		$files = glob($this->cfg['dir'].$this->GetVersion($version).'/*', GLOB_NOSORT);
		foreach($files as $file){
			if(is_file($file)){
				$del[] = @unlink($file);
			}
		}
		return (!is_null($del) && !in_array(false, $del));
	}

	########
	# SIZE #
	########
	public function Size($dir=null, $version=null){
		$dir = (!is_null($dir) ? $dir : $this->cfg['dir']).$this->GetVersion($version);
		if(!is_readable($dir)){
			die('Direrório não diponível ou sem permissão para leitura');
		}
		$size = 0;
		$files = glob(rtrim($dir, '/').'/*', GLOB_NOSORT);
		foreach($files as $file){
			$size += is_file($file) ? filesize($file) : $this->Size($file, $version);
		}

		$extSize = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];

		return ($size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $extSize[$i] : 'Vazio');
	}
}