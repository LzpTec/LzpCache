# LzpCache
**LzpCache** é uma classe para cache.

## Versão Atual
1.1.0 - 06/06/2016

## Recursos
- Compressão do cache
- Nome personalizado para o cache/Hash de escolha do usuário
- Extensão do arquivo cache personalizada
- Criar/Obter/Deletar vários caches de uma vez
- É possível obter um cache mesmo que ele tenha expirado


## Recursos ainda não implementados
- Argumento opcional($cacheVersion) adicionado nas Configurações(version)
- Argumento opcional($cacheVersion) adicionado nas Funções(GetMultiples, CreateMultiples, DeleteMultiples, ExistsMultiples)
- Configuração personalizada para criar o cache($cache->Create($name, $data, $version, $config)


## Recursos que talvez sejam introduzidos
- Suporte para MemCache e MemCached.


##  Como Usar
Inicializar:
```php
	//Sem configurações
	$cache = new Lozep\LzpCache;

	//Com configurações
	$cache = new Lozep\LzpCache($config);
```

Para Configurar:
```php
	//Configurações
		$config = array('dir', 'expire', 'compress', 'cacheNameType', 'ext')
	//Parametros( = Padrão):
		$config['dir'] = __DIR__.'/cache/'; // Caminho do Diretório
		$config['expire'] = (int)600;
		$config['compress'] = (int)0;
		$config['cacheNameType'] = array('hash' => 'md5', 'prefix' => '%name%_'); //Use %name% para colocar o nome do cache no prefixo
		$config['ext'] = (string)'.cache';

	//Aplicar Configuração:
 	$cache->Config($config);
```

Para obter um único cache:
```php
	$cache->Get($cacheName, $getExpired, $cacheVersion); //Retorna null se o cache não existir
 	//Parametros( = Padrão):
 		$cacheName = 'nome_do_cache'; //Nome do cache(Parametro obrigatório)
 		$getExpired = false; //Ignora se o cache já expirou(opcional)
 		$cacheVersion = false; //Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
```

Para obter múltiplos caches:
```php
 	$cache->GetMultiples($cachesNames, $getExpired); //Retorna um array($nomeDoCache=>$valor)
 	//Parametros( = Padrão):
 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); //Array contendo o Nome de cada cache(Parametro obrigatório)
 		$getExpired = false; // Ignora se o cache já expirou(opcional)
```

Para criar um cache:
```php
 	$cache->Create($cacheName, $data, $cacheVersion); //Retorna true em caso de sucesso
 	//Parametros( = Padrão):
 		$cacheName = 'nome_do_cache'; //Nome do cache(Parametro obrigatório)
 		$data = 'dadosDoCache'; //Dados a serem guardados no cache, tudo é aceito(Parametro obrigatório)
 		$cacheVersion = false; //Versão do cache a ser criado - Valores Aceitos(float, string, int) - (opcional)
```

Para criar múltiplos caches:
```php
 	$cache->CreateMultiples($namesAndValues); //Retorna true se os caches forem criados
 	//Parametros:
		$namesAndValues = array('nome_do_cache00' => $value, 'nome_do_cache01' => $value); //Array contendo os Nomes e os valores dos caches a serem criados(Parametro obrigatório)
```

Para deletar um cache:
```php
 	$cache->Delete($cacheName, $cacheVersion); //Retorna true se o cache for excluido
 	//Parametros( = Padrão):
 		$cacheName = 'nome_do_cache'; //Nome do cache(Parametro obrigatório)
 		$cacheVersion = false; //Versão do cache a ser criado - Valores Aceitos(float, string, int) - (opcional)
```

Para Deletar Múltiplos caches:
```php
 	$cache->DeleteMultiples($cachesNames); //Retorna true se todos os caches forem excluidos
 	//Parametros:
 		$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); //Array contendo o Nome de cada cache(Parametro obrigatório)
```

Para deletar todos os caches:
```php
	$cache->DeleteAll();//Retorna true em sucesso 
```

Para verificar se um cache existe:
```php
 	$cacheExists = $cache->Exists($cacheName, $cacheVersion); // Retorna false se o cache não existir
```

Para verificar se vários caches existem:
```php
	$cache->ExistsMultiples(array('nome_do_cache00', 'nome_do_cache01')); // Retorna um array($nomecache=>$exists) caso esse cache não exista $exists recebe false
```