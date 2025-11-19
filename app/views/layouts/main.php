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
    
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1258031249465413');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1258031249465413&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
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