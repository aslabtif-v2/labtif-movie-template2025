<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_labtif_movie');

class Database
{
    protected $mysqli;
    protected $query;

    function __construct()
    {
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->mysqli->connect_errno) {
            echo "Koneksi Gagal: " . $this->mysqli->connect_error;
        }
    }

    function table($table)
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

    function get()
    {
        $result = $this->mysqli->query($this->query);
        return $result->fetch_all(MYSQLI_ASSOC);
        // echo $this->query;
    }

    // SELECT * FROM movies
    // INSERT INTO movies (title, genre, description, cover) VALUES ('Burung hantu', 'horror', 'asdhfuahds', 'foto.jpg')

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
        // if (!$q) {
        //     die($this->mysqli->error);
        // }

        if ($q->execute()) {
            return true;
        }

        // $q->execute();
        // echo $this->query;
    }

    //UPDATE table SET column1 = "value1", column2 = "value2" WHERE condition;
    function update($arr = array()) {
        //karena pemanggilan functiononnya adalah ->table()->where()->update()
        //maka disini kita hanya perlu mengganti kata SELECT * FROM menjadi UPDATE
        $this->query = str_replace('SELECT * FROM', 'UPDATE', $this->query);

        //memisahkan query menjadi 2 bagian berdasarkan kata WHERE
        $part = explode(' WHERE ', $this->query); 

        $val = '';
        foreach ($arr as $key => $value) {
            //column1 = 'value1', colum2 = 'value2', ......
            $val .= $key . " = '" . $value . "', "; 
        }

        //merangkai kembali query UPDATE
        $this->query = $part[0] . " SET " . substr($val, 0, -2) . " WHERE ". $part[1];

        //menyiapkan query
        $q = $this->mysqli->prepare($this->query) or die($this->mysqli->error);
        //eksekusi query
        $q->execute();

        // echo $this->query . "<br>";
        // echo $part[0] . "<br>";
        // echo $part[1] . "<br>";
        // echo $val . "<br>";



    }
}

// $db = new Database();
// echo $db->table('movies')->get();
// $db->table('movies')->insert([
//     'title' => 'adsf',
//     'genre' => 'asdfdsf',
//     'description' => 'asdfsdf',
//     'cover' => 'adsfsfs'
// ]);

// $db->table('movies')->where(['id' => '1'])->update(['title' => 'Contoh', 'genre' => 'Contoh Genre']);
