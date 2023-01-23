<?php
defined('BASEPATH') or exit('No direct script access allowed');

class admin_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function simpanFormActivity($post)
    {
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
        );
        $query = $this->db->insert('tb_activity', $params);
        $id = $this->db->insert_id();
        $number = $this->db->query("SELECT [number] FROM tb_activity WHERE id = '$id'")->row()->number;
        $params2 = array(
            'number_activity' => $number,
            'remaks' => $post['remaks'],
            'image_name' => $post['image_name'],
            'content' => $post['content'],
        );

        if ($post['remaks'] != '') {
            $this->db->insert('tb_activity_detail', $params2);
        }
        return $query;
    }

    public function getDataActivity($number = null)
    {
        $sql = "  SELECT t1.*, t2.activity_name FROM tb_activity t1
        INNER JOIN master_activity t2 ON t1.activity = t2.id";
        if ($number != null) {
            $sql .= " WHERE number = '$number'";
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
            $this->db->where('activity_number', $number);
            $this->db->delete('tb_komentar');
        } else {
            echo "gagal hapus data";
        }
    }

    public function cekDataSudahTerhapus($number)
    {
        $tb_activity = $this->db->query("SELECT * FROM tb_activity WHERE [number] = '$number'");
        $tb_activity_detail = $this->db->query("SELECT * FROM tb_activity_detail WHERE number_activity ='$number'");
        $tb_komentar = $this->db->query("SELECT * FROM tb_komentar WHERE activity_number = '$number'");

        if ($tb_activity->num_rows() > 0) {
            return false;
        } else if ($tb_activity_detail->num_rows() > 0) {
            return false;
        } else if ($tb_komentar->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getCustomer()
    {
        $sql = "SELECT CardCode, CustomerName, GroupCode FROM [PK-ANP].[dbo].[m_customer]";
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
        $params = array(
            'content_id' => $post['id_content'],
            'activity_number' => $post['number_activity'],
            'isi_komentar' => $post['isi_komentar'],
            'id_comentator' => $_SESSION['username'],
            'is_read' => 'N',
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

    public function updateKomentarIsRead($number)
    {
        $sql = "UPDATE tb_komentar SET is_read = 'Y' WHERE activity_number = '$number'";
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
}
