<?php
/**
 * LzpCache v1.2.1 - Requer PHP >= 5.5
 *
 * @author André Posso <andre.posso@lzp.com>
 * @copyright 2016 Lzp Tec
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
	namespace Lzp;
/*
*
*
* Recursos:
* - Compressão do cache
* - Nome personalizado para o cache/Hash de escolha do usuário
* - Extensão do arquivo cache personalizada
* - Criar/Obter/Deletar vários caches de uma vez
* - É possível obter um cache mesmo que ele tenha expirado
*
*
* Recursos ainda não implementados:
* - Argumento opcional($cacheVersion) adicionado nas Funções(GetMultiples, CreateMultiples, DeleteMultiples, ExistsMultiples)
* - Configuração personalizada para criar o cache($cache->Create($name, $data, $version, $config)
*
*
* Recursos que talvez sejam introduzidos:
* - Suporte para MemCache e MemCached.
*
*
* Como Usar:
* -Configurar:
* 	$config = array('dir', 'expire', 'version', 'compress', 'cacheNameType', 'ext');
* 	Parametros( = Padrão):
* 		$config['dir'] = (string)__DIR__.'/cache/' - Valores Aceitos(string) - (opcional)
* 		$config['expire'] = (int)600 - 0 para infinito - Valores Aceitos(int) - (opcional)
*		$config['version'] = false - Versão do cache a ser verificado - Valores Aceitos(float, string, int) - (opcional)
* 		$config['compress'] = (int)0 - Valores Aceitos(int) - (opcional)
* 		$config['cacheNameType'] = array('hash' => 'md5', 'prefix' => '%name%_') - Use %name% para colocar o nome do cache no prefixo  - (opcional)
* 		$config['ext'] = (string)'.cache'  - (opcional)
*
* 	Aplicar Configuração(Inicialização):
* 		$cache = new Lzp\Cache($config);
*
* 	Aplicar Configuração(Modificar depois de inicializado):
* 		$cache = new Lzp\Cache;
* 		$cache->Config($config);
*
*
* -Verificação da existência ou não do(s) cache(s):
*	Verificação única(Retorna true se o cache existir)
* 		$cache = new Lzp\Cache;
* 		$cache->Exists($cacheName, $cacheVersion);
* 		Parametros( = Padrão):
* 			$cacheName - Nome do cache(Parametro obrigatório)
* 			$cacheVersion = false - Versão do cache a ser verificado - Valores Aceitos(float, string, int) - (opcional)
*
* 	Verificação multipla(Retorna um array($nomecache=>$exists)):
* 		$cache = new Lzp\Cache;
* 		$cache->ExistsMultiples(array('nome_do_cache00', 'nome_do_cache01'));
*
*
* -Obtenção do(s) cache(s):
* 	Obter um único cache(Retorna null caso o cache não exista):
* 		$cache = new Lzp\Cache;
* 		$cache->Get($cacheName, $getExpired, $cacheVersion);
* 		Parametros( = Padrão):
* 			$cacheName - Nome do cache(Parametro obrigatório)
* 			$getExpired = false - Ignora se o cache já expirou(opcional)
* 			$cacheVersion = false - Versão do cache a ser obtido - Valores Aceitos(float, string, int) - (opcional)
*
* 	Obter Múltiplos caches(Retorna um array($nomecache=>$value)):
* 		$cache = new Lzp\Cache;
* 		$cache->GetMultiples($cachesNames, $getExpired);
* 		Parametros( = Padrão):
* 			$cachesNames - Array contendo os Nomes dos caches(Parametro obrigatório)
* 			$getExpired = false - Ignora se o cache já expirou(opcional)
*
*
* -Criação do(s) cache(s):
* 	Criar um único cache(Retorna true se o cache for criado)
* 		$cache = new Lzp\Cache;
* 		$cache->Create($cacheName, $data, $cacheVersion, $config);
* 		Parametros( = Padrão):
* 			$cacheName - Nome do cache(Parametro obrigatório)
* 			$data - Valor do cache, tudo é aceito(Parametro obrigatório)
* 			$cacheVersion = false - Versão do cache a ser criado - Valores Aceitos(float, string, int) - (opcional)
* 			$config = false - Configurações para o cache Aceito: array('expire', 'compress')
*
* 	Criar Múltiplos caches(Retorna um array($nomecache=>$foiCriado) se o cache foi criado com sucesso $foiCriado recebe true)
* 		$cache = new Lzp\Cache;
* 		$cache->CreateMultiples($namesAndValues);
* 		Parametros:
* 		$namesAndValues = Array('nomeCache' => 'esse é o valor') - Array contendo os Nomes e os valores dos caches a serem criados(Parametro obrigatório)
*
*
* -Exclusão do(s) cache(s):
* 	Deletar um único cache(Retorna true em caso de sucesso, false em caso de falha e null caso o cache não exista)
* 		$cache = new Lzp\Cache;
* 		$cache->Delete($cacheName, $cacheVersion);
* 		Parametros( = Padrão):
* 			$cacheName - Nome do cache(Parametro obrigatório)
* 			$cacheVersion = false - Versão do cache a ser deletado - Valores Aceitos(float, string, int) - (opcional)
*
* 	Deletar Múltiplos caches(Retorna um array($nomecache=>$foiDeletado)), $foiDeletado = true(sucesso), false(falha) ou null(cache não existe)
* 		$cache = new Lzp\Cache;
* 		$cache->DeleteMultiples($cachesNames);
* 		Parametros:
* 		$cachesNames - Array contendo os Nomes dos caches(Parametro obrigatório)
*
* 	Para deletar todos os caches(Retorna true se os caches forem excluídos)
* 		$cache = new Lzp\Cache;
* 		$cache->DeleteAll($version);
* 		Parametros:
* 		$version - Deleta os caches de uma certa versão(Parametro opicional)
*
*
*
*
*** ChangeLog ***
#####################################################################
# V 1.2.1															#
# -Argumento opcional adicionado nas Configurações('version')		#
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
# -Novo argumento opcional($cacheVersion)							#
# -Melhor documentação												#
# -Performance Otimizada											#
# -Nova função(cacheDirSize)										#
#####################################################################
# V 1.0.0															#
# -Lançamento do código para uso livre(MIT License)					#
#####################################################################
*/
class Cache{
	#######
	# CFG #
	#######
	protected $cfg;

