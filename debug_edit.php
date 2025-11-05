<?php
/**
 * Debug do sistema de edição de parceiros
 */

// Define as constantes necessárias
define('ROOT_PATH', dirname(__FILE__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('MODELS_PATH', APP_PATH . '/models');
define('CORE_PATH', APP_PATH . '/core');
define('CONFIG_PATH', ROOT_PATH . '/config');

// Carrega configurações
require_once CORE_PATH . '/Config.php';
Config::load();

// Define BASE_URL
define('BASE_URL', Config::get('url.base', 'http://localhost:8000/'));

// Carrega classes necessárias
require_once CORE_PATH . '/Database.php';
require_once MODELS_PATH . '/ParceiroModel.php';

echo "<h2>Debug do Sistema de Edição</h2>";

try {
    $parceiroModel = new ParceiroModel();
    
    // Lista todos os parceiros primeiro
    echo "<h3>1. Listando todos os parceiros:</h3>";
    $parceiros = $parceiroModel->getAll();
    
    if (empty($parceiros)) {
        echo "<p>Nenhum parceiro encontrado no banco.</p>";
        echo "<p>Vou inserir dados de exemplo...</p>";
        
        // Insere dados de exemplo
        $dadosExemplo = [
            'nome' => 'Empresa Exemplo',
            'nome_fantasia' => 'Exemplo Ltda',
            'tipo' => 'Parceiro Técnico',
            'categoria' => 'Tecnologia',
            'email' => 'contato@exemplo.com',
            'telefone' => '(22) 1234-5678',
            'website' => 'https://exemplo.com',
            'responsavel_nome' => 'João Silva',
            'responsavel_email' => 'joao@exemplo.com',
            'responsavel_telefone' => '(22) 9999-0000',
            'endereco' => 'Rua Exemplo, 123',
            'contribuicao' => 'Parceiro de tecnologia',
            'status' => 'ativo',
            'ativo' => 1
        ];
        
        $novoId = $parceiroModel->create($dadosExemplo);
        echo "<p>✓ Parceiro criado com ID: $novoId</p>";
        
        // Lista novamente
        $parceiros = $parceiroModel->getAll();
    }
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Tipo</th><th>Status</th></tr>";
    foreach ($parceiros as $parceiro) {
        echo "<tr>";
        echo "<td>" . $parceiro['id'] . "</td>";
        echo "<td>" . htmlspecialchars($parceiro['nome']) . "</td>";
        echo "<td>" . htmlspecialchars($parceiro['tipo']) . "</td>";
        echo "<td>" . htmlspecialchars($parceiro['status']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Agora testa buscar por ID específico
    if (!empty($parceiros)) {
        $primeiroId = $parceiros[0]['id'];
        echo "<h3>2. Testando busca por ID ($primeiroId):</h3>";
        
        $parceiro = $parceiroModel->getById($primeiroId);
        
        if ($parceiro) {
            echo "<p>✓ Parceiro encontrado!</p>";
            echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";
            echo htmlspecialchars(print_r($parceiro, true));
            echo "</pre>";
            
            echo "<h3>3. Testando condição de seleção:</h3>";
            $tipos = [
                'Parceiro Institucional',
                'Parceiro Público', 
                'Patrocinador Oficial',
                'Parceiro Técnico',
                'Apoiador'
            ];
            
            echo "<p><strong>Tipo do parceiro:</strong> '" . $parceiro['tipo'] . "'</p>";
            echo "<p><strong>Testes de comparação:</strong></p>";
            echo "<ul>";
            foreach ($tipos as $tipo) {
                $selected = ($parceiro['tipo'] === $tipo) ? 'SELECTED' : 'não selecionado';
                echo "<li>$tipo: <strong>$selected</strong></li>";
            }
            echo "</ul>";
            
        } else {
            echo "<p>✗ Parceiro não encontrado!</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>✗ Erro: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>