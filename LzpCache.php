<?php
/**
 * LzpCache v2.0.0 - Requer PHP >= 5.5
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
*		$config['version'] = null; 													//null desativa - Valores Aceitos float, string e int(opcional)
*		$config['compress'] = 0;													//0 desativa - Valor Aceito int de 0 a 9(opcional)
*		$config['cacheNameType'] = array('hash' => 'md5', 'prefix' => '%name%_'); 	//Use %name% para colocar o nome do cache no prefixo(opcional)
*		$config['ext'] = '.lzp'; 													//Extens�o do arquivo de cache(opcional)
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
* 		$cacheVersion = null;											//Vers�o do cache a ser obtido - Valores Aceitos float, string e int(opcional)
*
* Para obter m�ltiplos caches:
* 	$cache->Get($cachesNames, $getExpired, $cacheVersion);				//Retorna um array($nomeDoCache=>$valor)
* 	//Parametros( = Padr�o):
*		$cachesNames = array('nome_do_cache00', 'nome_do_cache01');		//Array contendo o Nome de cada cache(Parametro obrigat�rio)
*		$getExpired = false;											//Ignora se o cache j� expirou(opcional)
* 		$cacheVersion = null;											//Vers�o do cache a ser obtido - Valores Aceitos float, string e int(opcional)
*
*												
*
* Para criar um ou mais caches:
*	$cache->Create($namesAndValues, $data, $cacheVersion, $config); 					//Retorna true em caso de sucesso
*	//Parametros( = Padr�o):
*		$namesAndValues = array('nome_do_cache00' => $value); 							//Array contendo os Nomes e os valores dos caches a serem criados(Parametro obrigat�rio)
*		$cacheVersion = null;															//Vers�o do cache a ser criado - Valores Aceitos(float, string, int) - (opcional)
*		$config = false;																//Configura��es para o cache Aceito: array('expire', 'compress')
*
*
*
* Para deletar um cache
* 	$cache->Delete($cacheName, $cacheVersion); 							//Retorna true se o cache for excluido, false em caso de falha e null caso o cache n�o exista
* 	//Parametros( = Padr�o):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Required)
* 		$cacheVersion = null; 											//Vers�o do cache a ser deletado - Valores Aceitos float, string e int(Opcional)
*
* Para Deletar M�ltiplos caches
* 	$cache->Delete($cachesNames, $cacheVersion); 						//Retorna um array($nomecache=>$foiDeletado), $foiDeletado = true(sucesso), false(falha) ou null(cache n�o existe)
* 	//Parametros( = Padr�o):
* 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Required)
*		$cacheVersion = null; 											//Vers�o dos caches a serem deletados - Valores Aceitos float, string e int(Opcional)
*
* Para deletar todos os caches
* 	$cache->DeleteAll($cacheVersion); 									//Retorna true se os caches forem exclu�dos
* 	//Parametros( = Padr�o):
* 		$cacheVersion = null; 											//Deleta os caches de uma certa vers�o - Valores Aceitos float, string e int(Parametro opicional)
*
*
* Para verificar se um cache existe
* 	$cache->Exists($cacheName, $cacheVersion);							//Retorna true se o cache existe
* 	//Parametros( = Padr�o):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Required)
*		$cacheVersion = null; 											//Vers�o a ser verificada - Valores Aceitos float, string e int(Opcional)
*
* Para verificar se v�rios caches existem
* 	$cache->Exists($cachesNames, $cacheVersion);						//Retorna um array($nomecache=>$exists)
* 	//Parametros( = Padr�o):
* 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Required)
*		$cacheVersion = null; 											//Vers�o a ser verificada - Valores Aceitos float, string e int(Opcional)
*
*
*
* Para verificar o tamanho do diret�rio de cache
*	$cache->Size($dir, $version);
*	//Parametros( = Padr�o):
*		$dir = null;								//Diret�rio a ser verificado(Opcional)	
*		$cacheVersion = null; 						//Retorna o tamanho do cache de uma certa vers�o - Valores Aceitos float, string e int(Opcional)
*
*
*
*** ChangeLog ***
#####################################################################
# V 2.0.0															#
# -Diret�rios agora s�o criados com permiss�o 0777					#
# -C�digo Documentado												#
# -C�digo Reescrito													#
# -Performance Otimizada											#
# -Removida Criptografia do cache									#
# -Documenta��o atualizada											#
# -Modificado DirSize -> Size										#
# -Fun��o Unida ExistsMultiples -> Exists							#
# -Fun��o Unida CreateMultiples -> Create							#
# -Fun��o Unida GetMultiples -> Get									#
# -Fun��o Unida DeleteMultiples -> Delete							#
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
# -Argumento Opcional adicionado nas Configura��es('version')		#
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
# -Novo argumento Opcional($cacheVersion)							#
# -Melhor documenta��o												#
# -Performance Otimizada											#
# -Nova fun��o(cacheDirSize)										#
#####################################################################
# V 1.0.0															#
# -Lan�amento do c�digo para uso livre(MIT License)					#
#####################################################################
*/
class Cache{
	const DS = DIRECTORY_SEPARATOR;

