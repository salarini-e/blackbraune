<!-- Header -->
<header class="header">
    <div class="header-title">
        <h1>Cadastro de Parceiro</h1>
        <p>Adicione um novo parceiro ao movimento Black Braune</p>
    </div>
    <div class="header-actions">
        <a href="<?= Router::url('dashboard/parceiros') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
    </div>
</header>

<!-- DEBUG TEMPORÁRIO -->
<?php if (isset($parceiro)): ?>
<div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">
    <h4>DEBUG - Dados do parceiro:</h4>
    <pre><?= htmlspecialchars(print_r($parceiro, true)) ?></pre>
    <p><strong>Tipo:</strong> '<?= $parceiro['tipo'] ?? 'NULL' ?>'</p>
    <p><strong>isset($parceiro):</strong> <?= isset($parceiro) ? 'SIM' : 'NÃO' ?></p>
</div>
<?php endif; ?>

<!-- Main Content -->
<main class="main-content">
            <div class="form-container">
                <div class="form-header">
                    <h2><?= isset($parceiro) ? 'Editar Parceiro' : 'Novo Parceiro' ?></h2>
                    <p><?= isset($parceiro) ? 'Atualize as informações do parceiro' : 'Preencha as informações abaixo para cadastrar um novo parceiro' ?></p>
                </div>

                <div class="success-message" id="successMessage">
                    <i class="fas fa-check-circle"></i>
                    <?= isset($parceiro) ? 'Parceiro atualizado com sucesso!' : 'Parceiro cadastrado com sucesso!' ?>
                </div>

                <form id="parceiroForm" method="POST" action="<?= isset($parceiro) ? '/dashboard/parceiros/atualizar/' . $parceiro['id'] : '/dashboard/parceiros/criar' ?>" enctype="multipart/form-data">                    <!-- Informações Básicas -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informações Básicas
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Nome da Empresa <span class="required">*</span>
                                </label>
                                <input type="text" class="form-input" name="nome" required placeholder="Nome completo da empresa" value="<?= isset($parceiro) ? htmlspecialchars($parceiro['nome'] ?? '') : '' ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Tipo de Parceiro <span class="required">*</span>
                                </label>
                                <select class="form-select" name="tipo" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="Parceiro Institucional" <?= isset($parceiro) && $parceiro['tipo'] === 'Parceiro Institucional' ? 'selected' : '' ?>>Parceiro Institucional</option>
                                    <option value="Parceiro Público" <?= isset($parceiro) && $parceiro['tipo'] === 'Parceiro Público' ? 'selected' : '' ?>>Parceiro Público</option>
                                    <option value="Patrocinador Oficial" <?= isset($parceiro) && $parceiro['tipo'] === 'Patrocinador Oficial' ? 'selected' : '' ?>>Patrocinador Oficial</option>
                                    <option value="Parceiro Técnico" <?= isset($parceiro) && $parceiro['tipo'] === 'Parceiro Técnico' ? 'selected' : '' ?>>Parceiro Técnico</option>
                                    <option value="Apoiador" <?= isset($parceiro) && $parceiro['tipo'] === 'Apoiador' ? 'selected' : '' ?>>Apoiador</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Categoria <span class="required">*</span>
                                </label>
                                <input type="text" class="form-input" name="categoria" required placeholder="Ex: Associação Comercial, Tecnologia, etc." value="<?= isset($parceiro) ? htmlspecialchars($parceiro['categoria'] ?? '') : '' ?>">
                            </div>
                            
                        </div>
                    </div>

                    <!-- Logo -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-image"></i>
                            Logo da Empresa
                        </h3>
                        
                        <div class="form-group">
                            <div class="file-upload">
                                <input type="file" class="file-upload-input" name="logo" accept="image/*" id="logoUpload">
                                <label for="logoUpload" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                                    <div>
                                        <div>Clique para selecionar a logo</div>
                                        <small>ou arraste e solte aqui</small>
                                        <br><small>PNG, JPG até 5MB</small>
                                    </div>
                                </label>
                            </div>
                            <div class="file-preview" id="logoPreview">
                                <img id="logoImg" src="" alt="Preview da logo">
                            </div>
                        </div>
                    </div>

                    <!-- Contato -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-phone"></i>
                            Informações de Contato
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Email Principal <span class="required">*</span>
                                </label>
                                <input type="email" class="form-input" name="email" required placeholder="contato@empresa.com" value="<?= isset($parceiro) ? htmlspecialchars($parceiro['email'] ?? '') : '' ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Telefone
                                </label>
                                <input type="tel" class="form-input" name="telefone" placeholder="(22) 9999-9999" value="<?= isset($parceiro) ? htmlspecialchars($parceiro['telefone'] ?? '') : '' ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Site/Website
                                </label>
                                <input type="url" class="form-input" name="website" placeholder="https://www.empresa.com" value="<?= isset($parceiro) ? htmlspecialchars($parceiro['website'] ?? '') : '' ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Endereço
                                </label>
                                <input type="text" class="form-input" name="endereco" placeholder="Rua, número, bairro, cidade" value="<?= isset($parceiro) ? htmlspecialchars($parceiro['endereco'] ?? '') : '' ?>">
                            </div>
                            
                        </div>
                    </div>

                    <!-- Pessoa de Contato -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-user"></i>
                            Pessoa de Contato
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Nome do Responsável <span class="required">*</span>
                                </label>
                                <input type="text" class="form-input" name="responsavel_nome" required placeholder="Nome completo" value="<?= isset($parceiro) ? htmlspecialchars($parceiro['responsavel_nome'] ?? '') : '' ?>">
                            </div>                            

                            <div class="form-group">
                                <label class="form-label">
                                    Email do Responsável
                                </label>
                                <input type="email" class="form-input" name="responsavel_email" placeholder="responsavel@empresa.com" value="<?= isset($parceiro) ? htmlspecialchars($parceiro['responsavel_email'] ?? '') : '' ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Telefone do Responsável
                                </label>
                                <input type="tel" class="form-input" name="responsavel_telefone" placeholder="(22) 9999-9999" value="<?= isset($parceiro) ? htmlspecialchars($parceiro['responsavel_telefone'] ?? '') : '' ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Informações Adicionais -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-clipboard"></i>
                            Informações Adicionais
                        </h3>                            
                        <div class="form-group">
                            <label class="form-label">
                                Observações
                            </label>
                            <textarea class="form-textarea" name="contribuicao" placeholder="Como a empresa contribui para o movimento Black Braune..."><?= isset($parceiro) ? htmlspecialchars($parceiro['contribuicao'] ?? '') : '' ?></textarea>
                        </div>
                        <div class="form-group">
                                <label class="form-label">
                                    Status <span class="required">*</span>
                                </label>
                                <select class="form-select" name="status" required>
                                    <option value="ativo" <?= isset($parceiro) && $parceiro['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                                    <option value="pendente" <?= isset($parceiro) && $parceiro['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                    <option value="inativo" <?= isset($parceiro) && $parceiro['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                                </select>
                            </div>
                    </div>

                    <!-- Ações do Formulário -->
                    <div class="form-actions">
                        <a href="/dashboard/parceiros" class="btn btn-outline">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <?= isset($parceiro) ? 'Atualizar Parceiro' : 'Salvar Parceiro' ?>
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <script>
        // File upload preview
        document.getElementById('logoUpload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const logoPreview = document.getElementById('logoPreview');
                    const logoImg = document.getElementById('logoImg');
                    logoImg.src = e.target.result;
                    logoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission
        document.getElementById('parceiroForm').addEventListener('submit', function(e) {
            // Validar campos obrigatórios antes do envio
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
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
                return false;
            }
            
            // Se chegou até aqui, deixa o form ser enviado normalmente
            return true;
        });

        // Phone mask
        function maskPhone(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length >= 7) {
                value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else if (value.length >= 3) {
                value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            }
            input.value = value;
        }

        // Apply phone masks
        const phoneInputs = document.querySelectorAll('input[name="telefone"], input[name="responsavel_telefone"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function() {
                maskPhone(this);
            });
        });
    </script>