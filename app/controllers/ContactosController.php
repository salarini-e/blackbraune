<?php
require_once CORE_PATH . '/Controller.php';
require_once CONTROLLERS_PATH . '/AuthController.php';
require_once MODELS_PATH . '/ContactoModel.php';

class ContactosController extends Controller {
    private $contactoModel;
    
    public function __construct() {
        $this->contactoModel = new ContactoModel();
    }
    
    /**
     * Verificar autenticação
     */
    private function requireAuth()
    {
        AuthController::requireAuth();
    }
    
    public function index() {
        // Verificar se usuário está logado
        $this->requireAuth();
        
        $contactos = $this->contactoModel->getAll();
        $data = [
            'contactos' => $contactos,
            'page' => 'contactos',
            'title' => 'Gerenciar Contatos - Black Braune'
        ];
        $this->viewWithLayout('contactos/index', $data, 'dashboard');
    }
    
    public function create() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tipo' => trim($_POST['tipo']),
                'titulo' => trim($_POST['titulo']),
                'valor' => trim($_POST['valor']),
                'icone' => trim($_POST['icone']),
                'link' => trim($_POST['link']),
                'ordem' => intval($_POST['ordem'] ?? 0),
                'ativo' => isset($_POST['ativo']) ? 1 : 0
            ];
            
            // Validação básica
            if (empty($data['tipo']) || empty($data['titulo']) || empty($data['valor'])) {
                $_SESSION['error'] = 'Tipo, título e valor são obrigatórios.';
            } else {
                if ($this->contactoModel->create($data)) {
                    $_SESSION['success'] = 'Contato criado com sucesso!';
                    header('Location: ' . Router::url('contactos'));
                    exit();
                } else {
                    $_SESSION['error'] = 'Erro ao criar contato.';
                }
            }
        }
        
        $this->viewWithLayout('contactos/create', [
            'page' => 'contactos',
            'title' => 'Novo Contato - Black Braune'
        ], 'dashboard');
    }
    
    public function edit($id) {
        $this->requireAuth();
        
        $contacto = $this->contactoModel->getById($id);
        if (!$contacto) {
            $_SESSION['error'] = 'Contato não encontrado.';
            header('Location: ' . Router::url('contactos'));
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tipo' => trim($_POST['tipo']),
                'titulo' => trim($_POST['titulo']),
                'valor' => trim($_POST['valor']),
                'icone' => trim($_POST['icone']),
                'link' => trim($_POST['link']),
                'ordem' => intval($_POST['ordem'] ?? 0),
                'ativo' => isset($_POST['ativo']) ? 1 : 0
            ];
            
            // Validação básica
            if (empty($data['tipo']) || empty($data['titulo']) || empty($data['valor'])) {
                $_SESSION['error'] = 'Tipo, título e valor são obrigatórios.';
            } else {
                if ($this->contactoModel->update($id, $data)) {
                    $_SESSION['success'] = 'Contato atualizado com sucesso!';
                    header('Location: ' . Router::url('contactos'));
                    exit();
                } else {
                    $_SESSION['error'] = 'Erro ao atualizar contato.';
                }
            }
        }
        
        $this->viewWithLayout('contactos/edit', [
            'contacto' => $contacto,
            'page' => 'contactos',
            'title' => 'Editar Contato - Black Braune'
        ], 'dashboard');
    }
    
    public function delete($id) {
        $this->requireAuth();
        
        if ($this->contactoModel->delete($id)) {
            $_SESSION['success'] = 'Contato removido com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao remover contato.';
        }
        
        header('Location: ' . Router::url('contactos'));
        exit();
    }
    
    public function updateOrdem() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? null;
            $ordem = $input['ordem'] ?? null;
            
            if ($id && $ordem !== null) {
                if ($this->contactoModel->updateOrdem($id, $ordem)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar ordem']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            }
        }
        exit();
    }
}
?>