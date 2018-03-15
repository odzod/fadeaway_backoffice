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
        $sql = "INSERT INTO news(news_title,news_user_id) values('Nouvelle news, titre ici',2);";
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
              news_difuse
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

}