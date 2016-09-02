# LzpCache
**LzpCache** é uma classe para cache.

## Versão Atual
1.4.0 - 01/09/2016(Última atualização da versão 1.X)


## Recursos
- Compressão do cache
- Nome personalizado para o cache/Hash de escolha do usuário
- Extensão do arquivo cache personalizada
- Criar/Obter/Deletar vários caches de uma vez
- É possível obter um cache mesmo que ele tenha expirado


## Recursos que talvez sejam introduzidos
- Suporte para MemCache e MemCached.


##  Como Usar
Inicializar:
```php
	//Sem configurações
	$cache = new Lzp\Cache;

	//Com configurações
	$cache = new Lzp\Cache($config);
```

Para Configurar:
```php
	//Configurações
		$config = array('dir', 'expire', 'version', 'compress', 'cacheNameType', 'ext');
	//Parametros( = Padrão):
		$config['dir'] = __DIR__.'/cache/'; 										//Caminho do Diretório onde o cache será armazenado
		$config['expire'] = 600; 													//0 para infinito - Valor Aceito int(opcional)
		$config['version'] = false; 												//false ou 0 desativam - Valores Aceitos float, string e int(opcional)
		$config['compress'] = 0;													//0 desativa - Valor Aceito int de 0 a 9(opcional)
		$config['cacheNameType'] = array('hash' => 'md5', 'prefix' => '%name%_'); 	//Use %name% para colocar o nome do cache no prefixo(opcional)
		$config['ext'] = '.lzp'; 													//Extensão do arquivo de cache(opcional)
		$config['crypt'] = false; 													//false desativa, adicione uma Chave de 64 digitos hexadecimal para ativar
	//Aplicar Configuração:
		$cache->Config($config);
```

Para obter um único cache:
```php
	$cache->Get($cacheName, $getExpired, $cacheVersion);
	//Parametros( = Padrão):
 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Parametro obrigatório)
 		$getExpired = false;											//Ignora se o cache já expirou(opcional)
 		$cacheVersion = false;											//Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
```

Para obter múltiplos caches:
```php
 	$cache->GetMultiples($cachesNames, $getExpired, $cacheVersion);		//Retorna um array($nomeDoCache=>$valor)
 	//Parametros( = Padrão):
		$cachesNames = array('nome_do_cache00', 'nome_do_cache01');		//Array contendo o Nome de cada cache(Parametro obrigatório)
		$getExpired = false;											//Ignora se o cache já expirou(opcional)
 		$cacheVersion = false;											//Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
```

Para criar um cache:
```php
 	$cache->Create($cacheName, $data, $cacheVersion, $config); 								//Retorna true em caso de sucesso
	//Parametros( = Padrão):
 		$cacheName = 'nome_do_cache';														//Nome do cache(Parametro obrigatório)
 		$data = 'dadosDoCache';																//Dados a serem guardados no cache, tudo é aceito(Parametro obrigatório)
 		$cacheVersion = false;																//Versão do cache a ser criado - Valores Aceitos(float, string, int) - (opcional)
		$config = false;																	//Configurações para o cache Aceito: array('expire', 'compress')
```

Para criar múltiplos caches:
```php
  	$cache->CreateMultiples($namesValues, $cacheVersion); 									//Retorna um array($nomecache=>$foiCriado) se o cache foi criado com sucesso $foiCriado recebe true
	//Parametros( = Padrão):
		$namesValues = array('nome_do_cache00' => $value, 'nome_do_cache01' => $value); 	//Array contendo os Nomes e os valores dos caches a serem criados(Parametro obrigatório)
		$cacheVersion = false; 																//Versão dos caches a serem criados - Valores Aceitos float, string e int(opcional)
```

Para deletar um cache:
```php
 	$cache->Delete($cacheName, $cacheVersion); 							//Retorna true se o cache for excluido, false em caso de falha e null caso o cache não exista
 	//Parametros( = Padrão):
 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Parametro obrigatório)
 		$cacheVersion = false; 											//Versão do cache a ser deletado - Valores Aceitos float, string e int(opcional)
```

Para Deletar Múltiplos caches:
```php
 	$cache->DeleteMultiples($cachesNames, $cacheVersion); 				//Retorna um array($nomecache=>$foiDeletado), $foiDeletado = true(sucesso), false(falha) ou null(cache não existe)
 	//Parametros( = Padrão):
 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Parametro obrigatório)
		$cacheVersion = false; 											//Versão dos caches a serem deletados - Valores Aceitos float, string e int(opcional)
```

Para deletar todos os caches:
```php
 	$cache->DeleteAll($cacheVersion); 									//Retorna true se os caches forem excluídos
 	//Parametros( = Padrão):
 		$cacheVersion = false; 											//Deleta os caches de uma certa versão - Valores Aceitos float, string e int(Parametro opicional)
```

Para verificar se um cache existe:
```php
 	$cache->Exists($cacheName, $cacheVersion);
 	//Parametros( = Padrão):
 		$cacheName = 'nome_do_cache'; 									//Nome do cache(Parametro obrigatório)
		$cacheVersion = false; 											//Versão a ser verificada - Valores Aceitos float, string e int(opcional)
```

Para verificar se vários caches existem:
```php
	$cache->ExistsMultiples($cachesNames, $cacheVersion);
 	//Parametros( = Padrão):
 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Parametro obrigatório)
		$cacheVersion = false; 											//Versão a ser verificada - Valores Aceitos float, string e int(opcional)
```

Para verificar se vários caches existem:
```php
	$cache->ExistsMultiples($cachesNames, $cacheVersion);
 	//Parametros( = Padrão):
 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Parametro obrigatório)
		$cacheVersion = false; 											//Versão a ser verificada - Valores Aceitos float, string e int(opcional)
```

Para verificar o tamanho do diretório de cache:
```php
	$cache->DirSize($dir, $version);
	//Parametros( = Padrão):
		$dir = null;								//Diretório a ser verificado(opcional)	
		$cacheVersion = false; 						//Retorna o tamanho do cache de uma certa versão - Valores Aceitos float, string e int(opcional)
```