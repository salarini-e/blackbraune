<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Black Braune - Nova Friburgo' ?></title>
    <meta name="description" content="<?= isset($description) ? $description : 'Movimento de revitalização comercial em Nova Friburgo' ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= Router::url('assets/logo.png') ?>">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= Router::url('assets/styles.css') ?>">
    
    <!-- Meta tags adicionais -->
    <meta name="author" content="Black Braune">
    <meta name="keywords" content="Nova Friburgo, Black Braune, comércio, revitalização, movimento">
    <meta property="og:title" content="<?= isset($title) ? $title : 'Black Braune - Nova Friburgo' ?>">
    <meta property="og:description" content="<?= isset($description) ? $description : 'Movimento de revitalização comercial em Nova Friburgo' ?>">
    <meta property="og:image" content="<?= Router::url('assets/logo.png') ?>">
    <meta property="og:url" content="<?= Router::url() ?>">
    
    <!-- CSS adicional específico da página -->
    <?php if (isset($additionalCSS)): ?>
        <?= $additionalCSS ?>
    <?php endif; ?>
</head>
<body class="<?= isset($page) ? 'page-' . $page : '' ?>">
    
    <!-- Conteúdo da página -->
    <?= $content ?>
    
    <!-- JavaScript -->
    <script src="<?= Router::url('assets/script.js') ?>"></script>
    
    <!-- JavaScript adicional específico da página -->
    <?php if (isset($additionalJS)): ?>
        <?= $additionalJS ?>
    <?php endif; ?>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash'])): ?>
        <script>
            <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                console.log('Flash <?= $type ?>: <?= addslashes($message) ?>');
                // Aqui você pode adicionar notificações visuais
            <?php endforeach; ?>
        </script>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
</body>
</html>