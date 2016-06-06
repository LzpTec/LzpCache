<?php
/**
 * LzpCache v1.0 - Requer PHP > 5.4
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
* - Compressão do cache
* - Nome personalizado para o cache/Hash de escolha do usuário
* - Extensão do arquivo cache personalizada
* - Criar/Obter/Deletar vários caches de uma vez
* - É possível obter um cache mesmo que ele tenha expirado
*
*
* Recursos ainda não implementados:
* - Configuração personalizada para criar o cache($cache->Set(string $name, mixed $data, array $config)
*
*
* Recursos que talvez sejam introduzidos:
* - Suporte para MemCache e MemCached.
*
*
* Como Usar:
* -Para Configurar:
* 	$config = array('dir' => __DIR__.'/cache/', 'expire' => 600, 'compress' => 0, 'cacheNameType' => array('hash' => 'md5', 'prefix' => '%name%_'), 'ext' => '.cache'); //Valores padrões
* 	$cache = new Lozep\LzpCache([array $config]);
* 	$cache->Config([array $config]);
*
* -Para verificar se um cache existe:
* 	$cacheExists = $cache->Exists(string $cacheName); // Retorna false se o cache não existir
*
* -Para verificar se vários caches existem:
* 	$cachesExists = $cache->ExistsMultiples(array $cachesNames); // Retorna um array($nomecache=>$exists) caso esse cache não exista $exists recebe false
*
* -Para obter um Cache:
* 	$cacheGet = $cache->Get(string $cacheName, bool $getExpired=false); // Retorna null se o cache não existir
*
* -Para obter vários caches de uma vez:
* 	$cacheMultiples = $cache->GetMultiples(array $cachesNames, bool $getExpired=false); // Retorna um array($nomecache=>$valor) caso esse cache não exista retorna null
* 
* -Para criar um Cache:
* 	$cache->Create(string $cacheName, mixed $data);//Retorna true em sucesso
* 
* -Para criar vários caches:
* 	$namesAndValues = array('cacheName1' => $value, 'cacheName2' => $value);
* 	$cache->CreateMultiples(array $namesAndValues);//Retorna true em sucesso
* 
* -Para deletar um cache:
* 	$cache->Delete('cacheName');//Retorna true em sucesso 
* 
* -Para deletar vários caches:
* 	$cache->DeleteMultiples(array $names);//Retorna true em sucesso
* 
* -Para deletar todos os caches:
* 	$cache->DeleteAll();//Retorna true em sucesso
*
* 
*** ChangeLog *** 
#########################################################
# V 1.0													#
# -Lançamento do código para uso livre(MIT License)		#
# -Removido(cfgCacheFile)								#
# -Performance Otimizada								#
# -Nova Opção(cacheNameType)							#
# -Funções renomeadas:									#
# getMultiple => GetMultiples							#
# setMultiple => CreateMultiples						#
# delMultiple => DeleteMultiples						#
# existMultiple => ExistsMultiples						# 
# set => Create											#
# del => Delete											#
# exist => Exists										#
#########################################################
# V 0.6													#
# -Nova Opção(compress)									#
# -Novas funções(exist, existMultiple)					#
#########################################################
# V 0.5													#
# -Novas funções(delAll, delMultiple)					#
# -Nova opção(ext) 										#
#########################################################
# V 0.4													#
# -Pegar cache mesmo que tenha expirado					#
#########################################################
# V 0.3													#
# -Novas funções(setMultiple, getMultiple)				#
#########################################################
# V 0.2													#
# -Nova opção(cfgCacheFile)								#
#########################################################
# V 0.1													#
# -Primeira versão										#
# -Funções básicas(get, set, del)						#
#########################################################
*/
class LzpCache{
	#######
	# CFG #
	#######
	protected $cfg = array('dir' => __DIR__.'/cache/', 'expire' => 600, 'compress' => 0, 'cacheNameType' => array('hash' => 'md5', 'prefix' => '%name%_'), 'ext' => '.cache');

	#############
	# CONSTRUCT #
	#############
	function __construct($config = false){
		if(is_array($config)){
			$this->cfg = array_replace($this->cfg, $config);
		}
	}

	#################################################
	# CONFIG/NAME/ENCODE/DECODE/COMPRESS/UNCOMPRESS #
	#################################################
	public  function Config($config){$this->cfg = array_replace($this->cfg, $config);}
	private function Name($name){$name = preg_replace("/[^a-zA-Z0-9_.-]/", "", $name);$name = is_array($this->cfg['cacheNameType'])?str_ireplace('%name%', $name, $this->cfg['cacheNameType']['prefix']).hash($this->cfg['cacheNameType']['hash'], $name):false;return $name;}
    private function Encode($data){return (is_array($data) || is_object($data))?serialize($data):$data;}
    private function Decode($data){$x = @unserialize($data);return ($x === 'b:0;' || $x !== false)?$x:$data;}
	private function Compress($data){return (function_exists('gzdeflate') && function_exists('gzinflate'))?gzdeflate($data, $this->cfg['compress']):$data;}
	private function Uncompress($data){return (function_exists('gzinflate'))?gzinflate($data):$data;}

	##########
	# EXISTS #
	##########
	public function Exists($name){
		return is_readable($this->cfg['dir'].$this->Name($name).$this->cfg['ext']);
	}

	####################
	# EXISTS MULTIPLES #
	####################
	public function ExistsMultiples($names){
		foreach($names as $name){
			$exists[$name] = is_readable($this->cfg['dir'].$this->Name($name).$this->cfg['ext']);
		}
		return $exists;
	}

	##########
	# CREATE #
	##########
	public function Create($name, $data){
		if(!is_writeable($this->cfg['dir']))die('Direrório não diponível ou sem permissão para escrita');
		$name = $this->Name($name);
		$data = $this->Encode($data);

		$cacheData = array(
			'compress' => $this->cfg['compress'],
			'expire' => ($this->cfg['expire']!=0)?(time() + (Int)$this->cfg['expire']):0,
			'data' => ($this->cfg['compress'] > 0 && $this->cfg['compress'] <= 9)?$this->Compress($data):$data
		);

		$cacheData = $this->Encode($cacheData);

		$put = file_put_contents($this->cfg['dir'].$name.$this->cfg['ext'], $cacheData);

		return ($put!==false);
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
	public function Get($name, $expired=false){
		$name = $this->Name($name);
		$file = $this->cfg['dir'].$name.$this->cfg['ext'];

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
		foreach($names as $name){
			$data[$name] = $this->get($name, $expired);
		}
		return $data;
	}

	##########
	# DELETE #
	##########
	public function Delete($name){
		$name = $this->Name($name);
		$file = $this->cfg['dir'].$name.$this->cfg['ext'];

		if(is_file($file)){return @unlink($file);}
		return false;
	}

	####################
	# DELETE MULTIPLES #
	####################
	public function DeleteMultiples($names){
		$del = array();
		foreach($names as $name){
			$name = $this->Name($name);
			$file = $this->cfg['dir'].$name.$this->cfg['ext'];
			if(is_file($file)){$del[] = @unlink($file);}
		}
		return !in_array(false, $del);
	}

	##############
	# DELETE ALL #
	##############
	public function DeleteAll(){
		$del = array();
		$files = glob($this->cfg['dir']);
		foreach($files as $file){
		  if(is_file($file)){$del[] = @unlink($file);}
		}
		return !in_array(false, $del);
	}
}