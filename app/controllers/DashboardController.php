<?php
/**
 * DashboardController - Controller do dashboard administrativo
 */

require_once CORE_PATH . '/Controller.php';
require_once CONTROLLERS_PATH . '/AuthController.php';

class DashboardController extends Controller
{
    public function __construct()
    {
        // Verifica se usuário está autenticado
        AuthController::requireAuth();
    }
    
    /**
     * Dashboard principal
     */
    public function index()
    {
        // Dados mock dos últimos parceiros
        $ultimosParceiros = [
            [
                'nome' => 'Tech Store Friburgo',
                'tipo' => 'Loja',
                'data_cadastro' => '2025-11-03'
            ],
            [
                'nome' => 'Cafeteria do Centro',
                'tipo' => 'Restaurante',
                'data_cadastro' => '2025-11-03'
            ],
            [
                'nome' => 'Consultoria Empresarial',
                'tipo' => 'Serviço',
                'data_cadastro' => '2025-11-04'
            ]
        ];

        $data = [
            'title' => 'Dashboard - Black Braune',
            'description' => 'Dashboard administrativo do movimento Black Braune',
            'page' => 'dashboard',
            'ultimosParceiros' => $ultimosParceiros,
            // Dados mock para o dashboard
            'totalParceiros' => 8,
            'totalLojas' => 12,
            'totalPatrocinadores' => 4,
            'totalNewsletters' => 156,
            'stats' => [
                'total_parceiros' => 8,
                'parceiros_ativos' => 6,
                'aguardando_aprovacao' => 1,
                'patrocinadores_oficiais' => 4
            ]
        ];

        $this->viewWithLayout('dashboard/index', $data, 'dashboard');
    }
}
?>