<?php
/**
 * LzpCache v2.1.0 - Requer PHP >= 5.5
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
*		$config = array('dir', 'expire', 'version', 'compress', 'nameHash', 'ext', 'useLZF', 'useBZ');
*	//Parametros( = Padrão):
*		$config['dir'] = __DIR__.'/cache/';		//Caminho do Diretório onde o cache será armazenado
*		$config['expire'] = 600;				//0 para infinito - Valor Aceito int(opcional)
*		$config['version'] = null; 				//null desativa - Valores Aceitos float, string e int(opcional)
*		$config['compress'] = 0;				//0 desativa - Valor Aceito int de 0 a 9(opcional)
*		$config['nameHash'] = 'md5'				//Hash para gerar o nome do cache(opcional)
*		$config['ext'] = '.lzp'; 				//Extensão do arquivo de cache(opcional)
*		$config['compressType'] = 'gz'; 		//Compressão do cache - suportado: gz, lzf e bz
*	//Aplicar Configuração:
*		$cache->Config($config);
*
*
*
* Para obter um único cache:
*	$cache->Get($cacheName, $getExpired, $version);
*	$cache->Read($cacheName, $getExpired, $version);
*	//Parametros( = Padrão):
* 		$cacheName = 'nome_do_cache'; 						//Nome do cache(Parametro obrigatório)
* 		$getExpired = false;								//Ignora se o cache já expirou(opcional)
* 		$version = null;									//Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
*
* Para obter múltiplos caches:
* 	$cache->Get($cachesNames, $getExpired, $version);		//Retorna um array($nomeDoCache=>$valor)
*	$cache->Read($cachesNames, $getExpired, $version);
* 	//Parametros( = Padrão):
*		$cachesNames = array('nome_do_cache00', ...);		//Array contendo o Nome de cada cache(Parametro obrigatório)
*		$getExpired = false;								//Ignora se o cache já expirou(opcional)
* 		$version = null;									//Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
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
* 	$cache->Delete($cachesNames, $version); 				//Retorna um array($nomecache=>$foiDeletado), $foiDeletado = true(sucesso), false(falha) ou null(cache não existe)
*	$cache->Remove($cachesNames, $version);
* 	//Parametros( = Padrão):
* 		$cachesNames = array('nome_do_cache00', ...); 		//Array contendo o Nome de cada cache(Required)
*		$version = null; 									//Versão dos caches a serem deletados - Valores Aceitos float, string e int(Opcional)
*
* Para deletar todos os caches
* 	$cache->Clear($version); 		//Retorna true se os caches forem excluídos
* 	//Parametros( = Padrão):
* 		$version = null; 			//Deleta os caches de uma certa versão - Valores Aceitos float, string e int(Parametro opicional)
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
*	$cache->Size($version);
*	//Parametros( = Padrão):
*		$version = null; 			//Retorna o tamanho do cache de uma certa versão - Valores Aceitos float, string e int(Opcional)
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
     * Váriavel para a função Size
     * 
     * @var array
     */
	private $tempFileSize = null;

    /**
     * Inicia junto com a classe
     * 
     * @param array $options Array Opcional contendo configurações para o cache.
     * @return void
     */
	function __construct($options = null){
		$defaults = array(
			'dir' => (__DIR__).self::DS.'cache'.self::DS, 
			'expire' => 600, 
			'compress' => 0, 
			'version' => null, 
			'nameHash' => 'sha1', 
			'ext' => '.lzp',
			'compressType' => 'gz'
		);

		$this->cfg = is_array($options) ? array_replace($defaults, $options) : $defaultConfig;

		$this->CreateDir($this->cfg['dir']);
	}

    /**
     * Configura a classe
     * 
     * @param array $options Array contendo configurações para o cache.
     * @return void
     */
	public function Config($options){
		$this->cfg = array_replace($this->cfg, $options);

		$this->CreateDir($this->cfg['dir']);
	}

    /**
     * Verifica se um ou mais caches existem
     * 
     * @param array|string $names Nome(s) do(s) cache(s) a ser(em) verificado(s)
     * @param null|float|int|string $version Opcional Versão do(s) cache(s) a ser(em) obtido(s)
     * @return mixed
     */
	public function Exists($names, $version=null){
		$path = $this->cfg['dir'];
		$path .= $this->GetVersion($version);

		if(is_array($names)){
			$exists = array();

			foreach($names as $name){
				$newName = $this->Name($name);
				$newName = implode(self::DS, $newName);
				$file = $path.$newName.$this->cfg['ext'];
				$exists[$name] = is_file($file);
			}
		}else{
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
     * @param array $names Nomes dos caches a serem criados
     * @param boolean $expire Opcional Tempo para o cache expirar
     * @param null|float|int|string $version Opcional Versão dos caches a serem criados
     * @return array
     */
	public function Create($datas, $expire=null, $version=null){
		$path = $this->cfg['dir'];
		$path .= $this->GetVersion($version);

		$compress = $this->cfg['compress'];
		$expire = !is_null($expire) ? $expire : $this->cfg['expire'];

		if($expire!=0)
			$expire += time();

		$complete = array();

		foreach($datas as $name=>$data){
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
		$path  = $this->cfg['dir'];
		$path .= $this->GetVersion($version);

		if(is_array($names)){
			$data = array();
			foreach($names as $name){
				$newName = $this->Name($name);
				$newName = implode(self::DS, $newName);
				$cache = $this->Open($path.$newName.$this->cfg['ext']);

				if($cache['expire'] == 0 || time() < $cache['expire'] || $expired){
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

		if($cache['expire'] == 0 || time() < $cache['expire'] || $expired){
			$data = $cache['data'];
			$compress = explode('|', $cache['compress']);
			return ($compress[0] > 0) ? $this->Uncompress($data, $compress[1]) : $data;
		}
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

		$path = $this->cfg['dir'];
		$path .= $this->GetVersion($version);

		$del = array();
		foreach($names as $name){
			$newName = $this->Name($names);
			$newName = implode(self::DS, $newName);
			$file = $path.$newName.$this->cfg['ext'];
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
			die('Direrório não diponível ou sem permissão para escrita');

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
	public function Size($version=null){
		$path = !is_null($this->tempFileSize) ? $this->tempFileSize : $this->cfg['dir'];
		$path .= $this->GetVersion($version);

		if(!is_readable($path))
			die('Direrório não diponível ou sem permissão para leitura');

		$size = 0;
		$files = glob(rtrim($path, '/').'/*', GLOB_NOSORT);
		foreach($files as $file){
			$this->tempFileSize = $file;
			$size += is_file($file) ? filesize($file) : $this->Size($version);
		}

		$this->tempFileSize = null;

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
		if(!is_file($file))
			return null;
			
		$file = file_get_contents($file);
		return $this->Decode($file);
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
     * @return array
     */
	private function Name($name, $size=18){
		$name = $this->Filter($name);

		$nameHash = hash($this->cfg['nameHash'], $name, true);

		$nameHash = $this->base32($nameHash.$name);

		$path = str_split(strrev($nameHash), 3);

		$path = $path[0].self::DS.$path[1];

		return array($path, strtolower(substr($nameHash, 0, $size)));
	}

    /**
     * Cria um diretório
     * 
     * @param string $path caminho do diretório.
     * @return string
     */
	private function CreateDir($path){
		if(!file_exists($path))
			mkdir($path, 0777, true);
	}
	
    /**
     * Codifica para base32
     * 
     * @param string $data dados para serem codificados
     * @return string
     */
	private function base32($data){
		$dataSize = strlen($data);
		$result = '';
		$remainder = 0;
		$remainderSize = 0;
		$chars = '0123456789abcdefghijklmnopqrstuv';
		
		for($i = 0; $i < $dataSize; $i++){
			$b = ord($data[$i]);
			$remainder = ($remainder << 8) | $b;
			$remainderSize += 8;
			while ($remainderSize > 4){
				$remainderSize -= 5;
				$c = $remainder & (31 << $remainderSize);
				$c >>= $remainderSize;
				$result .= $chars[$c];
			}
		}
		if ($remainderSize > 0){
			$remainder <<= (5 - $remainderSize);
			$c = $remainder & 31;
			$result .= $chars[$c];
		}

		return $result;
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

		if($this->cfg['compressType'] == 'gz' && function_exists('gzdeflate'))
			return gzdeflate($data, $compressLevel);
		elseif($this->cfg['compressType'] == 'bz' && function_exists('bzcompress'))
			return bzcompress($data, $compressLevel);
		elseif($this->cfg['compressType'] == 'lzf' && function_exists('lzf_compress'))
			return lzf_compress($data);

		return $data;
	}

    /**
     * Descomprime o cache
     * 
     * @param string $data string que será descomprimida.
     * @return string
     */
	private function Uncompress($data, $type){
		$data = $this->Decode($data);

		if($type == 'bz' && function_exists('bzdecompress'))
			return bzdecompress($data);
		elseif($type == 'lzf' && function_exists('lzf_decompress'))
			return lzf_decompress($data);
		elseif($type == 'gz' && function_exists('gzinflate'))
			return gzinflate($data);

		return $data;
	}

    /**
     * Retorna a versão do cache filtrada
     * 
     * @param mixed $version versão do cache.
     * @return string
     */
	private function GetVersion($version){
		$version = !is_null($version) ? $version : $this->cfg['version'];
		return !is_null($version) ? $this->Filter($version).self::DS : '';
	}
}