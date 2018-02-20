<?php

function update_db()
{

    $_msg = array();

    $db = getDb();

    if(!$db){
        $_msg[] = "Erreur lors de la connexion a la bdd";
    }

    $res = $db->query("SELECT config_val FROM config WHERE config_var = 'db_version'");
    $num_version = 0;

    if (!$res) {
        $query = file_get_contents("0_base_version.sql");
        $res = $db->query($query);
        if(!$res){
            $_msg[]= 'Erreur lors de l init';
        }
        $_msg[] = "Init version 0<br/>";
    } else {
        $row = $res->fetchAll();
        $num_version = $row[0][0];
        $_msg[] = "Version actuelle : $num_version <br/>";
    }

    $dir = __DIR__ . "/../sql";
    $_files = scandir($dir);

    $breaker = false;

    foreach ($_files as $file) {
        if (!in_array($file, array(".", "..", "dbupdate.php")) && $breaker == false) {
            $_name = explode("_", $file);
            if (intval($_name[0] > intval($num_version))) {
                $query = file_get_contents("./" . $file);
                $query .= "UPDATE config SET config_val ='" . $_name[0] . "' WHERE config_var = 'db_version';";
                $res = $db->query($query);
                $num_version = $_name[0];
                if (!$res) {
                    $breaker = true;
                    break;
                }
                $_msg[] = "Maj vers version $num_version <br/>";
            }
        }
    }

    if ($breaker) {
        $_msg[] = "Attention une erreur est survenue lors de la mise à jour de la base de données lors de la maj $num_version";
    } else {
        $_msg[] = "Mise à jour effectuée";
    }

    return $_msg;

}





