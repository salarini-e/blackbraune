<?php
/**
 * Arquivo principal de entrada do sistema Black Braune
 * Sistema MVC em PHP
 */

// Define constantes do sistema primeiro
define('ROOT_PATH', dirname(__FILE__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('MODELS_PATH', APP_PATH . '/models');
define('CORE_PATH', APP_PATH . '/core');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// Carrega configurações antes de tudo
require_once CORE_PATH . '/Config.php';
Config::load();

// Configurações de sessão (antes de iniciar a sessão)
ini_set('session.cookie_lifetime', Config::get('session.lifetime', 86400));
ini_set('session.gc_maxlifetime', Config::get('session.lifetime', 86400));
session_name(Config::get('session.name', 'BLACK_BRAUNE_SESSION'));

// Inicia a sessão
session_start();

// Autoload das classes
spl_autoload_register(function($class) {
    $paths = [
        CONTROLLERS_PATH . '/' . $class . '.php',
        MODELS_PATH . '/' . $class . '.php',
        CORE_PATH . '/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Inclui arquivos de configuração
require_once CONFIG_PATH . '/config.php';

// Inclui e inicia o roteador
require_once CORE_PATH . '/Router.php';

// Processa a requisição
$router = new Router();
$router->run();
?>