<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');

class c_absensi extends CI_Controller {

	function __construct() {
		parent :: __construct();
		$this->load->model('m_menu');
		$this->client_logon = $this->session->userdata('login');
		$this->data['menus'] = $this->m_menu->getAll($this->client_logon['id_prev']);
		if ($this->client_logon['id_prev'] != "absen" && $this->client_logon['id_prev'] != "admin") {
			$this->redirectto($this->client_logon['id_prev']);
		}
	}

	public function index($src = null) {
		if ($_POST) {
			$d = $_REQUEST['d'] ? $_REQUEST['d'] : null;
			$m = $_REQUEST['m'] ? $_REQUEST['m'] : null;
			$y = $_REQUEST['y'] ? $_REQUEST['y'] : null;
			$src['tanggal'] = $y . '-' . $m . '-' . $d;
			$src['nama_person'] = $_REQUEST['nama_person'] ? $_REQUEST['nama_person'] : null;
			$src['no_induk'] = $_REQUEST['no_induk'] ? $_REQUEST['no_induk'] : null;
		}else{
			$src['tanggal'] = date('Y-m-d');
			$src['nama_person'] = null;
			$src['no_induk'] = null;
		}
		if ($this->client_logon) {
			$this->load->model('m_absen');
			$this->data['rows'] = $this->m_absen->display_absen_siswa($src);
			$this->data['tanggal'] = $src['tanggal'];
			$this->data['title'] = "Data Absensi";
			$this->data['report'] = "report_siswa";
			$this->data['action'] = "index";
			$this->load->view('v_header', $this->data);
			$this->load->view('v_absensi', $this->data);
			$this->load->view('v_footer', $this->data);
		} else {
			redirect('login');
		}
	}
	
	public function report_siswa($tgl) {
			$src['tanggal'] = $tgl;
			$this->load->model('m_absen');
			$this->data['rows'] = $this->m_absen->display_absen_siswa($src);
			$this->data['tanggal'] = $src['tanggal'];
			$this->load->view('report', $this->data);
	}
	
	public function report_guru($tgl) {
			$src['tanggal'] = $tgl;
			$this->load->model('m_absen');
			$this->data['rows'] = $this->m_absen->display_absen_guru($src);
			$this->data['tanggal'] = $src['tanggal'];
			$this->load->view('report', $this->data);
	}

	public function absent() {
		$this->load->model('m_absen');
		$this->data['rows'] = $this->m_absen->get_not_absen();
		$this->data['title'] = "Absensi";
		$this->load->view('v_header', $this->data);
		$this->load->view('v_add_absensi', $this->data);
		$this->load->view('v_footer', $this->data);
	}

	public function editAbsensi($no_absensi) {
		$this->load->model('m_absen');
		$this->data['rows'] = $this->m_absen->editAbsensi($no_absensi);
		$this->data['title'] = "Edit Data Absensi";
		$this->load->view('v_header', $this->data);
		$this->load->view('v_edit_absensi', $this->data);
		$this->load->view('v_footer', $this->data);
	}

	public function updateAbsensi() {
		$this->load->model('m_absen');
		$keterangan = $this->input->post('keterangan');
		$no = $this->input->post('no_absensi');
		$this->m_absen->updateAbsensi($keterangan, $no);
		redirect(index_absensi);
	}

	public function show_absen_guru($src = null) {
		if ($_POST) {
			$d = $_REQUEST['d'] ? $_REQUEST['d'] : null;
			$m = $_REQUEST['m'] ? $_REQUEST['m'] : null;
			$y = $_REQUEST['y'] ? $_REQUEST['y'] : null;
			$src['tanggal'] = $y . '-' . $m . '-' . $d;
			$src['nama_person'] = $_REQUEST['nama_person'] ? $_REQUEST['nama_person'] : null;
			$src['no_induk'] = $_REQUEST['no_induk'] ? $_REQUEST['no_induk'] : null;
		}else{
			$src['tanggal'] = date('Y-m-d');
			$src['nama_person'] = null;
			$src['no_induk'] = null;
		}
		if ($this->client_logon) {
			$this->load->model('m_absen');
			$this->data['rows'] = $this->m_absen->display_absen_guru($src);
			$this->data['tanggal'] = $src['tanggal'];
			$this->data['title'] = "Data Absensi";
			$this->data['report'] = "report_guru";
			$this->data['action'] = "show_absen_guru";
			$this->load->view('v_header', $this->data);
			$this->load->view('v_absensi', $this->data);
			$this->load->view('v_footer', $this->data);
		} else {
			redirect('login');
		}
	}

	public function submit_absen() {
		$id = $_REQUEST['no_induk'];
		$alasan = $_REQUEST['status_absen'];
		$this->load->model('m_absen');
		$result = $this->m_absen->insert($id, $alasan);
		redirect('c_admin/index');
	}

	function search() {
		$d = $_REQUEST['d'] ? $_REQUEST['d'] : null;
		$m = $_REQUEST['m'] ? $_REQUEST['m'] : null;
		$y = $_REQUEST['y'] ? $_REQUEST['y'] : null;
		$src['tanggal'] = $y . '-' . $m . '-' . $d;
		$src['nama_person'] = $_REQUEST['nama_person'] ? $_REQUEST['nama_person'] : null;
		$src['no_induk'] = $_REQUEST['no_induk'] ? $_REQUEST['no_induk'] : null;
	}

	function redirectto($prev) {
		switch ($prev) {
			case 'absen' :
				redirect('index_absensi');
				break;
			case 'smsgw' :
				redirect('index_sms');
				break;
			case 'sppap' :
				redirect('index_spp');
				break;
			case 'nilai' :
				redirect('index_nilai');
				break;
			case 'admin' :
				redirect('index_admin');
				break;
		}
	}

}