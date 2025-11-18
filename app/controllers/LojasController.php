<?php
/**
 * LojasController - Controller simplificado para gerenciar lojas participantes
 */

require_once CORE_PATH . '/Controller.php';
require_once CONTROLLERS_PATH . '/AuthController.php';
require_once MODELS_PATH . '/LojaModel.php';

class LojasController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new LojaModel();
    }

    /**
     * Verificar autenticação
     */
    private function requireAuth()
    {
        AuthController::requireAuth();
    }

    /**
     * Lista todas as lojas (dashboard)
     */
    public function index()
    {
        $this->requireAuth();

        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        if (!empty($search)) {
            $lojas = $this->model->search($search);
        } else {
            $lojas = $this->model->getAll();
            
            // Filtrar por status se especificado
            if ($status === 'ativas') {
                $lojas = array_filter($lojas, function($loja) {
                    return $loja['ativo'] == 1;
                });
            } elseif ($status === 'inativas') {
                $lojas = array_filter($lojas, function($loja) {
                    return $loja['ativo'] == 0;
                });
            }
        }

        $stats = $this->model->getEstatisticas();

        $this->viewWithLayout('lojas/index', [
            'title' => 'Lojas - Dashboard',
            'lojas' => $lojas,
            'search' => $search,
            'status' => $status,
            'stats' => $stats,
            'page' => 'lojas'
        ], 'dashboard');
    }

    /**
     * Exibe formulário de criação
     */
    public function create()
    {
        $this->requireAuth();
        
        $data = [
            'title' => 'Nova Loja - Black Braune',
            'page' => 'lojas'
        ];
        
        $this->viewWithLayout('lojas/create', $data, 'dashboard');
    }

    /**
     * Processa criação de loja
     */
    public function store()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Router::redirect('dashboard/lojas/create');
            return;
        }
        
        $errors = [];
        $data = [];
        
        // Validação básica
        if (empty($_POST['nome'])) {
            $errors['nome'] = 'Nome da loja é obrigatório';
        } else {
            $data['nome'] = trim($_POST['nome']);
        }
        
        // Website (opcional)
        if (!empty($_POST['website'])) {
            $website = trim($_POST['website']);
            if (!filter_var($website, FILTER_VALIDATE_URL)) {
                $errors['website'] = 'Website deve ser uma URL válida';
            } else {
                $data['website'] = $website;
            }
        }
        
        // Status
        $data['ativo'] = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 1;
        
        // Upload do logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $logoPath = $this->uploadLogo($_FILES['logo']);
            if ($logoPath) {
                $data['logo'] = $logoPath;
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            $_SESSION['flash_message'] = 'Corrija os erros no formulário';
            $_SESSION['flash_type'] = 'error';
            Router::redirect('dashboard/lojas/create');
            return;
        }
        
        try {
            if ($this->model->create($data)) {
                $_SESSION['flash_message'] = 'Loja cadastrada com sucesso!';
                $_SESSION['flash_type'] = 'success';
                Router::redirect('dashboard/lojas');
            } else {
                throw new Exception('Erro ao salvar no banco de dados');
            }
        } catch (Exception $e) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['flash_message'] = 'Erro ao cadastrar loja: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
            Router::redirect('dashboard/lojas/create');
        }
    }

    /**
     * Exibe formulário de edição
     */
    public function edit($id = null)
    {
        $this->requireAuth();
        
        if (!$id) {
            Router::redirect('dashboard/lojas');
            return;
        }
        
        $loja = $this->model->getById($id);
        if (!$loja) {
            $_SESSION['flash_message'] = 'Loja não encontrada';
            $_SESSION['flash_type'] = 'error';
            Router::redirect('dashboard/lojas');
            return;
        }
        
        $data = [
            'title' => 'Editar Loja - Black Braune',
            'page' => 'lojas',
            'loja' => $loja
        ];
        
        $this->viewWithLayout('lojas/edit', $data, 'dashboard');
    }

    /**
     * Processa atualização de loja
     */
    public function update($id = null)
    {
        $this->requireAuth();
        
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            Router::redirect('dashboard/lojas');
            return;
        }
        
        $loja = $this->model->getById($id);
        if (!$loja) {
            $_SESSION['flash_message'] = 'Loja não encontrada';
            $_SESSION['flash_type'] = 'error';
            Router::redirect('dashboard/lojas');
            return;
        }
        
        $errors = [];
        $data = [];
        
        // Validação básica
        if (empty($_POST['nome'])) {
            $errors['nome'] = 'Nome da loja é obrigatório';
        } else {
            $data['nome'] = trim($_POST['nome']);
        }
        
        // Website (opcional)
        if (!empty($_POST['website'])) {
            $website = trim($_POST['website']);
            if (!filter_var($website, FILTER_VALIDATE_URL)) {
                $errors['website'] = 'Website deve ser uma URL válida';
            } else {
                $data['website'] = $website;
            }
        } else {
            $data['website'] = null;
        }
        
        // Status
        $data['ativo'] = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 1;
        
        // Gerenciamento do logo
        $data['logo'] = $loja['logo']; // Mantém o logo atual por padrão
        
        // Se marcou para remover logo
        if (isset($_POST['remove_logo']) && $_POST['remove_logo'] == '1') {
            if (!empty($loja['logo']) && file_exists(ASSETS_PATH . '/img/uploads/' . $loja['logo'])) {
                unlink(ASSETS_PATH . '/img/uploads/' . $loja['logo']);
            }
            $data['logo'] = null;
        }
        
        // Upload de novo logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            // Remove logo antigo se existir e não estiver marcado para remoção
            if (!empty($loja['logo']) && file_exists(ASSETS_PATH . '/img/uploads/' . $loja['logo'])) {
                unlink(ASSETS_PATH . '/img/uploads/' . $loja['logo']);
            }
            
            $logoPath = $this->uploadLogo($_FILES['logo']);
            if ($logoPath) {
                $data['logo'] = $logoPath;
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            $_SESSION['flash_message'] = 'Corrija os erros no formulário';
            $_SESSION['flash_type'] = 'error';
            Router::redirect('dashboard/lojas/edit/' . $id);
            return;
        }
        
        try {
            if ($this->model->update($id, $data)) {
                $_SESSION['flash_message'] = 'Loja atualizada com sucesso!';
                $_SESSION['flash_type'] = 'success';
                Router::redirect('dashboard/lojas');
            } else {
                throw new Exception('Erro ao atualizar no banco de dados');
            }
        } catch (Exception $e) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['flash_message'] = 'Erro ao atualizar loja: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
            Router::redirect('dashboard/lojas/edit/' . $id);
        }
    }

    /**
     * Exclui uma loja
     */
    public function delete($id = null)
    {
        $this->requireAuth();
        
        if (!$id) {
            Router::redirect('dashboard/lojas');
            return;
        }
        
        $loja = $this->model->getById($id);
        if (!$loja) {
            $_SESSION['flash_message'] = 'Loja não encontrada';
            $_SESSION['flash_type'] = 'error';
            Router::redirect('dashboard/lojas');
            return;
        }
        
        try {
            // Remove logo se existir
            if (!empty($loja['logo']) && file_exists(ASSETS_PATH . '/img/uploads/' . $loja['logo'])) {
                unlink(ASSETS_PATH . '/img/uploads/' . $loja['logo']);
            }
            
            if ($this->model->delete($id)) {
                $_SESSION['flash_message'] = 'Loja excluída com sucesso!';
                $_SESSION['flash_type'] = 'success';
            } else {
                throw new Exception('Erro ao excluir do banco de dados');
            }
        } catch (Exception $e) {
            $_SESSION['flash_message'] = 'Erro ao excluir loja: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
        
        Router::redirect('dashboard/lojas');
    }

    /**
     * Alterna status ativo/inativo da loja
     */
    public function toggleStatus($id = null)
    {
        $this->requireAuth();
        
        if (!$id) {
            Router::redirect('dashboard/lojas');
            return;
        }
        
        $loja = $this->model->getById($id);
        if (!$loja) {
            $_SESSION['flash_message'] = 'Loja não encontrada';
            $_SESSION['flash_type'] = 'error';
            Router::redirect('dashboard/lojas');
            return;
        }
        
        try {
            if ($this->model->toggleStatus($id)) {
                $status = $loja['ativo'] ? 'desativada' : 'ativada';
                $_SESSION['flash_message'] = "Loja {$status} com sucesso!";
                $_SESSION['flash_type'] = 'success';
            } else {
                throw new Exception('Erro ao alterar status no banco de dados');
            }
        } catch (Exception $e) {
            $_SESSION['flash_message'] = 'Erro ao alterar status: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
        
        Router::redirect('dashboard/lojas');
    }

    /**
     * Upload de logo da loja
     */
    private function uploadLogo($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        $uploadDir = ASSETS_PATH . '/img/uploads/';
        
        // Verificar tipo de arquivo
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['flash_message'] = 'Tipo de arquivo não permitido. Use PNG, JPG ou GIF.';
            $_SESSION['flash_type'] = 'error';
            return false;
        }
        
        // Verificar tamanho
        if ($file['size'] > $maxSize) {
            $_SESSION['flash_message'] = 'Arquivo muito grande. Máximo 5MB.';
            $_SESSION['flash_type'] = 'error';
            return false;
        }
        
        // Gerar nome único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('loja_', true) . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Mover arquivo
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        }
        
        $_SESSION['flash_message'] = 'Erro ao fazer upload do logo.';
        $_SESSION['flash_type'] = 'error';
        return false;
    }
}
?>