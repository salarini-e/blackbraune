<?php

require_once CORE_PATH . '/Database.php';
require_once CORE_PATH . '/Config.php';

class ProgramacaoModel {
    
    public function __construct() {
        // Não precisa fazer nada, Database é estático
    }
    
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS programacoes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            data_evento DATE NOT NULL,
            horario_inicio TIME NOT NULL,
            horario_fim TIME NULL,
            titulo VARCHAR(255) NOT NULL,
            descricao TEXT NULL,
            tipo_atividade VARCHAR(50) NOT NULL,
            local VARCHAR(255) NULL,
            ativo BOOLEAN DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        
        try {
            Database::getConnection()->exec($sql);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function getAll() {
        $sql = "SELECT * FROM programacoes ORDER BY data_evento ASC, horario_inicio ASC";
        return Database::fetchAll($sql);
    }
    
    public function getByDate($data) {
        $sql = "SELECT * FROM programacoes WHERE data_evento = ? AND ativo = 1 ORDER BY horario_inicio ASC";
        return Database::fetchAll($sql, [$data]);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM programacoes WHERE id = ?";
        error_log("Debug ProgramacaoModel::getById - ID recebido: " . $id);
        error_log("Debug ProgramacaoModel::getById - Tipo do ID: " . gettype($id));
        error_log("Debug ProgramacaoModel::getById - SQL: " . $sql);
        
        $result = Database::fetch($sql, [$id]);
        error_log("Debug ProgramacaoModel::getById - Resultado: " . ($result ? json_encode($result) : 'NULL'));
        
        return $result;
    }
    
    public function create($data) {
        $sql = "INSERT INTO programacoes (
            data_evento, horario_inicio, horario_fim, titulo, descricao, 
            tipo_atividade, local, ativo, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $now = date('Y-m-d H:i:s');
        
        try {
            Database::execute($sql, [
                $data['data_evento'],
                $data['horario_inicio'],
                $data['horario_fim'] ?? null,
                $data['titulo'],
                $data['descricao'] ?? null,
                $data['tipo_atividade'],
                $data['local'] ?? null,
                $data['ativo'] ?? 1,
                $now,
                $now
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function update($id, $data) {
        $sql = "UPDATE programacoes SET 
            data_evento = ?, horario_inicio = ?, horario_fim = ?, titulo = ?, 
            descricao = ?, tipo_atividade = ?, local = ?, ativo = ?, updated_at = ?
        WHERE id = ?";
        
        try {
            Database::execute($sql, [
                $data['data_evento'],
                $data['horario_inicio'],
                $data['horario_fim'] ?? null,
                $data['titulo'],
                $data['descricao'] ?? null,
                $data['tipo_atividade'],
                $data['local'] ?? null,
                $data['ativo'] ?? 1,
                date('Y-m-d H:i:s'),
                $id
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM programacoes WHERE id = ?";
        try {
            Database::execute($sql, [$id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function deleteAll() {
        $sql = "DELETE FROM programacoes";
        try {
            Database::execute($sql);
            return true;
        } catch (Exception $e) {
            error_log("Erro ao limpar tabela programacoes: " . $e->getMessage());
            return false;
        }
    }
    
    public function toggleStatus($id) {
        $sql = "UPDATE programacoes SET ativo = CASE WHEN ativo = 1 THEN 0 ELSE 1 END, updated_at = ? WHERE id = ?";
        try {
            Database::execute($sql, [date('Y-m-d H:i:s'), $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function getTiposAtividade() {
        return [
            'dj' => 'DJ',
            'artista_solo' => 'Artista Solo',
            'artista_dupla' => 'Artista/Dupla',
            'magico' => 'Mágico',
            'danca' => 'Dança',
            'atividade_infantil' => 'Atividade Infantil',
            'teatro' => 'Teatro',
            'show' => 'Show',
            'workshop' => 'Workshop',
            'palestra' => 'Palestra',
            'outro' => 'Outro'
        ];
    }
    
    public function getEventDates() {
        $sql = "SELECT DISTINCT data_evento FROM programacoes WHERE ativo = 1 ORDER BY data_evento ASC";
        $result = Database::fetchAll($sql);
        return array_column($result, 'data_evento');
    }
    
    public function getProgramacaoCompleta() {
        $dates = $this->getEventDates();
        $programacao = [];
        
        foreach ($dates as $date) {
            $programacao[$date] = $this->getByDate($date);
        }
        
        return $programacao;
    }
    
    public function search($termo) {
        $sql = "SELECT * FROM programacoes 
                WHERE (titulo LIKE ? OR descricao LIKE ? OR tipo_atividade LIKE ?)
                ORDER BY data_evento ASC, horario_inicio ASC";
        $searchTerm = "%{$termo}%";
        return Database::fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    public function getCount() {
        $sql = "SELECT COUNT(*) as total FROM programacoes";
        $result = Database::fetch($sql);
        return $result['total'];
    }
    
    public function getCountByStatus() {
        $sql = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN ativo = 1 THEN 1 ELSE 0 END) as ativas,
            SUM(CASE WHEN ativo = 0 THEN 1 ELSE 0 END) as inativas
            FROM programacoes";
        return Database::fetch($sql);
    }
}