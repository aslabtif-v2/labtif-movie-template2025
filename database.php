<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_labtif_movie');

class Database
{
    // mysqli connection
    protected $mysqli;
    protected $query;

    function __construct()
    {
        // mengkoneksikan ke database
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->mysqli->connect_errno) {
            echo "Koneksi Gagal: " . $this->mysqli->connect_error;
        }
    }

    // SELECT * FROM table
    function table($table)
    {
        // membuat query dasar untuk memilih semua data dari tabel
        $this->query = "SELECT * FROM $table";
        return $this;
    }

    // WHERE column = value AND column2 = value2 ...
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

    // execute query and get results
    //untuk mengambil data dari database
    function get()
    {
        $result = $this->mysqli->query($this->query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //INSERT INTO table (column1, column2, ...) VALUES (value1, value2, ...)
    public function insert($arr = array())
    {
        //karena pemanggilan functiononnya adalah ->table()->insert()
        //maka disini kita hanya perlu mengganti kata SELECT * FROM menjadi INSERT INTO
        $this->query = str_replace('SELECT * FROM', 'INSERT INTO', $this->query);

        $columns = '';
        $vals = '';

        //memisahkan kolom dan nilainya
        foreach ($arr as $key => $value) {
            $columns .= $key . ", ";
            $vals .= "'" . $value . "', ";
        }

        //merangkai kembali query INSERT
        $this->query .= " (" . substr($columns, 0, -2) . ") VALUES (" . substr($vals, 0, -2) . ")";
        $q = $this->mysqli->prepare($this->query) or die($this->mysqli->error);

        // execute query
        if ($q->execute()) {
            return true;
        }
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
    }

    //DELETE FROM table WHERE ....
    function delete() {
        //karena pemanggilan functiononnya adalah ->table()->where()->delete()
        //maka disini kita hanya perlu mengganti kata SELECT * FROM menjadi DELETE
        $this->query = str_replace('SELECT *', 'DELETE', $this->query);
        $q = $this->mysqli->prepare($this->query) or die($this->mysqli->error);
        $q->execute();
    }

}
