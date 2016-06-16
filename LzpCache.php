<?php
/**
 * LzpCache v1.3.0 - Requer PHP >= 5.5
 *
 * @author Andr� Posso <andre.posso@lzptec.com>
 * @copyright 2016 Lzp Tec
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
	namespace Lzp;
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
* - Configura��o personalizada para criar o cache($cache->Create($name, $data, $version, $config))
*
*
* Recursos que talvez sejam introduzidos:
* - Suporte para MemCache e MemCached.
*
*
****Como Usar****
*
* Inicializar:
*	//Sem configura��es
*		$cache = new Lzp\Cache;
*	//Com configura��es
*		$cache = new Lzp\Cache($config);
*
*
*
* Configurar:
*	//Configura��es
*		$config = array('dir', 'expire', 'version', 'compress', 'cacheNameType', 'ext');
*	//Parametros( = Padr�o):
*		$config['dir'] = __DIR__.'/cache/'; 										//Caminho do Diret�rio onde o cache ser� armazenado
*		$config['expire'] = 600; 													//0 para infinito - Valor Aceito int(opcional)
*		$config['version'] = false; 												//false ou 0 desativam - Valores Aceitos float, string e int(opcional)
*		$config['compress'] = 0;													//0 desativa - Valor Aceito int de 0 a 9(opcional)
*		$config['cacheNameType'] = array('hash' => 'md5', 'prefix' => '%name%_'); 	//Use %name% para colocar o nome do cache no prefixo(opcional)
*		$config['ext'] = '.lzp'; 													//Extens�o do arquivo de cache(opcional)
*		$config['crypt'] = false; 													//false desativa, adicione uma Chave de 64 digitos hexadecimal para ativar
*	//Aplicar Configura��o:
*		$cache->Config($config);
*
*
*
* Para obter um �nico cache:
*	$cache->Get($cacheName, $getExpired, $cacheVersion);
*	//Parametros( = Padr�o):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Parametro obrigat�rio)
* 		$getExpired = false;											//Ignora se o cache j� expirou(opcional)
* 		$cacheVersion = false;											//Vers�o do cache a ser obtido - Valores Aceitos float, string e int(opcional)
*
* Para obter m�ltiplos caches:
* 	$cache->GetMultiples($cachesNames, $getExpired, $cacheVersion);		//Retorna um array($nomeDoCache=>$valor)
* 	//Parametros( = Padr�o):
*		$cachesNames = array('nome_do_cache00', 'nome_do_cache01');		//Array contendo o Nome de cada cache(Parametro obrigat�rio)
*		$getExpired = false;											//Ignora se o cache j� expirou(opcional)
* 		$cacheVersion = false;											//Vers�o do cache a ser obtido - Valores Aceitos float, string e int(opcional)
*
*
*
* Para criar um cache:
* 	$cache->Create($cacheName, $data, $cacheVersion, $config); 								//Retorna true em caso de sucesso
*	//Parametros( = Padr�o):
* 		$cacheName = 'nome_do_cache';														//Nome do cache(Parametro obrigat�rio)
* 		$data = 'dadosDoCache';																//Dados a serem guardados no cache, tudo � aceito(Parametro obrigat�rio)
* 		$cacheVersion = false;																//Vers�o do cache a ser criado - Valores Aceitos(float, string, int) - (opcional)
*		$config = false;																	//Configura��es para o cache Aceito: array('expire', 'compress')
*
* Para criar m�ltiplos caches:
*  	$cache->CreateMultiples($namesValues, $cacheVersion); 									//Retorna um array($nomecache=>$foiCriado) se o cache foi criado com sucesso $foiCriado recebe true
*	//Parametros( = Padr�o):
*		$namesValues = array('nome_do_cache00' => $value, 'nome_do_cache01' => $value); 	//Array contendo os Nomes e os valores dos caches a serem criados(Parametro obrigat�rio)
*		$cacheVersion = false; 																//Vers�o dos caches a serem criados - Valores Aceitos float, string e int(opcional)
*
*
*
* Para deletar um cache
* 	$cache->Delete($cacheName, $cacheVersion); 							//Retorna true se o cache for excluido, false em caso de falha e null caso o cache n�o exista
* 	//Parametros( = Padr�o):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Parametro obrigat�rio)
* 		$cacheVersion = false; 											//Vers�o do cache a ser deletado - Valores Aceitos float, string e int(opcional)
*
* Para Deletar M�ltiplos caches
* 	$cache->DeleteMultiples($cachesNames, $cacheVersion); 				//Retorna um array($nomecache=>$foiDeletado), $foiDeletado = true(sucesso), false(falha) ou null(cache n�o existe)
* 	//Parametros( = Padr�o):
* 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Parametro obrigat�rio)
*		$cacheVersion = false; 											//Vers�o dos caches a serem deletados - Valores Aceitos float, string e int(opcional)
*
* Para deletar todos os caches
* 	$cache->DeleteAll($cacheVersion); 									//Retorna true se os caches forem exclu�dos
* 	//Parametros( = Padr�o):
* 		$cacheVersion = false; 											//Deleta os caches de uma certa vers�o - Valores Aceitos float, string e int(Parametro opicional)
*
*
*
* Para verificar se um cache existe
* 	$cache->Exists($cacheName, $cacheVersion);
* 	//Parametros( = Padr�o):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Parametro obrigat�rio)
*		$cacheVersion = false; 											//Vers�o a ser verificada - Valores Aceitos float, string e int(opcional)
*
* Para verificar se v�rios caches existem
* 	$cache->ExistsMultiples($cachesNames, $cacheVersion);
* 	//Parametros( = Padr�o):
* 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Parametro obrigat�rio)
*		$cacheVersion = false; 											//Vers�o a ser verificada - Valores Aceitos float, string e int(opcional)
*
*
* Para verificar o tamanho do diret�rio de cache
*	$cache->DirSize($dir, $version);
*	//Parametros( = Padr�o):
*		$dir = null;								//Diret�rio a ser verificado(opcional)	
*		$cacheVersion = false; 						//Retorna o tamanho do cache de uma certa vers�o - Valores Aceitos float, string e int(opcional)
*
*
*
*** ChangeLog ***
#####################################################################
# V 1.3.0															#
# -Performance Otimizada											#
# -Documenta��o atualizada											#
# -Criptografia do cache											#
# -Modificado CacheDirSize -> DirSize								#
# -Bugs na fun��o DirSize corrigidos								#
# -Novo parametro para ExistsMultiples($version)					#
# -Novo parametro para CreateMultiples($version)					#
# -Novo parametro para GetMultiples($version)						#
# -Novo parametro para DeleteMultiples($version)					#
# -Novo parametro para DirSize($version)							#
# -Extens�o padr�o modificada para(.lzp)							#
#####################################################################
# V 1.2.1															#
# -Argumento opcional adicionado nas Configura��es('version')		#
# -Performance Otimizada											#
#####################################################################
# V 1.2.0															#
# -Documenta��o atualizada											#
# -Performance Otimizada											#
# -Modificado LzpCache -> Cache e Lozep -> Lzp						#
# -Modificado new Lozep\LzpCache -> new Lzp\Cache					#
#####################################################################
# V 1.1.1															#
# -Performance Otimizada											#
# -Melhor Organiza��o do c�digo										#
# -Melhor documenta��o												#
# -Novo parametro para DeleteAll($version)							#
# -Novo parametro para Create($config)								#
# -Modifica��o nas fun��es(veja mais nos exemplos):					#
# DeleteMultiples -> Retorna um array($nomecache=>$foiDeletado)		#
# CreateMultiples -> Retorna um array($nomecache=>$foiCriado)		#
# Delete -> Retorna true(sucesso), false(falha) ou null(n�o existe)	#
#####################################################################
# V 1.1.0															#
# -Novo argumento opcional($cacheVersion)							#
# -Melhor documenta��o												#
# -Performance Otimizada											#
# -Nova fun��o(cacheDirSize)										#
#####################################################################
# V 1.0.0															#
# -Lan�amento do c�digo para uso livre(MIT License)					#
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
		$configDefault = array('dir' => __DIR__.'/cache/', 'expire' => 600, 'version' => false, 'compress' => 0, 'cacheNameType' => array('hash' => 'md5', 'prefix' => '%name%_'), 'ext' => '.lzp', 'crypt' => false);
		if(is_array($config))$this->cfg = array_replace($configDefault, $config);
		if(!is_dir($this->cfg['dir']))mkdir($this->cfg['dir'], 0755, true);
	}

	#################################################
	# CONFIG/NAME/ENCODE/DECODE/COMPRESS/UNCOMPRESS #
	#################################################
	public  function Config($config){if(is_array($config)){$this->cfg = array_replace($this->cfg, $config);if(!is_dir($this->cfg['dir']))mkdir($this->cfg['dir'], 0755, true);}}
	private function Filter($name){return preg_replace("/[^a-zA-Z0-9_.-]/", "", $name);}
	private function Name($name){$name = strtolower($name);$name = $this->Filter($name);$name = is_array($this->cfg['cacheNameType'])?str_ireplace('%name%', $name, $this->cfg['cacheNameType']['prefix']).hash($this->cfg['cacheNameType']['hash'], $name):false;return $name;}
    private function Encode($data){return (is_array($data) || is_object($data))?serialize($data):$data;}
    private function Decode($data){$x = @unserialize($data);return ($x === 'b:0;' || $x !== false)?$x:$data;}
	private function Compress($data, $compress){return (function_exists('gzdeflate') && function_exists('gzinflate'))?gzdeflate($data, $compress):$data;}
	private function Uncompress($data){return (function_exists('gzinflate'))?gzinflate($data):$data;}
	private function GetVersion($version){return (!empty($version))?$this->Filter($version):(!empty($this->cfg['version']))?$this->Filter($this->cfg['version']):'';}
	private function Encrypt($encrypt){$encrypt=serialize($encrypt);$iv=mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TWOFISH,MCRYPT_MODE_CBC),MCRYPT_DEV_URANDOM);$key=pack('H*',$this->cfg['cryptkey']);$mac=hash_hmac('sha256',$encrypt,substr(bin2hex($key),-32));$passcrypt=mcrypt_encrypt(MCRYPT_TWOFISH,$key,$encrypt.$mac,MCRYPT_MODE_CBC,$iv);$encoded=base64_encode($passcrypt).'!'.base64_encode($iv);return  $encoded;}
	private function Decrypt($decrypt){$decrypt=explode('!',$decrypt.'!');$decoded=base64_decode($decrypt[0]);$iv=base64_decode($decrypt[1]);if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_TWOFISH,MCRYPT_MODE_CBC))return false;$key=pack('H*',$this->cfg['cryptkey']);$decrypted=trim(mcrypt_decrypt(MCRYPT_TWOFISH,$key,$decoded,MCRYPT_MODE_CBC, $iv));$mac=substr($decrypted,-64);$decrypted=substr($decrypted,0,-64);$calcmac=hash_hmac('sha256',$decrypted,substr(bin2hex($key), -32));if($calcmac!==$mac)return false;return unserialize($decrypted);}

	##########
	# EXISTS #
	##########
	public function Exists($name, $version=false){
		$file = $this->cfg['dir'].$this->GetVersion($version).$this->Name($name).$this->cfg['ext'];
		return (is_file($file) && is_readable($file));
	}

	####################
	# EXISTS MULTIPLES #
	####################
	public function ExistsMultiples($names, $version=false){
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
	public function Create($name, $data, $version=false, $config=false){
		if(!is_writeable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para escrita');
		$name = $this->Name($name);
		$data = $this->Encode($data);
		$version = $this->GetVersion($version);
		$path = $this->cfg['dir'].$version;

		$crypt = false;
		/*if($this->cfg['crypt']!==false && ctype_xdigit($this->cfg['crypt']) && isset($this->cfg['crypt'][63])){$data = $this->Encrypt($data);$crypt = true;}*/

		$expire = isset($config['expire'])?$config['expire']:$this->cfg['expire'];
		$expire = ($expire!=0)?(time() + (Int)$expire):0;
		$compress = isset($config['compress'])?$config['compress']:$this->cfg['compress'];
		$compress = ($compress > 0 && $compress < 10)?$compress:0;

		$cacheData = array(
			'compress' => $compress,
			'expire' => $expire,
			'crypt' => $crypt,
			'data' => ($compress > 0)?$this->Compress($data, $compress):$data
		);

		$cacheData = $this->Encode($cacheData);

		if(!is_dir($path)){mkdir($path, 0775, true);}

		return file_put_contents($path.$name.$this->cfg['ext'], $cacheData);
	}

	####################
	# CREATE MULTIPLES #
	####################
	public function CreateMultiples($values, $version=false){
		if(!is_writeable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para escrita');
		$compress = ($this->cfg['compress'] > 0 && $this->cfg['compress'] < 10)?$this->cfg['compress']:0;
		$expire = ($this->cfg['expire']!=0)?(time() + (Int)$this->cfg['expire']):0;
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
	public function Get($name, $expired=false, $version=false){
		if(!is_readable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para leitura');
		$name = $this->Name($name);
		$version = $this->GetVersion($version);
		$file = $this->cfg['dir'].$version.$name.$this->cfg['ext'];

		if(is_readable($file)){
			$cacheData = $this->Decode(file_get_contents($file));
			if($cacheData['expire'] == 0 || time() <= $cacheData['expire'] || $expired===true){
				$data = $cacheData['data'];
				$data = ($cacheData['compress'] > 0)?$this->Uncompress($data):$data;
				$data = $this->Decode($data);
				
				/*if(isset($cacheData['crypt']) && $cacheData['crypt'] && ctype_xdigit($this->cfg['crypt']) && isset($this->cfg['crypt'][63]))$data = $this->Decrypt($data);*/

				return $data;
			}
		}
		return null;
	}

	#################
	# GET MULTIPLES #
	#################
	public function GetMultiples($names, $expired=false, $version=false){
		if(!is_readable($this->cfg['dir']))die('Direr�rio n�o dipon�vel ou sem permiss�o para leitura');
		foreach($names as $name){
			$data[$name] = $this->Get($name, $expired, $version);
		}
		return $data;
	}

	##########
	# DELETE #
	##########
	public function Delete($name, $version=false){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))return;
		$name = $this->Name($name);
		$version = $this->GetVersion($version);
		$file = $this->cfg['dir'].$version.$name.$this->cfg['ext'];

		if(is_file($file)){return @unlink($file);}else{return null;}
		return false;
	}

	####################
	# DELETE MULTIPLES #
	####################
	public function DeleteMultiples($names, $version = false){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))return;
		$del = array();
		foreach($names as $name){
			$del[$name] = $this->Delete($name, $version);
		}
		return $del;
	}

	##############
	# DELETE ALL #
	##############
	public function DeleteAll($version=false){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))return;
		$del = array();
		$files = glob($this->cfg['dir'].$this->GetVersion($version).'/*', GLOB_NOSORT);
		foreach($files as $file){
		  if(is_file($file)){$del[] = @unlink($file);}
		}
		return !in_array(false, $del);
	}

	##################
	# CACHE DIR SIZE #
	##################
	function DirSize($dir=null, $version=false){
		$dir = (($dir!=null)?$dir:$this->cfg['dir']).$this->GetVersion($version);
		if(!is_readable($dir))die('Direr�rio n�o dipon�vel ou sem permiss�o para leitura');
		$size = 0;
		$extSize = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];
		$files = glob(rtrim($dir, '/').'/*', GLOB_NOSORT);
		foreach($files as $file){
			$size += is_file($file) ? filesize($file) : $this->DirSize($file, $version);
		}

		return ($size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $extSize[$i] : 'Vazio');
	}
}