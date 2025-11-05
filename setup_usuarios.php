<?php
/**
 * Script para criar primeiro usuário administrador
 */

// Define as constantes necessárias
define('ROOT_PATH', dirname(__FILE__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', APP_PATH . '/core');
define('MODELS_PATH', APP_PATH . '/models');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Carrega configurações
require_once CORE_PATH . '/Config.php';
Config::load();

// Carrega classes necessárias
require_once CORE_PATH . '/Database.php';
require_once CONFIG_PATH . '/config.php';
require_once MODELS_PATH . '/UsuarioModel.php';

echo "<h2>Configuração Inicial - Usuários</h2>";

try {
    $usuarioModel = new UsuarioModel();
    
    // Verifica se já existem usuários
    $totalUsuarios = $usuarioModel->count();
    
    if ($totalUsuarios > 0) {
        echo "<p>✓ Sistema já possui $totalUsuarios usuário(s) cadastrado(s).</p>";
        
        // Lista usuários existentes
        $usuarios = $usuarioModel->getAll();
        echo "<h3>Usuários existentes:</h3>";
        echo "<ul>";
        foreach ($usuarios as $usuario) {
            $status = $usuario['ativo'] ? 'Ativo' : 'Inativo';
            echo "<li><strong>" . htmlspecialchars($usuario['nome']) . "</strong> - " . htmlspecialchars($usuario['email']) . " ($status)</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>⚠️ Nenhum usuário encontrado. Criando usuário administrador padrão...</p>";
        
        // Cria usuário admin padrão
        $adminPadrao = [
            'nome' => 'Administrador Black Braune',
            'email' => 'admin@blackbraune.com',
            'senha' => 'admin123',
            'confirmar_senha' => 'admin123',
            'tipo' => 'admin',
            'ativo' => 1
        ];
        
        $usuario = $usuarioModel->create($adminPadrao);
        
        echo "<p>✓ Usuário administrador criado com sucesso!</p>";
        echo "<div style='background: #e8f5e8; padding: 15px; border: 1px solid #4caf50; margin: 10px 0;'>";
        echo "<h3>Credenciais de Acesso:</h3>";
        echo "<p><strong>Email:</strong> admin@blackbraune.com</p>";
        echo "<p><strong>Senha:</strong> admin123</p>";
        echo "<p><strong>⚠️ IMPORTANTE:</strong> Altere essas credenciais após o primeiro acesso!</p>";
        echo "</div>";
    }
    
    echo "<h3>Acesso ao Sistema:</h3>";
    echo "<p>Para acessar o painel administrativo:</p>";
    echo "<ol>";
    echo "<li>Acesse: <strong>dashboard/usuarios</strong></li>";
    echo "<li>Faça login com as credenciais acima</li>";
    echo "<li>Gerencie usuários, adicione novos administradores</li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>