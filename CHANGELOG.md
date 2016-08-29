# LzpCache Changelog

## Versão Atual
2.0.0(Pré-Release) - 29/08/2016

## V 2.0.0
-Função Create modificada

-Nova função Set(Mesma coisa que a Create)

-Diretórios agora são criados com permissão 0777

-Código Documentado

-Código Reescrito

-Performance Otimizada

-Removida Criptografia do cache

-Documentação atualizada

-Modificado DirSize -> Size

-Função Unida ExistsMultiples -> Exists

-Função Unida CreateMultiples -> Create

-Função Unida GetMultiples -> Get

-Função Unida DeleteMultiples -> Delete

## V 1.3.0
-Performance Otimizada

-Documentação atualizada

-Criptografia do cache(Consome desempenho - Necessário remover comentários do código)

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