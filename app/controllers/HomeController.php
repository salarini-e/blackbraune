<?php
/**
 * HomeController - Controller da página inicial
 */

require_once CORE_PATH . '/Controller.php';

class HomeController extends Controller
{
    /**
     * Página inicial (index.html convertido)
     */
    public function index()
    {
        $data = [
            'title' => 'Black Braune - Nova Friburgo',
            'description' => 'Movimento de revitalização comercial em Nova Friburgo',
            'page' => 'home'
        ];

        $this->viewWithLayout('home/index', $data, 'main');
    }
}
?>