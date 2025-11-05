<!-- Header -->
<header class="header">
    <div class="header-title">
        <h1><?= isset($usuario) ? 'Editar Usuário' : 'Cadastro de Usuário' ?></h1>
        <p><?= isset($usuario) ? 'Atualize as informações do usuário' : 'Adicione um novo usuário administrador' ?></p>
    </div>
    <div class="header-actions">
        <a href="<?= Router::url('dashboard/usuarios') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="main-content">
    <div class="form-container">
        <div class="form-header">
            <h2><?= isset($usuario) ? 'Editar Usuário' : 'Novo Usuário' ?></h2>
            <p><?= isset($usuario) ? 'Atualize as informações do usuário' : 'Preencha as informações abaixo para cadastrar um novo usuário administrador' ?></p>
        </div>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= $_SESSION['flash_type'] ?>">
                <i class="fas fa-<?= $_SESSION['flash_type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= $_SESSION['flash_message'] ?>
            </div>
            <?php 
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            ?>
        <?php endif; ?>

        <form id="usuarioForm" method="POST" action="<?= isset($usuario) ? Router::url('dashboard/usuarios/atualizar/' . $usuario['id']) : Router::url('dashboard/usuarios/criar') ?>">
            <!-- Informações Básicas -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user"></i>
                    Informações Básicas
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Nome Completo <span class="required">*</span>
                        </label>
                        <input type="text" class="form-input" name="nome" required placeholder="Nome completo do usuário" value="<?= isset($usuario) ? htmlspecialchars($usuario['nome'] ?? '') : '' ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" class="form-input" name="email" required placeholder="email@exemplo.com" value="<?= isset($usuario) ? htmlspecialchars($usuario['email'] ?? '') : '' ?>">
                    </div>
                </div>
            </div>

            <!-- Segurança -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-lock"></i>
                    Segurança
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Senha <?= isset($usuario) ? '' : '<span class="required">*</span>' ?>
                        </label>
                        <input type="password" class="form-input" name="senha" <?= isset($usuario) ? '' : 'required' ?> placeholder="<?= isset($usuario) ? 'Deixe em branco para manter a senha atual' : 'Digite uma senha segura' ?>">
                        <?php if (isset($usuario)): ?>
                            <small class="form-help">Deixe em branco para manter a senha atual</small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Confirmar Senha <?= isset($usuario) ? '' : '<span class="required">*</span>' ?>
                        </label>
                        <input type="password" class="form-input" name="confirmar_senha" <?= isset($usuario) ? '' : 'required' ?> placeholder="<?= isset($usuario) ? 'Confirme a nova senha' : 'Confirme a senha' ?>">
                    </div>
                </div>
            </div>

            <!-- Configurações -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-cog"></i>
                    Configurações
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            Tipo de Usuário <span class="required">*</span>
                        </label>
                        <select class="form-select" name="tipo" required>
                            <option value="admin" <?= isset($usuario) && $usuario['tipo'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            <option value="moderador" <?= isset($usuario) && $usuario['tipo'] === 'moderador' ? 'selected' : '' ?>>Moderador</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Status <span class="required">*</span>
                        </label>
                        <select class="form-select" name="ativo" required>
                            <option value="1" <?= isset($usuario) && $usuario['ativo'] == 1 ? 'selected' : '' ?>>Ativo</option>
                            <option value="0" <?= isset($usuario) && $usuario['ativo'] == 0 ? 'selected' : '' ?>>Inativo</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Ações do Formulário -->
            <div class="form-actions">
                <a href="<?= Router::url('dashboard/usuarios') ?>" class="btn btn-outline">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <?= isset($usuario) ? 'Atualizar Usuário' : 'Salvar Usuário' ?>
                </button>
            </div>
        </form>
    </div>
</main>

<script>
// Form submission e validação
document.getElementById('usuarioForm').addEventListener('submit', function(e) {
    // Validar campos obrigatórios
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('error');
            isValid = false;
        } else {
            field.classList.remove('error');
        }
    });
    
    // Validar email
    const email = this.querySelector('input[name="email"]');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
        email.classList.add('error');
        isValid = false;
        alert('Por favor, digite um email válido.');
        e.preventDefault();
        return false;
    }
    
    // Validar senhas
    const senha = this.querySelector('input[name="senha"]');
    const confirmarSenha = this.querySelector('input[name="confirmar_senha"]');
    
    // Se está criando um novo usuário ou se preencheu uma nova senha
    const isNovoUsuario = <?= isset($usuario) ? 'false' : 'true' ?>;
    const preencheuSenha = senha.value.length > 0;
    
    if (isNovoUsuario || preencheuSenha) {
        if (senha.value.length < 6) {
            senha.classList.add('error');
            isValid = false;
            alert('A senha deve ter pelo menos 6 caracteres.');
            e.preventDefault();
            return false;
        }
        
        if (senha.value !== confirmarSenha.value) {
            senha.classList.add('error');
            confirmarSenha.classList.add('error');
            isValid = false;
            alert('As senhas não coincidem.');
            e.preventDefault();
            return false;
        }
    }
    
    if (!isValid) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios corretamente.');
        return false;
    }
    
    return true;
});

// Remover classe de erro quando usuário começar a digitar
document.querySelectorAll('.form-input, .form-select').forEach(field => {
    field.addEventListener('input', function() {
        this.classList.remove('error');
    });
});

// Validação em tempo real do email
document.querySelector('input[name="email"]').addEventListener('blur', function() {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (this.value && !emailPattern.test(this.value)) {
        this.classList.add('error');
    } else {
        this.classList.remove('error');
    }
});

// Força senhas coincidentes
document.querySelector('input[name="confirmar_senha"]').addEventListener('input', function() {
    const senha = document.querySelector('input[name="senha"]');
    if (this.value && senha.value && this.value !== senha.value) {
        this.classList.add('error');
    } else {
        this.classList.remove('error');
    }
});
</script>