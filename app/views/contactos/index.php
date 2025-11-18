<div class="main-content">
    <div class="dashboard-main">
        <div class="page-header">
            <div class="header-left">
                <h1>Gerenciar Contatos</h1>
                <p>Gerencie as informações de contato e redes sociais do rodapé</p>
            </div>
            <div class="header-right">
                <a href="<?= Router::url('contactos/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Novo Contato
                </a>
            </div>
        </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= $_SESSION['success'] ?>
            <button class="alert-close" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

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
            <h3>Lista de Contatos</h3>
        </div>
        <div class="card-body">
            <?php if (empty($contactos)): ?>
                <div class="empty-state">
                    <i class="fas fa-address-book empty-icon"></i>
                    <h3>Nenhum contato cadastrado</h3>
                    <p>Comece criando seu primeiro contato para o rodapé do site.</p>
                    <a href="<?= Router::url('contactos/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Criar Primeiro Contato
                    </a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="data-table" id="contactos-table">
                            <thead>
                                <tr>
                                    <th>Ordem</th>
                                    <th>Tipo</th>
                                    <th>Título</th>
                                    <th>Valor</th>
                                    <th>Ícone</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contactos as $contacto): ?>
                                    <tr data-id="<?= $contacto['id'] ?>">
                                        <td>
                                            <span class="badge badge-secondary ordem-badge" title="Arraste para reordenar">
                                                <?= $contacto['ordem'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?= ucfirst($contacto['tipo']) ?></span>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($contacto['titulo']) ?></strong>
                                        </td>
                                        <td>
                                            <div class="contact-value">
                                                <?php if ($contacto['link']): ?>
                                                    <a href="<?= htmlspecialchars($contacto['link']) ?>" target="_blank" class="external-link">
                                                        <?= htmlspecialchars($contacto['valor']) ?>
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <?= nl2br(htmlspecialchars($contacto['valor'])) ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($contacto['icone']): ?>
                                                <i class="<?= htmlspecialchars($contacto['icone']) ?> icon-preview"></i>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($contacto['ativo']): ?>
                                                <span class="badge badge-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="<?= Router::url('contactos/edit/' . $contacto['id']) ?>" 
                                                   class="btn btn-sm btn-outline" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline btn-danger" 
                                                        title="Excluir"
                                                        onclick="confirmarExclusao(<?= $contacto['id'] ?>, '<?= htmlspecialchars($contacto['titulo']) ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>

<script>
function confirmarExclusao(id, titulo) {
    if (confirm(`Tem certeza que deseja excluir o contato "${titulo}"?`)) {
        window.location.href = '<?= Router::url('contactos/delete/') ?>' + id;
    }
}

// Funcionalidade de reordenação simples
document.addEventListener('DOMContentLoaded', function() {
    const badges = document.querySelectorAll('.ordem-badge');
    
    badges.forEach(badge => {
        badge.style.cursor = 'move';
        badge.title = 'Arraste para reordenar';
    });
    
    // Implementar drag and drop nativo se necessário
    // Por agora, usuário pode editar manualmente a ordem
});
</script>

<style>
.contact-value {
    max-width: 250px;
    word-wrap: break-word;
}

.external-link {
    color: var(--primary-color);
    text-decoration: none;
}

.external-link:hover {
    text-decoration: underline;
}

.external-link i {
    margin-left: 0.5rem;
    font-size: 0.8rem;
    opacity: 0.7;
}

.icon-preview {
    font-size: 1.2rem;
    color: var(--primary-color);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-buttons .btn {
    padding: 0.25rem 0.5rem;
}

.ordem-badge {
    cursor: move;
    user-select: none;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--text-muted);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    margin: 1rem 0;
    color: var(--text-light);
}

.alert {
    position: relative;
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-success {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #22c55e;
}

.alert-danger {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.alert-close {
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    margin-left: auto;
    padding: 0.25rem;
    border-radius: 0.25rem;
    transition: background-color 0.2s;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.1);
}
</style>