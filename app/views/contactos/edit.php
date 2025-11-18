<div class="main-content">
    <div class="dashboard-main">
        <div class="page-header">
            <div class="header-left">
                <h1>Editar Contato</h1>
                <p>Modifique as informações do contato selecionado</p>
            </div>
            <div class="header-right">
                <a href="<?= Router::url('contactos') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Voltar
                </a>
            </div>
        </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= $_SESSION['error'] ?>
            <button class="alert-close" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="content-card">
        <div class="card-header">
            <h3>Informações do Contato</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= Router::url('contactos/edit/' . $contacto['id']) ?>" class="contact-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tipo" class="form-label">
                            <i class="fas fa-tag"></i> Tipo *
                        </label>
                        <select class="form-input" id="tipo" name="tipo" required>
                            <option value="">Selecione o tipo</option>
                            <option value="endereco" <?= $contacto['tipo'] == 'endereco' ? 'selected' : '' ?>>Endereço</option>
                            <option value="email" <?= $contacto['tipo'] == 'email' ? 'selected' : '' ?>>E-mail</option>
                            <option value="telefone" <?= $contacto['tipo'] == 'telefone' ? 'selected' : '' ?>>Telefone</option>
                            <option value="facebook" <?= $contacto['tipo'] == 'facebook' ? 'selected' : '' ?>>Facebook</option>
                            <option value="instagram" <?= $contacto['tipo'] == 'instagram' ? 'selected' : '' ?>>Instagram</option>
                            <option value="whatsapp" <?= $contacto['tipo'] == 'whatsapp' ? 'selected' : '' ?>>WhatsApp</option>
                            <option value="youtube" <?= $contacto['tipo'] == 'youtube' ? 'selected' : '' ?>>YouTube</option>
                            <option value="linkedin" <?= $contacto['tipo'] == 'linkedin' ? 'selected' : '' ?>>LinkedIn</option>
                            <option value="twitter" <?= $contacto['tipo'] == 'twitter' ? 'selected' : '' ?>>Twitter</option>
                            <option value="outros" <?= $contacto['tipo'] == 'outros' ? 'selected' : '' ?>>Outros</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="titulo" class="form-label">
                            <i class="fas fa-heading"></i> Título *
                        </label>
                        <input type="text" class="form-input" id="titulo" name="titulo" required
                               placeholder="Ex: E-mail, Telefone, Facebook..."
                               value="<?= htmlspecialchars($_POST['titulo'] ?? $contacto['titulo']) ?>">
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="valor" class="form-label">
                        <i class="fas fa-info-circle"></i> Valor *
                    </label>
                    <textarea class="form-input" id="valor" name="valor" rows="3" required
                              placeholder="Ex: contato@blackbraune.com.br, (22) 99999-9999, @blackbraune..."><?= htmlspecialchars($_POST['valor'] ?? $contacto['valor']) ?></textarea>
                    <div class="form-help">Para endereços, você pode usar &lt;br&gt; para quebras de linha.</div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="icone" class="form-label">
                            <i class="fas fa-icons"></i> Ícone
                        </label>
                        <div class="input-with-button">
                            <input type="text" class="form-input" id="icone" name="icone"
                                   placeholder="Ex: fas fa-envelope, fab fa-facebook..."
                                   value="<?= htmlspecialchars($_POST['icone'] ?? $contacto['icone']) ?>">
                            <button type="button" class="btn btn-outline" id="preview-icon">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-help">
                            Use classes do Font Awesome. 
                            <a href="https://fontawesome.com/icons" target="_blank">Ver ícones disponíveis</a>
                        </div>
                        <div id="icon-preview" class="icon-preview-area">
                            <?php if ($contacto['icone']): ?>
                                <i class="<?= htmlspecialchars($contacto['icone']) ?>"></i> 
                                <span><?= htmlspecialchars($contacto['icone']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="link" class="form-label">
                            <i class="fas fa-link"></i> Link
                        </label>
                        <input type="url" class="form-input" id="link" name="link"
                               placeholder="https://example.com ou mailto:email@domain.com"
                               value="<?= htmlspecialchars($_POST['link'] ?? $contacto['link']) ?>">
                        <div class="form-help">URL completa com protocolo (http://, mailto:, tel:, etc.)</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="ordem" class="form-label">
                            <i class="fas fa-sort-numeric-up"></i> Ordem
                        </label>
                        <input type="number" class="form-input" id="ordem" name="ordem" min="0"
                               placeholder="0"
                               value="<?= htmlspecialchars($_POST['ordem'] ?? $contacto['ordem']) ?>">
                        <div class="form-help">Número para ordenação (menor aparece primeiro)</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-toggle-on"></i> Status
                        </label>
                        <div class="form-switch">
                            <input type="checkbox" id="ativo" name="ativo" 
                                   <?= (isset($_POST['ativo']) ? $_POST['ativo'] : $contacto['ativo']) ? 'checked' : '' ?>
                                   class="switch-input">
                            <label for="ativo" class="switch-label">Ativo</label>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Salvar Alterações
                    </button>
                    <a href="<?= Router::url('contactos') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<script>
// Preview do ícone
document.getElementById('preview-icon').addEventListener('click', function() {
    const iconeInput = document.getElementById('icone');
    const preview = document.getElementById('icon-preview');
    const iconeClass = iconeInput.value.trim();
    
    if (iconeClass) {
        preview.innerHTML = `<i class="${iconeClass}"></i> <span>${iconeClass}</span>`;
    } else {
        preview.innerHTML = '<span style="color: var(--text-muted)">Digite a classe do ícone para visualizar</span>';
    }
});

// Auto-completar campos baseado no tipo selecionado
document.getElementById('tipo').addEventListener('change', function() {
    const tipo = this.value;
    const iconeInput = document.getElementById('icone');
    const tituloInput = document.getElementById('titulo');
    
    const tiposConfig = {
        'endereco': { icone: 'fas fa-map-marker-alt', titulo: 'Endereço' },
        'email': { icone: 'fas fa-envelope', titulo: 'E-mail' },
        'telefone': { icone: 'fas fa-phone', titulo: 'Telefone' },
        'facebook': { icone: 'fab fa-facebook', titulo: 'Facebook' },
        'instagram': { icone: 'fab fa-instagram', titulo: 'Instagram' },
        'whatsapp': { icone: 'fab fa-whatsapp', titulo: 'WhatsApp' },
        'youtube': { icone: 'fab fa-youtube', titulo: 'YouTube' },
        'linkedin': { icone: 'fab fa-linkedin', titulo: 'LinkedIn' },
        'twitter': { icone: 'fab fa-twitter', titulo: 'Twitter' }
    };
    
    if (tiposConfig[tipo] && confirm('Deseja preencher automaticamente o ícone e título para este tipo?')) {
        iconeInput.value = tiposConfig[tipo].icone;
        tituloInput.value = tiposConfig[tipo].titulo;
        
        // Atualizar preview automaticamente
        document.getElementById('preview-icon').click();
    }
});

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const tipo = document.getElementById('tipo').value;
    const titulo = document.getElementById('titulo').value.trim();
    const valor = document.getElementById('valor').value.trim();
    
    if (!tipo || !titulo || !valor) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
        return false;
    }
});
</script>