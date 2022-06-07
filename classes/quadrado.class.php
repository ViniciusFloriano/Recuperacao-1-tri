<?php
    include_once 'conf/Conexao.php';
    require_once 'conf/conf.inc.php';
    class Quadrado{
        private $id;
        private $lado;
        private $cor;
        private $idtabuleiro;

        public function __construct($id, $lado, $cor, $idtabuleiro) {
            $this->setid($id);
            $this->setlado($lado);
            $this->setcor($cor);
            $this->settabuleiro($idtabuleiro);
        }

        public function getid(){ 
            return $this->id; 
        }

        public function setid($id){ 
            $this->id = $id;
        }      
        
        public function getlado() {
            return $this->lado;
        }

        public function setlado($lado) {
            if ($lado >  0)
                $this->lado = $lado;
        }

        public function getcor() {
            return $this->cor;
        }

        public function setcor($cor) {
            if (strlen($cor) > 0)    
                $this->cor = $cor;
        }

        public function gettabuleiro() {
            return $this->idtabuleiro;
        }

        public function settabuleiro($idtabuleiro) {
            if ($idtabuleiro >  0)
                $this->idtabuleiro = $idtabuleiro;
        }

        public function __toString() {
            return  "[Quadrado]<br>Lado: ".$this->getlado()."<br>".
                    "Cor: ".$this->getcor()."<br>".
                    "Area: ".$this->Area()."<br>".
                    "Perimetro: ".$this->Perimetro()."<br>".
                    "Diagonal: ".$this->Diagonal()."<br>".
                    "Id do Tabuleiro: ".$this->gettabuleiro()."<br>";
        }

        public function Area() {
            $area = $this->lado * $this->lado;
            return $area;
        }

        public function Perimetro() {
            $perimetro = $this->lado * 4;
            return $perimetro;
        }

        public function Diagonal() {
            $diagonal = $this->lado * sqrt(2);
            return $diagonal;
        }

        public function inserir(){
            $pdo = Conexao::getInstance();
                $stmt = $pdo->prepare('INSERT INTO recuperacao.quadrado (lado, cor, idtabuleiro) VALUES (:lado, :cor, :idtabuleiro)');
                $stmt->bindParam(':lado', $this->getlado(), PDO::PARAM_STR);
                $stmt->bindParam(':cor', $this->getcor(), PDO::PARAM_STR);
                $stmt->bindParam(':idtabuleiro', $this->gettabuleiro(), PDO::PARAM_STR);
                return $stmt->execute();
        }

        public function excluir($id){
            $pdo = Conexao::getInstance();
            $stmt = $pdo->prepare('DELETE FROM quadrado WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function editar(){
            $pdo = Conexao::getInstance();
            $stmt = $pdo->prepare('UPDATE quadrado SET lado = :lado, cor = :cor, idtabuleiro = :idtabuleiro WHERE id = :id');
            $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':lado', $this->getLado(), PDO::PARAM_STR);
            $stmt->bindValue(':cor', $this->getCor(), PDO::PARAM_STR);
            $stmt->bindValue(':idtabuleiro', $this->gettabuleiro(), PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function listar($buscar = 0, $procurar = ""){
            $pdo = Conexao::getInstance();
            $sql = "SELECT * FROM quadrado";
            if ($buscar > 0)
                switch($buscar){
                    case(1): $sql .= " WHERE id like :procurar"; $procurar = "%".$procurar."%"; break;
                    case(2): $sql .= " WHERE lado like :procurar"; $procurar .="%"; break;
                    case(3): $sql .= " WHERE cor like :procurar"; $procurar = "%".$procurar."%"; break;
                    case(4): $sql .= " WHERE idtabuleiro like :procurar"; $procurar = "%".$procurar."%"; break;
                }
            $stmt = $pdo->prepare($sql);
            if ($buscar > 0)
                $stmt->bindValue(':procurar', $procurar, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        public function desenha(){
            $str = "<div style='width: ".$this->getlado()."vh; height: ".$this->getlado()."vh; background: ".$this->getcor().";border: 5px solid;'></div><br>";
            return $str;
        }

        public function select($rows="*", $where = null, $search = null, $order = null, $group = null) {
            $pdo = Conexao::getInstance();
            $sql= "SELECT $rows FROM quadrado";
            if($where != null) {
                $sql .= " WHERE $where";
                if($search != null) {
                    if(is_numeric($search) == false) {
                        $sql .= " LIKE '%". trim($search) ."%'";
                    } else if(is_numeric($search) == true) {
                        $sql .= " <= '". trim($search) ."'";
                    }
                }
            }
            if($order != null) {
                $sql .= " ORDER BY $order";
            }
            if($group != null) {
                $sql .= " GROUP BY $group";
            }
            $sql .= ";";
            return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>