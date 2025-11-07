<style>
/* Responsividade para Newsletter */
@media (max-width: 768px) {
    /* Header responsivo */
    .content-header > div > div {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 1rem;
    }
    
    /* Estatísticas responsivas */
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
    }
    
    /* Header do card responsivo */
    .card-header-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 1rem;
    }
    
    .card-header-flex .card-tools {
        margin-top: 0 !important;
        width: 100%;
    }
    
    /* Filtros responsivos */
    .filters-grid {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    
    .filters-inner-grid {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    
    .filters-buttons {
        justify-content: stretch !important;
    }
    
    .filters-buttons > * {
        flex: 1 !important;
        text-align: center !important;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr !important;
    }
    
    .filters-buttons {
        flex-direction: column !important;
    }
    
    .filters-buttons > * {
        width: 100% !important;
    }
}
</style>

<div class="content-header">
    <div class="container-fluid">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap;">
            <div style="flex: 1;">
                <h1 style="margin: 0; font-size: 1.8rem; font-weight: 600;">
                    <i class="fas fa-envelope-open-text" style="margin-right: 10px;"></i> Newsletter & Cadastros
                </h1>
            </div>
            <div style="flex-shrink: 0;">
                <ol class="breadcrumb" style="display: flex; list-style: none; margin: 0; padding: 0; background: transparent;">
                    <li class="breadcrumb-item" style="margin-right: 0.5rem;">
                        <a href="/dashboard" style="text-decoration: none; font-weight: 500;">Dashboard</a>
                        <span style="margin: 0 0.5rem;">/</span>
                    </li>
                    <li class="breadcrumb-item active" style="font-weight: 500;">Newsletter</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Alertas -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
                <button type="button" class="close" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <i class="fas fa-exclamation-triangle"></i> <?= $_SESSION['error'] ?>
                <button type="button" class="close" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Estatísticas -->
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div class="small-box bg-info" style="border-radius: 12px; padding: 1.5rem; position: relative; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); min-height: 120px;">
                <div class="inner" style="position: relative; z-index: 2;">
                    <h3 style="font-size: 2.2rem; font-weight: bold; margin: 0 0 0.5rem 0; line-height: 1;"><?= $estatisticas['total'] ?? 0 ?></h3>
                    <p style="margin: 0; font-size: 0.95rem; font-weight: 500;">Total de Cadastros</p>
                </div>
                <div class="icon" style="position: absolute; top: 20px; right: 20px; font-size: 3.5rem; opacity: 0.2; z-index: 1;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            
            <div class="small-box bg-success" style="border-radius: 12px; padding: 1.5rem; position: relative; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); min-height: 120px;">
                <div class="inner" style="position: relative; z-index: 2;">
                    <h3 style="font-size: 2.2rem; font-weight: bold; margin: 0 0 0.5rem 0; line-height: 1;"><?= $estatisticas['ativos'] ?? 0 ?></h3>
                    <p style="margin: 0; font-size: 0.95rem; font-weight: 500;">Cadastros Ativos</p>
                </div>
                <div class="icon" style="position: absolute; top: 20px; right: 20px; font-size: 3.5rem; opacity: 0.2; z-index: 1;">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            
            <div class="small-box bg-warning" style="border-radius: 12px; padding: 1.5rem; position: relative; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); min-height: 120px;">
                <div class="inner" style="position: relative; z-index: 2;">
                    <h3 style="font-size: 2.2rem; font-weight: bold; margin: 0 0 0.5rem 0; line-height: 1;"><?= $estatisticas['newsletter'] ?? 0 ?></h3>
                    <p style="margin: 0; font-size: 0.95rem; font-weight: 500;">Newsletter Ativa</p>
                </div>
                <div class="icon" style="position: absolute; top: 20px; right: 20px; font-size: 3.5rem; opacity: 0.2; z-index: 1;">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            
            <div class="small-box bg-danger" style="border-radius: 12px; padding: 1.5rem; position: relative; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); min-height: 120px;">
                <div class="inner" style="position: relative; z-index: 2;">
                    <h3 style="font-size: 2.2rem; font-weight: bold; margin: 0 0 0.5rem 0; line-height: 1;"><?= isset($estatisticas['por_mes']) ? count($estatisticas['por_mes']) : 0 ?></h3>
                    <p style="margin: 0; font-size: 0.95rem; font-weight: 500;">Meses Ativos</p>
                </div>
                <div class="icon" style="position: absolute; top: 20px; right: 20px; font-size: 3.5rem; opacity: 0.2; z-index: 1;">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>

        <!-- Card Principal -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-header-flex" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                        <h3 class="card-title" style="margin: 0; display: flex; align-items: center;">
                            <i class="fas fa-table" style="margin-right: 8px;"></i> Gerenciar Cadastros
                        </h3>
                        <div class="card-tools" style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
                            <a href="/newsletter/create" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center;">
                                <i class="fas fa-plus" style="margin-right: 6px;"></i> Novo Cadastro
                            </a>
                            <a href="/newsletter/export" class="btn btn-success btn-sm" style="display: inline-flex; align-items: center;">
                                <i class="fas fa-download" style="margin-right: 6px;"></i> Exportar CSV
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Filtros -->
                        <div style="margin-bottom: 1.5rem;">
                            <form method="GET" class="filters-grid" style="display: grid; grid-template-columns: 1fr auto; gap: 1rem; align-items: end;">
                                <div class="filters-inner-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; align-items: end;">
                                    <div style="position: relative;">
                                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.875rem;">Buscar</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="search" class="form-control"
                                                   placeholder="Buscar por nome, email ou cidade..." 
                                                   value="<?= htmlspecialchars($search ?? '') ?>">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.875rem;">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">Todos os status</option>
                                            <option value="ativos" <?= ($status ?? '') === 'ativos' ? 'selected' : '' ?>>Apenas Ativos</option>
                                            <option value="newsletter" <?= ($status ?? '') === 'newsletter' ? 'selected' : '' ?>>Newsletter Ativa</option>
                                            <option value="inativos" <?= ($status ?? '') === 'inativos' ? 'selected' : '' ?>>Inativos</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="filters-buttons" style="display: flex; gap: 0.5rem; align-items: end;">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-search" style="margin-right: 6px;"></i> Filtrar
                                    </button>
                                    <?php if (($search ?? '') || ($status ?? '')): ?>
                                        <a href="/newsletter" class="btn btn-outline-secondary">
                                            <i class="fas fa-times" style="margin-right: 6px;"></i> Limpar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>

                        <!-- Tabela -->
                        <div class="table-responsive">
                            <?php if (empty($cadastros)): ?>
                                <div class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h4>Nenhum cadastro encontrado</h4>
                                        <p class="text-muted">
                                            <?php if (($search ?? '') || ($status ?? '')): ?>
                                                Tente ajustar os filtros ou <a href="/newsletter">ver todos os cadastros</a>
                                            <?php else: ?>
                                                <a href="/newsletter/create" class="btn btn-primary">Cadastre o primeiro usuário</a>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="60">ID</th>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th width="120">Telefone</th>
                                            <th>Cidade</th>
                                            <th width="100">Newsletter</th>
                                            <th width="80">Status</th>
                                            <th width="120">Data</th>
                                            <th width="100">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cadastros as $cadastro): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <strong><?= $cadastro['id'] ?></strong>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary text-white mr-2">
                                                            <?= strtoupper(substr($cadastro['nome'], 0, 1)) ?>
                                                        </div>
                                                        <?= htmlspecialchars($cadastro['nome']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="mailto:<?= htmlspecialchars($cadastro['email']) ?>" class="text-primary">
                                                        <i class="fas fa-envelope mr-1"></i>
                                                        <?= htmlspecialchars($cadastro['email']) ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php if ($cadastro['telefone']): ?>
                                                        <a href="tel:<?= htmlspecialchars($cadastro['telefone']) ?>" class="text-success">
                                                            <i class="fas fa-phone mr-1"></i>
                                                            <?= htmlspecialchars($cadastro['telefone']) ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                                                    <?= htmlspecialchars($cadastro['cidade'] ?? 'Nova Friburgo') ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-<?= $cadastro['newsletter_ativo'] ? 'success' : 'secondary' ?>">
                                                        <?= $cadastro['newsletter_ativo'] ? 'Ativa' : 'Inativa' ?>
                                                    </span>
                                                    <br>
                                                    <a href="/newsletter/toggle-newsletter/<?= $cadastro['id'] ?>" 
                                                       class="btn btn-xs btn-outline-secondary mt-1"
                                                       onclick="return confirm('Alterar preferência de newsletter?')"
                                                       title="Toggle Newsletter">
                                                        <i class="fas fa-toggle-<?= $cadastro['newsletter_ativo'] ? 'on text-success' : 'off text-muted' ?>"></i>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-<?= $cadastro['status'] === 'ativo' ? 'success' : 'danger' ?>">
                                                        <?= ucfirst($cadastro['status']) ?>
                                                    </span>
                                                    <br>
                                                    <a href="/newsletter/toggle-status/<?= $cadastro['id'] ?>" 
                                                       class="btn btn-xs btn-outline-secondary mt-1"
                                                       onclick="return confirm('Alterar status do cadastro?')"
                                                       title="Toggle Status">
                                                        <i class="fas fa-toggle-<?= $cadastro['status'] === 'ativo' ? 'on text-success' : 'off text-muted' ?>"></i>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <small>
                                                        <?= date('d/m/Y', strtotime($cadastro['data_cadastro'])) ?><br>
                                                        <?= date('H:i', strtotime($cadastro['data_cadastro'])) ?>
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group-vertical btn-group-sm">
                                                        <a href="/newsletter/edit/<?= $cadastro['id'] ?>" 
                                                           class="btn btn-outline-primary btn-xs"
                                                           title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="/newsletter/delete/<?= $cadastro['id'] ?>" 
                                                           class="btn btn-outline-danger btn-xs"
                                                           onclick="return confirm('Tem certeza que deseja excluir este cadastro?\n\nEsta ação não pode ser desfeita.')"
                                                           title="Excluir">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <!-- Informações da tabela -->
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <small class="text-muted">
                                            Exibindo <?= count($cadastros) ?> cadastro(s)
                                            <?php if (($search ?? '') || ($status ?? '')): ?>
                                                (filtrado)
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <small class="text-muted">
                                            Última atualização: <?= date('d/m/Y H:i') ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos de Estatísticas -->
        <?php if (!empty($estatisticas['interesses_populares']) || !empty($estatisticas['por_mes'])): ?>
        <div class="row">
            <?php if (!empty($estatisticas['interesses_populares'])): ?>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-heart"></i> Interesses Mais Populares</h3>
                    </div>
                    <div class="card-body">
                        <?php foreach ($estatisticas['interesses_populares'] as $interesse => $count): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><?= ucfirst($interesse) ?></span>
                                    <span class="badge badge-primary"><?= $count ?></span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" 
                                         style="width: <?= ($estatisticas['total'] > 0) ? ($count / $estatisticas['total']) * 100 : 0 ?>%">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($estatisticas['por_mes'])): ?>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar"></i> Cadastros por Mês</h3>
                    </div>
                    <div class="card-body">
                        <?php 
                        $maxCadastros = max(array_column($estatisticas['por_mes'], 'total'));
                        foreach ($estatisticas['por_mes'] as $mes): 
                        ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><?= date('m/Y', strtotime($mes['mes'] . '-01')) ?></span>
                                    <span class="badge badge-success"><?= $mes['total'] ?></span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" 
                                         style="width: <?= $maxCadastros > 0 ? ($mes['total'] / $maxCadastros) * 100 : 0 ?>%">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>