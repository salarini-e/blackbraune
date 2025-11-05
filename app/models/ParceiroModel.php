<?php

require_once CORE_PATH . '/Database.php';
require_once CORE_PATH . '/Config.php';

class ParceiroModel {
    
    public function __construct() {
        // Não precisa fazer nada, Database é estático
    }
    
    /**
     * Buscar todos os parceiros
     */
    public function getAll($filters = []) {
        try {
            $sql = "SELECT * FROM parceiros WHERE 1=1";
            $params = [];
            
            // Aplicar filtros
            if (isset($filters['tipo']) && !empty($filters['tipo'])) {
                $sql .= " AND tipo = :tipo";
                $params['tipo'] = $filters['tipo'];
            }
            
            if (isset($filters['status']) && !empty($filters['status'])) {
                $sql .= " AND status = :status";
                $params['status'] = $filters['status'];
            }
            
            if (isset($filters['search']) && !empty($filters['search'])) {
                $sql .= " AND (nome LIKE :search OR nome_fantasia LIKE :search OR email LIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }
            
            $sql .= " ORDER BY data_cadastro DESC";
            
            $parceiros = Database::fetchAll($sql, $params);
            
            // Se não há dados, inserir dados de exemplo
            if (empty($parceiros)) {
                $this->insertSampleData();
                $parceiros = Database::fetchAll($sql, $params);
            }
            
            return $parceiros;
            
        } catch (Exception $e) {
            // Se houver erro com banco, retorna dados mock
            return $this->getMockData($filters);
        }
    }
    
    /**
     * Buscar parceiro por ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM parceiros WHERE id = :id";
            return Database::fetch($sql, ['id' => $id]);
        } catch (Exception $e) {
            // Fallback para mock data
            $parceiros = $this->getMockData();
            foreach ($parceiros as $parceiro) {
                if ($parceiro['id'] == $id) {
                    return $parceiro;
                }
            }
            return null;
        }
    }
    
    /**
     * Criar novo parceiro
     */
    public function create($data) {
        // Validar dados obrigatórios
        $requiredFields = ['nomeFantasia', 'tipo', 'categoria', 'email', 'responsavelNome'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Campo obrigatório: {$field}");
            }
        }
        
        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }
        
