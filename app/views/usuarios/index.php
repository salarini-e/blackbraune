<div class="usuarios-main">
    <div class="page-header">
        <div class="header-left">
            <h1>Gerenciar Usuários</h1>
            <p>Lista de todos os usuários administradores do sistema</p>
        </div>
        <div class="header-right">
            <a href="<?= Router::url('dashboard/usuarios/cadastro') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Novo Usuário
            </a>
        </div>
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

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['total_usuarios'] ?></h3>
                <p>Total de Usuários</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['usuarios_ativos'] ?></h3>
                <p>Usuários Ativos</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['admins'] ?></h3>
                <p>Administradores</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="stat-info">
                <h3><?= $stats['inativos'] ?></h3>
                <p>Inativos</p>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-filters">
            <div class="filter-group">
                <input type="text" placeholder="Buscar por nome ou email..." class="search-input" id="searchUsuarios">
            </div>
            <div class="filter-group">
                <select class="filter-select" id="filterAtivo">
                    <option value="">Todos os status</option>
                    <option value="1">Ativos</option>
                    <option value="0">Inativos</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr data-id="<?= $usuario['id'] ?>">
                                <td><?= $usuario['id'] ?></td>
                                <td>
                                    <div class="user-info">
                                        <strong><?= htmlspecialchars($usuario['nome']) ?></strong>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td>
                                    <span class="badge badge-<?= strtolower($usuario['tipo']) ?>">
                                        <?= ucfirst($usuario['tipo']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status status-<?= $usuario['ativo'] ? 'active' : 'inactive' ?>">
                                        <?= $usuario['ativo'] ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($usuario['data_cadastro'] ?? 'now')) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= Router::url('dashboard/usuarios/editar/' . $usuario['id']) ?>" 
                                           class="btn btn-sm btn-secondary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="deleteUsuario(<?= $usuario['id'] ?>)"
                                                title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x"></i>
                                    <h3>Nenhum usuário encontrado</h3>
                                    <p>Comece adicionando o primeiro usuário administrador.</p>
                                    <a href="<?= Router::url('dashboard/usuarios/cadastro') ?>" class="btn btn-primary">
                                        Cadastrar Primeiro Usuário
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar Exclusão</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Tem certeza que deseja excluir este usuário?</p>
            <p><strong>Esta ação não pode ser desfeita.</strong></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger">Excluir</button>
            </form>
        </div>
    </div>
</div>

<script>
// Função para busca em tempo real
document.getElementById('searchUsuarios').addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('.data-table tbody tr');
    
    rows.forEach(row => {
        const nome = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        if (nome.includes(filter) || email.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Função para filtrar por status
document.getElementById('filterAtivo').addEventListener('change', function() {
    const filter = this.value;
    const rows = document.querySelectorAll('.data-table tbody tr');
    
    rows.forEach(row => {
        if (!filter) {
            row.style.display = '';
        } else {
            const status = row.querySelector('.status');
            const isActive = status.classList.contains('status-active');
            const shouldShow = (filter === '1' && isActive) || (filter === '0' && !isActive);
            
            if (shouldShow) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
});

// Função para excluir usuário
function deleteUsuario(id) {
    document.getElementById('deleteForm').action = `<?= Router::url('dashboard/usuarios/deletar/') ?>${id}`;
    document.getElementById('deleteModal').style.display = 'block';
}

// Função para fechar modal
function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Fechar modal clicando no X ou fora dele
document.querySelector('.close').onclick = closeModal;
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>