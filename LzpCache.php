<?php
/**
 * LzpCache v1.1.0 - Requer PHP >= 5.4
 *
 * @author Lozep Tecnologia <admin@lozep.com.br>
 * @copyright 2016 Lozep Tecnologia
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
	namespace Lozep;
/*
*
*
* Recursos:
* - Compress�o do cache
* - Nome personalizado para o cache/Hash de escolha do usu�rio
* - Extens�o do arquivo cache personalizada
* - Criar/Obter/Deletar v�rios caches de uma vez
* - � poss�vel obter um cache mesmo que ele tenha expirado
*
*
* Recursos ainda n�o implementados:
* - Argumento opcional($cacheVersion) adicionado nas Configura��es(version)
* - Argumento opcional($cacheVersion) adicionado nas Fun��es(GetMultiples, CreateMultiples, DeleteMultiples, ExistsMultiples)
* - Configura��o personalizada para criar o cache($cache->Create($name, $data, $version, $config)
*
*
* Recursos que talvez sejam introduzidos:
* - Suporte para MemCache e MemCached.
*
*
* Como Usar:
* -Configurar:
* 	$config = array('dir', 'expire', 'compress', 'cacheNameType', 'ext')
* 	Parametros( = Padr�o):
* 		$config['dir'] = (string)__DIR__.'/cache/'
* 		$config['expire'] = (int)600
* 		$config['compress'] = (int)0
* 		$config['cacheNameType'] = array('hash' => 'md5', 'prefix' => '%name%_') - Use %name% para colocar o nome do cache no prefixo
* 		$config['ext'] = (string)'.cache'
*
* 	Aplicar Configura��o:
* 		$cache = new Lozep\LzpCache($config);
* 		ou
* 		$cache = new Lozep\LzpCache;
* 		$cache->Config($config);
*
*
* -Verifica��o da exist�ncia ou n�o do(s) cache(s):
*	Verifica��o �nica(Retorna true se o cache existir)
* 		$cache = new Lozep\LzpCache;
* 		$cache->Exists($cacheName, $cacheVersion);
* 		Parametros( = Padr�o):
* 			$cacheName - Nome do cache(Parametro obrigat�rio)
* 			$cacheVersion = false - Vers�o do cache a ser verificado - Valores Aceitos(float, string, int) - (opcional)
*
* 	Verifica��o multipla(Retorna um array($nomecache=>$exists)):
* 		$cache = new Lozep\LzpCache;
* 		$cache->ExistsMultiples(array('nome_do_cache00', 'nome_do_cache01'));
*
*
* -Obten��o do(s) cache(s):
* 	Obter um �nico cache(Retorna null caso o cache n�o exista):
* 		$cache = new Lozep\LzpCache;
* 		$cache->Get($cacheName, $getExpired, $cacheVersion);
* 		Parametros( = Padr�o):
* 			$cacheName - Nome do cache(Parametro obrigat�rio)
* 			$getExpired = false - Ignora se o cache j� expirou(opcional)
* 			$cacheVersion = false - Vers�o do cache a ser obtido - Valores Aceitos(float, string, int) - (opcional)
*
* 	Obter M�ltiplos caches(Retorna um array($nomecache=>$value)):
* 		$cache = new Lozep\LzpCache;
* 		$cache->GetMultiples($cachesNames, $getExpired);
* 		Parametros( = Padr�o):
* 			$cachesNames - Array contendo os Nomes dos caches(Parametro obrigat�rio)
* 			$getExpired = false - Ignora se o cache j� expirou(opcional)
*
*
* -Cria��o do(s) cache(s):
* 	Criar um �nico cache(Retorna true se o cache for criado)
* 		$cache = new Lozep\LzpCache;
* 		$cache->Create($cacheName, $data, $cacheVersion);
* 		Parametros( = Padr�o):
* 			$cacheName - Nome do cache(Parametro obrigat�rio)
* 			$data - Valor do cache, tudo � aceito(Parametro obrigat�rio)
* 			$cacheVersion = false - Vers�o do cache a ser criado - Valores Aceitos(float, string, int) - (opcional)
*
* 	Criar M�ltiplos caches(Retorna true se os caches forem criados)
* 		$cache = new Lozep\LzpCache;
* 		$cache->CreateMultiples($namesAndValues);
* 		Parametros:
* 		$namesAndValues = Array('nomeCache' => 'esse � o valor') - Array contendo os Nomes e os valores dos caches a serem criados(Parametro obrigat�rio)
*
*
* -Exclus�o do(s) cache(s):
* 	Deletar um �nico cache(Retorna true se o cache for excluido)
* 		$cache = new Lozep\LzpCache;
* 		$cache->Delete($cacheName, $cacheVersion);
* 		Parametros( = Padr�o):
* 			$cacheName - Nome do cache(Parametro obrigat�rio)
* 			$cacheVersion = false - Vers�o do cache a ser deletado - Valores Aceitos(float, string, int) - (opcional)
*
* 	Deletar M�ltiplos caches(Retorna true se os caches forem excluidos)
* 		$cache = new Lozep\LzpCache;
* 		$cache->DeleteMultiples($cachesNames);
* 		Parametros:
* 		$cachesNames - Array contendo os Nomes dos caches(Parametro obrigat�rio)
*
* 	Para deletar todos os caches(Retorna true se os caches forem exclu�dos)
* 		$cache = new Lozep\LzpCache;
* 		$cache->DeleteAll();
*
*
*** ChangeLog *** 
#########################################################
# V 1.1													#
# -Novo argumento opcional($cacheVersion)				#
# -Melhor documenta��o									#
# -Performance Otimizada								#
# -Nova fun��o(cacheDirSize)							#
#########################################################
# V 1.0													#
# -Lan�amento do c�digo para uso livre(MIT License)		#
#########################################################
*/
class LzpCache{
	#######
	# CFG #
	#######
	protected $cfg = array('dir' => __DIR__.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR, 'expire' => 600, 'compress' => 0, 'cacheNameType' => array('hash' => 'md5', 'prefix' => '%name%_'), 'ext' => '.cache');

