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
        $sql = "INSERT INTO news(news_title,news_user_id) VALUES('Nouvelle news, titre ici',2);";
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
              news_title,
              news_title_contains,
              news_img,
              news_contains,
              news_user_id,
              news_difuse,
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
        $sql = "UPDATE news SET
          news_difuse = '".(($_POST['news_difuse']==false || $_POST['news_difuse']=="false")?0:1)."'
          ,news_update = current_date
          ,news_title = '".addslashes($_POST['news_title'])."'
          ,news_title_contains = '".addslashes($_POST['news_title_contains'])."'
          ,news_contains = '".addslashes($_POST['news_contains'])."'
          ".(($img)?",news_img = 'http://api.fadeaway.fr/images/news_".$_POST['news_id'].".jpeg'":"")."
          WHERE news_id = ".$_POST['news_id']."
        ";
        $this->db->query($sql);

        $test = new \Html2Text\Html2Text();
        echo "/**** $sql ****/";
        echo "/****".$test->convert($_POST['news_contains'])."***/";
        die('test');

        return true;
    }

}