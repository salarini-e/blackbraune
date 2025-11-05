<?php
/**
 * ParceirosController - Controller para gestão de parceiros
 */

require_once CORE_PATH . '/Controller.php';
require_once MODELS_PATH . '/ParceiroModel.php';
require_once CONTROLLERS_PATH . '/AuthController.php';

class ParceirosController extends Controller
{
    private $parceiroModel;
    
    public function __construct()
    {
        // Verifica se usuário está autenticado para páginas do dashboard
        if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) {
            AuthController::requireAuth();
        }
        
        $this->parceiroModel = new ParceiroModel();
    }
    /**
     * Lista de parceiros (dashboard_parceiros.html convertido)
     */
    public function index()
    {
        try {
            // Buscar filtros da request
            $filters = [];
            if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
                $filters['tipo'] = $_GET['tipo'];
            }
            if (isset($_GET['status']) && !empty($_GET['status'])) {
                $filters['status'] = $_GET['status'];
            }
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $filters['search'] = $_GET['search'];
            }

            // Buscar parceiros usando o model
            $parceiros = $this->parceiroModel->getAll($filters);
            $totalParceiros = $this->parceiroModel->count();
            $parceirosAtivos = $this->parceiroModel->count(['status' => 'ativo']);
            $parceirosPendentes = $this->parceiroModel->count(['status' => 'pendente']);
            $patrocinadores = $this->parceiroModel->count(['tipo' => 'Patrocinador Oficial']);

            $data = [
                'title' => 'Gestão de Parceiros - Black Braune',
                'description' => 'Gerencie parceiros e patrocinadores do movimento',
                'page' => 'parceiros',
                'parceiros' => $parceiros,
                'stats' => [
                    'total_parceiros' => $totalParceiros,
                    'parceiros_ativos' => $parceirosAtivos,
                    'aguardando_aprovacao' => $parceirosPendentes,
                    'patrocinadores_oficiais' => $patrocinadores
                ]
            ];

            $this->viewWithLayout('parceiros/index', $data, 'dashboard');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao carregar parceiros: ' . $e->getMessage());
            $this->viewWithLayout('parceiros/index', [
                'title' => 'Gestão de Parceiros - Black Braune',
                'page' => 'parceiros',
                'parceiros' => [],
                'stats' => [
                    'total_parceiros' => 0,
                    'parceiros_ativos' => 0,
                    'aguardando_aprovacao' => 0,
                    'patrocinadores_oficiais' => 0
                ]
            ], 'dashboard');
        }
    }

    /**
     * Formulário de cadastro de parceiro (dashboard_parceiros_cadastro.html convertido)
     */
    public function cadastro()
    {
        $data = [
            'title' => 'Cadastro de Parceiro - Black Braune',
            'description' => 'Adicione um novo parceiro ao movimento',
            'page' => 'parceiros-cadastro'
        ];

        $this->viewWithLayout('parceiros/cadastro', $data, 'dashboard');
    }

    /**
     * Processa o cadastro de um novo parceiro
     */
    public function criar()
    {
        if (!$this->isPost()) {
            $this->redirect('dashboard/parceiros/cadastro');
            return;
        }

        try {
            $dados = $this->getPost();
            
            // Processar upload de logo se enviado
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $logoFileName = $this->parceiroModel->saveLogo($_FILES['logo'], 'temp');
                $dados['logo'] = $logoFileName;
            }

            // Criar parceiro usando o model
            $parceiro = $this->parceiroModel->create($dados);

            $this->setFlash('success', 'Parceiro cadastrado com sucesso!');
            $this->redirect('dashboard/parceiros');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao cadastrar parceiro: ' . $e->getMessage());
            $this->redirect('dashboard/parceiros/cadastro');
        }
    }

    /**
     * Formulário de edição de parceiro
     */
    public function editar($id = null)
    {
        if (!$id) {
            $this->redirect('dashboard/parceiros');
            return;
        }

        try {
            // Buscar parceiro pelo ID
            $parceiro = $this->parceiroModel->getById($id);
            
            if (!$parceiro) {
                $this->setFlash('error', 'Parceiro não encontrado.');
                $this->redirect('dashboard/parceiros');
                return;
            }

            $data = [
                'title' => 'Editar Parceiro - Black Braune',
                'description' => 'Atualize as informações do parceiro',
                'page' => 'parceiros-edicao',
                'parceiro' => $parceiro
            ];

            $this->viewWithLayout('parceiros/cadastro', $data, 'dashboard');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao carregar parceiro: ' . $e->getMessage());
            $this->redirect('dashboard/parceiros');
        }
    }

    /**
     * Processa a atualização de um parceiro
     */
    public function atualizar($id = null)
    {
        if (!$this->isPost() || !$id) {
            $this->redirect('dashboard/parceiros');
            return;
        }

        try {
            $dados = $this->getPost();
            
            // Processar upload de nova logo se enviado
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $logoFileName = $this->parceiroModel->saveLogo($_FILES['logo'], $id);
                $dados['logo'] = $logoFileName;
            }

            // Atualizar parceiro usando o model
            $parceiro = $this->parceiroModel->update($id, $dados);

            $this->setFlash('success', 'Parceiro atualizado com sucesso!');
            $this->redirect('dashboard/parceiros');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao atualizar parceiro: ' . $e->getMessage());
            $this->redirect('dashboard/parceiros/editar/' . $id);
        }
    }

    /**
     * Remove um parceiro
     */
    public function deletar($id = null)
    {
        if (!$id) {
            $this->redirect('dashboard/parceiros');
            return;
        }

        try {
            // Deletar parceiro usando o model
            $this->parceiroModel->delete($id);

            $this->setFlash('success', 'Parceiro removido com sucesso!');
            $this->redirect('dashboard/parceiros');
            
        } catch (Exception $e) {
            $this->setFlash('error', 'Erro ao remover parceiro: ' . $e->getMessage());
            $this->redirect('dashboard/parceiros');
        }
    }

    /**
     * Visualiza detalhes de um parceiro
     */
    public function visualizar($id = null)
    {
        if (!$id) {
            $this->json(['error' => 'ID não fornecido'], 400);
            return;
        }

        try {
            // Buscar parceiro pelo ID
            $parceiro = $this->parceiroModel->getById($id);
            
            if (!$parceiro) {
                $this->json(['error' => 'Parceiro não encontrado'], 404);
                return;
            }

            $this->json($parceiro);
            
        } catch (Exception $e) {
            $this->json(['error' => 'Erro ao buscar parceiro: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * API: Listar parceiros em JSON
     */
    public function api()
    {
        try {
            // Buscar filtros da request
            $filters = [];
            if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
                $filters['tipo'] = $_GET['tipo'];
            }
            if (isset($_GET['status']) && !empty($_GET['status'])) {
                $filters['status'] = $_GET['status'];
            }
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $filters['search'] = $_GET['search'];
            }

            // Buscar parceiros usando o model
            $parceiros = $this->parceiroModel->getAll($filters);
            
            $this->json([
                'success' => true,
                'data' => $parceiros,
                'total' => count($parceiros)
            ]);
            
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'error' => 'Erro ao buscar parceiros: ' . $e->getMessage()
            ], 500);
        }
    }
}
?>