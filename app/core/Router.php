<?php
/**
 * Router - Sistema de roteamento para Black Braune
 * Gerencia as rotas e direcionamento para controllers
 */

class Router
{
    private $routes = [];
    private $currentController = 'HomeController';
    private $currentMethod = 'index';
    private $params = [];

    public function __construct()
    {
        $this->setupRoutes();
    }

    /**
     * Define as rotas do sistema
     */
    private function setupRoutes()
    {
        // Rotas principais
        $this->routes = [
            '' => ['controller' => 'HomeController', 'method' => 'index'],
            '/' => ['controller' => 'HomeController', 'method' => 'index'],
            'home' => ['controller' => 'HomeController', 'method' => 'index'],
            'index' => ['controller' => 'HomeController', 'method' => 'index'],
            
            // Rotas do dashboard
            'dashboard' => ['controller' => 'DashboardController', 'method' => 'index'],
            'dashboard/index' => ['controller' => 'DashboardController', 'method' => 'index'],
            
            // Rotas de parceiros (dashboard)
            'dashboard/parceiros' => ['controller' => 'ParceirosController', 'method' => 'index'],
            'dashboard/parceiros/index' => ['controller' => 'ParceirosController', 'method' => 'index'],
            'dashboard/parceiros/cadastro' => ['controller' => 'ParceirosController', 'method' => 'cadastro'],
            'dashboard/parceiros/criar' => ['controller' => 'ParceirosController', 'method' => 'criar'],
            'dashboard/parceiros/editar' => ['controller' => 'ParceirosController', 'method' => 'editar'],
            'dashboard/parceiros/atualizar' => ['controller' => 'ParceirosController', 'method' => 'atualizar'],
            'dashboard/parceiros/deletar' => ['controller' => 'ParceirosController', 'method' => 'deletar'],
            'dashboard/parceiros/visualizar' => ['controller' => 'ParceirosController', 'method' => 'visualizar'],
            
            // Rotas de usuários (dashboard)
            'dashboard/usuarios' => ['controller' => 'UsuariosController', 'method' => 'index'],
            'dashboard/usuarios/index' => ['controller' => 'UsuariosController', 'method' => 'index'],
            'dashboard/usuarios/cadastro' => ['controller' => 'UsuariosController', 'method' => 'cadastro'],
            'dashboard/usuarios/criar' => ['controller' => 'UsuariosController', 'method' => 'criar'],
            'dashboard/usuarios/editar' => ['controller' => 'UsuariosController', 'method' => 'editar'],
            'dashboard/usuarios/atualizar' => ['controller' => 'UsuariosController', 'method' => 'atualizar'],
            'dashboard/usuarios/deletar' => ['controller' => 'UsuariosController', 'method' => 'deletar'],
            'dashboard/usuarios/visualizar' => ['controller' => 'UsuariosController', 'method' => 'visualizar'],
            
            // Rotas de programação (dashboard)
            'programacoes' => ['controller' => 'ProgramacoesController', 'method' => 'index'],
            'programacoes/index' => ['controller' => 'ProgramacoesController', 'method' => 'index'],
            'programacoes/create' => ['controller' => 'ProgramacoesController', 'method' => 'create'],
            'programacoes/store' => ['controller' => 'ProgramacoesController', 'method' => 'store'],
            'programacoes/edit' => ['controller' => 'ProgramacoesController', 'method' => 'edit'],
            'programacoes/update' => ['controller' => 'ProgramacoesController', 'method' => 'update'],
            'programacoes/delete' => ['controller' => 'ProgramacoesController', 'method' => 'delete'],
            'programacoes/toggle' => ['controller' => 'ProgramacoesController', 'method' => 'toggleStatus'],
            'programacoes/api' => ['controller' => 'ProgramacoesController', 'method' => 'api'],
            
            // Rotas de newsletter (dashboard)
            'newsletter' => ['controller' => 'NewsletterController', 'method' => 'index'],
            'newsletter/index' => ['controller' => 'NewsletterController', 'method' => 'index'],
            'newsletter/create' => ['controller' => 'NewsletterController', 'method' => 'create'],
            'newsletter/store' => ['controller' => 'NewsletterController', 'method' => 'store'],
            'newsletter/edit' => ['controller' => 'NewsletterController', 'method' => 'edit'],
            'newsletter/update' => ['controller' => 'NewsletterController', 'method' => 'update'],
            'newsletter/delete' => ['controller' => 'NewsletterController', 'method' => 'delete'],
            'newsletter/toggle-status' => ['controller' => 'NewsletterController', 'method' => 'toggleStatus'],
            'newsletter/toggle-newsletter' => ['controller' => 'NewsletterController', 'method' => 'toggleNewsletter'],
            'newsletter/export' => ['controller' => 'NewsletterController', 'method' => 'export'],
            
            // Rotas de autenticação
            'login' => ['controller' => 'AuthController', 'method' => 'login'],
            'auth/login' => ['controller' => 'AuthController', 'method' => 'login'],
            'auth/authenticate' => ['controller' => 'AuthController', 'method' => 'authenticate'],
            'logout' => ['controller' => 'AuthController', 'method' => 'logout'],
            'auth/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
            'auth/profile' => ['controller' => 'AuthController', 'method' => 'profile'],
            'auth/status' => ['controller' => 'AuthController', 'method' => 'status'],
            
            // Rotas de parceiros (públicas)
            'parceiros' => ['controller' => 'ParceirosController', 'method' => 'index'],
            'parceiros/index' => ['controller' => 'ParceirosController', 'method' => 'index'],
            
            // Rotas de API (para implementação futura)
            'api/parceiros' => ['controller' => 'ApiController', 'method' => 'parceiros'],
        ];
    }

