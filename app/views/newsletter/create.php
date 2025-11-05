<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Novo Cadastro</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/newsletter">Newsletter</a></li>
                    <li class="breadcrumb-item active">Novo Cadastro</li>
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
                    </div>
                    <form method="POST" action="/newsletter/store">
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
                                               value="<?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">E-mail *</label>
                                        <input type="email" class="form-control" id="email" name="email" required 
                                               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefone">Telefone</label>
                                        <input type="tel" class="form-control" id="telefone" name="telefone" 
                                               value="<?= isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" 
                                               value="<?= isset($_POST['cidade']) ? htmlspecialchars($_POST['cidade']) : 'Nova Friburgo' ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="interesses">Áreas de Interesse</label>
                                <select class="form-control" id="interesses" name="interesses[]" multiple>
                                    <?php foreach ($interessesOptions as $value => $label): ?>
                                        <option value="<?= $value ?>" 
                                                <?= isset($_POST['interesses']) && in_array($value, $_POST['interesses']) ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">
                                    Segure Ctrl (ou Cmd no Mac) para selecionar múltiplas opções.
                                </small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="newsletter_ativo" 
                                           name="newsletter_ativo" checked>
                                    <label class="custom-control-label" for="newsletter_ativo">
                                        Receber newsletter com novidades e promoções
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Criar Cadastro
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
                        <h3 class="card-title">Informações</h3>
                    </div>
                    <div class="card-body">
                        <h5>Campos Obrigatórios</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Nome completo</li>
                            <li><i class="fas fa-check text-success"></i> E-mail válido</li>
                        </ul>

                        <h5 class="mt-3">Campos Opcionais</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-info text-info"></i> Telefone</li>
                            <li><i class="fas fa-info text-info"></i> Cidade</li>
                            <li><i class="fas fa-info text-info"></i> Áreas de interesse</li>
                        </ul>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Dica:</strong> As áreas de interesse ajudam a segmentar melhor os envios de newsletter.
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