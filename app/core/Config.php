<?php

/**
 * Config - Classe para gerenciar configurações do sistema
 */
class Config
{
    private static $config = null;
    
    /**
     * Carrega as configurações do arquivo .envvars.yaml
     */
    public static function load()
    {
        if (self::$config !== null) {
            return self::$config;
        }
        
        $configFile = ROOT_PATH . '/.envvars.yaml';
        $exampleFile = ROOT_PATH . '/.envvars.example.yaml';
        
        // Se não existe o arquivo de configuração, usa o exemplo
        if (!file_exists($configFile)) {
            if (file_exists($exampleFile)) {
                copy($exampleFile, $configFile);
            } else {
                throw new Exception("Arquivo de configuração não encontrado.");
            }
        }
        
        // Lê o arquivo YAML
        $yamlContent = file_get_contents($configFile);
        self::$config = self::parseYaml($yamlContent);
        
        return self::$config;
    }
    
    /**
     * Obtém um valor de configuração
     */
    public static function get($key, $default = null)
    {
        if (self::$config === null) {
            self::load();
        }
        
        $keys = explode('.', $key);
        $value = self::$config;
        
        foreach ($keys as $k) {
            if (is_array($value) && isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
    
    /**
     * Parser YAML simples (sem biblioteca externa)
     */
    private static function parseYaml($content)
    {
        $lines = explode("\n", $content);
        $result = [];
        $stack = [&$result];
        $currentIndent = 0;
        
        foreach ($lines as $line) {
            $line = rtrim($line);
            
            // Pula linhas vazias e comentários
            if (empty($line) || $line[0] === '#') {
                continue;
            }
            
            // Calcula indentação
            $indent = 0;
            for ($i = 0; $i < strlen($line); $i++) {
                if ($line[$i] === ' ') {
                    $indent++;
                } else {
                    break;
                }
            }
            
            $line = ltrim($line);
            
            // Ajusta o stack baseado na indentação
            $level = intval($indent / 2);
            while (count($stack) > $level + 1) {
                array_pop($stack);
            }
            
            if (strpos($line, ':') !== false) {
                $parts = explode(':', $line, 2);
                $key = trim($parts[0]);
                $value = isset($parts[1]) ? trim($parts[1]) : null;
                
                // Remove aspas se presentes
                if ($value && (($value[0] === '"' && $value[-1] === '"') || 
                              ($value[0] === "'" && $value[-1] === "'"))) {
                    $value = substr($value, 1, -1);
                }
                
                // Converte valores especiais
                if ($value === 'true') {
                    $value = true;
                } elseif ($value === 'false') {
                    $value = false;
                } elseif ($value === 'null') {
                    $value = null;
                } elseif (is_numeric($value)) {
                    $value = is_float($value + 0) ? (float)$value : (int)$value;
                }
                
                if ($value === null || $value === '') {
                    // É um objeto/array
                    $stack[count($stack) - 1][$key] = [];
                    $stack[] = &$stack[count($stack) - 1][$key];
                } else {
                    $stack[count($stack) - 1][$key] = $value;
                }
            } elseif (strpos($line, '- ') === 0) {
                // É um item de array
                $value = trim(substr($line, 2));
                
                // Remove aspas se presentes
                if ($value && (($value[0] === '"' && $value[-1] === '"') || 
                              ($value[0] === "'" && $value[-1] === "'"))) {
                    $value = substr($value, 1, -1);
                }
                
                $stack[count($stack) - 1][] = $value;
            }
        }
        
        return $result;
    }
    
    /**
     * Obtém configurações do banco de dados
     */
    public static function getDatabase()
    {
        $driver = self::get('database.driver', 'sqlite');
        
        if ($driver === 'mysql') {
            return [
                'driver' => 'mysql',
                'host' => self::get('database.mysql.host', 'localhost'),
                'port' => self::get('database.mysql.port', 3306),
                'database' => self::get('database.mysql.database', 'black_braune'),
                'username' => self::get('database.mysql.username', 'root'),
                'password' => self::get('database.mysql.password', ''),
                'charset' => self::get('database.mysql.charset', 'utf8mb4')
            ];
        } else {
            return [
                'driver' => 'sqlite',
                'file' => ROOT_PATH . '/' . self::get('database.sqlite.file', 'database/black_braune.db')
            ];
        }
    }
}