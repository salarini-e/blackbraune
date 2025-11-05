<?php
/**
 * Controller Base - Classe base para todos os controllers
 */

class Controller
{
    /**
     * Carrega uma view
     */
    protected function view($view, $data = [])
    {
        // Extrai os dados para variáveis
        if (!empty($data)) {
            extract($data);
        }

        // Verifica se a view existe
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View '{$view}' não encontrada.");
        }
    }

    /**
     * Carrega uma view com layout
     */
    protected function viewWithLayout($view, $data = [], $layout = 'main')
    {
        // Extrai os dados para variáveis
        if (!empty($data)) {
            extract($data);
        }

        // Captura o conteúdo da view
        ob_start();
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View '{$view}' não encontrada.");
        }
        $content = ob_get_clean();

        // Carrega o layout
        $layoutFile = VIEWS_PATH . '/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            die("Layout '{$layout}' não encontrado.");
        }
    }

    /**
     * Carrega um model
     */
    protected function model($model)
    {
        $modelFile = MODELS_PATH . '/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            die("Model '{$model}' não encontrado.");
        }
    }

    /**
     * Retorna dados em JSON
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Verifica se a requisição é POST
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Verifica se a requisição é GET
     */
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Obtém dados do POST
     */
    protected function getPost($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    /**
     * Obtém dados do GET
     */
    protected function getGet($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    /**
     * Define uma mensagem flash na sessão
     */
    protected function setFlash($type, $message)
    {
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Obtém e remove uma mensagem flash da sessão
     */
    protected function getFlash($type)
    {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    /**
     * Redireciona para uma URL
     */
    protected function redirect($url)
    {
        Router::redirect($url);
    }

    /**
     * Gera uma URL
     */
    protected function url($path = '')
    {
        return Router::url($path);
    }
}
?>