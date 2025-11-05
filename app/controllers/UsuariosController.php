<?php
/**
 * UsuariosController - Controller para gestão de usuários
 */

require_once CORE_PATH . '/Controller.php';
require_once MODELS_PATH . '/UsuarioModel.php';
require_once CONTROLLERS_PATH . '/AuthController.php';

class UsuariosController extends Controller
{
    private $usuarioModel;
    
    public function __construct()
    {
        // Verifica se usuário está autenticado e é admin
        AuthController::requireAdmin();
        
        $this->usuarioModel = new UsuarioModel();
    }
    
    /**
     * Lista de usuários
     */
    public function index()
    {
        try {
            // Buscar filtros da request
            $filters = [];
            if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
                $filters['tipo'] = $_GET['tipo'];
            }
            if (isset($_GET['ativo']) && $_GET['ativo'] !== '') {
                $filters['ativo'] = (int)$_GET['ativo'];
            }
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $filters['search'] = $_GET['search'];
            }

            // Buscar usuários usando o model
            $usuarios = $this->usuarioModel->getAll($filters);
            $totalUsuarios = $this->usuarioModel->count();
            $usuariosAtivos = $this->usuarioModel->count(['ativo' => 1]);
            $admins = $this->usuarioModel->count(['tipo' => 'admin']);

            $data = [
                'title' => 'Gestão de Usuários - Black Braune',
                'description' => 'Gerencie usuários administradores do sistema',
                'page' => 'usuarios',
                'usuarios' => $usuarios,
                'stats' => [
                    'total_usuarios' => $totalUsuarios,
                    'usuarios_ativos' => $usuariosAtivos,
                    'admins' => $admins,
                    'inativos' => $totalUsuarios - $usuariosAtivos
                ]
            ];

            $this->viewWithLayout('usuarios/index', $data, 'dashboard');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao carregar usuários: ' . $e->getMessage());
            $this->viewWithLayout('usuarios/index', [
                'title' => 'Gestão de Usuários - Black Braune',
                'page' => 'usuarios',
                'usuarios' => [],
                'stats' => [
                    'total_usuarios' => 0,
                    'usuarios_ativos' => 0,
                    'admins' => 0,
                    'inativos' => 0
                ]
            ], 'dashboard');
        }
    }

    /**
     * Formulário de cadastro de usuário
     */
    public function cadastro()
    {
        $data = [
            'title' => 'Cadastro de Usuário - Black Braune',
            'description' => 'Adicione um novo usuário administrador',
            'page' => 'usuarios-cadastro'
        ];

        $this->viewWithLayout('usuarios/cadastro', $data, 'dashboard');
    }

    /**
     * Processa o cadastro de um novo usuário
     */
    public function criar()
    {
        if (!$this->isPost()) {
            $this->redirect('dashboard/usuarios/cadastro');
            return;
        }

        try {
            $dados = $this->getPost();
            
            // Criar usuário usando o model
            $usuario = $this->usuarioModel->create($dados);

            $this->setFlash('success', 'Usuário cadastrado com sucesso!');
            $this->redirect('dashboard/usuarios');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao cadastrar usuário: ' . $e->getMessage());
            $this->redirect('dashboard/usuarios/cadastro');
        }
    }

    /**
     * Formulário de edição de usuário
     */
    public function editar($id = null)
    {
        if (!$id) {
            $this->redirect('dashboard/usuarios');
            return;
        }

        try {
            // Buscar usuário pelo ID
            $usuario = $this->usuarioModel->getById($id);
            
            if (!$usuario) {
                $this->setFlash('error', 'Usuário não encontrado.');
                $this->redirect('dashboard/usuarios');
                return;
            }

            $data = [
                'title' => 'Editar Usuário - Black Braune',
                'description' => 'Atualize as informações do usuário',
                'page' => 'usuarios-edicao',
                'usuario' => $usuario
            ];

            $this->viewWithLayout('usuarios/cadastro', $data, 'dashboard');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao carregar usuário: ' . $e->getMessage());
            $this->redirect('dashboard/usuarios');
        }
    }

    /**
     * Processa a atualização de um usuário
     */
    public function atualizar($id = null)
    {
        if (!$this->isPost() || !$id) {
            $this->redirect('dashboard/usuarios');
            return;
        }

        try {
            $dados = $this->getPost();
            
            // Atualizar usuário usando o model
            $usuario = $this->usuarioModel->update($id, $dados);

            $this->setFlash('success', 'Usuário atualizado com sucesso!');
            $this->redirect('dashboard/usuarios');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
            $this->redirect('dashboard/usuarios/editar/' . $id);
        }
    }

    /**
     * Remove um usuário
     */
    public function deletar($id = null)
    {
        if (!$id) {
            $this->redirect('dashboard/usuarios');
            return;
        }

        try {
            // Deletar usuário usando o model
            $this->usuarioModel->delete($id);

            $this->setFlash('success', 'Usuário removido com sucesso!');
            $this->redirect('dashboard/usuarios');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao remover usuário: ' . $e->getMessage());
            $this->redirect('dashboard/usuarios');
        }
    }

    /**
     * Visualiza detalhes de um usuário
     */
    public function visualizar($id = null)
    {
        if (!$id) {
            $this->json(['error' => 'ID não fornecido'], 400);
            return;
        }

        try {
            // Buscar usuário pelo ID
            $usuario = $this->usuarioModel->getById($id);
            
            if (!$usuario) {
                $this->json(['error' => 'Usuário não encontrado'], 404);
                return;
            }

            $this->json($usuario);
            
        } catch (Exception $e) {
            $this->json(['error' => 'Erro ao buscar usuário: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * API: Listar usuários em JSON
     */
    public function api()
    {
        try {
            // Buscar filtros da request
            $filters = [];
            if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
                $filters['tipo'] = $_GET['tipo'];
            }
            if (isset($_GET['ativo']) && $_GET['ativo'] !== '') {
                $filters['ativo'] = (int)$_GET['ativo'];
            }
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $filters['search'] = $_GET['search'];
            }

            // Buscar usuários usando o model
            $usuarios = $this->usuarioModel->getAll($filters);
            
            $this->json([
                'success' => true,
                'data' => $usuarios,
                'total' => count($usuarios)
            ]);
            
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'error' => 'Erro ao buscar usuários: ' . $e->getMessage()
            ], 500);
        }
    }
}
?>