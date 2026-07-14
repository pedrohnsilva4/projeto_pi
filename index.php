<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login AdminLTE Simples</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (Ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="col-11 col-sm-8 col-md-6 col-lg-3">
        
        <!-- Título padrão AdminLTE -->
        <div class="text-center mb-3 fs-2">
            <a href="#" class="text-decoration-none text-dark"><b>Admin</b>LTE</a>
        </div>

        <div class="card rounded-0 border-top border-3 shadow-sm">
            <div class="card-body">
                <p class="text-center small">Faça login para iniciar sua sessão</p>

                <form action="logar.php" method="post">
                    <!-- Campo E-mail -->
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control rounded-0" placeholder="Email">
                        <span class="input-group-text bg-transparent rounded-0">
                            <i class="fas fa-envelope text-secondary"></i>
                        </span>
                    </div>

                    <!-- Campo Senha -->
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control rounded-0" placeholder="Senha">
                        <span class="input-group-text bg-transparent rounded-0">
                            <i class="fas fa-lock text-secondary"></i>
                        </span>
                    </div>

                    <div class="row align-items-center">
                        <!-- Checkbox -->
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label" for="remember">
                                    Lembrar-me
                                </label>
                            </div>
                        </div>
                        <!-- Botão -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary w-100 rounded-0 fw-bold">Entrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>