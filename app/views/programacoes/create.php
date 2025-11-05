<div class="main-content">
    <div class="dashboard-main">
        <div class="page-header">
            <div class="header-left">
                <h1>Nova Programação</h1>
                <p>Cadastre uma nova atividade na programação do evento</p>
            </div>
            <a href="<?= Router::url('programacoes') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Voltar
            </a>
        </div>

        <div class="form-container">
            <div class="form-header">
                <h2>Cadastrar Atividade</h2>
                <p>Preencha os dados da nova atividade da programação</p>
            </div>

            <form method="POST" action="<?= Router::url('programacoes/store') ?>" id="programacaoForm">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informações Básicas
                    </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="data_evento" class="form-label">Data do Evento <span class="required">*</span></label>
                            <input 
                                type="date" 
                                id="data_evento" 
                                name="data_evento" 
                                class="form-input <?= isset($_SESSION['form_errors']['data_evento']) ? 'error' : '' ?>"
                                value="<?= $_SESSION['form_data']['data_evento'] ?? '' ?>"
                                required
                            >
                            <?php if (isset($_SESSION['form_errors']['data_evento'])): ?>
                                <small class="error-message"><?= $_SESSION['form_errors']['data_evento'] ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="tipo_atividade" class="form-label">Tipo de Atividade <span class="required">*</span></label>
                            <select 
                                id="tipo_atividade" 
                                name="tipo_atividade" 
                                class="form-select <?= isset($_SESSION['form_errors']['tipo_atividade']) ? 'error' : '' ?>"
                                required
                            >
                                <option value="">Selecione um tipo</option>
                                <?php foreach ($tipos as $key => $tipo): ?>
                                    <option value="<?= $key ?>" <?= (($_SESSION['form_data']['tipo_atividade'] ?? '') === $key) ? 'selected' : '' ?>>
                                        <?= $tipo ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($_SESSION['form_errors']['tipo_atividade'])): ?>
                                <small class="error-message"><?= $_SESSION['form_errors']['tipo_atividade'] ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="horario_inicio" class="form-label">Horário de Início <span class="required">*</span></label>
                            <input 
                                type="time" 
                                id="horario_inicio" 
                                name="horario_inicio" 
                                class="form-input <?= isset($_SESSION['form_errors']['horario_inicio']) ? 'error' : '' ?>"
                                value="<?= $_SESSION['form_data']['horario_inicio'] ?? '' ?>"
                                required
                            >
                            <?php if (isset($_SESSION['form_errors']['horario_inicio'])): ?>
                                <small class="error-message"><?= $_SESSION['form_errors']['horario_inicio'] ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="horario_fim" class="form-label">Horário de Fim</label>
                            <input 
                                type="time" 
                                id="horario_fim" 
                                name="horario_fim" 
                                class="form-input <?= isset($_SESSION['form_errors']['horario_fim']) ? 'error' : '' ?>"
                                value="<?= $_SESSION['form_data']['horario_fim'] ?? '' ?>"
                            >
                            <?php if (isset($_SESSION['form_errors']['horario_fim'])): ?>
                                <small class="error-message"><?= $_SESSION['form_errors']['horario_fim'] ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="titulo" class="form-label">Título da Atividade <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="titulo" 
                            name="titulo" 
                            class="form-input <?= isset($_SESSION['form_errors']['titulo']) ? 'error' : '' ?>"
                            value="<?= $_SESSION['form_data']['titulo'] ?? '' ?>"
                            placeholder="Ex: Apresentação Musical, DJ Set, Teatro Infantil..."
                            maxlength="255"
                            required
                        >
                        <?php if (isset($_SESSION['form_errors']['titulo'])): ?>
                            <small class="error-message"><?= $_SESSION['form_errors']['titulo'] ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group full-width">
                        <label for="local" class="form-label">Local</label>
                        <input 
                            type="text" 
                            id="local" 
                            name="local" 
                            class="form-input"
                            value="<?= $_SESSION['form_data']['local'] ?? '' ?>"
                            placeholder="Ex: Palco Principal, Rua Alberto Braune, Praça..."
                            maxlength="255"
                        >
                    </div>

                    <div class="form-group full-width">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea 
                            id="descricao" 
                            name="descricao" 
                            class="form-textarea"
                            placeholder="Descreva a atividade, artistas envolvidos, informações adicionais..."
                            rows="4"
                        ><?= $_SESSION['form_data']['descricao'] ?? '' ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-cog"></i>
                        Configurações
                    </h3>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                id="ativo" 
                                name="ativo" 
                                <?= (($_SESSION['form_data']['ativo'] ?? '1') == '1') ? 'checked' : '' ?>
                            >
                            <span class="checkmark"></span>
                            Atividade ativa (visível no site)
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?= Router::url('programacoes') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Cadastrar Programação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validação do formulário
document.getElementById('programacaoForm').addEventListener('submit', function(e) {
    let isValid = true;
    
    // Remover classes de erro
    document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(field => {
        field.classList.remove('error');
    });
    
    // Validar campos obrigatórios
    const requiredFields = ['data_evento', 'horario_inicio', 'titulo', 'tipo_atividade'];
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            field.classList.add('error');
            isValid = false;
        }
    });
    
    // Validar horários
    const horarioInicio = document.getElementById('horario_inicio').value;
    const horarioFim = document.getElementById('horario_fim').value;
    
    if (horarioInicio && horarioFim && horarioInicio >= horarioFim) {
        document.getElementById('horario_fim').classList.add('error');
        alert('O horário de fim deve ser maior que o horário de início.');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
        alert('Por favor, corrija os campos destacados.');
    }
});

// Limpar dados da sessão ao sair
window.addEventListener('beforeunload', function() {
    // Enviar requisição para limpar dados da sessão
    fetch('<?= Router::url('programacoes') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'clear_session=1'
    });
});
</script>

<style>
.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

.form-input.error,
.form-select.error,
.form-textarea.error {
    border-color: #dc3545 !important;
    background: rgba(220, 53, 69, 0.1) !important;
}

.required {
    color: #dc3545;
}

.full-width {
    grid-column: 1 / -1;
}
</style>

<?php 
// Limpar dados da sessão
if (isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
if (isset($_SESSION['form_errors'])) {
    unset($_SESSION['form_errors']);
}
?>