    /**
     * Array contendo as configura��es.
     * 
     * @var array
     */
	protected $cfg;

    /**
     * Inicia junto com a classe
     * 
     * @param array $config Array Opcional contendo configura��es para o cache.
     * @return void
     */
	function __construct($config = null){
		$defaultConfig = array(
			'dir' => (__DIR__).self::DS.'cache'.self::DS, 
			'expire' => 600, 
			'compress' => 0, 
			'memcache' => false,
			'version' => null, 
			'cacheNameType' => array('hash' => 'md5', 'prefix' => '%name%_'), 
			'ext' => '.lzp'
		);

		if(is_array($config))
			$this->cfg = array_replace($defaultConfig, $config);
		else
			$this->cfg = $defaultConfig;

		if(!is_dir($this->cfg['dir']))
			mkdir($this->cfg['dir'], 0777, true);
	}

    /**
     * Configura a classe
     * 
     * @param array $config Array contendo configura��es para o cache.
     * @return void
     */
	public function Config($config){
		$this->cfg = array_replace($this->cfg, $config);

		if(!is_dir($this->cfg['dir']))
			mkdir($this->cfg['dir'], 0777, true);
	}

    /**
     * Verifica se um ou mais caches existem
     * 
     * @param array|string $names Nome(s) do(s) cache(s) a ser(em) verificado(s)
     * @param null|float|int|string $version Opcional Vers�o do(s) cache(s) a ser(em) obtido(s)
     * @return mixed
     */
	public function Exists($names, $version=null){
		$version = $this->GetVersion($version);
		$path = $this->cfg['dir'].$version;

		if(is_array($names)){
			$exists = array();

			foreach($names as $name){
				$name = $this->Name($name);
				$file = $path.$name.$this->cfg['ext'];
				$exists[$name] = is_file($file);
			}
		}else{
			$name = $this->Name($names);
			$file = $path.$name.$this->cfg['ext'];
			$exists = is_file($file);
		}

		return $exists;
	}

	##########
	# CREATE #
	##########
	public function Create($datas, $version=null, $config=null){
		$version = $this->GetVersion($version);
		$path = $this->cfg['dir'].$version;

		$compress = isset($config['compress']) ? $config['compress'] : $this->cfg['compress'];
		$expire = isset($config['expire']) ? $config['expire'] : $this->cfg['expire'];
		$expire = $expire!=0 ? (time()+$expire) : 0;

		if(!is_dir($path))
			mkdir($path, 0777, true);

		$complete = array();

		foreach($datas as $name=>$data){
			$name = $this->Name($name);

			$cacheData = array(
				'compress' => $compress,
				'expire' => $expire,
				'data' => ($compress > 0) ? $this->Compress($data, $compress) : $data
			);

			$cacheData = $this->Encode($cacheData);
			$complete[$name] = $this->Write($path.$name.$this->cfg['ext'], $cacheData);
		}

		return $complete;
	}

	public function Set($datas, $version=null, $config=null){
		return $this->Create($datas, $version=null, $config=null);
	}

    /**
     * Obt�m o(s) cache(s)
     * 
     * @param array|string $names Nome(s) do(s) cache(s) a ser(em) obtido(s)
     * @param boolean $expired Opcional ignora se o cache j� expirou
     * @param null|float|int|string $version Opcional Vers�o do(s) cache(s) a ser(em) obtido(s)
     * @return mixed
     */
	public function Get($names, $expired=false, $version=null){
		$dir = $this->cfg['dir'];
		$version = $this->GetVersion($version);
		$ext = $this->cfg['ext'];
		$path = $dir.$version;

		if(!is_readable($dir))
			die('Direr�rio n�o dipon�vel ou sem permiss�o para leitura');

		if(is_array($names)){
			$data = array();
			foreach($names as $name){
				$name = $this->Name($name);
				$file = $this->Read($path.$name.$ext);
				$cache = $this->Decode($file);

				if($cache['expire'] == 0 || time() < $cache['expire'] || $expired){
					$cacheData = $cache['data'];
					$data[$name] = ($cache['compress'] > 0) ? $this->Uncompress($cacheData) : $cacheData;
				}
			}
		}else{
			$name = $this->Name($names);
			$data = $this->Read($path.$name.$ext);
			$cache = $this->Decode($data);

			if($cache['expire'] == 0 || time() < $cache['expire'] || $expired){
				$data = $cache['data'];
				$data = ($cache['compress'] > 0) ? $this->Uncompress($data) : $data;
			}
		}

		return $data;
	}

