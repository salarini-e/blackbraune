<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="<?= Router::url('assets/logo.png') ?>" alt="Black Braune">
        <h2>Black Braune</h2>
    </div>
    
    <!-- User Info -->
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
    <div class="sidebar-user">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($_SESSION['user_nome'] ?? 'Usuário') ?></div>
            <div class="user-role"><?= ucfirst($_SESSION['user_tipo'] ?? 'admin') ?></div>
        </div>
    </div>
    <?php endif; ?>
    
    <nav>
        <ul class="sidebar-nav">
            <li class="sidebar-nav-item">
                <a href="<?= Router::url('dashboard') ?>" class="sidebar-nav-link <?= ($page === 'dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    Dashboard
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="<?= Router::url('dashboard/parceiros') ?>" class="sidebar-nav-link <?= (strpos($page, 'parceiros') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-handshake"></i>
                    Parceiros
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="<?= Router::url('programacoes') ?>" class="sidebar-nav-link <?= (strpos($page, 'programacoes') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                    Programação
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="<?= Router::url('newsletter') ?>" class="sidebar-nav-link <?= (strpos($page, 'newsletter') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-envelope"></i>
                    Newsletter
                </a>
            </li>
            <!-- <li class="sidebar-nav-item">
                <a href="#" class="sidebar-nav-link">
                    <i class="fas fa-store"></i>
                    Lojas
                </a>
            </li> -->
            <li class="sidebar-nav-item">
                <a href="<?= Router::url('dashboard/usuarios') ?>" class="sidebar-nav-link <?= (strpos($page, 'usuarios') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    Usuários
                </a>
            </li>            
            
            <!-- <li class="sidebar-nav-item">
                <a href="#" class="sidebar-nav-link">
                    <i class="fas fa-images"></i>
                    Galeria
                </a>
            </li> -->
        
            <li class="sidebar-nav-item sidebar-divider">
                <a href="<?= Router::url() ?>" class="sidebar-nav-link">
                    <i class="fas fa-globe"></i>
                    Site Principal
                </a>
            </li>
            <!-- <li class="sidebar-nav-item">
                <a href="#" class="sidebar-nav-link">
                    <i class="fas fa-cog"></i>
                    Configurações
                </a>
            </li> -->
            <li class="sidebar-nav-item">
                <a href="<?= Router::url('logout') ?>" class="sidebar-nav-link" onclick="return confirm('Tem certeza que deseja sair?')">
                    <i class="fas fa-sign-out-alt"></i>
                    Sair
                </a>
            </li>
        </ul>
    </nav>
</aside>