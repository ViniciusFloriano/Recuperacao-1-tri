<?php
    include_once 'conf/Conexao.php';
    require_once 'conf/conf.inc.php';
    class Tabuleiro{
        private $idtabuleiro;
        private $lado;

        public function __construct($idtabuleiro, $lado) {
            $this->settabuleiro($idtabuleiro);
            $this->setlado($lado);
        }

        public function gettabuleiro() {
            return $this->idtabuleiro;
        }

        public function settabuleiro($idtabuleiro) {
            if ($idtabuleiro >  0)
                $this->idtabuleiro = $idtabuleiro;
        }     
        
        public function getlado() {
            return $this->lado;
        }

        public function setlado($lado) {
            if ($lado >  0)
                $this->lado = $lado;
        }

        public function __toString() {
            return  "[Tabuleiro]<br>Id do Tabuleiro: ".$this->gettabuleiro()."<br>".
                    "Lado: ".$this->getlado()."<br>".
                    "Area: ".$this->Area()."<br>".
                    "Perimetro: ".$this->Perimetro()."<br>".
                    "Diagonal: ".$this->Diagonal()."<br>";
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
                $stmt = $pdo->prepare('INSERT INTO recuperacao.tabuleiro (lado) VALUES (:lado)');
                $stmt->bindParam(':lado', $this->getlado(), PDO::PARAM_STR);
                return $stmt->execute();
        }

        public function excluir($idtabuleiro){
            $pdo = Conexao::getInstance();
            $stmt = $pdo->prepare('DELETE FROM tabuleiro WHERE idtabuleiro = :idtabuleiro');
            $stmt->bindParam(':idtabuleiro', $idtabuleiro, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function editar(){
            $pdo = Conexao::getInstance();
            $stmt = $pdo->prepare('UPDATE tabuleiro SET lado = :lado WHERE idtabuleiro = :idtabuleiro');
            $stmt->bindValue(':idtabuleiro', $this->gettabuleiro(), PDO::PARAM_INT);
            $stmt->bindValue(':lado', $this->getLado(), PDO::PARAM_STR);
            return $stmt->execute();
        }

        public function listar($buscar = 0, $procurar = ""){
            $pdo = Conexao::getInstance();
            $sql = "SELECT * FROM tabuleiro";
            if ($buscar > 0)
                switch($buscar){
                    case(1): $sql .= " WHERE idtabuleiro like :procurar"; $procurar = "%".$procurar."%"; break;
                    case(2): $sql .= " WHERE lado like :procurar"; $procurar = "%".$procurar."%"; break;
                }
            $stmt = $pdo->prepare($sql);
            if ($buscar > 0)
                $stmt->bindValue(':procurar', $procurar, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function desenha(){
            $str = "<div style='width: ".$this->getlado()."vh;height: ".$this->getlado()."vh;border: 5px solid;'></div><br>";
            return $str;
        }

        public function select($rows="*", $where = null, $search = null, $order = null, $group = null) {
            $pdo = Conexao::getInstance();
            $sql= "SELECT $rows FROM tabuleiro";
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