<?php
/**
 * Debug do template de edição
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

// Inicia sessão
session_start();

// Autoload básico
spl_autoload_register(function($class) {
    $paths = [
        CONTROLLERS_PATH . '/' . $class . '.php',
        MODELS_PATH . '/' . $class . '.php',
        CORE_PATH . '/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Carrega configurações do banco
require_once CONFIG_PATH . '/config.php';

echo "<h2>Debug do Template de Edição</h2>";

try {
    require_once MODELS_PATH . '/ParceiroModel.php';
    $parceiroModel = new ParceiroModel();
    
    // Busca todos os parceiros
    $parceiros = $parceiroModel->getAll();
    
    if (!empty($parceiros)) {
        $primeiroId = $parceiros[0]['id'];
        echo "<p><strong>Simulando edição do parceiro ID:</strong> $primeiroId</p>";
        
        // Simula o que acontece no método editar do controller
        $parceiro = $parceiroModel->getById($primeiroId);
        
        if ($parceiro) {
            echo "<h3>Dados retornados pelo getById:</h3>";
            echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";
            echo htmlspecialchars(print_r($parceiro, true));
            echo "</pre>";
            
            echo "<h3>Teste das condições do select:</h3>";
            echo "<p><strong>Valor do campo 'tipo':</strong> '" . ($parceiro['tipo'] ?? 'NULL') . "'</p>";
            echo "<p><strong>isset(\$parceiro):</strong> " . (isset($parceiro) ? 'TRUE' : 'FALSE') . "</p>";
            
            $tipos = [
                'Parceiro Institucional',
                'Parceiro Público', 
                'Patrocinador Oficial',
                'Parceiro Técnico',
                'Apoiador'
            ];
            
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Tipo</th><th>Comparação</th><th>Resultado</th><th>HTML</th></tr>";
            
            foreach ($tipos as $tipo) {
                $comparison = "'{$parceiro['tipo']}' === '$tipo'";
                $result = ($parceiro['tipo'] === $tipo);
                $selected = $result ? 'selected' : '';
                $htmlOutput = "<?= isset(\$parceiro) && \$parceiro['tipo'] === '$tipo' ? 'selected' : '' ?>";
                
                echo "<tr>";
                echo "<td>$tipo</td>";
                echo "<td>$comparison</td>";
                echo "<td>" . ($result ? '✓ TRUE' : '✗ FALSE') . "</td>";
                echo "<td>$selected</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<h3>HTML gerado para o select:</h3>";
            echo "<code style='background: #f5f5f5; padding: 10px; display: block; white-space: pre;'>";
            echo htmlspecialchars('<select class="form-select" name="tipo" required>') . "\n";
            echo htmlspecialchars('    <option value="">Selecione o tipo</option>') . "\n";
            
            foreach ($tipos as $tipo) {
                $selected = ($parceiro['tipo'] === $tipo) ? ' selected' : '';
                echo htmlspecialchars("    <option value=\"$tipo\"$selected>$tipo</option>") . "\n";
            }
            echo htmlspecialchars('</select>');
            echo "</code>";
            
        } else {
            echo "<p>❌ Nenhum dados retornados pelo getById!</p>";
        }
    } else {
        echo "<p>❌ Nenhum parceiro encontrado no banco!</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>