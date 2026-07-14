<?php

$base_url = "http://localhost/projeto99/admin/";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel AdminLTE</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light" style="min-height: 100vh;">
    <!-- 1. NAVBAR SUPERIOR (Top Navigation) -->
    <nav class="navbar navbar-expand navbar-white bg-white border-bottom sticky-top py-2">
        <div class="container-fluid px-3">
            <!-- Lado Esquerdo: Links Rápidos -->
            <ul class="navbar-nav align-items-center">
                <li class="nav-item me-3 d-none d-md-block">
                    <span class="navbar-brand fs-5 fw-bold m-0">Admin<span class="text-primary">LTE</span></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary" href="<?= $base_url ?>home.php">Dashboard</a>
                </li>
            </ul>
            <!-- Lado Direito: Perfil / Sair -->
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-danger fw-semibold d-flex align-items-center gap-1" href="<?= $base_url ?>logout.php">
                        <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Sair</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Conteiner Principal para a estrutura de Sidebar + Conteúdo -->
    <div class="container-fluid p-0">
        <div class="row g-0" style="min-height: calc(100vh - 56px);">

            <!-- 2. SIDEBAR LATERAL (Menu de Navegação Principal) -->
            <!-- Ocupa 2 colunas em telas grandes (lg) e vira topo em telas pequenas -->
            <aside class="col-12 col-lg-2 bg-dark text-light p-3">
                <!-- Info do Usuário Logado -->
                <div class="d-flex align-items-center pb-3 mb-3 border-bottom border-secondary px-2">
                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white me-2" style="width: 35px; height: 35px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <span class="d-block fw-lighter text-secondary small">Bem-vindo</span>
                        <span class="d-block fw-bold text-wrap small">Usuário Admin</span>
                    </div>
                </div>
                <!-- Título da Seção do Menu -->
                <p class="text-uppercase text-secondary fw-bold px-2 mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Navegação</p>
                <!-- Links do Menu -->
                <ul class="nav nav-pills flex-column gap-1">
                    <li class="nav-item">
                        <a href="<?= $base_url ?>home.php" class="nav-link text-light d-flex align-items-center gap-2 active py-2">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_url ?>site/select.php" class="nav-link text-light d-flex align-items-center gap-2 py-2 opacity-75">
                            <i class="bi bi-people-fill"></i>
                            <span>Produtos</span>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- 3. ÁREA DE CONTEÚDO (Onde os arquivos do seu projeto vão renderizar) -->
            <!-- Ocupa as 10 colunas restantes -->
            <main class="col-12 col-lg-10 p-4">