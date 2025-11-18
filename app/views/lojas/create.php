<div class="main-content">
    <div class="dashboard-main">
        <div class="page-header">
            <div class="header-left">
                <h1>Nova Loja</h1>
                <p>Cadastre uma nova loja participante do movimento</p>
            </div>
            <a href="<?= Router::url('dashboard/lojas') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="form-container">
            <div class="form-header">
                <h2>Cadastrar Loja</h2>
                <p>Preencha os dados da nova loja participante</p>
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

            <form method="POST" action="<?= Router::url('dashboard/lojas/store') ?>" enctype="multipart/form-data" id="lojaForm">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informações da Loja
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nome">Nome da Loja *</label>
                            <input type="text" id="nome" name="nome" required
                                   value="<?= htmlspecialchars($_SESSION['form_data']['nome'] ?? '') ?>"
                                   class="<?= isset($_SESSION['form_errors']['nome']) ? 'error' : '' ?>">
                            <?php if (isset($_SESSION['form_errors']['nome'])): ?>
                                <span class="error-message"><?= $_SESSION['form_errors']['nome'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="website">Website (opcional)</label>
                            <input type="url" id="website" name="website" 
                                   placeholder="https://www.loja.com"
                                   value="<?= htmlspecialchars($_SESSION['form_data']['website'] ?? '') ?>"
                                   class="<?= isset($_SESSION['form_errors']['website']) ? 'error' : '' ?>">
                            <?php if (isset($_SESSION['form_errors']['website'])): ?>
                                <span class="error-message"><?= $_SESSION['form_errors']['website'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="ativo">Status</label>
                            <select id="ativo" name="ativo">
                                <option value="1" <?= ($_SESSION['form_data']['ativo'] ?? '1') === '1' ? 'selected' : '' ?>>Ativa</option>
                                <option value="0" <?= ($_SESSION['form_data']['ativo'] ?? '1') === '0' ? 'selected' : '' ?>>Inativa</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-image"></i>
                        Logo da Loja
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="logo">Logo (PNG, JPG ou GIF - máx. 5MB)</label>
                            <input type="file" id="logo" name="logo" accept="image/*" onchange="previewLogo(this)">
                            <small class="form-help">
                                Recomendação: Imagem quadrada (300x300px ou maior) com fundo transparente ou branco
                            </small>
                            <div id="logoPreview" style="margin-top: 1rem; display: none;">
                                <img id="logoPreviewImg" style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 1px solid var(--dark-lighter);">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?= Router::url('dashboard/lojas') ?>" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Cadastrar Loja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview do logo
function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    const previewImg = document.getElementById('logoPreviewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

// Máscara removida - não necessária para formulário simplificado

// Limpa dados de sessão
<?php 
unset($_SESSION['form_data']);
unset($_SESSION['form_errors']);
?>
</script>