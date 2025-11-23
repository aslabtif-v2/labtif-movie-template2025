    <?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'labtif_movie');

    class Database
    {
        protected $mysqli;
        protected $query;

        public function __construct()
        {
            $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($this->mysqli->connect_errno) {
                echo "Failed to connect to MySql " . $this->mysqli->connect_error;
            }
        }

        public function table($table)
        {
            $this->query = "SELECT * FROM $table";
            return $this;
        }

        public function where($arr = array())
        {
            $sql = ' WHERE ';

            if (count($arr) == 1) {
                foreach ($arr as $key => $value) {
                    $sql .= $key . ' = ' . $value;
                }
            } else {
                foreach ($arr as $key => $value) {
                    $sql .= $key . " = '" . $value . "' AND ";
                }
                $sql = substr($sql, 0, -5);
            }

            $this->query .= $sql;
            return $this;
        }

        public function get()
        {
            $result = $this->mysqli->query($this->query);

            return $result->fetch_all(MYSQLI_ASSOC);
            // echo $this->query;
        }

        public function insert($arr = array())
        {
            $this->query = str_replace('SELECT * FROM', 'INSERT INTO', $this->query);

            $columns = '';
            $vals = '';

            foreach ($arr as $key => $value) {
                $columns .= $key . ", ";
                $vals .= "'" . $value . "', ";
            }

            $this->query .= " (" . substr($columns, 0, -2) . ") VALUES (" . substr($vals, 0, -2) . ")";

            // echo $this->query;
            // prepare query
            $q = $this->mysqli->prepare($this->query) or die($this->mysqli->error);

            // eksekusi query
            $q->execute();
            // echo $this->query;
        }
    }

    // $db = new Database();

    // echo $datas = $db->table('movies')->where(['id' => '1'])->get() . "<br>";
    // echo $datas = $db->table('movies')->insert(['id' => '5']);
