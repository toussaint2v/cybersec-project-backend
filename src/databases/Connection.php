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
    private $password = "password";

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
        try {
            $sql = $this->pdo->prepare($req);
            $sql->execute($form);
            $res = ['data' => 'Ok', 'status' => 200];
        } catch (PDOException $e) {
            $status = $e->getCode();
            $mess = $e->getMessage();
            $res = ['data' => $mess, 'status' => $status];
        }
        return $res;
    }

    public function get($req, $form = null){
        try {
            $sql = $this->pdo->prepare($req);
            $sql->execute($form);
            $data = $sql->fetch(PDO::FETCH_ASSOC);
            $res = ['data' => $data, 'status' => 200];
        } catch (PDOException $e) {
            $status = $e->getCode();
            $mess = $e->getMessage();
            $res = ['data' => $mess, 'status' => $status];
        }
        return $res;
    }

    public function getAll($req, $form = null){
        try {
            $sql = $this->pdo->prepare($req);
            $sql->execute($form);
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $res = ['data' => $data, 'status' => 200];
        } catch (PDOException $e) {
            $status = $e->getCode();
            $mess = $e->getMessage();
            $res = ['data' => $mess, 'status' => $status];
        }
        return $res;
    }

}


?>