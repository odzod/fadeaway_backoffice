<?php
session_start();

if ($_REQUEST['TOKEN'] == 'ptrn4883') {

    include_once('../include/config.include.php');

    $link = mysqli_connect(mysql_host, mysql_user, mysql_password);
    mysqli_select_db($link, mysql_db);

    $res = mysqli_query($link, "SELECT config_val FROM config WHERE config_var = 'db_version'");
    $num_version = 0;

    if (!$res) {
        $query = file_get_contents("0_base_version.sql");
        mysqli_query($link, $query);
    } else {
        $row = mysqli_fetch_array();
        $num_version = $row[0];
    }

    $dir = './';
    $_files = scandir($dir);

    $breaker = false;

    foreach ($_files as $file) {
        if (!in_array($file, array(".", "..", "update_base.php")) and $breaker = false) {
            $_name = explode("_", $file);
            if (intval($_name[0] > intval($num_version))) {
                $query = file_get_contents("./" . $file);
                $query .= "UPDATE config SET config_val ='" . $_name[0] . "' WHERE config_var = 'db_version';";
                $res = mysqli_query($link, $query);
                $num_version = $_name[0];
                if (!$res) {
                    $breaker = true;
                    break;
                }
            }
        }
    }

    if ($breaker) {
        echo "Attention une erreur est survenue lors de la mise à jour de la base de données lors de la maj $num_version";
    } else {
        echo "Mise à jour effectuée";
    }


} else {
    echo "Vous n'avez pas accès à cette commande";
}





