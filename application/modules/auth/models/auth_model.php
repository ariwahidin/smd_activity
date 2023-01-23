<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class auth_model extends CI_Model {

    public function cek_data_user($post){
        $username = $post['username'];
        $password = $post['password'];
        // $sql = "SELECT * FROM [SALES_VISIT].[sales_visit].[config].[master_user] where username = '$username' and [password] = '$password'";
        $sql = "SELECT * FROM [PK_ACTIVITY].[dbo].[master_user] where username = '$username' and [password] = '$password'";
        $query = $this->db->query($sql);
        return $query;
    }

}