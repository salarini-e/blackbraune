<?php
/**
 * Teste simples de busca de parceiro
 */

// Define as constantes necessárias
define('ROOT_PATH', dirname(__FILE__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', APP_PATH . '/core');
define('MODELS_PATH', APP_PATH . '/models');

// Carrega configurações
require_once CORE_PATH . '/Config.php';
Config::load();

// Carrega classes necessárias
require_once CORE_PATH . '/Database.php';
require_once MODELS_PATH . '/ParceiroModel.php';

echo "<h2>Teste de Busca de Parceiro</h2>";

try {
    $parceiroModel = new ParceiroModel();
    
    // Lista primeiro para ver se há dados
    $parceiros = $parceiroModel->getAll();
    echo "<p><strong>Total de parceiros:</strong> " . count($parceiros) . "</p>";
    
    if (!empty($parceiros)) {
        $primeiroId = $parceiros[0]['id'];
        echo "<p><strong>Testando busca do ID:</strong> $primeiroId</p>";
        
        $parceiro = $parceiroModel->getById($primeiroId);
        
        if ($parceiro) {
            echo "<h3>Dados encontrados:</h3>";
            echo "<ul>";
            foreach ($parceiro as $campo => $valor) {
                echo "<li><strong>$campo:</strong> " . htmlspecialchars($valor ?? '') . "</li>";
            }
            echo "</ul>";
            
            echo "<h3>Teste de seleção do tipo:</h3>";
            $tipoAtual = $parceiro['tipo'];
            echo "<p><strong>Tipo atual:</strong> '$tipoAtual'</p>";
            
            $tipos = [
                'Parceiro Institucional',
                'Parceiro Público', 
                'Patrocinador Oficial',
                'Parceiro Técnico',
                'Apoiador'
            ];
            
            echo "<p><strong>Comparações:</strong></p>";
            echo "<ul>";
            foreach ($tipos as $tipo) {
                $isSelected = ($tipoAtual === $tipo);
                $status = $isSelected ? '✓ SELECTED' : '✗ não selecionado';
                echo "<li>'$tipo': $status</li>";
            }
            echo "</ul>";
            
        } else {
            echo "<p>❌ Parceiro não encontrado!</p>";
        }
    } else {
        echo "<p>Nenhum parceiro encontrado. Criando um de exemplo...</p>";
        
        $dadosExemplo = [
            'nome' => 'Empresa Teste',
            'nome_fantasia' => 'Teste Ltda',
            'tipo' => 'Parceiro Técnico',
            'categoria' => 'Tecnologia',
            'email' => 'teste@teste.com',
            'telefone' => '(22) 1234-5678',
            'website' => 'https://teste.com',
            'responsavel_nome' => 'João Teste',
            'responsavel_email' => 'joao@teste.com',
            'responsavel_telefone' => '(22) 9999-0000',
            'endereco' => 'Rua Teste, 123',
            'contribuicao' => 'Empresa de teste',
            'status' => 'ativo',
            'ativo' => 1
        ];
        
        $novoId = $parceiroModel->create($dadosExemplo);
        echo "<p>✓ Parceiro criado com ID: $novoId</p>";
        echo "<p>Agora você pode testar a edição acessando:</p>";
        echo "<p><a href='dashboard/parceiros/editar/$novoId'>dashboard/parceiros/editar/$novoId</a></p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>