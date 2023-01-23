<?php
defined('BASEPATH') or exit('No direct script access allowed');

class sales_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function simpanFormActivity($post)
    {
        // var_dump($post);
        // die;
        $params = array(
            'number' => $this->getNumber(),
            'activity' => $post['activity'],
            'start_periode' => $post['start_periode'],
            'end_periode' => $post['end_periode'],
            'brand_code' => $post['brand'],
            'assign' => $post['assign'],
            'customer_code' => $post['customer'],
            'no_ref' => $post['no_ref'],
            'priority' => $post['priority'],
            'status' => $post['status'],
            'CreatedBy' => $_SESSION['username'],
            'ClosedTime' => $post['status'] == 'close' ? date("Y-m-d H:i:s") : null,
            'ClosedBy' => $post['status'] == 'close' ? $_SESSION['username'] : null,
            'is_read' => 'N',
        );
        $query = $this->db->insert('tb_activity', $params);
        $id = $this->db->insert_id();
        $number = $this->db->query("SELECT [number] FROM tb_activity WHERE id = '$id'")->row()->number;
        $params2 = array(
            'number_activity' => $number,
            'remaks' => $post['remaks'],
            'image_name' => $post['image_name'],
            'content' => $post['content'],
            'CreatedBy' => $_SESSION['username'],
        );

        if ($post['remaks'] != '') {
            $this->db->insert('tb_activity_detail', $params2);
        }
        return $number;
    }


    public function follow_up($post)
    {
        var_dump($post);
        $params = array(
            'number_activity' => $post['activity_number'],
            'remaks' => $post['remaks'],
            'image_name' => $post['image_name'],
            'content' => $post['content'],
            'CreatedBy' => $_SESSION['username'],
        );
        $this->db->insert('tb_activity_detail', $params);
    }

    public function getDataActivity($number = null)
    {
        $username = $_SESSION['username'];
        $sql = "SELECT t1.*, t2.activity_name FROM tb_activity t1
        INNER JOIN master_activity t2 ON t1.activity = t2.id
        WHERE t1.CreatedBy IN(SELECT '$username' UNION SELECT spv FROM [pk_activity].[dbo].[master_user] WHERE username = '$username' )
        AND t1.assign IN(SELECT '$username' UNION SELECT spv FROM [pk_activity].[dbo].[master_user] WHERE username = '$username' )";
        if ($number != null) {
            $sql .= " AND t1.number = '$number'";
        }
        $sql .= " ORDER BY id DESC";
        $query = $this->db->query($sql);
        return $query;
    }

    public function getDataActivityDetail($number)
    {
        $sql = "SELECT * FROM tb_activity_detail WHERE number_activity = '$number' ORDER BY content_id ASC";
        $query = $this->db->query($sql);
        return $query;
    }

    public function updateActivity($post)
    {
        $params = array(
            'number' => $post['number'],
            'start_periode' => $post['start_periode'],
            'end_periode' => $post['end_periode'],
            'assign' => $post['assign'],
            'no_ref' => $post['no_ref'],
            'status' => $post['status'],
            'UpdateTime' => date("Y-m-d H:i:s"),
            'UpdatedBy' => $_SESSION['username'],
            'ClosedTime' => $post['status'] == 'close' ? date("Y-m-d H:i:s") : null,
            'ClosedBy' => $post['status'] == 'close' ? $_SESSION['username'] : null,
        );

        $params2 = array(
            'number_activity' => $post['number'],
            'remaks' => $post['remaks'],
            'image_name' => $post['image_name'],
            'content' => $post['content'],
            'UpdateTime' => date("Y-m-d H:i:s"),
        );
        $this->db->where('number', $post['number']);
        $this->db->update('tb_activity', $params);

        if ($post['remaks'] != '') {
            $this->db->insert('tb_activity_detail', $params2);
        }
    }

    public function deleteDataActivity($number)
    {
        $image_name = $this->db->query("SELECT image_name FROM tb_activity_detail WHERE number_activity = '$number'");

        foreach ($image_name->result() as $data) {
            $image = explode(',', $data->image_name);
            foreach ($image as $image_delete) {
                $image_path = realpath(APPPATH . '../uploads/image_activity/') . '/' . $image_delete;
                if ($image_delete != '') {
                    $delete_image = unlink($image_path);
                } else {
                    $delete_image = true;
                }
            }
        };

        if ($delete_image == true) {
            $this->db->where('number', $number);
            $this->db->delete('tb_activity');
            $this->db->where('number_activity', $number);
            $this->db->delete('tb_activity_detail');
        } else {
            echo "gagal hapus data";
        }
    }

    public function getCustomer()
    {
        $sql = "SELECT CardCode, CustomerName, GroupCode FROM master_customer";
        $query = $this->db->query($sql);
        return $query;
    }

    public function getNumber()
    {
        $sql = "SELECT FORMAT(MAX(SUBSTRING(number,5,8))+1, 'd4') as number from tb_activity";
        $query = $this->db->query($sql);
        $number = $query->row()->number;
        if ($number == null) {
            $number = '0001';
        }
        $sql2 = "SELECT concat(cast(year(getdate()) AS varchar),'$number') as num";
        $query2 = $this->db->query($sql2);
        $number = $query2->row()->num;
        return $number;
    }

    public function getActivity()
    {
        $sql = "SELECT * FROM master_activity";
        $query = $this->db->query($sql);
        return $query;
    }

    public function getBrand()
    {
        $sql = "SELECT BrandCode, BrandName FROM [PK-ANP].[dbo].[m_brand]";
        $query = $this->db->query($sql);
        return $query;
    }

    public function kirim_komentar($post)
    {
        $activity_number = $post['number_activity'];
        $params = array(
            'content_id' => $post['id_content'],
            'activity_number' => $post['number_activity'],
            'isi_komentar' => $post['isi_komentar'],
            'id_comentator' => $_SESSION['username'],
            'is_read' => 'N',
            'created_by' => $_SESSION['username'],
            'activity_created_by' => $this->db->query("SELECT DISTINCT CreatedBy FROM tb_activity WHERE [number] = '$activity_number'")->row()->CreatedBy,
            'activity_assign' => $this->db->query("SELECT DISTINCT assign FROM tb_activity WHERE [number] = '$activity_number'")->row()->assign,
            'sender' => $_SESSION['username'],
            'receiver' => $this->db->query("SELECT DISTINCT assign FROM tb_activity WHERE [number] = '$activity_number'")->row()->assign,
        );
        $query = $this->db->insert('tb_komentar', $params);
        return $query;
    }

    public function cekKomentarBelumDibaca($number)
    {
        $sql = "SELECT * FROM tb_komentar WHERE activity_number = '$number' AND is_read = 'N'";
        $query = $this->db->query($sql);
        return $query;
    }

    public function cekNotifBaruBelumDibaca($number)
    {
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM tb_activity WHERE is_read='N' AND [number] = '$number' AND assign = '$username'";
        $query = $this->db->query($sql);
        return $query;
    }

    public function updateNotifSudahDibaca($number)
    {
        $username = $_SESSION['username'];
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE tb_activity SET is_read = 'Y', read_date = '$date', read_by = '$username'
        WHERE is_read = 'N' AND assign = '$username' AND [number] = '$number'";
        $query = $this->db->query($sql);
        return $query;
    }

    public function updateKomentarIsRead($number)
    {
        $date = date("Y-m-d H:i:s");
        $komentator = $_SESSION['username'];
        $sql = "UPDATE tb_komentar SET is_read = 'Y', 
        read_by = '$komentator', read_time = '$date' WHERE activity_number = '$number' AND created_by != '$komentator'";
        $query = $this->db->query($sql);
        return $query;
    }

    public function getSpv()
    {
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM [PK_ACTIVITY].[dbo].[master_user] WHERE username =  '$username' ";
        $query = $this->db->query($sql);
        return $query;
    }

    public function closeActivity($number)
    {
        $date = date('Y-m-d h:i:s');
        $username = $_SESSION['username'];
        $sql = "UPDATE tb_activity SET [status] = 'close', closedTime = '$date', closedBy = '$username' WHERE [number] = '$number'";
        $query = $this->db->query($sql);
        return $query;
    }
}
