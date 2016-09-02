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
* Recursos:
* - Compressão do cache
* - Nome personalizado para o cache/Hash de escolha do usuário
* - Extensão do arquivo cache personalizada
* - Criar/Obter/Deletar vários caches de uma vez
* - É possível obter um cache mesmo que ele tenha expirado
*
*
* Recursos que talvez sejam introduzidos:
* - Suporte para MemCache e MemCached.
*
*
****Como Usar****
*
* Inicializar:
*	//Sem configurações
*		$cache = new Lzp\Cache;
*	//Com configurações
*		$cache = new Lzp\Cache($config);
*
*
*
* Configurar:
*	//Configurações
*		$config = array('dir', 'expire', 'version', 'compress', 'cacheNameCfg', 'ext');
*	//Parametros( = Padrão):
*		$config['dir'] = __DIR__.'/cache/'; 										//Caminho do Diretório onde o cache será armazenado
*		$config['expire'] = 600; 													//0 para infinito - Valor Aceito int(opcional)
*		$config['version'] = null; 													//null desativa - Valores Aceitos float, string e int(opcional)
*		$config['compress'] = 0;													//0 desativa - Valor Aceito int de 0 a 9(opcional)
*		$config['cacheNameCfg'] = array('hash' => 'md5', 'prefix' => '%name%_'); 	//Use %name% para colocar o nome do cache no prefixo(opcional)
*		$config['ext'] = '.lzp'; 													//Extensão do arquivo de cache(opcional)
*	//Aplicar Configuração:
*		$cache->Config($config);
*
*
*
* Para obter um único cache:
*	$cache->Get($cacheName, $getExpired, $version);
*	//Parametros( = Padrão):
* 		$cacheName = 'nome_do_cache'; 								//Nome do cache(Parametro obrigatório)
* 		$getExpired = false;										//Ignora se o cache já expirou(opcional)
* 		$version = null;											//Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
*
* Para obter múltiplos caches:
* 	$cache->Get($cachesNames, $getExpired, $version);				//Retorna um array($nomeDoCache=>$valor)
*	$cache->Read($cachesNames, $getExpired, $version);
* 	//Parametros( = Padrão):
*		$cachesNames = array('nome_do_cache00', ...);				//Array contendo o Nome de cada cache(Parametro obrigatório)
*		$getExpired = false;										//Ignora se o cache já expirou(opcional)
* 		$version = null;											//Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
*
*												
*
* Para criar um ou mais caches:
*	$cache->Create($namesAndValues, $expire, $version); 			//Retorna true em caso de sucesso
*	$cache->Set($namesAndValues, $expire, $version);
*	//Parametros( = Padrão):
*		$namesAndValues = array('nome_do_cache00' => $value); 		//Array contendo os Nomes e os valores dos caches a serem criados(Parametro obrigatório)
*		$expire = null;												//Tempo do cache / 0 para infinito ou null para padrão das configurações - Valor Aceito int(opcional)
*		$version = null;											//Versão do cache a ser criado - Valores Aceitos(float, string, int) - (opcional)
*
*
*
* Para deletar um ou mais caches:
* 	$cache->Delete($cachesNames, $version); 					//Retorna um array($nomecache=>$foiDeletado), $foiDeletado = true(sucesso), false(falha) ou null(cache não existe)
*	$cache->Remove($cachesNames, $version);
* 	//Parametros( = Padrão):
* 		$cachesNames = array('nome_do_cache00', ...); 			//Array contendo o Nome de cada cache(Required)
*		$version = null; 										//Versão dos caches a serem deletados - Valores Aceitos float, string e int(Opcional)
*
* Para deletar todos os caches
* 	$cache->Clear($version); 			//Retorna true se os caches forem excluídos
* 	//Parametros( = Padrão):
* 		$version = null; 				//Deleta os caches de uma certa versão - Valores Aceitos float, string e int(Parametro opicional)
*
*
* Para verificar se um cache existe
* 	$cache->Exists($cacheName, $version);								//Retorna true se o cache existe
* 	//Parametros( = Padrão):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Required)
*		$version = null; 												//Versão a ser verificada - Valores Aceitos float, string e int(Opcional)
*
* Para verificar se vários caches existem
* 	$cache->Exists($cachesNames, $version);								//Retorna um array($nomecache=>$exists)
* 	//Parametros( = Padrão):
* 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Required)
*		$version = null; 												//Versão a ser verificada - Valores Aceitos float, string e int(Opcional)
*
*
*
* Para verificar o tamanho do diretório de cache
*	$cache->Size($dir, $version);
*	//Parametros( = Padrão):
*		$dir = null;								//Diretório a ser verificado(Opcional)	
*		$version = null; 							//Retorna o tamanho do cache de uma certa versão - Valores Aceitos float, string e int(Opcional)
*
*
*/
class Cache{
	const DS = DIRECTORY_SEPARATOR;

