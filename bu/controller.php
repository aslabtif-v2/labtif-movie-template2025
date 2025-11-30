<?php

require_once '../database.php';

class Movie
{
    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function getAllMovies()
    {
        return $this->db->table('movies')->get();
    }

    function getMovieById($id)
    {
        return $this->db->table('movies')->where(['id' => $id])->get();
    }

    function addMovie($data)
    {
        return $this->db->table('movies')->insert($data);
    }

    function updateMovie($id, $data)
    {
        return $this->db->table('movies')->where(['id' => $id])->update($data);
    }
}