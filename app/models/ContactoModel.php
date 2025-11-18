<?php
require_once CORE_PATH . '/Database.php';

class ContactoModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
        $this->createTable();
        $this->initializeDefaultData();
    }
    
    private function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS contactos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tipo VARCHAR(50) NOT NULL,
            titulo VARCHAR(100) NOT NULL,
            valor TEXT NOT NULL,
            icone VARCHAR(50),
            link VARCHAR(255),
            ordem INTEGER DEFAULT 0,
            ativo BOOLEAN DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        
        $this->db->exec($sql);
    }
    
    private function initializeDefaultData() {
        $count = $this->db->query("SELECT COUNT(*) as count FROM contactos")->fetch();
        if ($count['count'] == 0) {
            $defaultData = [
                ['endereco', 'Endereço', 'Avenida Alberto Braune, Centro<br>Nova Friburgo - RJ', 'fas fa-map-marker-alt', null, 1],
                ['email', 'E-mail', 'contato@blackbraune.com.br', 'fas fa-envelope', 'mailto:contato@blackbraune.com.br', 2],
                ['telefone', 'Telefone', '(22) 99999-9999', 'fas fa-phone', 'tel:+5522999999999', 3],
                ['facebook', 'Facebook', 'Black Braune', 'fab fa-facebook', 'https://facebook.com/blackbraune', 4],
                ['instagram', 'Instagram', '@blackbraune', 'fab fa-instagram', 'https://instagram.com/blackbraune', 5],
                ['whatsapp', 'WhatsApp', '(22) 99999-9999', 'fab fa-whatsapp', 'https://wa.me/5522999999999', 6],
                ['youtube', 'YouTube', 'Black Braune', 'fab fa-youtube', 'https://youtube.com/@blackbraune', 7]
            ];
            
            $stmt = $this->db->prepare("INSERT INTO contactos (tipo, titulo, valor, icone, link, ordem) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($defaultData as $data) {
                $stmt->execute($data);
            }
        }
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM contactos WHERE ativo = 1 ORDER BY ordem, id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByTipo($tipo) {
        $stmt = $this->db->prepare("SELECT * FROM contactos WHERE tipo = ? AND ativo = 1 ORDER BY ordem");
        $stmt->execute([$tipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getContatos() {
        $tipos = ['endereco', 'email', 'telefone'];
        $stmt = $this->db->prepare("SELECT * FROM contactos WHERE tipo IN ('" . implode("','", $tipos) . "') AND ativo = 1 ORDER BY ordem");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRedesSociais() {
        $tipos = ['facebook', 'instagram', 'whatsapp', 'youtube'];
        $stmt = $this->db->prepare("SELECT * FROM contactos WHERE tipo IN ('" . implode("','", $tipos) . "') AND ativo = 1 ORDER BY ordem");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM contactos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO contactos (tipo, titulo, valor, icone, link, ordem, ativo) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['tipo'],
            $data['titulo'],
            $data['valor'],
            $data['icone'] ?? null,
            $data['link'] ?? null,
            $data['ordem'] ?? 0,
            $data['ativo'] ?? 1
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE contactos 
            SET tipo = ?, titulo = ?, valor = ?, icone = ?, link = ?, ordem = ?, ativo = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['tipo'],
            $data['titulo'],
            $data['valor'],
            $data['icone'] ?? null,
            $data['link'] ?? null,
            $data['ordem'] ?? 0,
            $data['ativo'] ?? 1,
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE contactos SET ativo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function forceDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM contactos WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function updateOrdem($id, $ordem) {
        $stmt = $this->db->prepare("UPDATE contactos SET ordem = ? WHERE id = ?");
        return $stmt->execute([$ordem, $id]);
    }
}
?>