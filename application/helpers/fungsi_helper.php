<?php

function cek_login()
{
    if (!isset($_SESSION['username']) || !isset($_SESSION['fullname'])) {
        redirect(base_url('activity/auth/login'));
    }
}


function BrandName($brand_code)
{
    $CI = &get_instance();
    $sql = "SELECT BrandName FROM [PK-ANP].[dbo].[m_brand] WHERE BrandCode = '$brand_code'";
    $query = $CI->db->query($sql);
    return $query->row()->BrandName;
}

function getComentar($number, $id_content)
{
    $CI = &get_instance();
    $sql = "SELECT * FROM tb_komentar WHERE activity_number = '$number' AND content_id = '$id_content' ORDER BY create_date ASC";
    $query = $CI->db->query($sql);
    return $query;
}

function cekKomentarBelumDibacaSales()
{
    $CI = &get_instance();
    $username = $_SESSION['username'];
    $sql = "SELECT t1.* FROM tb_komentar t1
    INNER JOIN tb_activity t2 ON t1.activity_number = t2.number
    WHERE t1.is_read = 'N'
    AND sender != '$username'
    AND receiver IN (SELECT '$username' UNION SELECT spv FROM [PK_ACTIVITY].[dbo].[master_user] WHERE username = '$username')
    AND activity_created_by IN (SELECT '$username' UNION SELECT spv FROM [PK_ACTIVITY].[dbo].[master_user] WHERE username = '$username')";
    $query = $CI->db->query($sql);
    return $query;
}

function cekKomentarBelumDibacaFromSpvPage()
{
    $CI = &get_instance();
    $username = $_SESSION['username'];
    $sql = "SELECT t1.* FROM tb_komentar t1
    INNER JOIN tb_activity t2 ON t1.activity_number = t2.number
    WHERE t1.is_read = 'N' 
    AND created_by IN(SELECT username FROM [PK_ACTIVITY].[dbo].[master_user] WHERE spv = '$username')
    AND activity_created_by IN (SELECT '$username' UNION SELECT username FROM [PK_ACTIVITY].[dbo].[master_user] WHERE spv = '$username')";
    $query = $CI->db->query($sql);
    return $query;
}

function cekActivityOpen()
{
    $CI = &get_instance();
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM tb_activity WHERE [status] = 'open' 
    AND CreatedBy IN(SELECT '$username' UNION SELECT spv FROM [pk_activity].[dbo].[master_user] WHERE username = '$username')
    AND assign IN(SELECT '$username' UNION SELECT spv FROM [pk_activity].[dbo].[master_user] WHERE username = '$username')";
    $query = $CI->db->query($sql);
    return $query;
}

function cekActivityOpenManagerPage()
{
    $CI = &get_instance();
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM tb_activity WHERE [status] = 'open'";
    $query = $CI->db->query($sql);
    return $query;
}

function cekActivityOpenSpvPage()
{
    $CI = &get_instance();
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM tb_activity WHERE [status] = 'open' 
    AND CreatedBy IN(SELECT '$username' UNION SELECT username FROM [pk_activity].[dbo].[master_user] WHERE spv = '$username')";
    $query = $CI->db->query($sql);
    return $query;
}

function notifActivityBaruPageSpv()
{
    $CI = &get_instance();
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM tb_activity WHERE assign = '$username' AND is_read = 'N'";
    $query = $CI->db->query($sql);
    return $query;
}

function notifActivityBaruPageSales()
{
    $CI = &get_instance();
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM tb_activity WHERE assign = '$username' AND is_read = 'N'";
    $query = $CI->db->query($sql);
    return $query;
}

function getActivityName($id)
{
    $CI = &get_instance();
    $sql = "SELECT activity_name FROM master_activity WHERE id = '$id'";
    $query = $CI->db->query($sql);
    return $query->row()->activity_name;
}

function getCustomerName($card_code)
{
    $CI = &get_instance();
    $sql = "SELECT CustomerName FROM master_customer WHERE CardCode = '$card_code'";
    $query = $CI->db->query($sql);
    return $query->row()->CustomerName;
}

function getFullname($username)
{
    $CI = &get_instance();
    $sql = "SELECT fullname FROM [pk_activity].[dbo].[master_user] WHERE username = '$username'";
    $query = $CI->db->query($sql);
    return $query->row()->fullname;
}
