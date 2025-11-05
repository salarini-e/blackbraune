<?php
/**
 * Arquivo de configuração do sistema Black Braune
 */

// Carrega configurações do arquivo YAML
require_once CORE_PATH . '/Config.php';

// Carrega as configurações
Config::load();

// Configurações gerais
define('APP_NAME', Config::get('app.name', 'Black Braune'));
define('APP_VERSION', Config::get('app.version', '1.0.0'));
define('APP_DESCRIPTION', 'Sistema de gestão do movimento Black Braune - Nova Friburgo');

// Configurações de URL
define('BASE_URL', Config::get('url.base', 'http://localhost:8000/'));
define('ASSETS_URL', Config::get('url.assets', BASE_URL . 'assets/'));

// Configurações de timezone
date_default_timezone_set(Config::get('app.timezone', 'America/Sao_Paulo'));

// Configurações de erro (desenvolvimento)
if (Config::get('app.debug', false)) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Headers de segurança
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Configura o banco de dados
require_once CORE_PATH . '/Database.php';

// Cria tabelas se necessário
try {
    Database::createTables();
} catch (Exception $e) {
    if (Config::get('app.debug', false)) {
        die("Erro ao configurar banco de dados: " . $e->getMessage());
    }
}
?>