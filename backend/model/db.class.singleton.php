<?
    class DB {
        private $server;
        private $user;
        private $password;
        private $database;
        private $link;
        private $stmt;
        private $array;
        static $_instance;

        private function __construct() {
            $this->setConexion();
            $this->conectar();
        }

        private function setConexion() {
            require_once 'Conf.class.singleton.php';
            $conf = Conf::getInstance();

            $this->server = $conf->_hostdb;
            $this->database = $conf->_db;
            $this->user = $conf->_userdb;
            $this->password = $conf->_passdb;
        }

        private function __clone() {
        }

        /**
         * create an instance of the classe only if it doesn't exists
         *
         * @return self
         */
        public static function getInstance() {
            if (!(self::$_instance instanceof self))
                self::$_instance = new self();
            return self::$_instance;
        }

        /**
         * create the connection with the db
         *
         * @return void
         */
        private function conectar() {
            $this->link = new mysqli($this->server, $this->user, $this->password);
            $this->link->select_db($this->database);
        }

        /**
         * execute a query
         *
         * @param string $sql
         * @return array
         */
        public function ejecutar($sql) {
            $this->stmt = $this->link->query($sql);
            return $this->stmt;
        }

        // public function listar($stmt) {
        //     $this->array = array();
        //     while ($row = $stmt->fetch_array(MYSQLI_ASSOC)) {
        //         array_push($this->array, $row);
        //     }
        //     return $this->array;
        // }

    }
    