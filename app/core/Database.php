<?php

/**
 * Database - Classe para gerenciar conexões de banco de dados
 */
class Database
{
    private static $connection = null;
    private static $config = null;
    
    /**
     * Obtém a conexão com o banco de dados
     */
    public static function getConnection()
    {
        if (self::$connection !== null) {
            return self::$connection;
        }
        
        self::$config = Config::getDatabase();
        
        try {
            if (self::$config['driver'] === 'mysql') {
                self::connectMySQL();
            } else {
                self::connectSQLite();
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao conectar com banco de dados: " . $e->getMessage());
        }
        
        return self::$connection;
    }
    
    /**
     * Conecta com MySQL
     */
    private static function connectMySQL()
    {
        $dsn = "mysql:host=" . self::$config['host'] . ";port=" . self::$config['port'] . ";dbname=" . self::$config['database'] . ";charset=" . self::$config['charset'];
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        self::$connection = new PDO($dsn, self::$config['username'], self::$config['password'], $options);
    }
    
    /**
     * Conecta com SQLite
     */
    private static function connectSQLite()
    {
        // Cria diretório se não existir
        $databaseDir = dirname(self::$config['file']);
        if (!file_exists($databaseDir)) {
            mkdir($databaseDir, 0777, true);
        }
        
        $dsn = "sqlite:" . self::$config['file'];
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        
        self::$connection = new PDO($dsn, null, null, $options);
        
        // Habilita chaves estrangeiras no SQLite
        self::$connection->exec("PRAGMA foreign_keys = ON");
    }
    
    /**
     * Executa uma query
     */
    public static function query($sql, $params = [])
    {
        $connection = self::getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * Busca todos os registros
     */
    public static function fetchAll($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Busca um registro
     */
    public static function fetch($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Executa insert e retorna o ID
     */
    public static function insert($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return self::getConnection()->lastInsertId();
    }
    
    /**
     * Executa update/delete e retorna número de linhas afetadas
     */
    public static function execute($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Inicia uma transação
     */
    public static function beginTransaction()
    {
        return self::getConnection()->beginTransaction();
    }
    
    /**
     * Confirma uma transação
     */
    public static function commit()
    {
        return self::getConnection()->commit();
    }
    
    /**
     * Desfaz uma transação
     */
    public static function rollback()
    {
        return self::getConnection()->rollback();
    }
    
    /**
     * Cria as tabelas do sistema
     */
    public static function createTables()
    {
        $connection = self::getConnection();
        
        if (self::$config['driver'] === 'mysql') {
            self::createMySQLTables($connection);
        } else {
            self::createSQLiteTables($connection);
        }
    }
    
    /**
     * Cria tabelas para MySQL
     */
    private static function createMySQLTables($connection)
    {
        // Tabela de parceiros
        $sql = "
        CREATE TABLE IF NOT EXISTS parceiros (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            nome_fantasia VARCHAR(255) NOT NULL,
            tipo VARCHAR(100) NOT NULL,
            categoria VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            telefone VARCHAR(20),
            website VARCHAR(255),
            responsavel_nome VARCHAR(255) NOT NULL,
            responsavel_email VARCHAR(255),
            responsavel_telefone VARCHAR(20),
            endereco TEXT,
            contribuicao TEXT,
            status VARCHAR(20) DEFAULT 'ativo',
            ativo BOOLEAN DEFAULT TRUE,
            logo VARCHAR(255),
            data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_tipo (tipo),
            INDEX idx_status (status),
            INDEX idx_ativo (ativo)
        ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $connection->exec($sql);
        
        // Tabela de usuários
        $sqlUsers = "
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            tipo VARCHAR(50) DEFAULT 'admin',
            ativo BOOLEAN DEFAULT TRUE,
            data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_tipo (tipo),
            INDEX idx_ativo (ativo)
        ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $connection->exec($sqlUsers);
    }
    
    /**
     * Cria tabelas para SQLite
     */
    private static function createSQLiteTables($connection)
    {
        // Tabela de parceiros
        $sql = "
        CREATE TABLE IF NOT EXISTS parceiros (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            nome_fantasia TEXT NOT NULL,
            tipo TEXT NOT NULL,
            categoria TEXT NOT NULL,
            email TEXT NOT NULL,
            telefone TEXT,
            website TEXT,
            responsavel_nome TEXT NOT NULL,
            responsavel_email TEXT,
            responsavel_telefone TEXT,
            endereco TEXT,
            contribuicao TEXT,
            status TEXT DEFAULT 'ativo',
            ativo INTEGER DEFAULT 1,
            logo TEXT,
            data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
            data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE INDEX IF NOT EXISTS idx_parceiros_tipo ON parceiros(tipo);
        CREATE INDEX IF NOT EXISTS idx_parceiros_status ON parceiros(status);
        CREATE INDEX IF NOT EXISTS idx_parceiros_ativo ON parceiros(ativo);
        ";
        
        $connection->exec($sql);
        
        // Tabela de usuários
        $sqlUsers = "
        CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            senha TEXT NOT NULL,
            tipo TEXT DEFAULT 'admin',
            ativo INTEGER DEFAULT 1,
            data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
            data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE INDEX IF NOT EXISTS idx_usuarios_email ON usuarios(email);
        CREATE INDEX IF NOT EXISTS idx_usuarios_tipo ON usuarios(tipo);
        CREATE INDEX IF NOT EXISTS idx_usuarios_ativo ON usuarios(ativo);
        ";
        
        $connection->exec($sqlUsers);
    }
}