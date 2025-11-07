<div class="main-content">
    <div class="dashboard-main">
        <div class="page-header">
            <div class="header-left">
                <h1>Gerenciar Parceiros</h1>
                <p>Lista de todos os parceiros cadastrados no sistema</p>
            </div>
            <div class="header-right">
                <a href="<?= Router::url('dashboard/parceiros/cadastro') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Novo Parceiro
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

    <div class="table-container">
        <div class="table-filters">
            <div class="filter-group">
                <input type="text" placeholder="Buscar por nome..." class="search-input" id="searchParceiros">
            </div>
            <div class="filter-group">
                <select class="filter-select" id="filterTipo">
                    <option value="">Todos os tipos</option>
                    <option value="Parceiro Institucional">Parceiro Institucional</option>
                    <option value="Parceiro Público">Parceiro Público</option>
                    <option value="Patrocinador Oficial">Patrocinador Oficial</option>
                    <option value="Parceiro Técnico">Parceiro Técnico</option>
                    <option value="Apoiador">Apoiador</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Contato</th>
                        <th>Status</th>
                        <th>Data Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($parceiros)): ?>
                        <?php foreach ($parceiros as $parceiro): ?>
                            <tr data-id="<?= $parceiro['id'] ?>">
                                <td><?= $parceiro['id'] ?></td>
                                <td>
                                    <div class="user-info">
                                        <strong><?= htmlspecialchars($parceiro['nome']) ?></strong>
                                        <?php if (!empty($parceiro['endereco'])): ?>
                                            <small><?= htmlspecialchars($parceiro['endereco']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-<?= strtolower(str_replace(' ', '-', $parceiro['tipo'])) ?>">
                                        <?= htmlspecialchars($parceiro['tipo']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <?php if (!empty($parceiro['email'])): ?>
                                            <div><?= htmlspecialchars($parceiro['email']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($parceiro['telefone'])): ?>
                                            <small><?= htmlspecialchars($parceiro['telefone']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status status-<?= $parceiro['ativo'] ? 'active' : 'inactive' ?>">
                                        <?= $parceiro['ativo'] ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($parceiro['data_cadastro'] ?? 'now')) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= Router::url('dashboard/parceiros/editar/' . $parceiro['id']) ?>" 
                                           class="btn btn-sm btn-secondary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="deleteParceiro(<?= $parceiro['id'] ?>)"
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
                                    <h3>Nenhum parceiro encontrado</h3>
                                    <p>Comece adicionando o primeiro parceiro ao sistema.</p>
                                    <a href="<?= Router::url('dashboard/parceiros/cadastro') ?>" class="btn btn-primary">
                                        Cadastrar Primeiro Parceiro
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
            <p>Tem certeza que deseja excluir este parceiro?</p>
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
document.getElementById('searchParceiros').addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('.data-table tbody tr');
    
    rows.forEach(row => {
        const nome = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const contato = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        
        if (nome.includes(filter) || contato.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Função para filtrar por tipo
document.getElementById('filterTipo').addEventListener('change', function() {
    const filter = this.value;
    const rows = document.querySelectorAll('.data-table tbody tr');
    
    rows.forEach(row => {
        if (!filter) {
            row.style.display = '';
        } else {
            const tipo = row.querySelector('.badge').textContent.trim();
            if (tipo === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
});

// Função para excluir parceiro
function deleteParceiro(id) {
    document.getElementById('deleteForm').action = `<?= Router::url('dashboard/parceiros/deletar/') ?>${id}`;
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