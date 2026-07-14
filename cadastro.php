
<!DOCTYPE html>
<html lang="pt-br">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Atleta | Iron Guard</title>
    <!-- CSS do Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
 
        h2,
        h4 {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }
 
        .registration-header {
            background-color: #121212;
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
            position: relative;
        }
 
        .card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
 
        .section-title {
            border-left: 5px solid #d9534f;
            padding-left: 15px;
            margin-bottom: 20px;
            color: #333;
        }
 
        .btn-danger {
            background-color: #d9534f;
            border: none;
            padding: 12px 30px;
        }
 
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: 0.3s;
        }
 
        .back-link:hover {
            color: white;
        }
    </style>
</head>
 
<body>
 
    <header class="registration-header text-center">
        <a href="menu.php" class="back-link small">← Voltar para o Início</a>
        <div class="container">
            <h2>Junte-se à Arte Suave</h2>
            <p class="mb-0 text-white-50">Preencha sua ficha para começar os treinos</p>
        </div>
    </header>
 
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card p-4 p-md-5">
 
                    <?php if (isset($_GET['erro'])): ?>
                        <div class="alert alert-danger">
                            <?php
                            $erros = [
                                'campos' => 'Preencha todos os campos obrigatórios.',
                                'senha' => 'As senhas digitadas não conferem.',
                                'email' => 'Este e-mail já está cadastrado.',
                            ];
                            echo $erros[$_GET['erro']] ?? 'Erro ao processar o cadastro.';
                            ?>
                        </div>
                    <?php endif; ?>
 
                    <form action="cadastro-processar.php" method="POST">
                        <!-- SEÇÃO 1: DADOS PESSOAIS -->
                        <h4 class="section-title">Dados Pessoais</h4>
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label">Nome Completo</label>
                                <input type="text" name="nome" class="form-control" placeholder="Ex: Hélio Gracie" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" placeholder="atleta@email.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CPF</label>
                                <input type="text" name="cpf" class="form-control" placeholder="000.000.000-00" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Senha</label>
                                <input type="password" name="senha" class="form-control" placeholder="Mínimo 6 caracteres" minlength="6" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar Senha</label>
                                <input type="password" name="confirmar_senha" class="form-control" placeholder="Repita a senha" minlength="6" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">CEP</label>
                                <input type="text" name="endereco" class="form-control" placeholder="00000-000">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Rua</label>
                                <input type="text" name="endereco" class="form-control" placeholder="Rua">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Bairro</label>
                                <input type="text" name="endereco" class="form-control" placeholder="Bairro">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Cidade</label>
                                <input type="text" name="endereco" class="form-control" placeholder="Cidade">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Numero</label>
                                <input type="text" name="endereco" class="form-control" placeholder="Número">
                            </div>
                        </div>
 
                        <!-- BOTÕES -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="menu.php" class="btn btn-outline-secondary me-md-2">Sair e Voltar ao Início</a>
                            <button type="submit" class="btn btn-danger btn-lg text-uppercase fw-bold">Finalizar
                                Cadastro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 
    <!-- JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
 
</html>
