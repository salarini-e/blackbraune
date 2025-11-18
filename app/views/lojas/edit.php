<div class="main-content">
    <div class="dashboard-main">
        <div class="page-header">
            <div class="header-left">
                <h1>Editar Loja</h1>
                <p>Atualizar informações de <?= htmlspecialchars($loja['nome']) ?></p>
            </div>
            <a href="<?= Router::url('dashboard/lojas') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="form-container">
            <div class="form-header">
                <h2>Editar Loja</h2>
                <p>Atualize os dados da loja participante</p>
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

            <form method="POST" action="<?= Router::url('dashboard/lojas/update/' . $loja['id']) ?>" enctype="multipart/form-data" id="lojaForm">
                <input type="hidden" name="_method" value="PUT">
                
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informações da Loja
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nome">Nome da Loja *</label>
                            <input type="text" id="nome" name="nome" required
                                   value="<?= htmlspecialchars($_SESSION['form_data']['nome'] ?? $loja['nome']) ?>"
                                   class="<?= isset($_SESSION['form_errors']['nome']) ? 'error' : '' ?>">
                            <?php if (isset($_SESSION['form_errors']['nome'])): ?>
                                <span class="error-message"><?= $_SESSION['form_errors']['nome'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="website">Website (opcional)</label>
                            <input type="url" id="website" name="website" 
                                   placeholder="https://www.loja.com"
                                   value="<?= htmlspecialchars($_SESSION['form_data']['website'] ?? $loja['website']) ?>"
                                   class="<?= isset($_SESSION['form_errors']['website']) ? 'error' : '' ?>">
                            <?php if (isset($_SESSION['form_errors']['website'])): ?>
                                <span class="error-message"><?= $_SESSION['form_errors']['website'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="ativo">Status</label>
                            <select id="ativo" name="ativo">
                                <option value="1" <?= ($_SESSION['form_data']['ativo'] ?? $loja['ativo']) == 1 ? 'selected' : '' ?>>Ativa</option>
                                <option value="0" <?= ($_SESSION['form_data']['ativo'] ?? $loja['ativo']) == 0 ? 'selected' : '' ?>>Inativa</option>
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
                            
                            <?php if (!empty($loja['logo'])): ?>
                                <div class="current-logo" style="margin-bottom: 1rem;">
                                    <span class="label">Logo atual:</span>
                                    <img src="<?= Router::url('assets/img/uploads/' . $loja['logo']) ?>" 
                                         alt="Logo atual" 
                                         style="max-width: 100px; max-height: 100px; border-radius: 8px; border: 1px solid var(--dark-lighter); margin-left: 10px;">
                                </div>
                            <?php endif; ?>
                            
                            <input type="file" id="logo" name="logo" accept="image/*" onchange="previewLogo(this)">
                            <small class="form-help">
                                Deixe em branco para manter a logo atual. Recomendação: Imagem quadrada (300x300px ou maior)
                            </small>
                            
                            <div id="logoPreview" style="margin-top: 1rem; display: none;">
                                <span class="label">Nova logo:</span>
                                <img id="logoPreviewImg" style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 1px solid var(--dark-lighter); margin-left: 10px;">
                            </div>
                            
                            <?php if (!empty($loja['logo'])): ?>
                                <div style="margin-top: 10px;">
                                    <label>
                                        <input type="checkbox" name="remove_logo" value="1">
                                        <span style="color: var(--danger); font-size: 0.9rem;">Remover logo atual</span>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?= Router::url('dashboard/lojas') ?>" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Atualizar Loja
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

// Máscara para telefone
document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        } else {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        }
        e.target.value = value;
    }
});

// Limpa dados de sessão
<?php 
unset($_SESSION['form_data']);
unset($_SESSION['form_errors']);
?>
</script>