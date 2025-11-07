<div class="dashboard-main">
    <div class="dashboard-header">
        <h1>Dashboard Principal</h1>
        <p>Bem-vindo ao painel administrativo do Black Braune</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>Total de Parceiros</h3>
                <span class="stat-number"><?= $totalParceiros ?? 0 ?></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="stat-info">
                <h3>Lojas Cadastradas</h3>
                <span class="stat-number"><?= $totalLojas ?? 0 ?></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="stat-info">
                <h3>Patrocinadores</h3>
                <span class="stat-number"><?= $totalPatrocinadores ?? 0 ?></span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-info">
                <h3>Cadastros Newsletter</h3>
                <span class="stat-number"><?= $totalNewsletters ?? 0 ?></span>
            </div>
        </div>
    </div>

    <div class="dashboard-widgets">
        <div class="widget">
            <div class="widget-header">
                <h3>Últimos Parceiros</h3>
                <a href="<?= Router::url('dashboard/parceiros') ?>" class="widget-link">Ver todos</a>
            </div>
            <div class="widget-content">
                <?php if (!empty($ultimosParceiros)): ?>
                    <div class="recent-items">
                        <?php foreach ($ultimosParceiros as $parceiro): ?>
                            <div class="recent-item">
                                <div class="item-info">
                                    <h4><?= htmlspecialchars($parceiro['nome']) ?></h4>
                                    <p><?= htmlspecialchars($parceiro['tipo']) ?></p>
                                </div>
                                <div class="item-date">
                                    <small><?= date('d/m/Y', strtotime($parceiro['data_cadastro'] ?? 'now')) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-data">Nenhum parceiro cadastrado ainda.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="widget">
            <div class="widget-header">
                <h3>Atividade Recente</h3>
            </div>
            <div class="widget-content">
                <div class="activity-feed">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-plus-circle text-success"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>Novo parceiro cadastrado</strong></p>
                            <small>Há 2 horas</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-edit text-warning"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>Parceiro atualizado</strong></p>
                            <small>Há 1 dia</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus text-info"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>Novo cadastro newsletter</strong></p>
                            <small>Há 2 dias</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>