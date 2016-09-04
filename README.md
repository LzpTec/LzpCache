# LzpCache
**LzpCache** é uma classe escrita em php para cache.

## Versão Atual
2.0.3 - 03/09/2016

**LzpCache V2** os caches das versões anteriores não vão funcionar na nova versão

## Recursos
- Compressão do cache
- Nome personalizado para o cache/Hash de escolha do usuário
- Extensão do arquivo cache personalizada
- Criar/Obter/Deletar vários caches de uma vez
- É possível obter um cache mesmo que ele tenha expirado
- Compressão LZF e Bzip2


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
	$config = array('dir', 'expire', 'version', 'compress', 'nameHash', 'ext', 'useNewNameSystem', 'useLZF', 'useBZ');
//Parametros( = Padrão):
	$config['dir'] = __DIR__.'/cache/'; 				//Caminho do Diretório onde o cache será armazenado
	$config['expire'] = 600; 							//0 para infinito - Valor Aceito int(opcional)
	$config['version'] = null; 							//null desativa - Valores Aceitos float, string e int(opcional)
	$config['compress'] = 0;							//0 desativa - Valor Aceito int de 0 a 9(opcional)
	$config['nameHash'] = 'md5'							//Hash para gerar o nome do cache(opcional)
	$config['ext'] = '.lzp'; 							//Extensão do arquivo de cache(opcional)
	$config['useNewNameSystem'] = false; 				//Novo sistema de nome dos arquivos, nomes melhores e limitados a 30 caracteres
	$config['useLZF'] = false; 							//Substui a compressão Gzip pela compressão Lzf
	$config['useBZ'] = false; 							//Substui a compressão Gzip pela compressão Bzip2
//Aplicar Configuração:
	$cache->Config($config);
```

Para obter um único cache:
```php
$cache->Get($cacheName, $getExpired, $version);
$cache->Read($cacheName, $getExpired, $version);
//Parametros( = Padrão):
	$cacheName = 'nome_do_cache'; 	//Nome do cache(Parametro obrigatório)
	$getExpired = false;			//Ignora se o cache já expirou(opcional)
	$version = null;				//Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
```

Para obter múltiplos caches:
```php
$cache->Get($cachesNames, $getExpired, $version);					//Retorna um array($nomeDoCache=>$valor)
$cache->Read($cachesNames, $getExpired, $version);
//Parametros( = Padrão):
	$cachesNames = array('nome_do_cache00', 'nome_do_cache01');		//Array contendo o Nome de cada cache(Parametro obrigatório)
	$getExpired = false;											//Ignora se o cache já expirou(opcional)
	$version = null;												//Versão do cache a ser obtido - Valores Aceitos float, string e int(opcional)
```

Para criar um ou mais caches:
```php
$cache->Create($namesAndValues, $expire, $version); 			//Retorna true em caso de sucesso
$cache->Set($namesAndValues, $expire, $version); 				//Retorna true em caso de sucesso
//Parametros( = Padrão):
	$namesAndValues = array('nome_do_cache00' => $value); 		//Array contendo os Nomes e os valores dos caches a serem criados(Parametro obrigatório)
	$expire = 0;												//Tempo do cache / 0 para infinito - Valor Aceito int(opcional)
	$version = null;											//Versão do cache a ser criado - Valores Aceitos(float, string, int) - (opcional)
```

Para deletar um ou mais caches:
```php
$cache->Delete($cachesNames, $version); 				//Retorna um array($nomecache=>$foiDeletado), $foiDeletado = true(sucesso), false(falha) ou null(cache não existe)
$cache->Remove($cachesNames, $version);
//Parametros( = Padrão):
	$cachesNames = array('nome_do_cache00', ...); 		//Array contendo o Nome de cada cache(Required)
	$version = null; 									//Versão dos caches a serem deletados - Valores Aceitos float, string e int(Opcional)
```

```php
Para deletar todos os caches:
$cache->Clear($version); 			//Retorna true se os caches forem excluídos
//Parametros( = Padrão):
	$version = null; 				//Deleta os caches de uma certa versão - Valores Aceitos float, string e int(Parametro opicional)
```

Para verificar se um cache existe:
```php
$cache->Exists($cacheName, $version);		//Retorna true se o cache existe
//Parametros( = Padrão):
	$cacheName = 'nome_do_cache'; 			//Nome do cache(Required)
	$version = null; 						//Versão a ser verificada - Valores Aceitos float, string e int(Opcional)
```

Para verificar se vários caches existem:
```php
$cache->Exists($cachesNames, $version);								//Retorna um array($nomecache=>$exists)
//Parametros( = Padrão):
	$cachesNames = array('nome_do_cache00', 'nome_do_cache01'); 	//Array contendo o Nome de cada cache(Required)
	$version = null; 												//Versão a ser verificada - Valores Aceitos float, string e int(Opcional)
```

Para verificar o tamanho do diretório de cache:
```php
$cache->Size($version);			//Retorna tamanho do diretório ou null(diretório vazio)
//Parametros( = Padrão):
	$version = null; 			//Retorna o tamanho do cache de uma certa versão - Valores Aceitos float, string e int(Opcional)
```