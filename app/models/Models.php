<?php
/**
 * Created by PhpStorm.
 * User: ptrn4883
 * Date: 20/02/2018
 * Time: 13:35
 */

class Models_Models
{

    public $db;

    function __construct(PDO $db)
    {
        $this->db = $db;
    }

}