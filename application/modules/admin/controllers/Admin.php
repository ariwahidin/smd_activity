<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		cek_login();
		$this->load->model(['admin_model']);
	}

	public function index()
	{
		$this->showActivity();
	}

	public function showForm()
	{
		$number = $this->admin_model->getNumber();
		$getActivity = $this->admin_model->getActivity();
		$getBrand = $this->admin_model->getBrand();
		$customer = $this->admin_model->getCustomer();
		$assign_to = $this->admin_model->getSpv();
		$activity = new stdClass();
		$activity->id = null;
		$activity->number = $number;
		$activity->assign = null;
		$activity->activity = null;
		$activity->start_periode = null;
		$activity->end_periode = null;
		$activity->brand_code = null;
		$activity->customer_code = null;
		$activity->priority = null;
		$activity->status = null;
		$activity->no_ref = null;
		$activity->remaks = null;
		$activity->link_document = null;
		$activity->content = null;
		$data = array(
			'activity' => $activity,
			'showActivity' => $getActivity->result(),
			'showBrand' => $getBrand->result(),
			'page' => 'create_new',
			'customer' => $customer->result(),
			'assign_to' => $assign_to,
		);
		$this->load->view('activity_form_v.php', $data);
	}

	public function showFormUpdate($number)
	{
		$activity = $this->admin_model->getDataActivity($number);
		$customer = $this->admin_model->getCustomer();
		$activity_detail = $this->admin_model->getDataActivityDetail($number);
		$getActivity = $this->admin_model->getActivity();
		$getBrand = $this->admin_model->getBrand();
		$data = array(
			'activity' => $activity->row(),
			'activity_detail' => $activity_detail,
			'customer' => $customer->result(),
			'page' => 'update',
			'showActivity' => $getActivity->result(),
			'showBrand' => $getBrand->result(),
		);
		$this->load->view('activity_form_v.php', $data);
	}

	function compress($source, $destination, $quality)
	{
		$info = getimagesize($source);
		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($source);
		elseif ($info['mime'] == 'image/gif')
			$image = imagecreatefromgif($source);
		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($source);
		$result = imagejpeg($image, $destination, $quality);
		return $result;
	}

	public function uploadimage()
	{
		// var_dump($_POST);
		// var_dump($_FILES);
		$file_temp = $_FILES['fileImage']['tmp_name'];
		$destination = realpath(APPPATH . '../uploads/image_activity/') . '/' . $_FILES['fileImage']['name'];
		move_uploaded_file($file_temp, $destination);
		echo json_encode(array('sendimage' => true));
	}

	public function prosesActivity()
	{
		$image = [];
		$folderPath = APPPATH . '../uploads/image_activity/';
		if (!empty($_POST['image'])) {
			for ($i = 0; $i < count($_POST['image']); $i++) {
				$image_parts = explode(";base64,", $_POST['image'][$i]);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				$image_base64 = base64_decode($image_parts[1]);
				$image_name = uniqid() . '.' . $image_type;
				$file = $folderPath . $image_name;

				file_put_contents($file, $image_base64);
				// echo "Tanda Tangan Sukses Diupload ";
				array_push($image, $image_name);
			}
		}
		// var_dump($image);
		// die;

		$data = $_POST;

		if (!empty($data['brand'])) {
			$data['brand'] = implode(',', $data['brand']);
		}

		if (!empty($data['image'])) {
			// $data['image_name'] = array_diff(explode(',', $data['image_name']), ['']);
			$data['image_name'] = implode(',', $image);
		} else {
			$data['image_name'] = '';
		}

		if ($_POST['page'] == 'create_new') {
			$this->admin_model->simpanFormActivity($data);
		} else {
			$this->admin_model->updateActivity($data);
		}

		if ($this->db->affected_rows() > 0) {
			$params = array('success' => true);
		} else {
			$params = array('success' => false);
		}

		echo json_encode(array('success' => true));
	}

	public function showActivity()
	{
		$activity = $this->admin_model->getDataActivity();
		$data = array(
			'activity' => $activity,
		);
		$this->load->view('activity_data_v.php', $data);
	}

	public function deleteActivity($number)
	{
		$this->admin_model->deleteDataActivity($number);
		$cek = $this->admin_model->cekDataSudahTerhapus($number);
		if ($cek == true) {
			redirect(base_url($_SESSION['user_page']));
		} else {
			echo "gagal hapus";
		};
	}

	public function showFormReadonly($number)
	{
		$activity = $this->admin_model->getDataActivity($number);
		$customer = $this->admin_model->getCustomer();
		$activity_detail = $this->admin_model->getDataActivityDetail($number);
		$getBrand = $this->admin_model->getBrand();
		$getActivity = $this->admin_model->getActivity();
		$cekKomentarBelumDibaca = $this->admin_model->cekKomentarBelumDibaca($number);
		$assign_to = $this->admin_model->getSpv();
		if ($cekKomentarBelumDibaca->num_rows() > 0) {
			$this->admin_model->updateKomentarIsRead($number);
		}
		// $komentar 
		$data = array(
			'activity' => $activity->row(),
			'activity_detail' => $activity_detail,
			'customer' => $customer->result(),
			'showBrand' => $getBrand->result(),
			'showActivity' => $getActivity->result(),
			'page' => 'readonly',
			'assign_to' => $assign_to,
		);
		$this->load->view('activity_form_v.php', $data);
	}

	public function showModalImage()
	{
		$data = array(
			'image' => $_POST['img'],
		);
		$this->load->view('modal/modal_img', $data);
	}

	public function testSimpanImg()
	{
		var_dump($_POST);
		// die;
		$image = [];
		$folderPath = APPPATH . '../uploads/image_activity/';
		for ($i = 0; $i < count($_POST['image']); $i++) {
			$image_parts = explode(";base64,", $_POST['image'][$i]);
			$image_type_aux = explode("image/", $image_parts[0]);
			$image_type = $image_type_aux[1];
			$image_base64 = base64_decode($image_parts[1]);
			$image_name = 'testlagicoy' . $i . '.' . $image_type;
			$file = $folderPath . $image_name;

			// file_put_contents($file, $image_base64);
			// echo "Tanda Tangan Sukses Diupload ";

			array_push($image, $image_name);
		}
		var_dump($image);
		die;
	}

	public function kirim_komentar()
	{
		$komentar = $this->admin_model->kirim_komentar($_POST);
		if ($this->db->affected_rows() > 0) {
			echo json_encode(['komentar' => 'masuk']);
		} else {
			echo json_encode(['komentar' => 'gagal']);
		}
	}

	public function reload_comment_after_send()
	{
		$data = array(
			'number_activity' => $_POST['number_activity'],
			'content_id' => $_POST['content_id']
		);
		$this->load->view('update_comment_v', $data);
	}
	public function reload_comment()
	{
		$data = array(
			'number_activity' => $_POST['number_activity'],
			'content_id' => $_POST['content_id']
		);
		$this->load->view('update_comment_v', $data);
	}
}
