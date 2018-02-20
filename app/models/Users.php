<?php
/**
 * Created by PhpStorm.
 * User: ptrn4883
 * Date: 20/02/2018
 * Time: 14:12
 */

class Models_Users extends Models_Models
{

    public $id;
    public $data;

    function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function loadDataFromId($id){
        $query = "
            SELECT * FROM users WHERE user_id = $id
        ";
        $res = $this->db->query($query)->fetchAll();
        $this->data = $res[0];
        $this->id = $id;
    }

    public function getData(){
        return $this->data;
    }

}