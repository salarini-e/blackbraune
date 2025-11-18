<div class="main-content">
    <div class="dashboard-main">
        <div class="page-header">
            <div class="header-left">
                <h1>Lojas Participantes</h1>
                <p>Gerencie as lojas que fazem parte do movimento</p>
            </div>
            <a href="<?= Router::url('dashboard/lojas/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Nova Loja
            </a>
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

        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-info">
                    <h3>Total de Lojas</h3>
                    <div class="stat-number"><?= $stats['total'] ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>Lojas Ativas</h3>
                    <div class="stat-number"><?= $stats['ativas'] ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>Lojas Inativas</h3>
                    <div class="stat-number"><?= $stats['inativas'] ?></div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-filters">
                <div class="filter-group">
                    <form method="GET" action="<?= Router::url('dashboard/lojas') ?>" style="display: flex; gap: 1rem;">
                        <input 
                            type="text" 
                            name="search" 
                            value="<?= htmlspecialchars($search) ?>" 
                            placeholder="Buscar por nome..."
                            class="search-input"
                        >
                        <select name="status" class="filter-select">
                            <option value="">Todos os status</option>
                            <option value="ativas" <?= $status === 'ativas' ? 'selected' : '' ?>>Apenas Ativas</option>
                            <option value="inativas" <?= $status === 'inativas' ? 'selected' : '' ?>>Apenas Inativas</option>
                        </select>
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i>
                            Buscar
                        </button>
                        <?php if ($search || $status): ?>
                            <a href="<?= Router::url('dashboard/lojas') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                                Limpar
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="table-wrapper">
                <?php if (empty($lojas)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <h3>Nenhuma loja encontrada</h3>
                        <p>
                            <?php if ($search || $status): ?>
                                Tente ajustar os filtros ou cadastre uma nova loja.
                            <?php else: ?>
                                Comece cadastrando a primeira loja participante do movimento.
                            <?php endif; ?>
                        </p>
                        <a href="<?= Router::url('dashboard/lojas/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Cadastrar Primeira Loja
                        </a>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>Nome</th>
                                <th>Website</th>
                                <th>Status</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lojas as $loja): ?>
                                <tr>
                                    <td><?= $loja['id'] ?></td>
                                    <td>
                                        <?php if ($loja['logo']): ?>
                                            <img src="<?= Router::url('assets/img/uploads/' . $loja['logo']) ?>" 
                                                 alt="<?= htmlspecialchars($loja['nome']) ?>"
                                                 style="width: 50px; height: 50px; object-fit: contain; border-radius: 8px; background: white; padding: 5px;">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; background: var(--dark-lighter); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--gray);">
                                                <i class="fas fa-store"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <strong><?= htmlspecialchars($loja['nome']) ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($loja['website'])): ?>
                                            <a href="<?= htmlspecialchars($loja['website']) ?>" target="_blank" style="color: var(--secondary-color); text-decoration: none;">
                                                <i class="fas fa-external-link-alt"></i>
                                                Visitar
                                            </a>
                                        <?php else: ?>
                                            <span style="color: var(--gray);">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge <?= $loja['ativo'] ? 'status-active' : 'status-inactive' ?>">
                                            <?= $loja['ativo'] ? 'Ativa' : 'Inativa' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y H:i', strtotime($loja['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= Router::url('dashboard/lojas/edit/' . $loja['id']) ?>" 
                                               class="btn btn-sm btn-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm <?= $loja['ativo'] ? 'btn-warning' : 'btn-success' ?>" 
                                                    onclick="toggleStatus(<?= $loja['id'] ?>)"
                                                    title="<?= $loja['ativo'] ? 'Desativar' : 'Ativar' ?>">
                                                <i class="fas fa-<?= $loja['ativo'] ? 'eye-slash' : 'eye' ?>"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="deleteLoja(<?= $loja['id'] ?>, '<?= htmlspecialchars($loja['nome']) ?>')"
                                                    title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modais de confirmação -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Confirmar Exclusão</h3>
        <p>Tem certeza que deseja excluir a loja <strong id="deleteLojaName"></strong>?</p>
        <p style="color: var(--danger); font-size: 0.9rem;">
            <i class="fas fa-exclamation-triangle"></i>
            Esta ação não pode ser desfeita.
        </p>
        <div class="modal-buttons">
            <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancelar</button>
            <button type="button" id="confirmDelete" class="btn btn-danger">Excluir</button>
        </div>
    </div>
</div>

<script>
let deleteLojaId = null;

// Função para alternar status
function toggleStatus(id) {
    if (confirm('Deseja alterar o status desta loja?')) {
        window.location.href = '<?= Router::url("dashboard/lojas/toggle/") ?>' + id;
    }
}

// Função para excluir loja
function deleteLoja(id, name) {
    deleteLojaId = id;
    document.getElementById('deleteLojaName').textContent = name;
    openModal('deleteModal');
}

// Confirmar exclusão
document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deleteLojaId) {
        window.location.href = '<?= Router::url("dashboard/lojas/delete/") ?>' + deleteLojaId;
    }
});

// Funções de modal
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fechar modal ao clicar fora
window.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeModal(modal.id);
        }
    });
});

// Fechar modal com ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (modal.style.display === 'flex') {
                closeModal(modal.id);
            }
        });
    }
});
</script>