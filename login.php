<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso do Atleta | Iron Guard</title>
    <!-- CSS do Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Roboto', sans-serif; 
            background-color: #121212; /* Fundo escuro para combinar com a academia */
            height: 100vh;
            display: flex;
            align-items: center;
        }
        h2 { font-family: 'Oswald', sans-serif; text-transform: uppercase; }
        .login-card {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .btn-danger { background-color: #d9534f; border: none; }
        .btn-danger:hover { background-color: #c9302c; }
        .form-control:focus { border-color: #d9534f; box-shadow: 0 0 0 0.25rem rgba(217, 83, 79, 0.25); }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-5 col-lg-4">
                
                <!-- Logo ou Nome da Academia -->
                <div class="text-center mb-4">
                    <a href="index.html" class="text-decoration-none">
                        <h2 class="text-white">Arte Suave</h2>
                    </a>
                </div>

                <div class="card login-card p-4">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Acesso do Atleta</h2>
                        
                        <form action="admin/logar.php" method="POST">
                            <!-- Campo E-mail -->
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail ou CPF</label>
                                <input type="email" class="form-control" id="email" placeholder="nome@exemplo.com" name="email" required>
                            </div>
                            
                            <!-- Campo Senha -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" placeholder="Sua senha" name="password" required>
                            </div>

                            <!-- Link Esqueci Senha -->
                            <div class="mb-3 text-end">
                                <a href="#" class="text-muted small">Esqueceu a senha?</a>
                            </div>

                            <!-- Botão de Entrar -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-dark btn-lg">Entrar no Tatame</button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <!-- Seção para Novos Alunos -->
                        <div class="text-center">
                            <p class="mb-2 text-muted">Ainda não tem conta?</p>
                            <a href="cadastro.html" class="btn btn-outline-danger w-100">Criar Cadastro de Perfil</a>
                        </div>
                    </div>
                </div>

                <!-- Botão Voltar -->
                <div class="text-center mt-4">
                    <a href="index.html" class="text-white-50 text-decoration-none small">← Voltar para a página inicial</a>
                </div>

            </div>
        </div>
    </div>

    <!-- JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>