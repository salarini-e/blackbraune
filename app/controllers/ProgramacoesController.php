<?php

require_once __DIR__ . '/../models/ProgramacaoModel.php';

class ProgramacoesController extends Controller {
    private $programacaoModel;
    
    public function __construct() {
        $this->programacaoModel = new ProgramacaoModel();
        
        // Verificar se a tabela existe, se não, criar
        $this->programacaoModel->createTable();
    }
    
    public function index() {
        // Verificar autenticação
        AuthController::requireAuth();
        
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            $programacoes = $this->programacaoModel->search($search);
        } else {
            $programacoes = $this->programacaoModel->getAll();
        }
        
        $stats = $this->programacaoModel->getCountByStatus();
        $tipos = $this->programacaoModel->getTiposAtividade();
        
        $this->viewWithLayout('programacoes/index', [
            'title' => 'Programação - Dashboard',
            'programacoes' => $programacoes,
            'search' => $search,
            'stats' => $stats,
            'tipos' => $tipos,
            'page' => 'programacoes'
        ], 'dashboard');
    }
    
    public function create() {
        // Verificar autenticação
        AuthController::requireAuth();
        
        $tipos = $this->programacaoModel->getTiposAtividade();
        
        $this->viewWithLayout('programacoes/create', [
            'title' => 'Nova Programação - Dashboard',
            'tipos' => $tipos,
            'page' => 'programacoes'
        ], 'dashboard');
    }
    
    public function store() {
        // Verificar autenticação
        AuthController::requireAuth();
        
        try {
            // Validação dos dados
            $errors = $this->validateProgramacao($_POST);
            
            if (!empty($errors)) {
                $_SESSION['flash_message'] = 'Por favor, corrija os erros no formulário.';
                $_SESSION['flash_type'] = 'error';
                $_SESSION['form_data'] = $_POST;
                $_SESSION['form_errors'] = $errors;
                header('Location: ' . Router::url('programacoes/create'));
                exit;
            }
            
            // Preparar dados
            $data = [
                'data_evento' => $_POST['data_evento'],
                'horario_inicio' => $_POST['horario_inicio'],
                'horario_fim' => !empty($_POST['horario_fim']) ? $_POST['horario_fim'] : null,
                'titulo' => trim($_POST['titulo']),
                'descricao' => !empty($_POST['descricao']) ? trim($_POST['descricao']) : null,
                'tipo_atividade' => $_POST['tipo_atividade'],
                'local' => !empty($_POST['local']) ? trim($_POST['local']) : null,
                'ativo' => isset($_POST['ativo']) ? 1 : 0
            ];
            
            if ($this->programacaoModel->create($data)) {
                $_SESSION['flash_message'] = 'Programação cadastrada com sucesso!';
                $_SESSION['flash_type'] = 'success';
                header('Location: ' . Router::url('programacoes'));
            } else {
                throw new Exception('Erro ao cadastrar programação.');
            }
            
        } catch (Exception $e) {
            $_SESSION['flash_message'] = 'Erro: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . Router::url('programacoes/create'));
        }
        
        exit;
    }
    
    public function edit($id) {
        // Verificar autenticação
        AuthController::requireAuth();
        
        $programacao = $this->programacaoModel->getById($id);
        
        if (!$programacao) {
            $_SESSION['flash_message'] = 'Programação não encontrada.';
            $_SESSION['flash_type'] = 'error';
            header('Location: ' . Router::url('programacoes'));
            exit;
        }
        
        $tipos = $this->programacaoModel->getTiposAtividade();
        
        $this->viewWithLayout('programacoes/edit', [
            'title' => 'Editar Programação - Dashboard',
            'programacao' => $programacao,
            'tipos' => $tipos,
            'page' => 'programacoes'
        ], 'dashboard');
    }
    
    public function update($id) {
        // Verificar autenticação
        AuthController::requireAuth();
        
        try {
            $programacao = $this->programacaoModel->getById($id);
            
            if (!$programacao) {
                throw new Exception('Programação não encontrada.');
            }
            
            // Validação dos dados
            $errors = $this->validateProgramacao($_POST);
            
            if (!empty($errors)) {
                $_SESSION['flash_message'] = 'Por favor, corrija os erros no formulário.';
                $_SESSION['flash_type'] = 'error';
                $_SESSION['form_data'] = $_POST;
                $_SESSION['form_errors'] = $errors;
                header('Location: ' . Router::url('programacoes/edit/' . $id));
                exit;
            }
            
            // Preparar dados
            $data = [
                'data_evento' => $_POST['data_evento'],
                'horario_inicio' => $_POST['horario_inicio'],
                'horario_fim' => !empty($_POST['horario_fim']) ? $_POST['horario_fim'] : null,
                'titulo' => trim($_POST['titulo']),
                'descricao' => !empty($_POST['descricao']) ? trim($_POST['descricao']) : null,
                'tipo_atividade' => $_POST['tipo_atividade'],
                'local' => !empty($_POST['local']) ? trim($_POST['local']) : null,
                'ativo' => isset($_POST['ativo']) ? 1 : 0
            ];
            
            if ($this->programacaoModel->update($id, $data)) {
                $_SESSION['flash_message'] = 'Programação atualizada com sucesso!';
                $_SESSION['flash_type'] = 'success';
                header('Location: ' . Router::url('programacoes'));
            } else {
                throw new Exception('Erro ao atualizar programação.');
            }
            
        } catch (Exception $e) {
            $_SESSION['flash_message'] = 'Erro: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . Router::url('programacoes/edit/' . $id));
        }
        
        exit;
    }
    
    public function delete($id) {
        // Debug
        error_log("Debug ProgramacoesController::delete - ID recebido: " . $id);
        error_log("Debug ProgramacoesController::delete - Parâmetros: " . print_r($this->params, true));
        
        // Listar todos os IDs disponíveis para debug
        $todasProgramacoes = $this->programacaoModel->getAll();
        $idsDisponiveis = array_column($todasProgramacoes, 'id');
        error_log("Debug ProgramacoesController::delete - IDs disponíveis na base: " . implode(', ', $idsDisponiveis));
        
        // Verificar autenticação
        AuthController::requireAuth();
        
        try {
            $programacao = $this->programacaoModel->getById($id);
            error_log("Debug ProgramacoesController::delete - Programação encontrada: " . ($programacao ? 'SIM' : 'NÃO'));
            
            if (!$programacao) {
                error_log("Debug ProgramacoesController::delete - Programação não encontrada para ID: " . $id);
                throw new Exception('Programação não encontrada.');
            }
            
            $resultado = $this->programacaoModel->delete($id);
            error_log("Debug ProgramacoesController::delete - Resultado da exclusão: " . ($resultado ? 'SUCESSO' : 'FALHA'));
            
            if ($resultado) {
                $_SESSION['flash_message'] = 'Programação excluída com sucesso!';
                $_SESSION['flash_type'] = 'success';
                error_log("Debug ProgramacoesController::delete - Flash message definida: sucesso");
            } else {
                throw new Exception('Erro ao excluir programação.');
            }
            
        } catch (Exception $e) {
            error_log("Debug ProgramacoesController::delete - Erro: " . $e->getMessage());
            $_SESSION['flash_message'] = 'Erro: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
        
        error_log("Debug ProgramacoesController::delete - Redirecionando para: " . Router::url('programacoes'));
        header('Location: ' . Router::url('programacoes'));
        exit;
    }
    
    public function toggleStatus($id) {
        // Verificar autenticação
        AuthController::requireAuth();
        
        try {
            $programacao = $this->programacaoModel->getById($id);
            
            if (!$programacao) {
                throw new Exception('Programação não encontrada.');
            }
            
            if ($this->programacaoModel->toggleStatus($id)) {
                $status = $programacao['ativo'] ? 'desativada' : 'ativada';
                $_SESSION['flash_message'] = "Programação {$status} com sucesso!";
                $_SESSION['flash_type'] = 'success';
            } else {
                throw new Exception('Erro ao alterar status da programação.');
            }
            
        } catch (Exception $e) {
            $_SESSION['flash_message'] = 'Erro: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'error';
        }
        
        header('Location: ' . Router::url('programacoes'));
        exit;
    }
    
    // API endpoint para programação pública
    public function api() {
        header('Content-Type: application/json');
        
        try {
            $programacao = $this->programacaoModel->getProgramacaoCompleta();
            
            echo json_encode([
                'success' => true,
                'data' => $programacao
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao buscar programação: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    private function validateProgramacao($data) {
        $errors = [];
        
        // Data do evento
        if (empty($data['data_evento'])) {
            $errors['data_evento'] = 'Data do evento é obrigatória';
        } elseif (!strtotime($data['data_evento'])) {
            $errors['data_evento'] = 'Data do evento inválida';
        }
        
        // Horário de início
        if (empty($data['horario_inicio'])) {
            $errors['horario_inicio'] = 'Horário de início é obrigatório';
        }
        
        // Título
        if (empty($data['titulo'])) {
            $errors['titulo'] = 'Título é obrigatório';
        } elseif (strlen($data['titulo']) < 3) {
            $errors['titulo'] = 'Título deve ter pelo menos 3 caracteres';
        } elseif (strlen($data['titulo']) > 255) {
            $errors['titulo'] = 'Título deve ter no máximo 255 caracteres';
        }
        
        // Tipo de atividade
        if (empty($data['tipo_atividade'])) {
            $errors['tipo_atividade'] = 'Tipo de atividade é obrigatório';
        } else {
            $tipos = array_keys($this->programacaoModel->getTiposAtividade());
            if (!in_array($data['tipo_atividade'], $tipos)) {
                $errors['tipo_atividade'] = 'Tipo de atividade inválido';
            }
        }
        
        // Validar horários
        if (!empty($data['horario_inicio']) && !empty($data['horario_fim'])) {
            $inicio = strtotime($data['horario_inicio']);
            $fim = strtotime($data['horario_fim']);
            
            if ($inicio >= $fim) {
                $errors['horario_fim'] = 'Horário de fim deve ser maior que horário de início';
            }
        }
        
        return $errors;
    }
}