	#############
	# CONSTRUCT #
	#############
	function __construct($config = false){
		if(is_array($config)){
			$this->cfg = array_replace($this->cfg, $config);
		}
		if(!is_dir($this->cfg['dir'])){mkdir($this->cfg['dir'], 0755, true);}
	}

	#################################################
	# CONFIG/NAME/ENCODE/DECODE/COMPRESS/UNCOMPRESS #
	#################################################
	public  function Config($config){$this->cfg = array_replace($this->cfg, $config);}
	private function Filter($name){return preg_replace("/[^a-z0-9_.-]/", "", $name);}
	private function Name($name){$name = strtolower($name);$name = $this->Filter($name);$name = is_array($this->cfg['cacheNameType'])?str_ireplace('%name%', $name, $this->cfg['cacheNameType']['prefix']).hash($this->cfg['cacheNameType']['hash'], $name):false;return $name;}
    private function Encode($data){return (is_array($data) || is_object($data))?serialize($data):$data;}
    private function Decode($data){$x = @unserialize($data);return ($x === 'b:0;' || $x !== false)?$x:$data;}
	private function Compress($data){return (function_exists('gzdeflate') && function_exists('gzinflate'))?gzdeflate($data, $this->cfg['compress']):$data;}
	private function Uncompress($data){return (function_exists('gzinflate'))?gzinflate($data):$data;}

	##########
	# EXISTS #
	##########
	public function Exists($name, $version=false){
		$version = ($version != false && $version != '' && (is_string($version) || is_float($version) || is_int($version)))?$this->Filter($version).DIRECTORY_SEPARATOR:'';
		$file = $this->cfg['dir'].$version.$this->Name($name).$this->cfg['ext'];
		return (is_file($file) && is_readable($file));
	}

	####################
	# EXISTS MULTIPLES #
	####################
	public function ExistsMultiples($names){
		foreach($names as $name){
			$file = $this->cfg['dir'].$this->Name($name).$this->cfg['ext'];
			$exists[$name] = (is_file($file) && is_readable($file));
		}
		return $exists;
	}

