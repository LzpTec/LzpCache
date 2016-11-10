# LzpCache Changelog

## Versão Atual
2.1.2 - 02/10/2016

## V2.1.2
-Corrigido bug na configuração

-Melhorias na configuração do cache

-Pequena melhoria na performance

## V2.1.1
-Corrigidos bugs nas funções de compressão e descompressão

-Compressão melhorada

-Melhorias no código

-Pequena melhoria na performance

-Nova opção para configurar a compressão(compressType)

## V2.1.0
-Novo Sistema de nome(Não funciona com a v2.0)

-Nomes agora são case-sensitive

-Performance Otimizada

-Removida a configuração useNewNameSystem

-Alguns bug's corrigidos

-Hash padrão trocada para SHA1

## V2.0.3
-Corrigida a opção de compressão Bzip2

## V2.0.2[YANKED]
-Novas opções de compressão(Lzf e Bzip2)

## V2.0.1
-Removida a configuração cacheNameCfg

-Nova configuração nameHash

-Novo Sistema de nome(BETA) - configuração useNewNameSystem

-Performance Otimizada

-Melhorias na documentação

-Removido argumento dir da função Size

-Corrigido um bug no sistema de versão do cache

-Corrigido bug na função delete

## V2.0.0
-Novo parametro para Create($expire)

-Função Delete e Create modificada

-Função DeleteAll renomeada para Clear

-Nova função Set(Mesma coisa que a Create)

-Nova função Read(Mesma coisa que a Get)

-Nova função Remove(Mesma coisa que a Delete)

-Diretórios agora são criados com permissão 0777

-Código Documentado

-Código Reescrito

-Performance Otimizada

-Documentação atualizada

-Configuração cacheNameType renomeada para cacheNameCfg

-Removido parametro da Create($config)

-Removida Criptografia do cache

-Renomeado DirSize para Size

-Função Unida ExistsMultiples -> Exists

-Função Unida CreateMultiples -> Create

-Função Unida GetMultiples -> Get

-Função Unida DeleteMultiples -> Delete

-Removido changelog do código

## V1.3.0
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

## V1.2.1
-Argumento opcional adicionado nas Configurações('version')

-Performance Otimizada

## V1.2.0
-Documentação atualizada

-Performance Otimizada

-Modificado LzpCache -> Cache e Lozep -> Lzp

-Modificado new Lozep\LzpCache -> new Lzp\Cache

## V1.1.1
-Performance Otimizada

-Melhora na Organização do código

-Melhora na documentação

-Novo parametro para DeleteAll($version)

-Novo parametro para Create($config)

-Modificação nas funções(veja mais nos exemplos):

DeleteMultiples -> Retorna um array($nomecache=>$foiDeletado)

CreateMultiples -> Retorna um array($nomecache=>$foiCriado)

Delete -> Retorna true(sucesso), false(falha) ou null(não existe)


## V1.1.0
-Novo argumento opcional($cacheVersion)

-Melhor documentação

-Performance Otimizada

-Nova função(cacheDirSize)

## V1.0.0
-Lançamento do código para uso livre(MIT License)