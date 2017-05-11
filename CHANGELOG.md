# LzpCache Changelog

## Versão Atual
1.5 - 11/05/2017

## V 1.5
-Código mais organizado

-Mudança no sistema de versão

-Performance otimizada

-Parâmetro dividido: cacheNameType -> namePrefix & storageHash

## V 1.4.1
-Mudança no sistema de versão

-Removido changelog do código

-Criptografia do cache removida

-Performance otimizada

## V 1.4.0
-Criptografia do cache final adicionada(Consome desempenho)

## V 1.3.0
-Performance Otimizada

-Documentação atualizada

-Criptografia do cache(Consome desempenho)

-Modificado CacheDirSize -> DirSize

-Bugs na função DirSize corrigidos

-Novo parâmetro para ExistsMultiples($version)

-Novo parâmetro para CreateMultiples($version)

-Novo parâmetro para GetMultiples($version)

-Novo parâmetro para DeleteMultiples($version)

-Novo parâmetro para DirSize($version)

-Extensão padrão modificada para(.lzp)

## V 1.2.1
-Argumento opcional adicionado nas Configurações('version')

-Performance Otimizada

## V 1.2.0
-Documentação atualizada

-Performance Otimizada

-Modificado LzpCache -> Cache e Lozep -> Lzp

-Modificado new Lozep\LzpCache -> new Lzp\Cache

## V 1.1.1
-Performance Otimizada

-Melhora na Organização do código

-Melhora na documentação

-Novo parametro para DeleteAll($version)

-Novo parametro para Create($config)

-Modificação nas funções(veja mais nos exemplos):

DeleteMultiples -> Retorna um array($nomecache=>$foiDeletado)

CreateMultiples -> Retorna um array($nomecache=>$foiCriado)

Delete -> Retorna true(sucesso), false(falha) ou null(não existe)


## V 1.1.0
-Novo argumento opcional($cacheVersion)

-Melhor documentação

-Performance Otimizada

-Nova função(cacheDirSize)

## V 1.0.0
-Lançamento do código para uso livre(MIT License)