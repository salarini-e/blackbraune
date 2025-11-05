<?php

require_once CORE_PATH . '/Controller.php';
require_once 'app/controllers/AuthController.php';
require_once 'app/models/NewsletterModel.php';

class NewsletterController extends Controller {
    private $newsletterModel;

    public function __construct() {
        $this->newsletterModel = new NewsletterModel();
    }

    public function index() {
        AuthController::requireAuth();
        
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        
        if ($search) {
            $cadastros = $this->newsletterModel->search($search);
        } elseif ($status === 'ativos') {
            $cadastros = $this->newsletterModel->getAtivos();
        } elseif ($status === 'newsletter') {
            $cadastros = $this->newsletterModel->getNewsletterAtivos();
        } else {
            $cadastros = $this->newsletterModel->getAll();
        }
        
        $estatisticas = $this->newsletterModel->getEstatisticas();
        
        $this->viewWithLayout('newsletter/index', [
            'cadastros' => $cadastros,
            'estatisticas' => $estatisticas,
            'search' => $search,
            'status' => $status
        ], 'dashboard');
    }

    public function create() {
        AuthController::requireAuth();
        
        $interessesOptions = $this->newsletterModel->getInteressesOptions();
        
        $this->viewWithLayout('newsletter/create', [
            'interessesOptions' => $interessesOptions
        ], 'dashboard');
    }

    public function store() {
        try {
            // Se a requisição vem do formulário público (AJAX)
            if (isset($_POST['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
                header('Content-Type: application/json');
                
                $data = [
                    'nome' => $_POST['nome'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'telefone' => $_POST['telefone'] ?? '',
                    'cidade' => $_POST['cidade'] ?? 'Nova Friburgo',
                    'interesses' => $_POST['interesses'] ?? [],
                    'newsletter' => isset($_POST['newsletter']),
                    'termos' => isset($_POST['termos'])
                ];

                // Validações
                if (empty($data['nome'])) {
                    throw new Exception('Nome é obrigatório');
                }
                if (empty($data['email'])) {
                    throw new Exception('E-mail é obrigatório');
                }
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('E-mail inválido');
                }
                if (!$data['termos']) {
                    throw new Exception('É necessário aceitar os termos de uso');
                }

                $this->newsletterModel->create($data);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Cadastro realizado com sucesso! Obrigado por se juntar a nós.'
                ]);
                return;
            }
            
            // Requisição do painel admin
            AuthController::requireAuth();
            
            $data = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'telefone' => $_POST['telefone'] ?? '',
                'cidade' => $_POST['cidade'] ?? 'Nova Friburgo',
                'interesses' => $_POST['interesses'] ?? [],
                'newsletter' => isset($_POST['newsletter_ativo']),
                'termos' => true // Admin sempre pode cadastrar
            ];

            $this->newsletterModel->create($data);
            
            $_SESSION['success'] = 'Cadastro criado com sucesso!';
            header('Location: /newsletter');
            
        } catch (Exception $e) {
            if (isset($_POST['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                return;
            }
            
            $_SESSION['error'] = $e->getMessage();
            header('Location: /newsletter/create');
        }
    }

    public function edit($id) {
        AuthController::requireAuth();
        
        $cadastro = $this->newsletterModel->getById($id);
        if (!$cadastro) {
            $_SESSION['error'] = 'Cadastro não encontrado!';
            header('Location: /newsletter');
            return;
        }
        
        $interessesOptions = $this->newsletterModel->getInteressesOptions();
        
        // Decodifica interesses JSON
        $interessesSelecionados = [];
        if ($cadastro['interesses']) {
            $interessesJson = json_decode($cadastro['interesses'], true);
            if (is_array($interessesJson)) {
                $interessesSelecionados = $interessesJson;
            }
        }
        
        $this->viewWithLayout('newsletter/edit', [
            'cadastro' => $cadastro,
            'interessesOptions' => $interessesOptions,
            'interessesSelecionados' => $interessesSelecionados
        ], 'dashboard');
    }

    public function update($id) {
        AuthController::requireAuth();
        
        try {
            $data = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'telefone' => $_POST['telefone'] ?? '',
                'cidade' => $_POST['cidade'] ?? 'Nova Friburgo',
                'interesses' => $_POST['interesses'] ?? [],
                'newsletter_ativo' => isset($_POST['newsletter_ativo']) ? 1 : 0,
                'status' => $_POST['status'] ?? 'ativo'
            ];

            $this->newsletterModel->update($id, $data);
            
            $_SESSION['success'] = 'Cadastro atualizado com sucesso!';
            header('Location: /newsletter');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: /newsletter/edit/{$id}");
        }
    }

    public function delete($id) {
        AuthController::requireAuth();
        
        try {
            $this->newsletterModel->delete($id);
            $_SESSION['success'] = 'Cadastro excluído com sucesso!';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: /newsletter');
    }

    public function toggleStatus($id) {
        AuthController::requireAuth();
        
        try {
            $this->newsletterModel->toggleStatus($id);
            $_SESSION['success'] = 'Status alterado com sucesso!';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: /newsletter');
    }

    public function toggleNewsletter($id) {
        AuthController::requireAuth();
        
        try {
            $this->newsletterModel->toggleNewsletter($id);
            $_SESSION['success'] = 'Preferência de newsletter alterada com sucesso!';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: /newsletter');
    }

    public function export() {
        AuthController::requireAuth();
        
        $cadastros = $this->newsletterModel->getNewsletterAtivos();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="newsletter_cadastros_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Cabeçalhos
        fputcsv($output, ['Nome', 'Email', 'Telefone', 'Cidade', 'Data Cadastro'], ';');
        
        // Dados
        foreach ($cadastros as $cadastro) {
            fputcsv($output, [
                $cadastro['nome'],
                $cadastro['email'],
                $cadastro['telefone'],
                $cadastro['cidade'],
                date('d/m/Y H:i', strtotime($cadastro['data_cadastro']))
            ], ';');
        }
        
        fclose($output);
    }
}