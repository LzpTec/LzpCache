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
*		$config = array('dir', 'expire', 'version', 'compress', 'cacheNameType', 'ext');
*	//Parametros( = Padrão):
*		$config['dir'] = __DIR__.'/cache/'; 										//Caminho do Diretório onde o cache será armazenado
*		$config['expire'] = 600; 													//0 para infinito - Valor Aceito int(opcional)
*		$config['version'] = null; 													//null desativa - Valores Aceitos float, string e int(opcional)
*		$config['compress'] = 0;													//0 desativa - Valor Aceito int de 0 a 9(opcional)
*		$config['cacheNameType'] = array('hash' => 'md5', 'prefix' => '%name%_'); 	//Use %name% para colocar o nome do cache no prefixo(opcional)
*		$config['ext'] = '.lzp'; 													//Extensão do arquivo de cache(opcional)
*	//Aplicar Configuração:
*		$cache->Config($config);
*
*
*
* Para obter um único cache:
*	$cache->Get($cacheName, $getExpired, $cacheVersion);
*	//Parametros( = Padrão):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Parametro obrigatório)
* 		$getExpired = false;											//Ignora se o cache já expirou(opcional)
* 		$cacheVersion = null;											//Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
*
* Para obter múltiplos caches:
* 	$cache->Get($cachesNames, $getExpired, $cacheVersion);				//Retorna um array($nomeDoCache=>$valor)
* 	//Parametros( = Padrão):
*		$cachesNames = array('nome_do_cache00', 'nome_do_cache01');		//Array contendo o Nome de cada cache(Parametro obrigatório)
*		$getExpired = false;											//Ignora se o cache já expirou(opcional)
* 		$cacheVersion = null;											//Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
*
*
*
* Para criar um cache:
* 	$cache->Create($cacheName, $data, $cacheVersion, $config); 								//Retorna true em caso de sucesso
*	//Parametros( = Padrão):
* 		$cacheName = 'nome_do_cache';														//Nome do cache(Parametro obrigatório)
* 		$data = 'dadosDoCache';																//Dados a serem guardados no cache, tudo é aceito(Parametro obrigatório)
* 		$cacheVersion = null;																//Versão do cache a ser criado - Valores Aceitos(float, string, int) - (opcional)
*		$config = false;																	//Configurações para o cache Aceito: array('expire', 'compress')
*
* Para criar múltiplos caches:
*  	$cache->CreateMultiples($namesValues, $cacheVersion); 									//Retorna um array($nomecache=>$foiCriado)
*	//Parametros( = Padrão):
*		$namesValues = array('nome_do_cache00' => $value, 'nome_do_cache01' => $value); 	//Array contendo os Nomes e os valores dos caches a serem criados(Parametro obrigatório)
*		$cacheVersion = null; 																//Versão dos caches a serem criados - Valores Aceitos float, string e int(opcional)
*
*
*
* Para deletar um cache
* 	$cache->Delete($cacheName, $cacheVersion); 							//Retorna true se o cache for excluido, false em caso de falha e null caso o cache não exista
* 	//Parametros( = Padrão):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Required)
* 		$cacheVersion = null; 											//Versão do cache a ser deletado - Valores Aceitos float, string e int(Opcional)
*
* Para Deletar Múltiplos caches
* 	$cache->Delete($cachesNames, $cacheVersion); 						//Retorna um array($nomecache=>$foiDeletado), $foiDeletado = true(sucesso), false(falha) ou null(cache não existe)
* 	//Parametros( = Padrão):
* 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Required)
*		$cacheVersion = null; 											//Versão dos caches a serem deletados - Valores Aceitos float, string e int(Opcional)
*
* Para deletar todos os caches
* 	$cache->DeleteAll($cacheVersion); 									//Retorna true se os caches forem excluídos
* 	//Parametros( = Padrão):
* 		$cacheVersion = null; 											//Deleta os caches de uma certa versão - Valores Aceitos float, string e int(Parametro opicional)
*
*
* Para verificar se um cache existe
* 	$cache->Exists($cacheName, $cacheVersion);							//Retorna true se o cache existe
* 	//Parametros( = Padrão):
* 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Required)
*		$cacheVersion = null; 											//Versão a ser verificada - Valores Aceitos float, string e int(Opcional)
*
* Para verificar se vários caches existem
* 	$cache->Exists($cachesNames, $cacheVersion);						//Retorna um array($nomecache=>$exists)
* 	//Parametros( = Padrão):
* 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Required)
*		$cacheVersion = null; 											//Versão a ser verificada - Valores Aceitos float, string e int(Opcional)
*
*
*
* Para verificar o tamanho do diretório de cache
*	$cache->Size($dir, $version);
*	//Parametros( = Padrão):
*		$dir = null;								//Diretório a ser verificado(Opcional)	
*		$cacheVersion = null; 						//Retorna o tamanho do cache de uma certa versão - Valores Aceitos float, string e int(Opcional)
*
*
*
*** ChangeLog ***
#####################################################################
# V 2.0.0															#
# -Diretórios agora são criados com permissão 0777					#
# -Código Documentado												#
# -Código Reescrito													#
# -Performance Otimizada											#
# -Removida Criptografia do cache									#
# -Documentação atualizada											#
# -Modificado DirSize -> Size										#
# -Função Unida ExistsMultiples -> Exists							#
# -Função Unida CreateMultiples -> Create							#
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
# -Argumento Opcional adicionado nas Configurações('version')		#
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
# -Novo argumento Opcional($cacheVersion)							#
# -Melhor documentação												#
# -Performance Otimizada											#
# -Nova função(cacheDirSize)										#
#####################################################################
# V 1.0.0															#
# -Lançamento do código para uso livre(MIT License)					#
#####################################################################
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
	public function Create($names, $data=null, $version=null, $config=null){
		$version = $this->GetVersion($version);
		$path = $this->cfg['dir'].$version;

		$compress = isset($config['compress']) ? $config['compress'] : $this->cfg['compress'];
		$expire = isset($config['expire']) ? $config['expire'] : $this->cfg['expire'];
		$expire = $expire!=0 ? (time()+$expire) : 0;

		if(!is_dir($path))
			mkdir($path, 0777, true);

		if(is_array($names)){
			$complete = array();

			foreach($names as $name=>$data){
				$name = $this->Name($name);
				$data = $this->Encode($data);

				$cacheData = array(
					'compress' => $compress,
					'expire' => $expire,
					'data' => ($compress > 0) ? $this->Compress($data, $compress) : $data
				);

				$cacheData = $this->Encode($cacheData);
				$complete[$name] = $this->Set($path.$name.$this->cfg['ext'], $cacheData);
			}

		}else{
			$name = $this->Name($names);
			$data = $this->Encode($data);
			
			$cacheData = array(
				'compress' => $compress,
				'expire' => $expire,
				'data' => ($compress > 0) ? $this->Compress($data, $compress) : $data
			);

			$cacheData = $this->Encode($cacheData);

			$complete = $this->Set($path.$name.$this->cfg['ext'], $cacheData);
		}
		return $complete;
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
			foreach($names as $name){
				$name = $this->Name($name);
				$file = $this->Read($path.$name.$ext);
				$cache = $this->Decode($file);

				if($cache['expire'] == 0 || time() < $cache['expire'] || $expired){
					$cacheData = $cache['data'];
					$cacheData = ($cache['compress'] > 0) ? $this->Uncompress($cacheData) : $cacheData;
					$data[$name] = $this->Decode($cacheData);
				}
			}
		}else{
			$name = $this->Name($names);
			$data = $this->Read($path.$name.$ext);
			$cache = $this->Decode($data);

			if($cache['expire'] == 0 || time() < $cache['expire'] || $expired){
				$data = $cache['data'];
				$data = ($cache['compress'] > 0) ? $this->Uncompress($data) : $data;
				$data = $this->Decode($data);
			}
		}

		return $data;
	}

    /**
     * Deleta o(s) cache(s)
     * 
     * @param array|string $names Nome(s) do(s) cache(s) a ser(em) deletado(s)
     * @param null|float|int|string $version Opcional Versão do(s) cache(s) a ser(em) deletado(s)
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
     * @param null|float|int|string $version Opcional Versão do(s) cache(s) a ser(em) deletado(s)
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
     * Lê e retorna o tamanho do diretório de cache
     * 
     * @param string $dir diretório para ser lido
	 * @param null|float|int|string $version Opcional Versão dos caches a serem lidos
     * @return null|string
     */
	public function Size($dir=null, $version=null){
		$dir = (!is_null($dir) ? $dir : $this->cfg['dir']).$this->GetVersion($version);
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
	private function Set($file, $data){
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
		$name = $this->Filter(strtolower($name));
		$name = str_ireplace('%name%', $name, $this->cfg['cacheNameType']['prefix']).hash($this->cfg['cacheNameType']['hash'], $name);

		return $name;
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
		return (function_exists('gzdeflate') && function_exists('gzinflate')) ? gzdeflate($data, $compressLevel) : $data;
	}

    /**
     * Descomprime o cache
     * 
     * @param string $data string que será descomprimida.
     * @return string
     */
	private function Uncompress($data){
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