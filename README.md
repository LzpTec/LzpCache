# LzpCache
**LzpCache** é uma classe para cache.

## Versão Atual
1.0.0

## Recursos
- Compressão do cache
- Nome personalizado para o cache/Hash de escolha do usuário
- Extensão do arquivo cache personalizada
- Criar/Obter/Deletar vários caches de uma vez
- É possível obter um cache mesmo que ele tenha expirado


## Recursos ainda não implementados
- Configuração personalizada para criar o cache($cache->Set(string $name, mixed $data, array $config)


## Recursos que talvez sejam introduzidos
- Suporte para MemCache e MemCached.


##  Como Usar
Para Configurar:
```php
 	$config = array('dir' => __DIR__.'/cache/', 'expire' => 600, 'compress' => 0, 'cacheNameType' => array('hash' => 'md5', 'prefix' => '%name%_'), 'ext' => '.cache'); //Valores padrões
 	$cache = new Lozep\LzpCache([array $config]);
 	$cache->Config([array $config]);
```

Para verificar se um cache existe:
```php
 	$cacheExists = $cache->Exists(string $cacheName); // Retorna false se o cache não existir
```

Para verificar se vários caches existem:
```php
	$cachesExists = $cache->ExistsMultiples(array $cachesNames); // Retorna um array($nomecache=>$exists) caso esse cache não exista $exists recebe false
```
Para obter um Cache:
```php	
	$cacheGet = $cache->Get(string $cacheName, bool $getExpired=false); // Retorna null se o cache não existir
```
Para obter vários caches de uma vez:
```php
	$cacheMultiples = $cache->GetMultiples(array $cachesNames, bool $getExpired=false); // Retorna um array($nomecache=>$valor) caso esse cache não exista retorna null
```
Para criar um Cache:
```php
	$cache->Create(string $cacheName, mixed $data);//Retorna true em sucesso
```
Para criar vários caches:
```php
	$namesAndValues = array('cacheName1' => $value, 'cacheName2' => $value);
	$cache->CreateMultiples(array $namesAndValues);//Retorna true em sucesso
```
Para deletar um cache:
```php
	$cache->Delete('cacheName');//Retorna true em sucesso 
```
Para deletar vários caches:
```php
	$cache->DeleteMultiples(array $names);//Retorna true em sucesso
```
Para deletar todos os caches:
```php
	$cache->DeleteAll();//Retorna true em sucesso 
```