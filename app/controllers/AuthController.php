<?php
/**
 * AuthController - Controller para autenticação
 */

require_once CORE_PATH . '/Controller.php';
require_once MODELS_PATH . '/UsuarioModel.php';

class AuthController extends Controller
{
    private $usuarioModel;
    
    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }
    
    /**
     * Página de login
     */
    public function login()
    {
        // Se já está logado, redireciona para dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
            return;
        }
        
        $data = [
            'title' => 'Login - Black Braune',
            'description' => 'Acesso ao painel administrativo',
            'page' => 'login'
        ];

        $this->view('auth/login', $data);
    }
    
    /**
     * Processa o login
     */
    public function authenticate()
    {
        // Se já está logado, redireciona para dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
            return;
        }
        
        if (!$this->isPost()) {
            $this->redirect('login');
            return;
        }

        try {
            $dados = $this->getPost();
            
            // Validações básicas
            if (empty($dados['email']) || empty($dados['senha'])) {
                throw new Exception('Email e senha são obrigatórios');
            }
            
            // Tenta autenticar
            $usuario = $this->usuarioModel->authenticate($dados['email'], $dados['senha']);
            
            if (!$usuario) {
                throw new Exception('Email ou senha incorretos');
            }
            
            if (!$usuario['ativo']) {
                throw new Exception('Usuário inativo. Entre em contato com o administrador.');
            }
            
            // Salva dados na sessão
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nome'] = $usuario['nome'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_tipo'] = $usuario['tipo'];
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();
            
            // Limpa tentativas de login
            unset($_SESSION['login_attempts']);
            
            $this->setFlash('success', 'Login realizado com sucesso!');
            
            // Redireciona para dashboard ou página solicitada
            $redirect = $_SESSION['intended_url'] ?? 'dashboard';
            unset($_SESSION['intended_url']);
            
            $this->redirect($redirect);
            
        } catch (Exception $e) {
            // Controle de tentativas de login
            if (!isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts'] = 0;
            }
            $_SESSION['login_attempts']++;
            
            // Bloqueia temporariamente após muitas tentativas
            if ($_SESSION['login_attempts'] >= 5) {
                $this->setFlash('error', 'Muitas tentativas de login. Tente novamente em alguns minutos.');
            } else {
                $this->setFlash('error', $e->getMessage());
            }
            
            $this->redirect('login');
        }
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        // Destrói dados da sessão relacionados ao usuário
        unset($_SESSION['user_id']);
        unset($_SESSION['user_nome']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_tipo']);
        unset($_SESSION['logged_in']);
        unset($_SESSION['login_time']);
        unset($_SESSION['intended_url']);
        
        // Regenera ID da sessão por segurança
        session_regenerate_id(true);
        
        $this->setFlash('success', 'Logout realizado com sucesso!');
        $this->redirect('login');
    }
    
    /**
     * Verifica se usuário está logado
     */
    private function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id']);
    }
    
    /**
     * Middleware para verificar autenticação
     */
    public static function requireAuth()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            // Salva a URL que o usuário tentou acessar
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '';
            
            // Define flash message
            $_SESSION['flash_message'] = 'Você precisa fazer login para acessar esta página';
            $_SESSION['flash_type'] = 'warning';
            
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        // Verifica se a sessão não expirou (opcional - 8 horas)
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 28800) {
            // Sessão expirada
            unset($_SESSION['logged_in']);
            unset($_SESSION['user_id']);
            
            $_SESSION['flash_message'] = 'Sua sessão expirou. Faça login novamente.';
            $_SESSION['flash_type'] = 'warning';
            
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }
    
    /**
     * Verifica se usuário é admin
     */
    public static function requireAdmin()
    {
        self::requireAuth();
        
        if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'admin') {
            $_SESSION['flash_message'] = 'Você não tem permissão para acessar esta página';
            $_SESSION['flash_type'] = 'error';
            
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
    }
    
    /**
     * Informações do usuário logado
     */
    public function profile()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
            return;
        }
        
        try {
            $usuario = $this->usuarioModel->getById($_SESSION['user_id']);
            
            if (!$usuario) {
                throw new Exception('Usuário não encontrado');
            }
            
            $data = [
                'title' => 'Meu Perfil - Black Braune',
                'description' => 'Informações do usuário logado',
                'page' => 'profile',
                'usuario' => $usuario
            ];

            $this->viewWithLayout('auth/profile', $data, 'dashboard');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao carregar perfil: ' . $e->getMessage());
            $this->redirect('dashboard');
        }
    }
    
    /**
     * API para verificar status de login
     */
    public function status()
    {
        $this->json([
            'logged_in' => $this->isLoggedIn(),
            'user' => $this->isLoggedIn() ? [
                'id' => $_SESSION['user_id'],
                'nome' => $_SESSION['user_nome'],
                'email' => $_SESSION['user_email'],
                'tipo' => $_SESSION['user_tipo']
            ] : null
        ]);
    }
}
?>