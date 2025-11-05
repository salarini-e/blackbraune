<?php
/**
 * UsuarioModel - Model para gestão de usuários
 */

require_once CORE_PATH . '/Database.php';

class UsuarioModel
{
    /**
     * Buscar todos os usuários
     */
    public function getAll($filters = [])
    {
        try {
            $sql = "SELECT id, nome, email, tipo, ativo, data_cadastro FROM usuarios WHERE 1=1";
            $params = [];
            
            // Filtro por status ativo
            if (isset($filters['ativo'])) {
                $sql .= " AND ativo = :ativo";
                $params['ativo'] = $filters['ativo'];
            }
            
            // Filtro por tipo
            if (isset($filters['tipo']) && !empty($filters['tipo'])) {
                $sql .= " AND tipo = :tipo";
                $params['tipo'] = $filters['tipo'];
            }
            
            // Filtro por busca (nome ou email)
            if (isset($filters['search']) && !empty($filters['search'])) {
                $sql .= " AND (nome LIKE :search OR email LIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }
            
            $sql .= " ORDER BY nome ASC";
            
            return Database::fetchAll($sql, $params);
            
        } catch (Exception $e) {
            // Fallback para dados mock
            return $this->getMockData();
        }
    }
    
    /**
     * Buscar usuário por ID
     */
    public function getById($id)
    {
        try {
            $sql = "SELECT id, nome, email, tipo, ativo, data_cadastro FROM usuarios WHERE id = :id";
            return Database::fetch($sql, ['id' => $id]);
        } catch (Exception $e) {
            // Fallback para mock data
            $usuarios = $this->getMockData();
            foreach ($usuarios as $usuario) {
                if ($usuario['id'] == $id) {
                    return $usuario;
                }
            }
            return null;
        }
    }
    
    /**
     * Buscar usuário por email
     */
    public function getByEmail($email)
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email AND ativo = 1";
            return Database::fetch($sql, ['email' => $email]);
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Criar novo usuário
     */
    public function create($dados)
    {
        try {
            // Validações básicas
            if (empty($dados['nome']) || empty($dados['email']) || empty($dados['senha'])) {
                throw new Exception('Nome, email e senha são obrigatórios');
            }
            
            if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido');
            }
            
            // Validação de senha
            if (strlen($dados['senha']) < 6) {
                throw new Exception('A senha deve ter pelo menos 6 caracteres');
            }
            
            // Validação de confirmação de senha (se fornecida)
            if (isset($dados['confirmar_senha']) && $dados['senha'] !== $dados['confirmar_senha']) {
                throw new Exception('As senhas não coincidem');
            }
            
            // Verifica se email já existe
            $usuarioExistente = $this->getByEmail($dados['email']);
            if ($usuarioExistente) {
                throw new Exception('Este email já está cadastrado');
            }
            
            // Hash da senha
            $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);
            
            // Configurações do banco
            $dbConfig = Config::getDatabase();
            
            if ($dbConfig['driver'] === 'mysql') {
                $sql = "INSERT INTO usuarios (
                    nome, email, senha, tipo, ativo, data_cadastro, data_atualizacao
                ) VALUES (
                    :nome, :email, :senha, :tipo, :ativo, NOW(), NOW()
                )";
            } else {
                $sql = "INSERT INTO usuarios (
                    nome, email, senha, tipo, ativo, data_cadastro, data_atualizacao
                ) VALUES (
                    :nome, :email, :senha, :tipo, :ativo, datetime('now'), datetime('now')
                )";
            }
            
            $params = [
                'nome' => trim($dados['nome']),
                'email' => trim(strtolower($dados['email'])),
                'senha' => $senhaHash,
                'tipo' => $dados['tipo'] ?? 'admin',
                'ativo' => isset($dados['ativo']) ? (int)$dados['ativo'] : 1
            ];
            
            $id = Database::insert($sql, $params);
            
            return $this->getById($id);
            
        } catch (Exception $e) {
            throw new Exception('Erro ao criar usuário: ' . $e->getMessage());
        }
    }
    
    /**
     * Atualizar usuário
     */
    public function update($id, $dados)
    {
        try {
            // Busca usuário atual
            $usuarioAtual = $this->getById($id);
            if (!$usuarioAtual) {
                throw new Exception('Usuário não encontrado');
            }
            
            // Validações básicas
            if (empty($dados['nome']) || empty($dados['email'])) {
                throw new Exception('Nome e email são obrigatórios');
            }
            
            if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido');
            }
            
            // Verifica se email já existe (exceto o próprio usuário)
            $usuarioExistente = $this->getByEmail($dados['email']);
            if ($usuarioExistente && $usuarioExistente['id'] != $id) {
                throw new Exception('Este email já está cadastrado para outro usuário');
            }
            
            // Monta a query base
            $campos = [
                'nome = :nome',
                'email = :email',
                'tipo = :tipo',
                'ativo = :ativo'
            ];
            
            $params = [
                'nome' => trim($dados['nome']),
                'email' => trim(strtolower($dados['email'])),
                'tipo' => $dados['tipo'] ?? 'admin',
                'ativo' => isset($dados['ativo']) ? (int)$dados['ativo'] : 1,
                'id' => $id
            ];
            
            // Se foi fornecida uma nova senha
            if (!empty($dados['senha'])) {
                $campos[] = 'senha = :senha';
                $params['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
            }
            
            // Adiciona timestamp de atualização
            $dbConfig = Config::getDatabase();
            if ($dbConfig['driver'] === 'mysql') {
                $campos[] = 'data_atualizacao = NOW()';
            } else {
                $campos[] = "data_atualizacao = datetime('now')";
            }
            
            $sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE id = :id";
            
            Database::execute($sql, $params);
            
            return $this->getById($id);
            
        } catch (Exception $e) {
            throw new Exception('Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }
    
    /**
     * Deletar usuário
     */
    public function delete($id)
    {
        try {
            // Verifica se usuário existe
            $usuario = $this->getById($id);
            if (!$usuario) {
                throw new Exception('Usuário não encontrado');
            }
            
            // Verifica se não é o último admin ativo
            $adminsAtivos = Database::fetch(
                "SELECT COUNT(*) as total FROM usuarios WHERE tipo = 'admin' AND ativo = 1"
            );
            
            if ($adminsAtivos['total'] <= 1 && $usuario['tipo'] === 'admin' && $usuario['ativo']) {
                throw new Exception('Não é possível excluir o último administrador ativo');
            }
            
            $sql = "DELETE FROM usuarios WHERE id = :id";
            return Database::execute($sql, ['id' => $id]);
            
        } catch (Exception $e) {
            throw new Exception('Erro ao deletar usuário: ' . $e->getMessage());
        }
    }
    
    /**
     * Autenticar usuário
     */
    public function authenticate($email, $senha)
    {
        try {
            $usuario = $this->getByEmail($email);
            
            if (!$usuario) {
                return false;
            }
            
            if (!password_verify($senha, $usuario['senha'])) {
                return false;
            }
            
            // Remove a senha do retorno
            unset($usuario['senha']);
            
            return $usuario;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Contar usuários
     */
    public function count($filters = [])
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE 1=1";
            $params = [];
            
            if (isset($filters['ativo'])) {
                $sql .= " AND ativo = :ativo";
                $params['ativo'] = $filters['ativo'];
            }
            
            if (isset($filters['tipo']) && !empty($filters['tipo'])) {
                $sql .= " AND tipo = :tipo";
                $params['tipo'] = $filters['tipo'];
            }
            
            $result = Database::fetch($sql, $params);
            return $result['total'];
            
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Inserir dados de exemplo (primeiro acesso)
     */
    public function insertSampleData()
    {
        try {
            // Verifica se já existem usuários
            $count = $this->count();
            if ($count > 0) {
                return false; // Já existem usuários
            }
            
            // Cria usuário admin padrão
            $adminPadrao = [
                'nome' => 'Administrador',
                'email' => 'admin@blackbraune.com',
                'senha' => 'admin123', // Será criptografada
                'tipo' => 'admin',
                'ativo' => 1
            ];
            
            $this->create($adminPadrao);
            
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Dados mock para desenvolvimento
     */
    private function getMockData()
    {
        return [
            [
                'id' => 1,
                'nome' => 'Administrador Principal',
                'email' => 'admin@blackbraune.com',
                'tipo' => 'admin',
                'ativo' => 1,
                'data_cadastro' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'nome' => 'João Silva',
                'email' => 'joao@blackbraune.com',
                'tipo' => 'admin',
                'ativo' => 1,
                'data_cadastro' => date('Y-m-d H:i:s')
            ]
        ];
    }
}
?>