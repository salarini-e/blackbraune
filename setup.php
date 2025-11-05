<?php
/**
 * Setup inicial do sistema - Cria usuário admin padrão
 */

// Define as constantes necessárias
define('ROOT_PATH', dirname(__FILE__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', APP_PATH . '/core');
define('MODELS_PATH', APP_PATH . '/models');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Carrega configurações
require_once CORE_PATH . '/Config.php';
Config::load();

// Define BASE_URL
define('BASE_URL', Config::get('url.base', 'http://localhost:8000/'));

// Inicia sessão
session_start();

// Carrega classes necessárias
require_once CORE_PATH . '/Database.php';
require_once MODELS_PATH . '/UsuarioModel.php';
require_once CONFIG_PATH . '/config.php';

echo "<h2>Setup do Sistema Black Braune</h2>";

try {
    $usuarioModel = new UsuarioModel();
    
    // Verifica se já existem usuários
    $totalUsuarios = $usuarioModel->count();
    
    if ($totalUsuarios > 0) {
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 1rem; border-radius: 5px; margin: 1rem 0;'>";
        echo "<h3>✓ Sistema já configurado!</h3>";
        echo "<p>Já existem <strong>$totalUsuarios</strong> usuário(s) cadastrado(s) no sistema.</p>";
        echo "<p><a href='" . BASE_URL . "login'>Clique aqui para fazer login</a></p>";
        echo "</div>";
    } else {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 1rem; border-radius: 5px; margin: 1rem 0;'>";
        echo "<h3>Configurando sistema...</h3>";
        echo "</div>";
        
        // Cria usuário admin padrão
        $adminPadrao = [
            'nome' => 'Administrador',
            'email' => 'admin@blackbraune.com',
            'senha' => 'admin123',
            'tipo' => 'admin',
            'ativo' => 1
        ];
        
        $usuario = $usuarioModel->create($adminPadrao);
        
        if ($usuario) {
            echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; padding: 1rem; border-radius: 5px; margin: 1rem 0;'>";
            echo "<h3>✓ Sistema configurado com sucesso!</h3>";
            echo "<p>Usuário administrador criado:</p>";
            echo "<ul>";
            echo "<li><strong>Email:</strong> admin@blackbraune.com</li>";
            echo "<li><strong>Senha:</strong> admin123</li>";
            echo "<li><strong>Tipo:</strong> Administrador</li>";
            echo "</ul>";
            echo "<p><strong>⚠️ IMPORTANTE:</strong> Altere a senha padrão após o primeiro login!</p>";
            echo "<p><a href='" . BASE_URL . "login' style='background: #007bff; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px;'>Fazer Login</a></p>";
            echo "</div>";
        }
    }
    
    // Testa também se as tabelas foram criadas
    echo "<h3>Status das Tabelas:</h3>";
    
    // Testa parceiros
    try {
        $parceiros = Database::fetch("SELECT COUNT(*) as total FROM parceiros");
        echo "<p>✓ Tabela 'parceiros': " . $parceiros['total'] . " registro(s)</p>";
    } catch (Exception $e) {
        echo "<p>❌ Tabela 'parceiros': Erro - " . $e->getMessage() . "</p>";
    }
    
    // Testa usuários
    try {
        $usuarios = Database::fetch("SELECT COUNT(*) as total FROM usuarios");
        echo "<p>✓ Tabela 'usuarios': " . $usuarios['total'] . " registro(s)</p>";
    } catch (Exception $e) {
        echo "<p>❌ Tabela 'usuarios': Erro - " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 1rem; border-radius: 5px; margin: 1rem 0;'>";
    echo "<h3>❌ Erro na configuração</h3>";
    echo "<p>Erro: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "<hr>";
echo "<p><small>Sistema Black Braune - " . date('Y') . "</small></p>";
?>