<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login - Sistema PONTI' ?></title>
    <meta name="description" content="<?= $description ?? 'Acesso ao painel administrativo do Sistema PONTI' ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= ASSETS_URL ?>favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/all.min.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>styles.css">
    
    <style>
        body {
            background: #000000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        /* Background pattern/effects */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 80%, rgba(255, 197, 58, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 197, 58, 0.05) 0%, transparent 50%),
                        radial-gradient(circle at 40% 40%, rgba(255, 197, 58, 0.03) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }
        
        .login-container {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4),
                        inset 0 1px 0 rgba(255, 197, 58, 0.2);
            border: 1px solid rgba(255, 197, 58, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
            min-height: 600px;
            position: relative;
            z-index: 2;
        }
        
        .login-image {
            flex: 1;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-align: center;
            position: relative;
            border-right: 1px solid rgba(255, 197, 58, 0.2);
        }
        
        .login-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 50%, rgba(255, 197, 58, 0.05) 0%, transparent 70%);
            z-index: 1;
        }
        
        .login-image-content {
            position: relative;
            z-index: 2;
            padding: 2rem;
        }
        
        .login-image h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: #FFC53A;
            text-shadow: 0 2px 10px rgba(255, 197, 58, 0.3);
        }
        
        .login-image p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #ffffff;
            opacity: 0.9;
        }
        
        .login-form {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(0, 0, 0, 0.5);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            color: #ffffff;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .login-header p {
            color: #8a8a8a;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #ffffff;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid rgba(255, 197, 58, 0.3);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            backdrop-filter: blur(10px);
        }
        
        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .form-input:focus {
            outline: none;
            border-color: #FFC53A;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(255, 197, 58, 0.1);
        }
        
        .form-input.error {
            border-color: #e74c3c;
            background: rgba(231, 76, 60, 0.1);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: black;
            z-index: 2;
        }
        
        .input-group .form-input {
            padding-left: 1rem;
        }
        
        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #FFC53A, #E6A201);
            color: #000000;
            width: 100%;
            margin-top: 1rem;
            font-weight: 700;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: all 0.5s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 197, 58, 0.3);
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: none;
            backdrop-filter: blur(10px);
        }
        
        .alert-error {
            background: rgba(231, 76, 60, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }
        
        .alert-success {
            background: rgba(39, 174, 96, 0.1);
            color: #27ae60;
            border: 1px solid rgba(39, 174, 96, 0.3);
        }
        
        .alert-warning {
            background: rgba(255, 197, 58, 0.1);
            color: #FFC53A;
            border: 1px solid rgba(255, 197, 58, 0.3);
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .forgot-password a {
            color: #FFC53A;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .forgot-password a:hover {
            color: #E6A201;
            text-decoration: underline;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 197, 58, 0.2);
            color: #8a8a8a;
            font-size: 0.9rem;
        }
        
        .show-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #FFC53A;
            z-index: 2;
            transition: all 0.3s ease;
        }
        
        .show-password:hover {
            color: #E6A201;
        }
        
        .brand-logo {
            margin: 1rem 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .brand-logo img {
            max-width: 200px;
            max-height: 120px;
            width: auto;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(255, 197, 58, 0.2)) 
                    drop-shadow(0 0 10px rgba(255, 255, 255, 0.1));
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 1rem;
                max-width: none;
            }
            
            .login-image {
                min-height: 200px;
            }
            
            .login-form {
                padding: 2rem;
            }
            
            .login-image h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Lado da imagem -->
        <div class="login-image">
            <div class="login-image-content">
                <div class="brand-logo" style="margin-bottom: 5rem;">
                    <img src="<?= ASSETS_URL ?>logo_com_pmnf.png" alt="PONTI - Prefeitura de Nova Friburgo" />
                </div>
                <!-- <h2>Sistema PONTI</h2> -->
                <!-- <p>Plataforma de Oportunidades de Negócios e Tecnologia Integrada</p> -->
                <p>Entre com suas credenciais para acessar o painel administrativo.</p>
            </div>
        </div>
        
        <!-- Formulário de login -->
        <div class="login-form">
            <div class="login-header">
                <h1>Fazer Login</h1>
                <p>Acesse o painel administrativo</p>
            </div>
            
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="alert alert-<?= $_SESSION['flash_type'] ?>">
                    <i class="fas fa-<?= $_SESSION['flash_type'] === 'success' ? 'check-circle' : ($_SESSION['flash_type'] === 'warning' ? 'exclamation-triangle' : 'exclamation-circle') ?>"></i>
                    <?= $_SESSION['flash_message'] ?>
                </div>
                <?php 
                unset($_SESSION['flash_message']);
                unset($_SESSION['flash_type']);
                ?>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" action="<?= Router::url('auth/authenticate') ?>">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" class="form-input" name="email" required placeholder="Digite seu email" autocomplete="email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Senha</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" class="form-input" name="senha" id="senha" required placeholder="Digite sua senha" autocomplete="current-password">
                        <i class="fas fa-eye show-password" onclick="togglePassword()"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Entrar
                </button>
            </form>
            
            <!-- <div class="forgot-password">
                <a href="#" onclick="alert('Entre em contato com o administrador para recuperar sua senha.')">
                    Esqueceu sua senha?
                </a>
            </div> -->
            
            <div class="login-footer">
                <p>&copy; <?= date('Y') ?> PONTI - Prefeitura Municipal de Nova Friburgo. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        function togglePassword() {
            const senhaInput = document.getElementById('senha');
            const showPasswordIcon = document.querySelector('.show-password');
            
            if (senhaInput.type === 'password') {
                senhaInput.type = 'text';
                showPasswordIcon.classList.remove('fa-eye');
                showPasswordIcon.classList.add('fa-eye-slash');
            } else {
                senhaInput.type = 'password';
                showPasswordIcon.classList.remove('fa-eye-slash');
                showPasswordIcon.classList.add('fa-eye');
            }
        }
        
        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = this.querySelector('input[name="email"]');
            const senha = this.querySelector('input[name="senha"]');
            let isValid = true;
            
            // Reset error states
            email.classList.remove('error');
            senha.classList.remove('error');
            
            // Validate email
            if (!email.value.trim()) {
                email.classList.add('error');
                isValid = false;
            }
            
            // Validate password
            if (!senha.value.trim()) {
                senha.classList.add('error');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos.');
                return false;
            }
        });
        
        // Remove error class on input
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
            });
        });
        
        // Auto-focus no primeiro campo
        document.querySelector('input[name="email"]').focus();
    </script>
</body>
</html>