	#############
	# CONSTRUCT #
	#############
	function __construct($config = false){
		$configDefault = array('dir' => __DIR__.'/cache/', 'expire' => 600, 'compress' => 0, 'cacheNameType' => array('hash' => 'md5', 'prefix' => '%name%_'), 'ext' => '.cache');
		if(is_array($config)){
			$this->cfg = array_replace($configDefault, $config);
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
	private function Compress($data, $compress){return (function_exists('gzdeflate') && function_exists('gzinflate'))?gzdeflate($data, $compress):$data;}
	private function Uncompress($data){return (function_exists('gzinflate'))?gzinflate($data):$data;}
	private function ValidateVersion($version){if($version != false && $version != '' && (is_string($version) || is_numeric($version)))return true; return false;}

	##########
	# EXISTS #
	##########
	public function Exists($name, $version=false){
		$version = $this->ValidateVersion($version)?$this->Filter($version).DIRECTORY_SEPARATOR:($this->ValidateVersion($this->cfg['version'])?$this->Filter($this->cfg['version']).DIRECTORY_SEPARATOR:'');
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

	##########
	# CREATE #
	##########
	public function Create($name, $data, $version=false, $config=false){
		if(!is_writeable($this->cfg['dir']))die('Direrório não diponível ou sem permissão para escrita');
		$name = $this->Name($name);
		$data = $this->Encode($data);
		$version = $this->ValidateVersion($version)?$this->Filter($version).DIRECTORY_SEPARATOR:($this->ValidateVersion($this->cfg['version'])?$this->Filter($this->cfg['version']).DIRECTORY_SEPARATOR:'');
		$path = $this->cfg['dir'].$version;

		$expire = isset($config['expire'])?$config['expire']:$this->cfg['expire'];
		$expire = ($expire!=0)?(time() + (Int)$expire):0;
		$compress = isset($config['compress'])?$config['compress']:$this->cfg['compress'];
		$compress = ($compress > 0 && $compress <= 9)?$compress:0;

		$cacheData = array(
			'compress' => $compress,
			'expire' => $expire,
			'data' => ($compress > 0)?$this->Compress($data, $compress):$data
		);

		$cacheData = $this->Encode($cacheData);

		if(!is_dir($path)){mkdir($path, 0775, true);}

		return file_put_contents($path.$name.$this->cfg['ext'], $cacheData);
	}

	####################
	# CREATE MULTIPLES #
	####################
	public function CreateMultiples($values){
		if(!is_writeable($this->cfg['dir']))die('Direrório não diponível ou sem permissão para escrita');
		$compress = ($this->cfg['compress'] > 0 && $this->cfg['compress'] <= 9)?$this->cfg['compress']:0;
		$expire = ($this->cfg['expire']!=0)?(time() + (Int)$this->cfg['expire']):0;

		foreach($values as $name=>$data){
			$name = $this->Name($name);
			$data = $this->Encode($data);

			$cacheData = array(
				'compress' => $compress,
				'expire' => $expire,
				'data' => ($compress > 0)?$this->Compress($data, $compress):$data
			);

			$cacheData = $this->Encode($cacheData);
			$values[$name] = file_put_contents($this->cfg['dir'].$name.$this->cfg['ext'], $cacheData);
		}
		return $values;
	}

	#######
	# GET #
	#######
	public function Get($name, $expired=false, $version=false){
		if(!is_readable($this->cfg['dir']))die('Direrório não diponível ou sem permissão para leitura');
		$name = $this->Name($name);
		$version = $this->ValidateVersion($version)?$this->Filter($version).DIRECTORY_SEPARATOR:($this->ValidateVersion($this->cfg['version'])?$this->Filter($this->cfg['version']).DIRECTORY_SEPARATOR:'');
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
		if(!is_readable($this->cfg['dir']))die('Direrório não diponível ou sem permissão para leitura');
		foreach($names as $name){
			$data[$name] = $this->Get($name, $expired);
		}
		return $data;
	}

	##########
	# DELETE #
	##########
	public function Delete($name, $version=false){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))return;
		$version = $this->ValidateVersion($version)?$this->Filter($version).DIRECTORY_SEPARATOR:($this->ValidateVersion($this->cfg['version'])?$this->Filter($this->cfg['version']).DIRECTORY_SEPARATOR:'');
		$name = $this->Name($name);
		$file = $this->cfg['dir'].$version.$name.$this->cfg['ext'];

		if(is_file($file)){return @unlink($file);}else{return null;}
		return false;
	}

	####################
	# DELETE MULTIPLES #
	####################
	public function DeleteMultiples($names){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))return;
		$del = array();
		foreach($names as $name){
			$del[$name] = $this->Delete($name);
		}
		return $del;
	}

	##############
	# DELETE ALL #
	##############
	public function DeleteAll($version=false){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))return;
		$version = $this->ValidateVersion($version)?$this->Filter($version).DIRECTORY_SEPARATOR:($this->ValidateVersion($this->cfg['version'])?$this->Filter($this->cfg['version']).DIRECTORY_SEPARATOR:'');
		$del = array();
		$files = glob($this->cfg['dir'].$version.'/*', GLOB_NOSORT);
		foreach($files as $file){
		  if(is_file($file)){$del[] = @unlink($file);}
		}
		return !in_array(false, $del);
	}

	##################
	# CACHE DIR SIZE #
	##################
	function cacheDirSize($dir=null){
		$dir = ($dir!=null)?$dir:$this->cfg['dir'];
		if(!is_readable($dir))die('Direrório não diponível ou sem permissão para leitura');
		$size = 0;
		$extSize = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];
		$files = glob(rtrim($dir, '/').'/*', GLOB_NOSORT);
		foreach($files as $file){
			$size += is_file($file) ? filesize($file) : cacheDirSize($file);
		}

		return ($size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $extSize[$i] : 'Vazio');
	}
}