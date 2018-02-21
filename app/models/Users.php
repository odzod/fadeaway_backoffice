<?php
/**
 * Created by PhpStorm.
 * User: ptrn4883
 * Date: 20/02/2018
 * Time: 14:12
 */

class Users extends Models
{


    function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->table = "users";
        $this->col_id = "user_id";
    }


}