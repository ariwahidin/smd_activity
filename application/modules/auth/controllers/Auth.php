<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function login()
    {
        $this->load->view('index_v');
    }

    public function process()
    {
        $post = $this->input->post(null, TRUE);
        if (isset($post['login'])) {
            $login =  $this->auth_model->cek_data_user($post);
            if ($login->num_rows() > 0) {
                $username = $login->row()->username;
                $fullname = $login->row()->fullname;
                $user_page = $login->row()->page;
                $params = array(
                    'username' => $username,
                    'fullname' => $fullname,
                    'user_page' => $user_page,
                );
                
                if (is_null($login->row()->spv)) {
                    echo "<script>
                            alert('User Belum Terdaftar');
                            window.location='" . site_url('auth/login') . "';
                        </script>";
                    return false;
                }

                $this->session->set_userdata($params);
                redirect(base_url($user_page));
            } else {
                echo "<script>
                	alert('Login gagal, username/password salah');
                	window.location='" . site_url('auth/login') . "';
                </script>";
            }
        } else {
            echo "Tidak ada post";
        }
    }

    public function logout()
    {
        $params = array('username', 'fullname', 'user_page');
        $this->session->unset_userdata($params);
        redirect(base_url('auth/login'));
    }
}
