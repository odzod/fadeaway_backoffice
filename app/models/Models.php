<?php
/**
 * Created by PhpStorm.
 * User: ptrn4883
 * Date: 20/02/2018
 * Time: 13:35
 */

class Models
{

    public $db;
    public $table;
    public $col_id;

    public $id;
    public $data;

    function __construct(PDO $db)
    {
        $this->db = $db;
    }

    function loadDataById($id)
    {
        $query = "
            SELECT * FROM " . $this->table . " WHERE " . $this->col_id . " = $id
        ";
        $res = $this->db->query($query)->fetchAll();
        $this->data = $res[0];
        $this->id = $id;
    }

    public function getData()
    {
        return $this->data;
    }


}