    /**
     * Array contendo as configurações.
     * 
     * @var array
     */
	protected $cfg;

    /**
     * Inicia junto com a classe
     * 
     * @param array $config Array Opcional contendo configurações para o cache.
     * @return void
     */
	function __construct($config = null){
		$defaultConfig = array(
			'dir' => (__DIR__).self::DS.'cache'.self::DS, 
			'expire' => 600, 
			'compress' => 0, 
			'memcache' => false,
			'version' => null, 
			'cacheNameCfg' => array('hash' => 'md5', 'prefix' => '%name%_'), 
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
     * @param array $config Array contendo configurações para o cache.
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
     * @param null|float|int|string $version Opcional Versão do(s) cache(s) a ser(em) obtido(s)
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
	public function Create($datas, $expire=null, $version=null){
		$version = $this->GetVersion($version);
		$path = $this->cfg['dir'].$version;

		$compress = $this->cfg['compress'];
		$expire = !is_null($expire) ? $expire : $this->cfg['expire'];
		$expire = $expire==0 ? 0 : (time() + $expire);

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

			$complete[$name] = $this->Write($path.$name.$this->cfg['ext'], $cacheData);
		}

		return $complete;
	}

	public function Set($datas, $expire=null, $version=null){
		return $this->Create($datas, $expire, $version);
	}

    /**
     * Obtém o(s) cache(s)
     * 
     * @param array|string $names Nome(s) do(s) cache(s) a ser(em) obtido(s)
     * @param boolean $expired Opcional ignora se o cache já expirou
     * @param null|float|int|string $version Opcional Versão do(s) cache(s) a ser(em) obtido(s)
     * @return mixed
     */
	public function Get($names, $expired=false, $version=null){
		$dir = $this->cfg['dir'];
		$version = $this->GetVersion($version);
		$ext = $this->cfg['ext'];
		$path = $dir.$version;

		if(!is_readable($dir))
			die('Direrório não diponível ou sem permissão para leitura');

		if(is_array($names)){
			$data = array();
			foreach($names as $name){
				$name = $this->Name($name);
				$cache = $this->Open($path.$name.$ext);

				if($cache['expire'] == 0 || time() < $cache['expire'] || $expired){
					$cacheData = $cache['data'];
					$data[$name] = ($cache['compress'] > 0) ? $this->Uncompress($cacheData) : $cacheData;
				}
			}
		}else{
			$name = $this->Name($names);
			$cache = $this->Open($path.$name.$ext);

			if($cache['expire'] == 0 || time() < $cache['expire'] || $expired){
				$data = $cache['data'];
				$data = ($cache['compress'] > 0) ? $this->Uncompress($data) : $data;
			}
		}

		return $data;
	}

	public function Read($names, $expired=false, $version=null){
		return $this->Get($names, $expired, $version);
	}

    /**
     * Deleta o(s) cache(s)
     * 
     * @param array|string $names Nome(s) do(s) cache(s) a ser(em) deletado(s)
     * @param null|float|int|string $version Opcional Versão do(s) cache(s) a ser(em) deletado(s)
     * @return mixed
     */
	public function Delete($names, $version=null){
		if(!is_writeable($this->cfg['dir']))
			die('Direrório não diponível ou sem permissão para escrita');

		$version = $this->GetVersion($version);
		$path = $dir.$version;

		$del = array();
		foreach($names as $name){
			$name = $this->Name($name);
			$file = $path.$name.$this->cfg['ext'];
			$del[$name] = is_file($file) ? @unlink($file) : null;
		}
		return $del;
	}

	public function Remove($names, $version=null){
		return $this->Delete($names, $version);
	}

    /**
     * Deleta todos os caches
     * 
     * @param null|float|int|string $version Opcional Versão do(s) cache(s) a ser(em) deletado(s)
     * @return mixed
     */
	public function Clear($version=null){
		if(!is_writeable($this->cfg['dir']))
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
     * Lê e retorna o tamanho do diretório de cache
     * 
     * @param string $dir diretório para ser lido
	 * @param null|float|int|string $version Opcional Versão dos caches a serem lidos
     * @return null|string
     */
	public function Size($dir=null, $version=null){
		$dir = !is_null($dir) ? $dir : $this->cfg['dir'];
		$dir .= $this->GetVersion($version);

		if(!is_readable($dir))
			die('Direrório não diponível ou sem permissão para leitura');

		$size = 0;
		$files = glob(rtrim($dir, '/').'/*', GLOB_NOSORT);
		foreach($files as $file){
			$size += is_file($file) ? filesize($file) : $this->Size($file, $version);
		}

		$extSize = [' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];

		return ($size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $extSize[$i] : null);
	}

    /**
     * Lê e retorna os dados de um arquivo
     * 
     * @param string $file arquivo para ser lido
     * @return mixed
     */
	private function Open($file){
		if(is_file($file)){
			$file = file_get_contents($file);
			return $this->Decode($file);
		}else{
			return null;
		}
	}

    /**
     * Cria um arquivo e adiciona dados a ele
     * 
     * @param string $file arquivo a ser criado
	 * @param mixed $data dados a serem adicionados ao arquivo
     * @return mixed
     */
	private function Write($file, $data){
		$data = $this->Encode($data);
		return file_put_contents($file, $data);
	}

    /**
     * Filtra uma string
     * 
     * @param string $data String para ser filtrada, removendo qualquer caractere inválido.
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
		$name = strtolower($name);
		$name = $this->Filter($name);
		$newName = str_ireplace('%name%', $name, $this->cfg['cacheNameCfg']['prefix']);
		$newName .= hash($this->cfg['cacheNameCfg']['hash'], $name);

		return $newName;
	}

    /**
     * Codifica o cache
     * 
     * @param mixed $data Array ou Objeto que será convertido para string.
     * @return string
     */
	private function Encode($data){
		return (is_array($data) || is_object($data)) ? serialize($data) : $data;
	}

    /**
     * Decodifica o cache
     * 
     * @param mixed $data string que será decodificada.
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
     * @param string $data string que será comprimida.
	 * @param int $compressLevel nível de compressão de 1 a 9.
     * @return string
     */
	private function Compress($data, $compressLevel){
		$data = $this->Encode($data);
		return function_exists('gzdeflate') ? gzdeflate($data, $compressLevel) : $data;
	}

    /**
     * Descomprime o cache
     * 
     * @param string $data string que será descomprimida.
     * @return string
     */
	private function Uncompress($data){
		$data = $this->Decode($data);
		return function_exists('gzinflate') ? gzinflate($data) : $data;
	}

    /**
     * Retorna a versão do cache filtrada
     * 
     * @param mixed $version versão do cache.
     * @return string
     */
	private function GetVersion($version){
		$version = !is_null($version) ? $version : $this->cfg['version'];
		return !is_null($version) ? $this->Filter($version) : '';
	}
}