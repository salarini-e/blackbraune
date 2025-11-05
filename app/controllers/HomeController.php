<?php
/**
 * HomeController - Controller da página inicial
 */

require_once CORE_PATH . '/Controller.php';
require_once MODELS_PATH . '/ProgramacaoModel.php';

class HomeController extends Controller
{
    /**
     * Página inicial (index.html convertido)
     */
    public function index()
    {
        // Buscar dados da programação
        $programacaoModel = new ProgramacaoModel();
        $programacaoCompleta = $programacaoModel->getProgramacaoCompleta();
        $tipos = $programacaoModel->getTiposAtividade();
        
        $data = [
            'title' => 'Black Braune - Nova Friburgo',
            'description' => 'Movimento de revitalização comercial em Nova Friburgo',
            'page' => 'home',
            'programacao' => $programacaoCompleta,
            'tipos' => $tipos
        ];

        $this->viewWithLayout('home/index', $data, 'main');
    }
}
?>