	##################
	# CACHE DIR SIZE #
	##################
	function cacheDirSize(){
		$dir = $this->cfg['dir'];
		if(!is_readable($dir))die('Direr�rio n�o dipon�vel ou sem permiss�o para leitura');
		$size = 0;
		$extSize = [' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];
		$files = glob(rtrim($dir, '/').'/*', GLOB_NOSORT);
		foreach($files as $file){
			$size += is_file($file) ? filesize($file) : folderSize($file);
		}

		return ($size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $extSize[$i] : 'Vazio');
	}

	##########
	# CREATE #
	##########
	public function Create($name, $data, $version=false){
		if(!is_writeable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para escrita');
		$name = $this->Name($name);
		$data = $this->Encode($data);
		$path = $this->cfg['dir'].(($version != false && $version != '' && (is_string($version) || is_float($version) || is_int($version)))?$this->Filter($version).DIRECTORY_SEPARATOR:'');

		$cacheData = array(
			'compress' => $this->cfg['compress'],
			'expire' => ($this->cfg['expire']!=0)?(time() + (Int)$this->cfg['expire']):0,
			'data' => ($this->cfg['compress'] > 0 && $this->cfg['compress'] <= 9)?$this->Compress($data):$data
		);

		$cacheData = $this->Encode($cacheData);

		if(!is_dir($path)){mkdir($path, 0777, true);}
		$put = file_put_contents($path.$name.$this->cfg['ext'], $cacheData);

		return ($put!==false);
	}

	####################
	# CREATE MULTIPLES #
	####################
	public function CreateMultiples($values){
		if(!is_writeable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para escrita');
		$compress = ($this->cfg['compress'] > 0 && $this->cfg['compress'] <= 9)?$this->cfg['compress']:0;
		$expire = ($this->cfg['expire']!=0)?(time() + (Int)$this->cfg['expire']):0;

		foreach($values as $name=>$data){
			$name = $this->Name($name);
			$data = $this->Encode($data);

			$cacheData = array(
				'compress' => $compress,
				'expire' => $expire,
				'data' => ($compress > 0)?$this->Compress($data):$data
			);

			$cacheData = $this->Encode($cacheData);
			$values[$name] = file_put_contents($this->cfg['dir'].$name.$this->cfg['ext'], $cacheData);
		}
		return !in_array(false, $values);
	}

	#######
	# GET #
	#######
	public function Get($name, $expired=false, $version=false){
		if(!is_readable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para leitura');
		$name = $this->Name($name);
		$version = ($version != false && $version != '' && (is_string($version) || is_float($version) || is_int($version)))?$this->Filter($version).DIRECTORY_SEPARATOR:'';
		$file = $this->cfg['dir'].$version.$name.$this->cfg['ext'];

		if(is_readable($file)){
			$cacheData = $this->Decode(file_get_contents($file));
			if($cacheData['expire'] == 0 || time() <= $cacheData['expire'] || $expired===true){
				$data = $cacheData['data'];
				$data = ($cacheData['compress'] > 0)?$this->Uncompress($data):$data;
				return $this->Decode($data);
			}
		}
		return null;
	}

	#################
	# GET MULTIPLES #
	#################
	public function GetMultiples($names, $expired=false){
		if(!is_readable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para leitura');
		foreach($names as $name){
			$data[$name] = $this->Get($name, $expired);
		}
		return $data;
	}

	##########
	# DELETE #
	##########
	public function Delete($name, $version=false){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para escrita/leitura');
		$version = ($version != false && $version != '' && (is_string($version) || is_float($version) || is_int($version)))?$this->Filter($version).DIRECTORY_SEPARATOR:'';
		$name = $this->Name($name);
		$file = $this->cfg['dir'].$version.$name.$this->cfg['ext'];

		if(is_file($file)){return @unlink($file);}
		return false;
	}

	####################
	# DELETE MULTIPLES #
	####################
	public function DeleteMultiples($names){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para escrita/leitura');
		$del = array();
		foreach($names as $name){
			$del[] = $this->Delete($name);
		}
		return !in_array(false, $del);
	}

	##############
	# DELETE ALL #
	##############
	public function DeleteAll(){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para escrita/leitura');
		$del = array();
		$files = glob($this->cfg['dir'].'/*', GLOB_NOSORT);
		foreach($files as $file){
		  if(is_file($file)){$del[] = @unlink($file);}
		}
		return !in_array(false, $del);
	}
}