<?php
/**
 * Teste do sistema de banco de dados
 */

// Define as constantes necessárias
define('ROOT_PATH', dirname(__FILE__));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', APP_PATH . '/core');

// Carrega as classes necessárias
require_once CORE_PATH . '/Config.php';
require_once CORE_PATH . '/Database.php';

try {
    echo "=== TESTE DO SISTEMA DE BANCO DE DADOS ===\n\n";
    
    // Carrega configurações
    echo "1. Carregando configurações...\n";
    Config::load();
    $dbConfig = Config::getDatabase();
    echo "   Driver: " . $dbConfig['driver'] . "\n";
    if ($dbConfig['driver'] === 'sqlite') {
        echo "   Arquivo: " . $dbConfig['file'] . "\n";
    } else {
        echo "   Host: " . $dbConfig['host'] . "\n";
        echo "   Database: " . $dbConfig['database'] . "\n";
    }
    echo "   ✓ Configurações carregadas\n\n";
    
    // Testa conexão
    echo "2. Testando conexão com banco...\n";
    $connection = Database::getConnection();
    echo "   ✓ Conexão estabelecida\n\n";
    
    // Cria tabelas
    echo "3. Criando/verificando tabelas...\n";
    Database::createTables();
    echo "   ✓ Tabelas criadas/verificadas\n\n";
    
    // Testa inserção
    echo "4. Testando inserção de dados...\n";
    $testData = [
        'nome' => 'Teste Empresa',
        'nome_fantasia' => 'Teste Empresa Ltda',
        'tipo' => 'Parceiro Técnico',
        'categoria' => 'Teste',
        'email' => 'teste@empresa.com',
        'telefone' => '(22) 1234-5678',
        'website' => 'https://teste.com',
        'responsavel_nome' => 'João Teste',
        'responsavel_email' => 'joao@teste.com',
        'responsavel_telefone' => '(22) 9999-0000',
        'endereco' => 'Rua de Teste, 123',
        'contribuicao' => 'Empresa de teste para validação',
        'status' => 'ativo',
        'ativo' => 1
    ];
    
    $dbConfig = Config::getDatabase();
    if ($dbConfig['driver'] === 'mysql') {
        $sql = "INSERT INTO parceiros (
            nome, nome_fantasia, tipo, categoria, email, telefone, website,
            responsavel_nome, responsavel_email, responsavel_telefone,
            endereco, contribuicao, status, ativo,
            data_cadastro, data_atualizacao
        ) VALUES (
            :nome, :nome_fantasia, :tipo, :categoria, :email, :telefone, :website,
            :responsavel_nome, :responsavel_email, :responsavel_telefone,
            :endereco, :contribuicao, :status, :ativo,
            NOW(), NOW()
        )";
    } else {
        $sql = "INSERT INTO parceiros (
            nome, nome_fantasia, tipo, categoria, email, telefone, website,
            responsavel_nome, responsavel_email, responsavel_telefone,
            endereco, contribuicao, status, ativo,
            data_cadastro, data_atualizacao
        ) VALUES (
            :nome, :nome_fantasia, :tipo, :categoria, :email, :telefone, :website,
            :responsavel_nome, :responsavel_email, :responsavel_telefone,
            :endereco, :contribuicao, :status, :ativo,
            datetime('now'), datetime('now')
        )";
    }
    
    $id = Database::insert($sql, $testData);
    echo "   ✓ Dados inseridos com ID: $id\n\n";
    
    // Testa busca
    echo "5. Testando busca de dados...\n";
    $parceiro = Database::fetch("SELECT * FROM parceiros WHERE id = :id", ['id' => $id]);
    if ($parceiro) {
        echo "   ✓ Dados encontrados:\n";
        echo "     Nome: " . $parceiro['nome'] . "\n";
        echo "     Email: " . $parceiro['email'] . "\n";
        echo "     Status: " . $parceiro['status'] . "\n";
    } else {
        echo "   ✗ Dados não encontrados\n";
    }
    echo "\n";
    
    // Testa contagem
    echo "6. Testando contagem...\n";
    $count = Database::fetch("SELECT COUNT(*) as total FROM parceiros");
    echo "   ✓ Total de parceiros: " . $count['total'] . "\n\n";
    
    // Remove o registro de teste
    echo "7. Removendo dados de teste...\n";
    Database::execute("DELETE FROM parceiros WHERE id = :id", ['id' => $id]);
    echo "   ✓ Dados de teste removidos\n\n";
    
    echo "=== TODOS OS TESTES PASSARAM! ===\n";
    echo "O sistema de banco de dados está funcionando corretamente.\n";
    
} catch (Exception $e) {
    echo "✗ ERRO: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>