        try {
            $dbConfig = Config::getDatabase();
            
            if ($dbConfig['driver'] === 'mysql') {
                $sql = "INSERT INTO parceiros (
                    nome, nome_fantasia, tipo, categoria, email, telefone, website,
                    responsavel_nome, responsavel_email, responsavel_telefone,
                    endereco, contribuicao, status, ativo, logo,
                    data_cadastro, data_atualizacao
                ) VALUES (
                    :nome, :nome_fantasia, :tipo, :categoria, :email, :telefone, :website,
                    :responsavel_nome, :responsavel_email, :responsavel_telefone,
                    :endereco, :contribuicao, :status, :ativo, :logo,
                    NOW(), NOW()
                )";
            } else {
                $sql = "INSERT INTO parceiros (
                    nome, nome_fantasia, tipo, categoria, email, telefone, website,
                    responsavel_nome, responsavel_email, responsavel_telefone,
                    endereco, contribuicao, status, ativo, logo,
                    data_cadastro, data_atualizacao
                ) VALUES (
                    :nome, :nome_fantasia, :tipo, :categoria, :email, :telefone, :website,
                    :responsavel_nome, :responsavel_email, :responsavel_telefone,
                    :endereco, :contribuicao, :status, :ativo, :logo,
                    datetime('now'), datetime('now')
                )";
            }
            
            $params = [
                'nome' => $data['nomeFantasia'],
                'nome_fantasia' => $data['nomeFantasia'],
                'tipo' => $data['tipo'],
                'categoria' => $data['categoria'],
                'email' => $data['email'],
                'telefone' => $data['telefone'] ?? '',
                'website' => $data['website'] ?? '',
                'responsavel_nome' => $data['responsavelNome'],
                'responsavel_email' => $data['responsavelEmail'] ?? '',
                'responsavel_telefone' => $data['responsavelTelefone'] ?? '',
                'endereco' => $data['endereco'] ?? '',
                'contribuicao' => $data['contribuicao'] ?? '',
                'status' => $data['status'] ?? 'ativo',
                'ativo' => ($data['status'] ?? 'ativo') === 'ativo' ? 1 : 0,
                'logo' => $data['logo'] ?? null
            ];
            
            $id = Database::insert($sql, $params);
            
            // Retornar o parceiro criado
            return $this->getById($id);
            
        } catch (Exception $e) {
            throw new Exception("Erro ao criar parceiro: " . $e->getMessage());
        }
    }
    
    /**
     * Atualizar parceiro
     */
    public function update($id, $data) {
        // Verificar se parceiro existe
        $parceiro = $this->getById($id);
        if (!$parceiro) {
            throw new Exception("Parceiro não encontrado");
        }
        
        // Validar dados obrigatórios
        $requiredFields = ['nomeFantasia', 'tipo', 'categoria', 'email', 'responsavelNome'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Campo obrigatório: {$field}");
            }
        }
        
        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }
        
        try {
            $dbConfig = Config::getDatabase();
            
            if ($dbConfig['driver'] === 'mysql') {
                $sql = "UPDATE parceiros SET 
                    nome = :nome,
                    nome_fantasia = :nome_fantasia,
                    tipo = :tipo,
                    categoria = :categoria,
                    email = :email,
                    telefone = :telefone,
                    website = :website,
                    responsavel_nome = :responsavel_nome,
                    responsavel_email = :responsavel_email,
                    responsavel_telefone = :responsavel_telefone,
                    endereco = :endereco,
                    contribuicao = :contribuicao,
                    status = :status,
                    ativo = :ativo,
                    data_atualizacao = NOW()";
            } else {
                $sql = "UPDATE parceiros SET 
                    nome = :nome,
                    nome_fantasia = :nome_fantasia,
                    tipo = :tipo,
                    categoria = :categoria,
                    email = :email,
                    telefone = :telefone,
                    website = :website,
                    responsavel_nome = :responsavel_nome,
                    responsavel_email = :responsavel_email,
                    responsavel_telefone = :responsavel_telefone,
                    endereco = :endereco,
                    contribuicao = :contribuicao,
                    status = :status,
                    ativo = :ativo,
                    data_atualizacao = datetime('now')";
            }
            
            $params = [
                'nome' => $data['nomeFantasia'],
                'nome_fantasia' => $data['nomeFantasia'],
                'tipo' => $data['tipo'],
                'categoria' => $data['categoria'],
                'email' => $data['email'],
                'telefone' => $data['telefone'] ?? '',
                'website' => $data['website'] ?? '',
                'responsavel_nome' => $data['responsavelNome'],
                'responsavel_email' => $data['responsavelEmail'] ?? '',
                'responsavel_telefone' => $data['responsavelTelefone'] ?? '',
                'endereco' => $data['endereco'] ?? '',
                'contribuicao' => $data['contribuicao'] ?? '',
                'status' => $data['status'] ?? 'ativo',
                'ativo' => ($data['status'] ?? 'ativo') === 'ativo' ? 1 : 0,
                'id' => $id
            ];
            
            // Se tem nova logo
            if (isset($data['logo']) && !empty($data['logo'])) {
                $sql .= ", logo = :logo";
                $params['logo'] = $data['logo'];
            }
            
            $sql .= " WHERE id = :id";
            
            Database::execute($sql, $params);
            
            // Retornar o parceiro atualizado
            return $this->getById($id);
            
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar parceiro: " . $e->getMessage());
        }
    }
    
    /**
     * Deletar parceiro
     */
    public function delete($id) {
        // Verificar se parceiro existe
        $parceiro = $this->getById($id);
        if (!$parceiro) {
            throw new Exception("Parceiro não encontrado");
        }
        
        try {
            $sql = "DELETE FROM parceiros WHERE id = :id";
            $affected = Database::execute($sql, ['id' => $id]);
            
            if ($affected === 0) {
                throw new Exception("Nenhum registro foi removido");
            }
            
            return true;
            
        } catch (Exception $e) {
            throw new Exception("Erro ao deletar parceiro: " . $e->getMessage());
        }
    }
    
    /**
     * Buscar parceiros por tipo
     */
    public function getByTipo($tipo) {
        return $this->getAll(['tipo' => $tipo]);
    }
    
    /**
     * Buscar parceiros ativos
     */
    public function getAtivos() {
        return $this->getAll(['status' => 'ativo']);
    }
    
    /**
     * Contar total de parceiros
     */
    public function count($filters = []) {
        try {
            $sql = "SELECT COUNT(*) as total FROM parceiros WHERE 1=1";
            $params = [];
            
            // Aplicar filtros
            if (isset($filters['tipo']) && !empty($filters['tipo'])) {
                $sql .= " AND tipo = :tipo";
                $params['tipo'] = $filters['tipo'];
            }
            
            if (isset($filters['status']) && !empty($filters['status'])) {
                $sql .= " AND status = :status";
                $params['status'] = $filters['status'];
            }
            
            if (isset($filters['search']) && !empty($filters['search'])) {
                $sql .= " AND (nome LIKE :search OR nome_fantasia LIKE :search OR email LIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }
            
            $result = Database::fetch($sql, $params);
            return $result['total'];
            
        } catch (Exception $e) {
            return count($this->getMockData($filters));
        }
    }
    
    /**
     * Salvar logo do parceiro
     */
    public function saveLogo($file, $parceiroId) {
        // Validar arquivo
        $allowedTypes = Config::get('upload.allowed_types', ['image/jpeg', 'image/png', 'image/gif']);
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception("Tipo de arquivo não permitido. Use JPG, PNG ou GIF.");
        }
        
        // Validar tamanho
        $maxSize = Config::get('upload.max_size', 5242880); // 5MB default
        if ($file['size'] > $maxSize) {
            throw new Exception("Arquivo muito grande. Máximo " . ($maxSize / 1024 / 1024) . "MB.");
        }
        
        // Criar diretório se não existir
        $uploadDir = ROOT_PATH . '/' . Config::get('upload.parceiros_path', 'assets/uploads/parceiros/');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Gerar nome único para arquivo
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'parceiro_' . $parceiroId . '_' . time() . '.' . $extension;
        $filePath = $uploadDir . $fileName;
        
        // Mover arquivo
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $fileName;
        } else {
            throw new Exception("Erro ao salvar arquivo.");
        }
    }
    
    /**
     * Inserir dados de exemplo no banco
     */
    private function insertSampleData() {
        $sampleData = [
            [
                'nome' => 'Associação Comercial de Nova Friburgo',
                'nome_fantasia' => 'ACIF',
                'tipo' => 'Parceiro Institucional',
                'categoria' => 'Associação Comercial',
                'email' => 'contato@acif.com.br',
                'telefone' => '(22) 2522-3344',
                'website' => 'https://www.acif.com.br',
                'responsavel_nome' => 'Maria Silva',
                'responsavel_email' => 'maria@acif.com.br',
                'responsavel_telefone' => '(22) 99999-1234',
                'endereco' => 'Rua Dr. Silvio Henrique Braune, 123 - Centro',
                'contribuicao' => 'Apoio institucional e eventos de capacitação para empreendedores negros',
                'status' => 'ativo',
                'ativo' => 1
            ],
            [
                'nome' => 'Prefeitura de Nova Friburgo',
                'nome_fantasia' => 'PMNF',
                'tipo' => 'Parceiro Público',
                'categoria' => 'Órgão Público',
                'email' => 'gabinete@pmnf.rj.gov.br',
                'telefone' => '(22) 2543-8000',
                'website' => 'https://www.novafriburgo.rj.gov.br',
                'responsavel_nome' => 'João Santos',
                'responsavel_email' => 'joao.santos@pmnf.rj.gov.br',
                'responsavel_telefone' => '(22) 99888-7766',
                'endereco' => 'Praça Getúlio Vargas, 8 - Centro',
                'contribuicao' => 'Apoio em políticas públicas de inclusão e eventos municipais',
                'status' => 'ativo',
                'ativo' => 1
            ],
            [
                'nome' => 'TechNova Soluções',
                'nome_fantasia' => 'TechNova',
                'tipo' => 'Parceiro Técnico',
                'categoria' => 'Tecnologia',
                'email' => 'contato@technova.com.br',
                'telefone' => '(22) 3344-5566',
                'website' => 'https://www.technova.com.br',
                'responsavel_nome' => 'Ana Costa',
                'responsavel_email' => 'ana@technova.com.br',
                'responsavel_telefone' => '(22) 98765-4321',
                'endereco' => 'Av. Alberto Braune, 456 - Nova Suíça',
                'contribuicao' => 'Desenvolvimento de plataformas digitais e capacitação em tecnologia',
                'status' => 'ativo',
                'ativo' => 1
            ],
            [
                'nome' => 'Banco do Brasil',
                'nome_fantasia' => 'BB',
                'tipo' => 'Patrocinador Oficial',
                'categoria' => 'Instituição Financeira',
                'email' => 'agencia.novafriburgo@bb.com.br',
                'telefone' => '(22) 2522-1100',
                'website' => 'https://www.bb.com.br',
                'responsavel_nome' => 'Carlos Oliveira',
                'responsavel_email' => 'carlos.oliveira@bb.com.br',
                'responsavel_telefone' => '(22) 97654-3210',
                'endereco' => 'Rua Monsenhor Miranda, 789 - Centro',
                'contribuicao' => 'Patrocínio de eventos e linhas de crédito especiais para empreendedores',
                'status' => 'ativo',
                'ativo' => 1
            ],
            [
                'nome' => 'Instituto Braune de Cultura',
                'nome_fantasia' => 'IBC',
                'tipo' => 'Apoiador',
                'categoria' => 'Organização Cultural',
                'email' => 'contato@institutobraune.org.br',
                'telefone' => '(22) 2533-4455',
                'website' => 'https://www.institutobraune.org.br',
                'responsavel_nome' => 'Lucia Fernandes',
                'responsavel_email' => 'lucia@institutobraune.org.br',
                'responsavel_telefone' => '(22) 96543-2109',
                'endereco' => 'Rua General Osório, 321 - Centro',
                'contribuicao' => 'Apoio cultural e divulgação de eventos relacionados à cultura afro-brasileira',
                'status' => 'ativo',
                'ativo' => 1
            ]
        ];
        
        foreach ($sampleData as $data) {
            try {
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
                
                Database::insert($sql, $data);
            } catch (Exception $e) {
                // Continua mesmo se um registro falhar
            }
        }
    }
    
    /**
     * Dados mock para fallback
     */
    private function getMockData($filters = []) {
        $parceiros = [
            [
                'id' => 1,
                'nome' => 'Associação Comercial de Nova Friburgo',
                'nome_fantasia' => 'ACIF',
                'tipo' => 'Parceiro Institucional',
                'categoria' => 'Associação Comercial',
                'email' => 'contato@acif.com.br',
                'telefone' => '(22) 2522-3344',
                'website' => 'https://www.acif.com.br',
                'responsavel_nome' => 'Maria Silva',
                'responsavel_email' => 'maria@acif.com.br',
                'responsavel_telefone' => '(22) 99999-1234',
                'endereco' => 'Rua Dr. Silvio Henrique Braune, 123 - Centro',
                'contribuicao' => 'Apoio institucional e eventos de capacitação para empreendedores negros',
                'status' => 'ativo',
                'ativo' => 1,
                'logo' => null,
                'data_cadastro' => '2024-01-15 10:00:00',
                'data_atualizacao' => '2024-01-15 10:00:00'
            ]
        ];
        
        // Aplicar filtros se fornecidos
        if (!empty($filters)) {
            $parceiros = array_filter($parceiros, function($parceiro) use ($filters) {
                if (isset($filters['tipo']) && !empty($filters['tipo'])) {
                    if ($parceiro['tipo'] !== $filters['tipo']) return false;
                }
                if (isset($filters['status']) && !empty($filters['status'])) {
                    if ($parceiro['status'] !== $filters['status']) return false;
                }
                if (isset($filters['search']) && !empty($filters['search'])) {
                    $search = strtolower($filters['search']);
                    $nome = strtolower($parceiro['nome']);
                    $email = strtolower($parceiro['email']);
                    if (strpos($nome, $search) === false && strpos($email, $search) === false) return false;
                }
                return true;
            });
        }
        
        return array_values($parceiros);
    }
}