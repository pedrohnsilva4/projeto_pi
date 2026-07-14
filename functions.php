<?php
// ============================================================
// Funções de manipulação do carrinho de compras (sessão)
// Estrutura do carrinho: $_SESSION['carrinho'][chave] = [
//     'produto_id' => int,
//     'tamanho'    => string,
//     'quantidade' => int
// ]
// A "chave" combina produto_id + tamanho, pois o mesmo produto
// pode ser adicionado em tamanhos diferentes.
// ============================================================
 
/**
 * Adiciona um produto ao carrinho.
 * Se o mesmo produto+tamanho já existir, soma a quantidade.
 * Caso contrário, cria um novo item.
 */
function addCar($produtoId, $tamanho, $qtd = 1)
{
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }
 
    $produtoId = (int) $produtoId;
    $qtd       = max(1, (int) $qtd);
    $tamanho   = $tamanho !== '' ? $tamanho : 'ÚNICO';
    $chave     = $produtoId . '_' . $tamanho;
 
    if (isset($_SESSION['carrinho'][$chave])) {
        // já existe esse produto+tamanho no carrinho: soma a quantidade
        $_SESSION['carrinho'][$chave]['quantidade'] += $qtd;
    } else {
        // novo item no carrinho
        $_SESSION['carrinho'][$chave] = [
            'produto_id' => $produtoId,
            'tamanho'    => $tamanho,
            'quantidade' => $qtd,
        ];
    }
}
 
/**
 * Diminui a quantidade de um item do carrinho.
 * Remove o item automaticamente se a quantidade chegar a zero.
 */
function removeCar($chave, $qtd = 1)
{
    if (!isset($_SESSION['carrinho'][$chave])) {
        return;
    }
 
    $_SESSION['carrinho'][$chave]['quantidade'] -= max(1, (int) $qtd);
 
    if ($_SESSION['carrinho'][$chave]['quantidade'] <= 0) {
        unset($_SESSION['carrinho'][$chave]);
    }
}
 
/**
 * Remove um item do carrinho por completo, independente da quantidade.
 */
function removeItemCar($chave)
{
    if (isset($_SESSION['carrinho'][$chave])) {
        unset($_SESSION['carrinho'][$chave]);
    }
}
 
/**
 * Atualiza diretamente a quantidade de um item já existente no carrinho.
 */
function atualizarQtdCar($chave, $qtd)
{
    if (isset($_SESSION['carrinho'][$chave])) {
        $qtd = max(1, (int) $qtd);
        $_SESSION['carrinho'][$chave]['quantidade'] = $qtd;
    }
}
 
/**
 * Retorna a quantidade total de itens no carrinho (soma das quantidades).
 * Útil para exibir o badge "Carrinho (n)" no menu.
 */
function totalItensCar()
{
    if (empty($_SESSION['carrinho'])) {
        return 0;
    }
    return array_sum(array_column($_SESSION['carrinho'], 'quantidade'));
}
