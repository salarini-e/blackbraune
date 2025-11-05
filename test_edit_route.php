<?php
/**
 * Teste específico da rota de edição
 */

// Define as constantes necessárias
define('ROOT_PATH', dirname(__FILE__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('MODELS_PATH', APP_PATH . '/models');
define('CORE_PATH', APP_PATH . '/core');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// Carrega configurações
require_once CORE_PATH . '/Config.php';
Config::load();

// Define BASE_URL
define('BASE_URL', Config::get('url.base', 'http://localhost:8000/'));

// Inicia sessão
session_start();

// Autoload básico
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

// Carrega configurações do banco
require_once CONFIG_PATH . '/config.php';

echo "<h2>Teste da Rota de Edição</h2>";

// Simula URL de edição
$testUrl = 'dashboard/parceiros/editar/1';
$_GET['url'] = $testUrl;

echo "<p><strong>URL testada:</strong> $testUrl</p>";

// Carrega o router
require_once CORE_PATH . '/Router.php';

// Modifica a classe Router para debug
class DebugRouter extends Router 
{
    public function debugRun()
    {
        $url = $this->getDebugUrl();
        echo "<p><strong>URL processada:</strong> '$url'</p>";
        
        $urlArray = explode('/', trim($url, '/'));
        echo "<p><strong>URL Array:</strong> " . print_r($urlArray, true) . "</p>";
        
        if (!empty($urlArray[0]) && $urlArray[0] === 'dashboard') {
            echo "<p>✓ Detectou rota dashboard</p>";
            
            if (isset($urlArray[1]) && $urlArray[1] === 'parceiros') {
                echo "<p>✓ Detectou seção parceiros</p>";
                
                $controller = 'ParceirosController';
                echo "<p><strong>Controller:</strong> $controller</p>";
                
                if (isset($urlArray[2]) && !empty($urlArray[2])) {
                    $method = $urlArray[2];
                    echo "<p><strong>Method:</strong> $method</p>";
                    
                    $params = array_slice($urlArray, 3);
                    echo "<p><strong>Params:</strong> " . print_r($params, true) . "</p>";
                    
                    // Verifica se o controller existe
                    if (class_exists($controller)) {
                        echo "<p>✓ Controller existe</p>";
                        
                        // Verifica se o método existe
                        if (method_exists($controller, $method)) {
                            echo "<p>✓ Method existe</p>";
                            
                            // Tenta instanciar o controller
                            try {
                                $controllerInstance = new $controller();
                                echo "<p>✓ Controller instanciado</p>";
                                
                                // Simula chamada do método (sem executar)
                                echo "<p>🎯 Tudo pronto para executar: {$controller}->{$method}(" . implode(', ', $params) . ")</p>";
                                
                                // Agora executa de verdade
                                echo "<hr><h3>Executando o método...</h3>";
                                call_user_func_array([$controllerInstance, $method], $params);
                                
                            } catch (Exception $e) {
                                echo "<p>✗ Erro ao instanciar controller: " . $e->getMessage() . "</p>";
                            }
                        } else {
                            echo "<p>✗ Method não existe</p>";
                        }
                    } else {
                        echo "<p>✗ Controller não existe</p>";
                    }
                } else {
                    echo "<p><strong>Method:</strong> index (padrão)</p>";
                }
            }
        }
    }
    
    public function getDebugUrl()
    {
        $url = '';
        
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return $url;
        }
        
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            $requestUri = strtok($requestUri, '?');
            $url = ltrim($requestUri, '/');
            $url = rtrim($url, '/');
            return $url;
        }
        
        return '';
    }
}

$debugRouter = new DebugRouter();
$debugRouter->debugRun();
?>