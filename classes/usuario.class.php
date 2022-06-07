<?php
    include_once 'conf/Conexao.php';
    require_once 'conf/conf.inc.php';
    class Usuario{
        private $idusuario;
        private $nome;
        private $login;
        private $senha;

        public function __construct($idusuario, $nome, $login, $senha) {
            $this->setidusuario($idusuario);
            $this->setnome($nome);
            $this->setlogin($login);
            $this->setsenha($senha);
        }

        public function getidusuario(){ 
            return $this->ididusuario; 
        }

        public function setidusuario($ididusuario){ 
            $this->ididusuario = $ididusuario;
        }      
        
        public function getnome() {
            return $this->nome;
        }

        public function setnome($nome) {
            if (strlen($nome) > 0)
                $this->nome = $nome;
        }

        public function getlogin() {
            return $this->login;
        }

        public function setlogin($login) {
            if (strlen($login) > 0)    
                $this->login = $login;
        }

        public function getsenha() {
            return $this->senha;
        }

        public function setsenha($senha) {
            if (strlen($senha) > 0)
                $this->senha = $senha;
        }

        public function __toString() {
            return  "[Usuário]<br>Id do Usuário: ".$this->getidusuario()."<br>".
                    "nome: ".$this->getnome()."<br>".
                    "login: ".$this->getlogin()."<br>".
                    "senha: ".$this->getsenha()."<br>";
        }

        public function inserir(){
            $pdo = Conexao::getInstance();
            $stmt = $pdo->prepare('INSERT INTO recuperacao.usuario (nome, login, senha) VALUES (:nome, :login, :senha)');
            $stmt->bindParam(':nome', $this->getnome(), PDO::PARAM_STR);
            $stmt->bindParam(':login', $this->getlogin(), PDO::PARAM_STR);
            $stmt->bindParam(':senha', $this->getsenha(), PDO::PARAM_STR);
            return $stmt->execute();
        }

        public function excluir($idusuario){
            $pdo = Conexao::getInstance();
            $stmt = $pdo->prepare('DELETE FROM usuario WHERE idusuario = :idusuario');
            $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function editar(){
            $pdo = Conexao::getInstance();
            $stmt = $pdo->prepare('UPDATE usuario SET nome = :nome, login = :login, senha = :senha WHERE idusuario = :idusuario');
            $stmt->bindValue(':idusuario', $this->getidusuario(), PDO::PARAM_INT);
            $stmt->bindValue(':nome', $this->getnome(), PDO::PARAM_STR);
            $stmt->bindValue(':login', $this->getlogin(), PDO::PARAM_STR);
            $stmt->bindValue(':senha', $this->getsenha(), PDO::PARAM_STR);
            return $stmt->execute();
        }

        public function listar($buscar = 0, $procurar = ""){
            $pdo = Conexao::getInstance();
            $sql = "SELECT * FROM usuario";
            if ($buscar > 0)
                switch($buscar){
                    case(1): $sql .= " WHERE idusuario like :procurar"; $procurar = "%".$procurar."%"; break;
                    case(2): $sql .= " WHERE nome like :procurar"; $procurar = "%".$procurar."%"; break;
                }
            $stmt = $pdo->prepare($sql);
            if ($buscar > 0)
                $stmt->bindValue(':procurar', $procurar, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function select($rows="*", $where = null, $search = null, $order = null, $group = null) {
            $sql= "SELECT $rows FROM usuario";
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
            $pdo = Conexao::getInstance();
            return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        public function efetuarLogin($login, $senha) {
            $pdo = Conexao::getInstance();
            $verificacao = $this->select('nome', "login = '$login' AND senha = '$senha'");
            if($verificacao){
                $_SESSION["nome"] = $verificacao[0]['nome'];
                return true;
            }else{
                return false;
            }
        }
    }
?>