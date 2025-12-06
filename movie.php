<?php

require_once 'database.php';

class Movie
{
    protected $db;

    //konstruktor untuk membuat koneksi database
    function __construct()
    {
        $this->db = new Database();
    }

    //mengambil semua data movie
    //->table = SELECT * FROM movies 
    //->get = eksekusi query dan ambil hasilnya
    function getAllMovies()
    {
        return $this->db->table('movies')->get();
    }

    //mengambil data movie berdasarkan id
    //->table = SELECT * FROM movies
    //->where = WHERE id = $id
    //->get = eksekusi query dan ambil hasilnya
    function getMovieById($id)
    {
        return $this->db->table('movies')->where(['id' => $id])->get();
    }

    //menambahkan data movie
    //->table = SELECT * FROM movies
    //->insert = INSERT INTO movies (column1, column2, ...) VALUES (value1, value2, ...)
    function addMovie($data)
    {
        return $this->db->table('movies')->insert($data);
    }

    //mengupdate data movie berdasarkan id
    //->table = SELECT * FROM movies
    //->where = WHERE id = $id
    //->update = UPDATE movies SET column1 = value1, column2 = value2, ... WHERE id = $id
    function updateMovie($id, $data)
    {
        return $this->db->table('movies')->where(['id' => $id])->update($data);
    }

    //menghapus data movie berdasarkan id
    //->table = SELECT * FROM movies
    //->where = WHERE id = $id
    //->delete = DELETE FROM movies WHERE id = $id
    function deleteMovie($id) {
        return $this->db->table('movies')->where(['id' => $id])->delete();
    }
   
}
