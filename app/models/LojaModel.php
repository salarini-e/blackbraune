<?php
/**
 * LojaModel - Model simplificado para gerenciar lojas participantes
 * Campos: nome, website, logo, ativo
 */

require_once CORE_PATH . '/Database.php';

class LojaModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->createTable();
    }

    /**
     * Cria a tabela de lojas se não existir
     */
    public function createTable()
    {
        // Primeiro, verifica se a tabela existe
        $checkTable = $this->db->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name='lojas'");
        $checkTable->execute();
        $tableExists = $checkTable->fetch();
        
        if (!$tableExists) {
            // Tabela não existe, cria com a estrutura correta
            $sql = "
            CREATE TABLE lojas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                website TEXT,
                logo TEXT,
                ativo INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
            ";
            
            try {
                $this->db->exec($sql);
                return true;
            } catch (Exception $e) {
                error_log("Erro ao criar tabela lojas: " . $e->getMessage());
                return false;
            }
        } else {
            // Tabela existe, verifica se precisa de migração
            $this->migrateTable();
            return true;
        }
    }

    /**
     * Migra a tabela existente para a nova estrutura
     */
    private function migrateTable()
    {
        try {
            // Verifica quais colunas existem
            $pragma = $this->db->prepare("PRAGMA table_info(lojas)");
            $pragma->execute();
            $columns = $pragma->fetchAll(PDO::FETCH_ASSOC);
            
            $existingColumns = [];
            foreach ($columns as $column) {
                $existingColumns[] = $column['name'];
            }
            
            // Adiciona colunas que não existem
            if (!in_array('created_at', $existingColumns)) {
                $this->db->exec("ALTER TABLE lojas ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP");
            }
            
            if (!in_array('updated_at', $existingColumns)) {
                $this->db->exec("ALTER TABLE lojas ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP");
            }
            
            // Se existe data_cadastro, copia para created_at
            if (in_array('data_cadastro', $existingColumns) && in_array('created_at', $existingColumns)) {
                $this->db->exec("UPDATE lojas SET created_at = data_cadastro WHERE created_at IS NULL");
            }
            
            // Se existe data_atualizacao, copia para updated_at
            if (in_array('data_atualizacao', $existingColumns) && in_array('updated_at', $existingColumns)) {
                $this->db->exec("UPDATE lojas SET updated_at = data_atualizacao WHERE updated_at IS NULL");
            }
            
        } catch (Exception $e) {
            error_log("Erro na migração da tabela lojas: " . $e->getMessage());
        }
    }

    /**
     * Busca todas as lojas
     */
    public function getAll()
    {
        // Usa COALESCE para compatibilidade com nomes antigos de colunas
        $stmt = $this->db->prepare("
            SELECT id, nome, website, logo, ativo, 
                   COALESCE(created_at, data_cadastro) as created_at 
            FROM lojas 
            ORDER BY COALESCE(created_at, data_cadastro) DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca loja por ID
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT id, nome, website, logo, ativo, 
                   COALESCE(created_at, data_cadastro) as created_at 
            FROM lojas 
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca lojas por nome
     */
    public function search($termo)
    {
        $stmt = $this->db->prepare("
            SELECT id, nome, website, logo, ativo, 
                   COALESCE(created_at, data_cadastro) as created_at 
            FROM lojas 
            WHERE nome LIKE ? 
            ORDER BY nome ASC
        ");
        $searchTerm = "%{$termo}%";
        $stmt->execute([$searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cria nova loja
     */
    public function create($data)
    {
        $sql = "INSERT INTO lojas (nome, website, logo, ativo) 
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nome'],
            $data['website'] ?? null,
            $data['logo'] ?? null,
            $data['ativo'] ?? 1
        ]);
    }

    /**
     * Atualiza loja
     */
    public function update($id, $data)
    {
        $sql = "UPDATE lojas SET 
                nome = ?, website = ?, logo = ?, ativo = ?, 
                updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nome'],
            $data['website'] ?? null,
            $data['logo'],
            $data['ativo'] ?? 1,
            $id
        ]);
    }

    /**
     * Exclui loja
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM lojas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Toggle status da loja
     */
    public function toggleStatus($id)
    {
        $stmt = $this->db->prepare("UPDATE lojas SET ativo = NOT ativo, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Conta estatísticas das lojas
     */
    public function getEstatisticas()
    {
        $stats = [];

        // Total de lojas
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM lojas");
        $stmt->execute();
        $stats['total'] = $stmt->fetchColumn();

        // Lojas ativas
        $stmt = $this->db->prepare("SELECT COUNT(*) as ativas FROM lojas WHERE ativo = 1");
        $stmt->execute();
        $stats['ativas'] = $stmt->fetchColumn();

        // Lojas inativas
        $stats['inativas'] = $stats['total'] - $stats['ativas'];

        return $stats;
    }

    /**
     * Busca todas as lojas ativas para exibição pública
     */
    public function getActive()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, nome, website, logo, 
                       COALESCE(created_at, data_cadastro) as created_at 
                FROM lojas 
                WHERE ativo = 1 
                ORDER BY nome ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            error_log("Erro ao buscar lojas ativas: " . $e->getMessage());
            return [];
        }
    }
}
?>