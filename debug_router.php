<?php
/**
 * Debug do roteamento
 */

// Define as constantes necessárias
define('ROOT_PATH', dirname(__FILE__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', APP_PATH . '/core');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('MODELS_PATH', APP_PATH . '/models');
define('VIEWS_PATH', APP_PATH . '/views');

// Carrega as classes necessárias
require_once CORE_PATH . '/Config.php';
require_once CORE_PATH . '/Database.php';
require_once CORE_PATH . '/Router.php';
require_once CORE_PATH . '/Controller.php';
require_once CONTROLLERS_PATH . '/ParceirosController.php';

echo "<h2>Debug do Roteamento</h2>";

// Simula diferentes URLs
$testUrls = [
    'dashboard/parceiros',
    'dashboard/parceiros/editar/1',
    'dashboard/parceiros/cadastro',
    'dashboard/parceiros/criar'
];

foreach ($testUrls as $testUrl) {
    echo "<hr>";
    echo "<h3>Testando URL: $testUrl</h3>";
    
    // Simula a URL
    $_GET['url'] = $testUrl;
    $_SERVER['REQUEST_URI'] = "/$testUrl";
    
    // Cria novo router
    $router = new Router();
    
    // Usa reflexão para acessar métodos privados
    $reflection = new ReflectionClass($router);
    $getUrlMethod = $reflection->getMethod('getUrl');
    $getUrlMethod->setAccessible(true);
    
    $processedUrl = $getUrlMethod->invoke($router);
    echo "URL processada: '$processedUrl'<br>";
    
    // Simula o processamento (sem executar)
    $urlArray = explode('/', trim($processedUrl, '/'));
    echo "URL Array: " . print_r($urlArray, true) . "<br>";
    
    if (!empty($urlArray[0]) && $urlArray[0] === 'dashboard') {
        if (isset($urlArray[1]) && $urlArray[1] === 'parceiros') {
            echo "Controller: ParceirosController<br>";
            
            if (isset($urlArray[2]) && !empty($urlArray[2])) {
                $method = $urlArray[2];
                echo "Method: $method<br>";
                $params = array_slice($urlArray, 3);
                echo "Params: " . print_r($params, true) . "<br>";
            } else {
                echo "Method: index<br>";
                echo "Params: []<br>";
            }
        }
    }
}

echo "<hr>";
echo "<h3>Informações da Requisição Atual</h3>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'não definido') . "<br>";
echo "GET url: " . ($_GET['url'] ?? 'não definido') . "<br>";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'não definido') . "<br>";
?>