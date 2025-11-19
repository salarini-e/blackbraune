<div class="main-content">
    <div class="dashboard-main">
        <div class="page-header">
            <div class="header-left">
                <h1>Programação</h1>
                <p>Gerencie a programação dos eventos</p>
            </div>
            <a href="<?= Router::url('programacoes/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Nova Programação
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-info">
                    <h3>Total de Atividades</h3>
                    <div class="stat-number"><?= $stats['total'] ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>Ativas</h3>
                    <div class="stat-number"><?= $stats['ativas'] ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>Inativas</h3>
                    <div class="stat-number"><?= $stats['inativas'] ?></div>
                </div>
            </div>
        </div>

        <!-- Filtros e Busca -->
        <div class="table-container">
            <div class="table-filters">
                <div class="filter-group">
                    <form method="GET" action="<?= Router::url('programacoes') ?>" style="display: flex; gap: 1rem;">
                        <input 
                            type="text" 
                            name="search" 
                            value="<?= htmlspecialchars($search) ?>" 
                            placeholder="Buscar por título, descrição ou tipo..."
                            class="search-input"
                        >
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i>
                            Buscar
                        </button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= Router::url('programacoes') ?>" class="btn btn-outline">
                                <i class="fas fa-times"></i>
                                Limpar
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <?php if (!empty($programacoes)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th>Local</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($programacoes as $programacao): ?>
                                <tr>
                                    <td>
                                        <strong><?= date('d/m/Y', strtotime($programacao['data_evento'])) ?></strong><br>
                                        <small><?php
                                            $diasSemana = [
                                                'Sunday' => 'Domingo',
                                                'Monday' => 'Segunda-feira', 
                                                'Tuesday' => 'Terça-feira',
                                                'Wednesday' => 'Quarta-feira',
                                                'Thursday' => 'Quinta-feira',
                                                'Friday' => 'Sexta-feira',
                                                'Saturday' => 'Sábado'
                                            ];
                                            echo $diasSemana[date('l', strtotime($programacao['data_evento']))];
                                        ?></small>
                                    </td>
                                    <td>
                                        <div class="time-info">
                                            <strong><?= date('H:i', strtotime($programacao['horario_inicio'])) ?></strong>
                                            <?php if (!empty($programacao['horario_fim'])): ?>
                                                <br><small>até <?= date('H:i', strtotime($programacao['horario_fim'])) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="program-info">
                                            <strong><?= htmlspecialchars($programacao['titulo']) ?></strong>
                                            <?php if (!empty($programacao['descricao'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars(substr($programacao['descricao'], 0, 60)) ?><?= strlen($programacao['descricao']) > 60 ? '...' : '' ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= str_replace('_', '-', $programacao['tipo_atividade']) ?>">
                                            <?= $tipos[$programacao['tipo_atividade']] ?? $programacao['tipo_atividade'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= !empty($programacao['local']) ? htmlspecialchars($programacao['local']) : '<span class="text-muted">Não informado</span>' ?>
                                    </td>
                                    <td>
                                        <?php if ($programacao['ativo']): ?>
                                            <span class="status status-active">Ativa</span>
                                        <?php else: ?>
                                            <span class="status status-inactive">Inativa</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= Router::url('programacoes/edit/' . $programacao['id']) ?>" 
                                               class="btn btn-sm btn-secondary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="toggleStatus(<?= $programacao['id'] ?>)" 
                                                    class="btn btn-sm <?= $programacao['ativo'] ? 'btn-outline' : 'btn-secondary' ?>" 
                                                    title="<?= $programacao['ativo'] ? 'Desativar' : 'Ativar' ?>">
                                                <i class="fas fa-<?= $programacao['ativo'] ? 'pause' : 'play' ?>"></i>
                                            </button>
                                            <button onclick="deleteProgramacao(<?= $programacao['id'] ?>, '<?= addslashes($programacao['titulo']) ?>')" 
                                                    class="btn btn-sm btn-danger" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-times" style="font-size: 3rem;"></i>
                        <h3>Nenhuma programação encontrada</h3>
                        <?php if (!empty($search)): ?>
                            <p>Nenhuma programação foi encontrada para o termo "<?= htmlspecialchars($search) ?>".</p>
                            <a href="<?= Router::url('programacoes') ?>" class="btn btn-secondary">Ver todas</a>
                        <?php else: ?>
                            <p>Ainda não há atividades cadastradas na programação.</p>
                            <a href="<?= Router::url('programacoes/create') ?>" class="btn btn-primary">Cadastrar primeira atividade</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar Exclusão</h3>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Tem certeza que deseja excluir a programação "<span id="programacaoName"></span>"?</p>
            <p style="color: #f39c12; font-size: 0.9rem;">
                <i class="fas fa-exclamation-triangle"></i>
                Esta ação não pode ser desfeita.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">Excluir</button>
        </div>
    </div>
</div>

<script>
let deleteId = null;

function deleteProgramacao(id, name) {
    deleteId = id;
    document.getElementById('programacaoName').textContent = name;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
    deleteId = null;
}

function confirmDelete() {
    if (deleteId) {
        // Debug da URL
        const deleteUrl = '<?= Router::url('programacoes/delete/') ?>' + deleteId;
        console.log('URL de delete:', deleteUrl);
        console.log('ID para deletar:', deleteId);
        console.log('Base URL:', '<?= BASE_URL ?>');
        
        // Confirmar novamente antes de fazer requisição
        if (confirm('Confirma a exclusão? ID: ' + deleteId)) {
            console.log('Fazendo requisição para:', deleteUrl);
            
            // Usar fetch para debug melhor
            fetch(deleteUrl, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response URL:', response.url);
                console.log('Response OK:', response.ok);
                console.log('Response redirected:', response.redirected);
                
                if (response.ok || response.redirected || response.status === 302) {
                    console.log('Redirecionando para programacoes...');
                    window.location.href = '<?= Router::url('programacoes') ?>';
                } else {
                    console.error('Erro na resposta:', response);
                    return response.text().then(text => {
                        console.log('Response body:', text);
                        alert('Erro ao excluir. Ver console para detalhes.');
                    });
                }
            })
            .catch(error => {
                console.error('Erro na requisição fetch:', error);
                alert('Erro ao excluir: ' + error.message);
            });
        }
    } else {
        alert('Erro: ID não encontrado');
    }
}

function toggleStatus(id) {
    if (confirm('Tem certeza que deseja alterar o status desta programação?')) {
        window.location.href = '<?= Router::url('programacoes/toggle/') ?>' + id;
    }
}

// Fechar modal ao clicar fora
window.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeModal();
    }
});
</script>

<style>
.program-info strong {
    color: var(--text-light);
    font-weight: 600;
}

.time-info {
    text-align: center;
}

.time-info strong {
    color: var(--primary-color);
    font-size: 1.1rem;
}

.badge-dj { background: rgba(52, 152, 219, 0.2); color: #85c1e9; }
.badge-artista-solo { background: rgba(155, 89, 182, 0.2); color: #d7bde2; }
.badge-artista-dupla { background: rgba(155, 89, 182, 0.2); color: #d7bde2; }
.badge-magico { background: rgba(241, 196, 15, 0.2); color: #f4d03f; }
.badge-danca { background: rgba(231, 76, 60, 0.2); color: #f1948a; }
.badge-atividade-infantil { background: rgba(46, 204, 113, 0.2); color: #82e5aa; }
.badge-teatro { background: rgba(142, 68, 173, 0.2); color: #d2b4de; }
.badge-show { background: rgba(230, 126, 34, 0.2); color: #f8c471; }
.badge-workshop { background: rgba(26, 188, 156, 0.2); color: #7fb3d3; }
.badge-palestra { background: rgba(52, 73, 94, 0.2); color: #aeb6bf; }
.badge-outro { background: rgba(149, 165, 166, 0.2); color: #bdc3c7; }
</style>