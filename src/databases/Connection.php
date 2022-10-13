<?php
namespace Src\databases;

use Exception;
use PDO;
use PDOException;

class Connection{

    private $pdo;
    private string $error;

    private $host = "localhost";
    private $dbname = "cyber_sec";
    private $username = "root";
    private $password = "";

    public function __construct(){
        //connection à la base de données
        try{
            $this->pdo = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname.';charset=utf8',
                $this->username,$this->password);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(Exception $e){
            $this->error ='Erreur de connexion: '.$e->getMessage();
            die('Erreur de connexion: '.$e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function execute($req, $form = null){
        $status = 200;
        try {
            $sql = $this->pdo->prepare($req);
            $sql->execute($form);
            $res = "OK";
        } catch (PDOException $e) {
            $status = $e->getCode();
            $res = $e->getMessage();
        }
        http_response_code($status);
        return $res;
    }

    public function get($req, $form = null){
        $status = 200;
        try {
            $sql = $this->pdo->prepare($req);
            $sql->execute($form);
            $res = $sql->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $status = $e->getCode();
            $res = $e->getMessage();
        }
        http_response_code($status);
        return $res;
    }

    public function getAll($req, $form = null){
        $status = 200;
        try {
            $sql = $this->pdo->prepare($req);
            $sql->execute($form);
            $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $status = $e->getCode();
            $res = $e->getMessage();
        }
        http_response_code($status);
        return $res;
    }

}


?>