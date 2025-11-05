<?php

class NewsletterModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->createTable();
    }

    private function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS newsletter_cadastros (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            telefone VARCHAR(20),
            cidade VARCHAR(100),
            interesses TEXT,
            newsletter_ativo INTEGER DEFAULT 1,
            termos_aceitos INTEGER DEFAULT 1,
            data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
            status VARCHAR(20) DEFAULT 'ativo'
        )";
        
        Database::execute($sql);
    }

    public function getAll() {
        $sql = "SELECT * FROM newsletter_cadastros ORDER BY data_cadastro DESC";
        return Database::fetchAll($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM newsletter_cadastros WHERE id = ?";
        return Database::fetch($sql, [$id]);
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM newsletter_cadastros WHERE email = ?";
        return Database::fetch($sql, [$email]);
    }

    public function create($data) {
        try {
            // Verifica se email já existe
            if ($this->getByEmail($data['email'])) {
                throw new Exception('E-mail já cadastrado!');
            }

            // Converte array de interesses para string JSON
            $interesses = isset($data['interesses']) && is_array($data['interesses']) 
                ? json_encode($data['interesses']) 
                : (isset($data['interesses']) ? $data['interesses'] : '[]');

            $sql = "INSERT INTO newsletter_cadastros (nome, email, telefone, cidade, interesses, newsletter_ativo, termos_aceitos) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $data['nome'],
                $data['email'],
                $data['telefone'] ?? null,
                $data['cidade'] ?? 'Nova Friburgo',
                $interesses,
                isset($data['newsletter']) ? 1 : 0,
                isset($data['termos']) ? 1 : 0
            ];

            return Database::execute($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update($id, $data) {
        try {
            // Verifica se email já existe em outro registro
            $existingUser = $this->getByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $id) {
                throw new Exception('E-mail já cadastrado por outro usuário!');
            }

            // Converte array de interesses para string JSON
            $interesses = isset($data['interesses']) && is_array($data['interesses']) 
                ? json_encode($data['interesses']) 
                : (isset($data['interesses']) ? $data['interesses'] : '[]');

            $sql = "UPDATE newsletter_cadastros 
                    SET nome = ?, email = ?, telefone = ?, cidade = ?, interesses = ?, 
                        newsletter_ativo = ?, status = ?
                    WHERE id = ?";
            
            $params = [
                $data['nome'],
                $data['email'],
                $data['telefone'] ?? null,
                $data['cidade'] ?? 'Nova Friburgo',
                $interesses,
                isset($data['newsletter_ativo']) ? $data['newsletter_ativo'] : 1,
                $data['status'] ?? 'ativo',
                $id
            ];

            return Database::execute($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM newsletter_cadastros WHERE id = ?";
        return Database::execute($sql, [$id]);
    }

    public function toggleStatus($id) {
        $sql = "UPDATE newsletter_cadastros 
                SET status = CASE 
                    WHEN status = 'ativo' THEN 'inativo' 
                    ELSE 'ativo' 
                END 
                WHERE id = ?";
        return Database::execute($sql, [$id]);
    }

    public function toggleNewsletter($id) {
        $sql = "UPDATE newsletter_cadastros 
                SET newsletter_ativo = CASE 
                    WHEN newsletter_ativo = 1 THEN 0 
                    ELSE 1 
                END 
                WHERE id = ?";
        return Database::execute($sql, [$id]);
    }

    public function search($term) {
        $sql = "SELECT * FROM newsletter_cadastros 
                WHERE nome LIKE ? OR email LIKE ? OR cidade LIKE ?
                ORDER BY data_cadastro DESC";
        $searchTerm = "%{$term}%";
        return Database::fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }

    public function getAtivos() {
        $sql = "SELECT * FROM newsletter_cadastros WHERE status = 'ativo' ORDER BY data_cadastro DESC";
        return Database::fetchAll($sql);
    }

    public function getNewsletterAtivos() {
        $sql = "SELECT * FROM newsletter_cadastros 
                WHERE status = 'ativo' AND newsletter_ativo = 1 
                ORDER BY data_cadastro DESC";
        return Database::fetchAll($sql);
    }

    public function getEstatisticas() {
        $stats = [];
        
        // Total de cadastros
        $sql = "SELECT COUNT(*) as total FROM newsletter_cadastros";
        $result = Database::fetch($sql);
        $stats['total'] = $result['total'];
        
        // Cadastros ativos
        $sql = "SELECT COUNT(*) as ativos FROM newsletter_cadastros WHERE status = 'ativo'";
        $result = Database::fetch($sql);
        $stats['ativos'] = $result['ativos'];
        
        // Newsletter ativos
        $sql = "SELECT COUNT(*) as newsletter FROM newsletter_cadastros WHERE newsletter_ativo = 1 AND status = 'ativo'";
        $result = Database::fetch($sql);
        $stats['newsletter'] = $result['newsletter'];
        
        // Cadastros por mês (últimos 6 meses)
        $sql = "SELECT 
                    strftime('%Y-%m', data_cadastro) as mes,
                    COUNT(*) as total
                FROM newsletter_cadastros 
                WHERE data_cadastro >= datetime('now', '-6 months')
                GROUP BY strftime('%Y-%m', data_cadastro)
                ORDER BY mes DESC";
        $stats['por_mes'] = Database::fetchAll($sql);
        
        // Interesses mais populares
        $sql = "SELECT interesses FROM newsletter_cadastros WHERE interesses IS NOT NULL AND interesses != ''";
        $resultados = Database::fetchAll($sql);
        
        $interessesCount = [];
        foreach ($resultados as $row) {
            $interesses = json_decode($row['interesses'], true);
            if (is_array($interesses)) {
                foreach ($interesses as $interesse) {
                    $interessesCount[$interesse] = ($interessesCount[$interesse] ?? 0) + 1;
                }
            }
        }
        arsort($interessesCount);
        $stats['interesses_populares'] = array_slice($interessesCount, 0, 5, true);
        
        return $stats;
    }

    public function getInteressesOptions() {
        return [
            'moda' => 'Moda & Estilo',
            'gastronomia' => 'Gastronomia',
            'tecnologia' => 'Tecnologia',
            'beleza' => 'Beleza & Bem-estar',
            'casa' => 'Casa & Decoração',
            'cultura' => 'Cultura & Eventos'
        ];
    }
}