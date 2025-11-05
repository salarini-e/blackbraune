<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar Cadastro</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/newsletter">Newsletter</a></li>
                    <li class="breadcrumb-item active">Editar Cadastro</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informações do Cadastro</h3>
                        <div class="card-tools">
                            <span class="badge badge-info">ID: <?= $cadastro['id'] ?></span>
                        </div>
                    </div>
                    <form method="POST" action="/newsletter/update/<?= $cadastro['id'] ?>">
                        <div class="card-body">
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger">
                                    <?= $_SESSION['error'] ?>
                                    <?php unset($_SESSION['error']); ?>
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nome">Nome Completo *</label>
                                        <input type="text" class="form-control" id="nome" name="nome" required 
                                               value="<?= htmlspecialchars($cadastro['nome']) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">E-mail *</label>
                                        <input type="email" class="form-control" id="email" name="email" required 
                                               value="<?= htmlspecialchars($cadastro['email']) ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefone">Telefone</label>
                                        <input type="tel" class="form-control" id="telefone" name="telefone" 
                                               value="<?= htmlspecialchars($cadastro['telefone']) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" 
                                               value="<?= htmlspecialchars($cadastro['cidade']) ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="interesses">Áreas de Interesse</label>
                                <select class="form-control" id="interesses" name="interesses[]" multiple>
                                    <?php foreach ($interessesOptions as $value => $label): ?>
                                        <option value="<?= $value ?>" 
                                                <?= in_array($value, $interessesSelecionados) ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">
                                    Segure Ctrl (ou Cmd no Mac) para selecionar múltiplas opções.
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status do Cadastro</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="ativo" <?= $cadastro['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                                            <option value="inativo" <?= $cadastro['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="newsletter_ativo" 
                                                   name="newsletter_ativo" <?= $cadastro['newsletter_ativo'] ? 'checked' : '' ?>>
                                            <label class="custom-control-label" for="newsletter_ativo">
                                                Receber newsletter
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Atualizar Cadastro
                            </button>
                            <a href="/newsletter" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informações do Cadastro</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-5">Data de Cadastro:</dt>
                            <dd class="col-sm-7"><?= date('d/m/Y H:i', strtotime($cadastro['data_cadastro'])) ?></dd>
                            
                            <dt class="col-sm-5">Status Atual:</dt>
                            <dd class="col-sm-7">
                                <span class="badge badge-<?= $cadastro['status'] === 'ativo' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($cadastro['status']) ?>
                                </span>
                            </dd>
                            
                            <dt class="col-sm-5">Newsletter:</dt>
                            <dd class="col-sm-7">
                                <span class="badge badge-<?= $cadastro['newsletter_ativo'] ? 'success' : 'secondary' ?>">
                                    <?= $cadastro['newsletter_ativo'] ? 'Ativa' : 'Inativa' ?>
                                </span>
                            </dd>
                            
                            <dt class="col-sm-5">Termos Aceitos:</dt>
                            <dd class="col-sm-7">
                                <span class="badge badge-<?= $cadastro['termos_aceitos'] ? 'success' : 'danger' ?>">
                                    <?= $cadastro['termos_aceitos'] ? 'Sim' : 'Não' ?>
                                </span>
                            </dd>
                        </dl>

                        <?php if (!empty($interessesSelecionados)): ?>
                            <h6 class="mt-3">Interesses Atuais:</h6>
                            <div class="mb-2">
                                <?php foreach ($interessesSelecionados as $interesse): ?>
                                    <?php if (isset($interessesOptions[$interesse])): ?>
                                        <span class="badge badge-primary mr-1">
                                            <?= $interessesOptions[$interesse] ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nota:</strong> Alterações no status e newsletter podem ser feitas diretamente na listagem principal.
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ações Rápidas</h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-group d-flex" role="group">
                            <a href="/newsletter/toggle-status/<?= $cadastro['id'] ?>" 
                               class="btn btn-outline-secondary"
                               onclick="return confirm('Alterar status do cadastro?')">
                                <i class="fas fa-toggle-<?= $cadastro['status'] === 'ativo' ? 'on' : 'off' ?>"></i>
                                Toggle Status
                            </a>
                            <a href="/newsletter/toggle-newsletter/<?= $cadastro['id'] ?>" 
                               class="btn btn-outline-info"
                               onclick="return confirm('Alterar preferência de newsletter?')">
                                <i class="fas fa-envelope"></i>
                                Toggle Newsletter
                            </a>
                        </div>
                        
                        <div class="mt-2">
                            <a href="/newsletter/delete/<?= $cadastro['id'] ?>" 
                               class="btn btn-outline-danger btn-block"
                               onclick="return confirm('Tem certeza que deseja excluir este cadastro? Esta ação não pode ser desfeita.')">
                                <i class="fas fa-trash"></i> Excluir Cadastro
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                } else {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                }
                e.target.value = value;
            }
        });
    }
});
</script>