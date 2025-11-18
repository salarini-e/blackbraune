<?php
/**
 * HomeController - Controller da página inicial
 */

require_once CORE_PATH . '/Controller.php';
require_once MODELS_PATH . '/ProgramacaoModel.php';
require_once MODELS_PATH . '/LojaModel.php';
require_once MODELS_PATH . '/ContactoModel.php';

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
        
        // Buscar lojas ativas
        $lojaModel = new LojaModel();
        $lojas = $lojaModel->getActive();
        
        // Buscar dados de contato
        $contactoModel = new ContactoModel();
        $contatos = $contactoModel->getContatos();
        $redesSociais = $contactoModel->getRedesSociais();
        
        $data = [
            'title' => 'Black Braune - Nova Friburgo',
            'description' => 'Movimento de revitalização comercial em Nova Friburgo',
            'page' => 'home',
            'programacao' => $programacaoCompleta,
            'tipos' => $tipos,
            'lojas' => $lojas,
            'contatos' => $contatos,
            'redesSociais' => $redesSociais
        ];

        $this->viewWithLayout('home/index', $data, 'main');
    }
}