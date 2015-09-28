<?php
/**
 * Classe para conexao com o banco de dados MySql, via acesso nativo do PHP/PDO.
 */
class DB {

    /**
     * Instãncia singleton
     * @var DB 
     */
    private static $instance;
    
    /**
     * Conexão com o banco de dados
     * @var PDO 
     */
    private static $connection;

    /**
     * Construtor privado da classe singleton
     */
    private function __construct() {
        $url = parse_url(getenv("DATABASE_URL"));
        self::$connection = new PDO("pgsql:dbname=".substr($url["path"], 1).";host=".$url["host"].";port=".$url["port"],  $url["user"],  $url["pass"],array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    /**
     * Obtém a instancia da classe DB
     * @return type
     */
    public static function getInstance() {

        if (empty(self::$instance)) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    /**
     * Retorna a conexão PDO com o banco de dados 
     * @return PDO
     */
    public static function getConn() {
        self::getInstance();
        return self::$connection;
    }

    /**
     * Prepara a SQl para ser executada posteriormente
     * @param String $sql
     * @return PDOStatement stmt
     */
    public static function prepare($sql) {
        return self::getConn()->prepare($sql);
    }

    /**
     * Retorna o id da última consulta INSERT 
     * @return int
     */
    public static function lastInsertId() {
        return self::getConn()->lastInsertId();
    }

}