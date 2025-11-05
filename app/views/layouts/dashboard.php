<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Dashboard - Black Braune' ?></title>
    <meta name="description" content="<?= isset($description) ? $description : 'Dashboard administrativo do movimento Black Braune' ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= Router::url('assets/logo.png') ?>">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="<?= Router::url('assets/dashboard.css') ?>">
    
    <!-- CSS adicional específico da página -->
    <?php if (isset($additionalCSS)): ?>
        <?= $additionalCSS ?>
    <?php endif; ?>
</head>
<body class="dashboard <?= isset($page) ? 'page-' . $page : '' ?>">
    
    <div class="dashboard-container">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>

        <!-- Conteúdo da página -->
        <?= $content ?>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Funções globais do dashboard
        
        // Navegação ativa
        document.querySelectorAll('.sidebar-nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '#') {
                    e.preventDefault();
                }
            });
        });

        // Flash messages
        function showFlash(type, message) {
            const flash = document.createElement('div');
            flash.className = `flash-message flash-${type}`;
            flash.textContent = message;
            document.body.appendChild(flash);
            
            setTimeout(() => {
                flash.remove();
            }, 5000);
        }

        // Função de logout
        function logout() {
            if (confirm('Tem certeza que deseja sair do dashboard?')) {
                // Aqui você pode implementar a lógica de logout
                // Por enquanto, apenas redireciona para a home
                window.location.href = '<?= Router::url() ?>';
            }
        }
    </script>
    
    <!-- JavaScript adicional específico da página -->
    <?php if (isset($additionalJS)): ?>
        <?= $additionalJS ?>
    <?php endif; ?>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash'])): ?>
        <script>
            <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                showFlash('<?= $type ?>', '<?= addslashes($message) ?>');
            <?php endforeach; ?>
        </script>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
</body>
</html>