    /**
     * Deleta o(s) cache(s)
     * 
     * @param array|string $names Nome(s) do(s) cache(s) a ser(em) deletado(s)
     * @param null|float|int|string $version Opcional Vers�o do(s) cache(s) a ser(em) deletado(s)
     * @return mixed
     */
	public function Delete($names, $version=null){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))
			return;

		$version = $this->GetVersion($version);
		$path = $dir.$version;

		if(is_array($names)){
			$del = array();
			foreach($names as $name){
				$name = $this->Name($name);
				$file = $path.$name.$this->cfg['ext'];
				$del[$name] = is_file($file) ? @unlink($file) : null;
			}
		}else{
			$name = $this->Name($names);
			$file = $path.$name.$this->cfg['ext'];
			if(is_file($file))
				$del = @unlink($file);
		}
		return $del;
	}

    /**
     * Deleta todos os caches
     * 
     * @param null|float|int|string $version Opcional Vers�o do(s) cache(s) a ser(em) deletado(s)
     * @return mixed
     */
	public function DeleteAll($version=null){
		if(!is_writeable($this->cfg['dir']) && !is_readable($this->cfg['dir']))
			return;
		$del = array();

		$files = glob($this->cfg['dir'].$this->GetVersion($version).'/*', GLOB_NOSORT);
		foreach($files as $file){
			if(is_file($file))
				$del[] = @unlink($file);
		}
		return (!is_null($del) && !in_array(false, $del));
	}

    /**
     * L� e retorna o tamanho do diret�rio de cache
     * 
     * @param string $dir diret�rio para ser lido
	 * @param null|float|int|string $version Opcional Vers�o dos caches a serem lidos
     * @return null|string
     */
	public function Size($dir=null, $version=null){
		$dir = (!is_null($dir) ? $dir : $this->cfg['dir']).$this->GetVersion($version);
		if(!is_readable($dir))
			die('Direr�rio n�o dipon�vel ou sem permiss�o para leitura');

		$size = 0;
		$files = glob(rtrim($dir, '/').'/*', GLOB_NOSORT);
		foreach($files as $file){
			$size += is_file($file) ? filesize($file) : $this->Size($file, $version);
		}

		$extSize = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];

		return ($size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $extSize[$i] : null);
	}

    /**
     * L� e retorna os dados de um arquivo
     * 
     * @param string $file arquivo para ser lido
     * @return mixed
     */
	private function Read($file){
		if(is_file($file))
			return file_get_contents($file);
		else
			return null;
	}

    /**
     * Cria um arquivo e adiciona dados a ele
     * 
     * @param string $file arquivo a ser criado
	 * @param mixed $data dados a serem adicionados ao arquivo
     * @return mixed
     */
	private function Write($file, $data){
		return file_put_contents($file, $data);
	}

    /**
     * Filtra uma string
     * 
     * @param string $data String para ser filtrada, removendo qualquer caractere inv�lido.
     * @return string
     */
	private function Filter($data){
		return preg_replace("/[^a-zA-Z0-9_.-]/", "", $data);
	}

    /**
     * Retorna o nome final do cache
     * 
     * @param string $name Nome do cache.
     * @return string
     */
	private function Name($name){
		$name = $this->Filter(strtolower($name));
		$name = str_ireplace('%name%', $name, $this->cfg['cacheNameType']['prefix']).hash($this->cfg['cacheNameType']['hash'], $name);

		return $name;
	}

    /**
     * Codifica o cache
     * 
     * @param mixed $data Array ou Objeto que ser� convertido para string.
     * @return string
     */
	private function Encode($data){
		return (is_array($data) || is_object($data)) ? serialize($data) : $data;
	}

    /**
     * Decodifica o cache
     * 
     * @param mixed $data string que ser� decodificada.
     * @return mixed
     */
	private function Decode($data){
		if(is_null($data))
			return null;

		$x = @unserialize($data);
		return ($x === 'b:0;' || $x !== false) ? $x : $data;
	}

    /**
     * Comprime o cache
     * 
     * @param string $data string que ser� comprimida.
	 * @param int $compressLevel n�vel de compress�o de 1 a 9.
     * @return string
     */
	private function Compress($data, $compressLevel){
		$data = $this->Encode($data);
		return function_exists('gzdeflate') ? gzdeflate($data, $compressLevel) : $data;
	}

    /**
     * Descomprime o cache
     * 
     * @param string $data string que ser� descomprimida.
     * @return string
     */
	private function Uncompress($data){
		$data = $this->Decode($data);
		return function_exists('gzinflate') ? gzinflate($data) : $data;
	}

    /**
     * Retorna a vers�o do cache filtrada
     * 
     * @param mixed $version vers�o do cache.
     * @return string
     */
	private function GetVersion($version){
		$version = !is_null($version) ? $version : $this->cfg['version'];
		return !is_null($version) ? $this->Filter($version) : '';
	}
}