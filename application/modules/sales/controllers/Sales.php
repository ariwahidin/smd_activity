<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		cek_login();
		$this->load->model(['sales_model']);
	}

	public function index()
	{
		$this->showActivity();
	}

	public function showForm()
	{
		$number = $this->sales_model->getNumber();
		$getActivity = $this->sales_model->getActivity();
		$getBrand = $this->sales_model->getBrand();
		$customer = $this->sales_model->getCustomer();
		$assign_to = $this->sales_model->getSpv();
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
		$activity = $this->sales_model->getDataActivity($number);
		$customer = $this->sales_model->getCustomer();
		$activity_detail = $this->sales_model->getDataActivityDetail($number);
		$getActivity = $this->sales_model->getActivity();
		$getBrand = $this->sales_model->getBrand();
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
		if ($_FILES['link_document']['name'][0] != '') {
			for ($i = 0; $i < count($_FILES['link_document']['name']); $i++) {
				$file_tmp = $_FILES['link_document']['tmp_name'][$i];
				$nama = uniqid().'.jpg';
				move_uploaded_file($file_tmp, $folderPath.$nama);
				array_push($image, $nama);
			}
		}

		$data = $_POST;

		if (!empty($data['brand'])) {
			$data['brand'] = implode(',', $data['brand']);
		}

		if ($_FILES['link_document']['name'][0] != '') {
			$data['image_name'] = implode(',', $image);
		} else {
			$data['image_name'] = '';
		}

		if ($_POST['page'] == 'create_new') {
			$number = $this->sales_model->simpanFormActivity($data);
		} else {
			$this->sales_model->updateActivity($data);
		}

		if($this->db->affected_rows() > 0){
			redirect(base_url($_SESSION['user_page'].'/showFormReadOnly/'.$number));
		}else{
			echo "Gagal simpan data";
		}
	}

	public function follow_up(){
		$image = [];
		$folderPath = APPPATH . '../uploads/image_activity/';
		if ($_FILES['link_document']['name'][0] != '') {
			for ($i = 0; $i < count($_FILES['link_document']['name']); $i++) {
				$file_tmp = $_FILES['link_document']['tmp_name'][$i];
				$nama = uniqid().'.jpg';
				move_uploaded_file($file_tmp, $folderPath.$nama);
				array_push($image, $nama);
			}
		}

		$data = $_POST;

		if ($_FILES['link_document']['name'][0] != '') {
			$data['image_name'] = implode(',', $image);
		} else {
			$data['image_name'] = '';
		}

		// var_dump($data);

		$this->sales_model->follow_up($data);

		if($this->db->affected_rows() > 0){
			redirect(base_url($_SESSION['user_page'].'/showFormReadOnly/'.$_POST['activity_number']));
		}else{
			echo "Gagal simpan data";
		}
	}

	public function showActivity()
	{
		$activity = $this->sales_model->getDataActivity();
		$data = array(
			'activity' => $activity,
		);
		$this->load->view('activity_data_v.php', $data);
	}

	public function deleteActivity($number)
	{
		$this->sales_model->deleteDataActivity($number);
		if ($this->db->affected_rows() > 0) {
			redirect(base_url('activity'));
		} else {
			echo "gagal hapus";
		};
	}

	public function showFormReadonly($number)
	{
		$activity = $this->sales_model->getDataActivity($number);
		$customer = $this->sales_model->getCustomer();
		$activity_detail = $this->sales_model->getDataActivityDetail($number);
		$getBrand = $this->sales_model->getBrand();
		$getActivity = $this->sales_model->getActivity();
		$cekKomentarBelumDibaca = $this->sales_model->cekKomentarBelumDibaca($number);
		$assign_to = $this->sales_model->getSpv();
		if ($cekKomentarBelumDibaca->num_rows() > 0) {
			$this->sales_model->updateKomentarIsRead($number);
		}

		$cekNotifBaruBelumDiBaca = $this->sales_model->cekNotifBaruBelumDibaca($number);
		if ($cekNotifBaruBelumDiBaca->num_rows() > 0) {
			$this->sales_model->updateNotifSudahDibaca($number);
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
		$this->load->view('activity_form_read_v.php', $data);
	}

	public function showModalImage()
	{
		$data = array(
			'image' => $_POST['img'],
		);
		$this->load->view('modal/modal_img', $data);
	}

	// public function testSimpanImg()
	// {
	// 	var_dump($_POST);
	// 	// die;
	// 	$image = [];
	// 	$folderPath = APPPATH . '../uploads/image_activity/';
	// 	for ($i = 0; $i < count($_POST['image']); $i++) {
	// 		$image_parts = explode(";base64,", $_POST['image'][$i]);
	// 		$image_type_aux = explode("image/", $image_parts[0]);
	// 		$image_type = $image_type_aux[1];
	// 		$image_base64 = base64_decode($image_parts[1]);
	// 		$image_name = 'testlagicoy' . $i . '.' . $image_type;
	// 		$file = $folderPath . $image_name;

	// 		// file_put_contents($file, $image_base64);
	// 		// echo "Tanda Tangan Sukses Diupload ";

	// 		array_push($image, $image_name);
	// 	}
	// 	var_dump($image);
	// 	die;
	// }

	public function kirim_komentar()
	{
		$komentar = $this->sales_model->kirim_komentar($_POST);
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

	public function closeActivity($number)
	{
		// var_dump($number);
		$this->sales_model->closeActivity($number);
		if ($this->db->affected_rows() > 0) {
			redirect(base_url($_SESSION['user_page']).'/showFormReadOnly/'.$number);
		}else{
			echo "Gagal";
		}
	}

}
