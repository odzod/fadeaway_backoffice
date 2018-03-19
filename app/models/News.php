<?php
/**
 * Created by PhpStorm.
 * User: ptrn4883
 * Date: 20/02/2018
 * Time: 13:36
 */

class News extends Models
{

    public $item_per_page = 10;

    function __construct($db)
    {
        parent::__construct($db);
        $this->table = "news";
        $this->col_id = "news_id";
    }

    public function addNews()
    {
        $sql = "INSERT INTO news(
          news_title
          ,news_type
          ,news_user_id
          ,news_img
          )
          VALUES('Nouvelle news, titre ici'
          ,1
          ,2
          ,'http://api.fadeaway.fr/images/no_image.jpeg'
        );";
        $this->db->query($sql);
        $id = $this->db->lastInsertId();
        return $id;
    }

    public function getAdminNewsList($page = 0)
    {
        $limit_max = intval($page) + 1 * ($this->item_per_page);
        $limit_min = $limit_max - ($this->item_per_page);
        $query = "
            SELECT 
              news_id,
              news_type,
              nt_type as news_type_str,
              news_difuse,
              news_title,
              news_title_contains,
              news_img,
              news_contains,
              news_user_id,
              date_format(news_create,'%d/%m/%Y') as news_create,
              date_format(news_update,'%d/%m/%Y') as news_update 
            FROM news
            left join news_type on nt_id = news_type ORDER BY news_id DESC limit $limit_min , $limit_max
        ";
        $res = $this->db->query($query)->fetchAll();
        $data = array();
        foreach ($res as $news) {
            $user = new Users($this->db);
            $user->loadDataById($news['news_user_id']);
            $_userData = $user->getData();
            unset($news['news_user_id']);
            $news['news_user_nom'] = $_userData['user_nom'][0] . ".";
            $news['news_user_prenom'] = $_userData['user_prenom'];
            $data[] = $news;
        }
        return $data;
    }


    public function getLastNews($page = 0)
    {
        $limit_max = intval($page) + 1 * ($this->item_per_page);
        $limit_min = $limit_max - ($this->item_per_page);
        $query = "
            SELECT 
              news_id,
              nt_type as news_type,
              news_title,
              news_title_contains,
              news_img,
              news_contains,
              news_user_id,
              date_format(news_create,'%d/%m/%Y') as news_create,
              date_format(news_update,'%d/%m/%Y') as news_update 
            FROM news
            left join news_type on nt_id = news_type  WHERE news_difuse = 1 ORDER BY news_id DESC limit $limit_min , $limit_max
        ";
        $res = $this->db->query($query)->fetchAll();
        $data = array();
        foreach ($res as $news) {
            $user = new Users($this->db);
            $user->loadDataById($news['news_user_id']);
            $_userData = $user->getData();
            unset($news['news_user_id']);
            $news['news_user_nom'] = $_userData['user_nom'][0] . ".";
            $news['news_user_prenom'] = $_userData['user_prenom'];
            $data[] = $news;
        }
        return $data;
    }

    public function getNews($idNews)
    {
        $query = "
            SELECT 
              news_id,
              nt_type as news_type,
              nt_id as news_type_val,
              news_title,
              news_title_contains,
              news_img,
              news_contains,
              news_user_id,
              news_difuse,
              news_facebook,
              date_format(news_create,'%d/%m/%Y') as news_create,
              date_format(news_update,'%d/%m/%Y') as news_update 
            FROM news
             left join news_type on nt_id = news_type 
            WHERE news_id = $idNews
        ";
        $res = $this->db->query($query)->fetchAll();
        $data = array();
        foreach ($res as $news) {
            $user = new Users($this->db);
            $user->loadDataById($news['news_user_id']);
            $_userData = $user->getData();
            unset($news['news_user_id']);
            $news['news_user_nom'] = $_userData['user_nom'][0] . ".";
            $news['news_user_prenom'] = $_userData['user_prenom'];
            $data[] = $news;
        }
        return $data;
    }

    public function updateNews()
    {
        $img = false;
        if (isset($_FILES['uploadFile'])) {
            $img = true;
            $tmp_name = $_FILES["uploadFile"]["tmp_name"];
            $name = basename($_FILES["uploadFile"]["name"]);
            move_uploaded_file($tmp_name, __DIR__ . "/../../public/images/" . $name);
            redesign_image("http://api.fadeaway.fr/images/" . $name, "news_" . $_POST['news_id']);
        }


        if (stristr($_POST['news_contains'], 'iframe') === FALSE) {
            //nrf
        } else {
            $tmp = explode("<iframe", $_POST['news_contains']);
            $tmp2 = explode("</iframe>", $tmp[1]);
            $tmp3 = explode("src=\"", $tmp[1]);
            $tmp4 = explode("\" ", $tmp3[1]);
            $_POST['news_contains'] = $tmp[0] . "<iframe class=\"youtubeiframe\" src=\"" . $tmp4[0] . "\" frameborder=\"0\" allowfullscreen=\"\"></iframe>" . $tmp2[1];

        }

        if (isset($_POST['news_type']) and !empty($_POST['news_type'])) {
            $nt = ",news_type = '" . $_POST['news_type'] . "'";
        } else {
            $nt = "";
        }

        $difuse = (($_POST['news_difuse'] == false || $_POST['news_difuse'] == "false") ? 0 : 1);
        $sql = "UPDATE news SET
          news_difuse = '" . $difuse . "'
          ,news_update = now()
          " . $nt . "
          ,news_title = '" . addslashes($_POST['news_title']) . "'
          ,news_title_contains = '" . addslashes($_POST['news_title_contains']) . "'
          ,news_contains = '" . addslashes($_POST['news_contains']) . "'
          " . (($img) ? ",news_img = 'http://api.fadeaway.fr/images/news_" . $_POST['news_id'] . ".jpeg'" : "") . "
          WHERE news_id = " . $_POST['news_id'] . "
        ";
        $this->db->query($sql);

        $_data = $this->db->query("SELECT * FROM news WHERE news_id = " . $_POST['news_id'])->fetchAll();
        $data = $_data[0];

        if ($difuse == 1
            and ($data['news_facebook'] == "" or empty($data['news_facebook']))) {
            $test = new \Html2Text\Html2Text();
            $facebook0 = $test->convert($_POST['news_title']);
            $facebook1 = $test->convert($_POST['news_title_contains']);
            $facebook2 = $test->convert($_POST['news_contains']);
            $this->addToFacebook($_POST['news_id'], $facebook0 . "\n\n" . /*$facebook1 . "\n\n" . $facebook2 . */
                "", $data['news_img']);
        }

        return true;
    }

    public function addToFacebook($id, $msg, $img)
    {

        require_once __DIR__ . '/../../vendor/Facebook/autoload.php';

        $token_page = "EAAcQrm4dKVMBAFqFoWTAv2uEmuZArGcFQRrRJvdrnEaZCZCbSrKEpjJxATr1rZC4tL3GAPSeu2MmUmTzkEPYIGOLNEdkOfLzmWz42ns7t5Nwb7PJytDAIm15tZACKGhnKkpM9EawpJnZCKxfY5DBpntcdff3biAeFdOpPDYDWLKA1n9CeBXhZCW14UmyYudxOIWCad3tx3fGQZDZD";
        $token_pubish = "EAAcQrm4dKVMBAPO9KuE19tE7g7LBUaGpe5OEMc5qKRR4279UKxuHBwo9uuzL2hi5zZCUVlOMt8ZAzqzh7boGe5k6xqp4zP3qdKsEWRPe8uVZBFueaQZAXHnesZCNhRButGWa47kPPZA6eZAj4QWr1BwOWQd6ZBCPM4f7vZC0yJlRj9kfJfqjafa74d9VsSZAQv8meAUFZCNdLkIrAZDZD";
        $token_long = "EAAcQrm4dKVMBAEZAAszzChZBUkK6gLYhTZB7B5l7FAV4hfCPIY9HF1D3jgwcD5hZCsKCfdsl2ZA96E4fd45TFbfPU7EVoaPZALJNQZAZBjgG602EgDgL81CXumJfqBFC5Qun7vCoVrZCX64CdzZCAt6cfKO6kw7DiheBenitVhEmt7rAZDZD";

        $fb = new \Facebook\Facebook([
            'app_id' => '1988666194733395',
            'app_secret' => '8780450b687f8dfa978d15935d69c2ce',
            'default_graph_version' => 'v2.12',
            'default_access_token' => $token_long, // optional
        ]);


        $message = $msg;
        $link = "http://fb.fadeaway.fr/index.php?news=" . $id;
        $response = $fb->post('/feed', array("message" => $message, "link" => $link));
        $sql = "UPDATE news SET
          news_facebook = 'ok'
          WHERE news_id = " . $id . "
        ";
        $this->db->query($sql);


    }

}