    /**
     * Processa a URL e executa a rota correspondente
     */
    public function run()
    {
        $url = $this->getUrl();
        
        // Verifica se a rota existe exatamente
        if (isset($this->routes[$url])) {
            $route = $this->routes[$url];
            $this->currentController = $route['controller'];
            $this->currentMethod = $route['method'];
        } else {
            // Tenta processar URL dinâmica (controller/method/params)
            $urlArray = explode('/', trim($url, '/'));
            
            // Se a URL começa com 'dashboard', processa as rotas do dashboard
            if (!empty($urlArray[0]) && $urlArray[0] === 'dashboard') {
                if (isset($urlArray[1]) && $urlArray[1] === 'parceiros') {
                    $this->currentController = 'ParceirosController';
                    
                    // Se tem método específico
                    if (isset($urlArray[2]) && !empty($urlArray[2])) {
                        $method = $urlArray[2];
                        if (method_exists('ParceirosController', $method)) {
                            $this->currentMethod = $method;
                            // Parâmetros adicionais (como ID)
                            $this->params = array_slice($urlArray, 3);
                        }
                    } else {
                        $this->currentMethod = 'index';
                    }
                } elseif (isset($urlArray[1]) && $urlArray[1] === 'usuarios') {
                    $this->currentController = 'UsuariosController';
                    
                    // Se tem método específico
                    if (isset($urlArray[2]) && !empty($urlArray[2])) {
                        $method = $urlArray[2];
                        if (method_exists('UsuariosController', $method)) {
                            $this->currentMethod = $method;
                            // Parâmetros adicionais (como ID)
                            $this->params = array_slice($urlArray, 3);
                        }
                    } else {
                        $this->currentMethod = 'index';
                    }
                } elseif (isset($urlArray[1]) && $urlArray[1] === 'programacoes') {
                    $this->currentController = 'ProgramacoesController';
                    
                    // Se tem método específico
                    if (isset($urlArray[2]) && !empty($urlArray[2])) {
                        $method = $urlArray[2];
                        if (method_exists('ProgramacoesController', $method)) {
                            $this->currentMethod = $method;
                            // Parâmetros adicionais (como ID)
                            $this->params = array_slice($urlArray, 3);
                        }
                    } else {
                        $this->currentMethod = 'index';
                    }
                } else {
                    // Outras rotas do dashboard
                    $this->currentController = 'DashboardController';
                    $this->currentMethod = 'index';
                }
            } else {
                // Rotas gerais - incluindo programacoes
                if (!empty($urlArray[0])) {
                    if ($urlArray[0] === 'programacoes') {
                        $this->currentController = 'ProgramacoesController';
                        
                        // Se tem método específico
                        if (isset($urlArray[1]) && !empty($urlArray[1])) {
                            $method = $urlArray[1];
                            if (method_exists('ProgramacoesController', $method)) {
                                $this->currentMethod = $method;
                                // Parâmetros adicionais (como ID)
                                $this->params = array_slice($urlArray, 2);
                            }
                        } else {
                            $this->currentMethod = 'index';
                        }
                    } else {
                        $controller = ucfirst($urlArray[0]) . 'Controller';
                        if (class_exists($controller)) {
                            $this->currentController = $controller;
                            unset($urlArray[0]);
                        }
                    }
                }
                
                if (!empty($urlArray[1]) && $this->currentController !== 'ProgramacoesController') {
                    $method = $urlArray[1];
                    if (method_exists($this->currentController, $method)) {
                        $this->currentMethod = $method;
                        unset($urlArray[1]);
                    }
                }
                
                // Parâmetros restantes
                $this->params = $urlArray ? array_values($urlArray) : [];
            }
        }

        // Instancia o controller e executa o método
        if (!class_exists($this->currentController)) {
            $this->show404();
            return;
        }
        
        $controller = new $this->currentController;
        
        if (!method_exists($controller, $this->currentMethod)) {
            $this->show404();
            return;
        }
        
        call_user_func_array([$controller, $this->currentMethod], $this->params);
    }

    /**
     * Obtém a URL limpa
     */
    private function getUrl()
    {
        $url = '';
        
        // Primeiro tenta pegar da query string (funciona com .htaccess)
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return $url;
        }
        
        // Alternativa para servidor PHP interno
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            // Remove query string se existir
            $requestUri = strtok($requestUri, '?');
            // Remove a barra inicial
            $url = ltrim($requestUri, '/');
            // Remove barras finais
            $url = rtrim($url, '/');
            return $url;
        }
        
        return '';
    }

    /**
     * Exibe página 404
     */
    private function show404()
    {
        http_response_code(404);
        echo '<h1>404 - Página não encontrada</h1>';
        echo '<p>A página solicitada não foi encontrada.</p>';
        echo '<a href="' . BASE_URL . '">Voltar ao início</a>';
    }

    /**
     * Método estático para redirecionamento
     */
    public static function redirect($url)
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    /**
     * Método estático para gerar URLs
     */
    public static function url($path = '')
    {
        return BASE_URL . $path;
    }
}
?>