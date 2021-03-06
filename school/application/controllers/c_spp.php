<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');

class c_spp extends CI_Controller {

	function __construct() {
		parent :: __construct();
		$this->load->model('m_menu');
		$this->load->model('m_date');
		$this->client_logon = $this->session->userdata('login');
		$this->data['menus'] = $this->m_menu->getAll($this->client_logon[0]->admin_username);
	}
	
	public function report() {
		$this->load->model('m_periode');
		$this->data['periode'] = $this->m_periode->get_all();
		$this->data['title'] = "Laporan Keuangan";
		$this->load->view('v_header', $this->data);
		$this->load->view('v_form_report_keu', $this->data);
		$this->load->view('v_footer', $this->data);
	}
	
	public function submit_report() {
		$this->load->model('m_siswa');
		$this->load->model('m_kelas');
		$periode = $_REQUEST['id_periode'];
		$this->data['siswa'] = $this->m_siswa->get_keu_all();
		$this->data['spp'] = $this->m_siswa->get_spp_all($periode);
		$this->data['kelas'] = $this->m_kelas->show_kelas();
		$this->data['title'] = "Laporan Keuangan";
		$this->load->view('v_header', $this->data);
		$this->load->view('report_person', $this->data);
		$this->load->view('v_footer', $this->data);
	}	

	public function index() {
		$this->load->model('m_siswa');

		$key1 = $this->input->post('search_field1') ? $this->input->post('search_field1') : null;
		$key2 = $this->input->post('search_field2') ? $this->input->post('search_field2') : null;

		if ($key1 == NULL && $key2 == NULL) {
			$this->data['siswa'] = $this->m_siswa->get_spp();
		} else
			if ($key1 != NULL || $key2 != NULL) {
				$this->data['siswa'] = $this->m_siswa->get_spp($key1, $key2);
			}
		$this->data['title'] = "Keuangan Siswa";
		$this->load->view('v_header', $this->data);
		$this->load->view('v_data_keu_siswa', $this->data);
		$this->load->view('v_footer', $this->data);

	}

	public function view_detail($id) {
		$this->load->model('m_siswa');
		$this->load->model('m_dsp');
		$this->load->model('m_tahunan');
		$this->load->model('m_spp');
		$this->data['siswa'] = $this->m_siswa->get_spp($id);
		$this->data['dsp'] = $this->m_dsp->get_history($id);
		$this->data['tahunan'] = $this->m_tahunan->get_history($id);
		$this->data['spp'] = $this->m_spp->get_one($id);
		$this->data['title'] = "Keuangan Siswa";
		$this->load->view('v_header', $this->data);
		$this->load->view('v_detail_keu_siswa', $this->data);
		$this->load->view('v_footer', $this->data);

	}

	public function delete_bayar_dsp($id, $tgl, $nis) {
		$this->load->model('m_dsp');
		$value['id_dsp'] = $id;
		$value['tanggal_bayar_dsp'] = $tgl;
		$this->m_dsp->delete_bayar($value);
		redirect("c_spp/view_detail/$nis");
	}

	public function delete_bayar_tahunan($id, $tgl, $nis) {
		$this->load->model('m_tahunan');
		$value['id_tahunan'] = $id;
		$value['tanggal_bayar_tahunan'] = $tgl;
		$this->m_tahunan->delete_bayar($value);
		redirect("c_spp/view_detail/$nis");
	}

	public function delete_bayar_spp($bulan, $tahun, $nis) {
		$this->load->model('m_spp');
		$value['bulan_spp'] = $bulan;
		$value['id_periode'] = $tahun;
		$this->m_spp->delete_bayar($value);
		redirect("c_spp/view_detail/$nis");
	}

	public function edit_keu($id) {
		$this->load->model('m_siswa');
		$this->data['siswa'] = $this->m_siswa->get_spp($id);
		$this->data['title'] = "Keuangan Siswa";
		$this->load->view('v_header', $this->data);
		$this->load->view('v_form_keuangan_siswa', $this->data);
		$this->load->view('v_footer', $this->data);

	}

	public function submit_keuangan() {
		$this->load->model('m_tahunan');
		$this->load->model('m_spp');
		$this->load->model('m_dsp');
		$nis = $_REQUEST['nomor_induk_siswa'];
		$jml_dsp = $_REQUEST['jumlah_dsp'];
		$jumlah_tahunan = $_REQUEST['jumlah_tahunan'];
		$jumlah_spp = $_REQUEST['jumlah_spp'];
		$this->m_dsp->insert($nis, $jml_dsp);
		$this->m_tahunan->insert($nis, $jumlah_tahunan);
		$this->m_spp->insert($nis, $jumlah_spp);
		redirect('c_spp');
	}
		
		/* public function submit_dsp($nis,$dsp){
			$this->load->model('m_dsp');
			if($this->m_dsp->check())
				$this->m_dsp->update($nis, $dsp);
			else
				$this->m_dsp->insert($nis, $dsp);
		}
		
		public function submit_spp($nis,$spp){
			$this->load->model('m_spp');
			if($this->m_spp->check())
				$this->m_spp->update($nis, $spp);
			else
				$this->m_spp->insert($nis, $spp);
		}
		
		public function submit_tahunan($nis,$tahunan){
			$this->load->model('m_tahunan');
			if($this->m_tahunan->check())
				$this->m_tahunan->update($nis, $tahunan);
			else
				$this->m_tahunan->insert($nis, $tahunan);
		} */

	public function bayar($id,$alert=null) {
		$d = 0;
		$m = 0;
		$y = 0;
		$this->data['d'] = $this->m_date->day($d);
		$this->data['m'] = $this->m_date->month($m);
		$this->data['y'] = $this->m_date->year($y);
		$this->load->model('m_siswa');
		$this->load->model('m_periode');
		$this->data['per'] = $this->m_periode->get_all();
		$this->data['alert'] = $alert;
		$this->data['mon'] = $this->m_date->get_month();
		$this->data['siswa'] = $this->m_siswa->get_id_keu($id);
		$this->data['title'] = "Form Pembayaran Keuangan";
		$this->load->view('v_header', $this->data);
		$this->load->view('v_form_pembayaran', $this->data);
		$this->load->view('v_footer', $this->data);

	}

	public function submit_pembayaran() {
		$d = $_REQUEST['d'];
		$m = $_REQUEST['m'];
		$y = $_REQUEST['y'];
		$tgl = $this->m_date->merge($d, $m, $y);
		$data['nomor_induk_siswa'] = $_REQUEST['nis'];
		if ($_REQUEST['bulan']) {
			$data['jml_spp'] = $this->bayar_spp($_REQUEST['bulan'], $tgl,$data['nomor_induk_siswa']);
		}
		if ($_REQUEST['jumlah_bayar_dsp']) {
			$data['jml_dsp'] = $this->bayar_dsp($_REQUEST['jumlah_bayar_dsp'], $tgl);
		}
		if ($_REQUEST['jumlah_bayar_tahunan']) {
			$data['jml_tahunan'] = $this->bayar_tahunan($_REQUEST['jumlah_bayar_tahunan'], $tgl);
		}
		$data['no_pembayaran'] = $_REQUEST['nomor_bayar'];
		$data['tgl_pembayaran'] = $tgl;
		$this->load->model('m_pembayaran');
		$this->m_pembayaran->insert_pembayaran($data);
		redirect('c_spp/index');
		$row = $this->m_dsp->get_detail_nota($value['id_dsp']);
		$data['spp'] = false;
		$data['dsp'] = true;
		$data['tahunan'] = false;
		$data['kelas'] = $row->nama_kelas;
		$data['nama'] = $row->nama_siswa;
		$data['jumlah'] = $value['jumlah_bayar_dsp'];
		$this->print_note($data);
	}
	
	public function bayar_spp($bulan, $tgl, $nis){
		$this->load->model('m_spp');
		$value['id_periode'] = $_REQUEST['id_periode'];
		$value['id_spp'] = $_REQUEST['id_spp'];
		$i = 0;
		for ($i; $i < count($bulan); $i++) {
			$value['bulan_spp'] = $bulan[$i];
			$check = $this->m_spp->count($value);
			if ($check > 0) {
				$alert = "Bulan " . date('F',strtotime('01-'.$bulan[$i].'-2001')) . " sudah dibayar sebelumnya";
				redirect("c_spp/bayar/$nis/$alert");
				return;
			}
			$value['tanggal_bayar_spp'] = $tgl;
			$this->m_spp->insert_bayar($value);
		}
		return $this->get_spp_siswa($nis) * $i;
	}
	
	public function get_spp_siswa($nis){
		$this->load->model('m_siswa');
		$row = $this->m_siswa->get_data_spp($nis);
		return $row->jumlah_spp;
	}
	
	public function bayar_dsp($jml, $tgl){
		$this->load->model('m_dsp');
		$data['id_dsp'] = $_REQUEST['id_dsp'];
		$data['tanggal_bayar_dsp'] = $tgl;
		$data['jumlah_bayar_dsp'] = $jml;
		$this->m_dsp->insert_bayar_dsp($data);
		return $jml;
	}
	
	public function bayar_tahunan($jml, $tgl){
		$this->load->model('m_tahunan');
		$nilai['tanggal_bayar_tahunan'] = $tgl;
		$nilai['id_tahunan'] = $_REQUEST['id_tahunan'];
		$nilai['jumlah_bayar_tahunan'] = $jml;
		$this->m_tahunan->insert_bayar_tahunan($nilai);
		return $jml;
	}

	public function submit_tahunan() {
		$this->load->model('m_tahunan');
		$d = $_REQUEST['d'];
		$m = $_REQUEST['m'];
		$y = $_REQUEST['y'];
		$value['tanggal_bayar_tahunan'] = $this->m_date->merge($d, $m, $y);
		$value['id_tahunan'] = $_REQUEST['id_tahunan'];
		$value['jumlah_bayar_tahunan'] = $_REQUEST['jumlah_bayar_tahunan'];
		$this->m_tahunan->insert_bayar_tahunan($value);
		/*$row = $this->m_tahunan->get_detail_nota($value['id_tahunan']);
		$data['spp'] = false;
		$data['dsp'] = false;
		$data['tahunan'] = true;
		$data['kelas'] = $row->nama_kelas;
		$data['nama'] = $row->nama_siswa;
		$data['jumlah'] = $value['jumlah_bayar_tahunan'];
		$this->print_note($data);*/
	}

	public function add_tahunan($id) {
		$this->load->model('m_tahunan');
		$d = 0;
		$m = 0;
		$y = 0;
		$this->data['d'] = $this->m_date->day($d);
		$this->data['m'] = $this->m_date->month($m);
		$this->data['y'] = $this->m_date->year($y);
		$this->data['siswa'] = $this->m_tahunan->get_sisa($id);
		$this->data['title'] = "Keuangan Siswa";
		$this->load->view('v_header', $this->data);
		$this->load->view('v_form_tahunan_siswa', $this->data);
		$this->load->view('v_footer', $this->data);

	}

	public function add_print_note($nis) {
		$this->load->model('m_pembayaran');
		$this->data['no_pembayaran'] = $this->m_pembayaran->get_data_pembayaran($nis);
		$this->data['nis'] = $nis;
		$this->data['title'] = "Print Note";
		$this->load->view('v_header', $this->data);
		$this->load->view('v_form_print_note', $this->data);
		$this->load->view('v_footer', $this->data);

	}

	/*public function add_spp($id) {
		$this->load->model('m_spp');
		$this->data['title'] = "Keuangan Siswa";
		$this->data['nomor_induk_siswa'] = $id;
		$this->data['spp'] = $this->m_spp->get_one($id);
		$this->load->view('v_header', $this->data);
		$this->load->view('v_form_thn_spp_siswa', $this->data);
		$this->load->view('v_footer', $this->data);
	
	}*/

	public function add_spp($id, $alert = null) {
		$this->load->model('m_spp');
		$d = 0;
		$m = 0;
		$y = 0;
		$this->data['alert'] = $alert;
		$this->data['d'] = $this->m_date->day($d);
		$this->data['m'] = $this->m_date->month($m);
		$this->data['y'] = $this->m_date->year($y);
		$this->data['title'] = "Keuangan Siswa";
		$this->data['mon'] = $this->m_date->get_month();
		$this->data['id'] = $this->m_spp->get_one($id);
		$this->data['nis'] = $id;
		$this->load->view('v_header', $this->data);
		$this->load->view('v_form_spp_siswa', $this->data);
		$this->load->view('v_footer', $this->data);
	}

	public function submit_spp() {
		$this->load->model('m_spp');
		$d = $_REQUEST['d'];
		$m = $_REQUEST['m'];
		$y = $_REQUEST['y'];
		$nis = $_REQUEST['nis'];
		$bulan = $_REQUEST['bulan'];
		$value['id_spp'] = $_REQUEST['id_spp'];
		$value['tahun_spp'] = $_REQUEST['tahun_spp'];
		$value['tanggal_bayar_spp'] = $this->m_date->merge($d, $m, $y);
		$i = 0;
		for ($i; $i < count($bulan); $i++) {
			$value['bulan_spp'] = $bulan[$i];
			$check = $this->m_spp->count($value);
			echo $check;
			if ($check > 0) {
				$alert = "Bulan " . $bulan[$i] . " tahun ke " . $value['tahun_spp'] . " sudah dibayar sebelumnya";
				redirect("c_spp/add_spp/$nis/$alert");
				return;
			}
			$this->m_spp->insert_bayar($value);
		}
		/*$row = $this->m_spp->get_detail_nota($value['id_spp']);
		$data['spp'] = true;
		$data['dsp'] = false;
		$data['tahunan'] = false;
		$data['kelas'] = $row->nama_kelas;
		$data['nama'] = $row->nama_siswa;
		$data['jumlah'] = $i * $row->jumlah_spp;
		$this->print_note($data);*/
	}

	public function submit_note() {
		$this->load->model('m_siswa');
		$this->load->model('m_spp');
		$this->load->model('m_pembayaran');
		$no = $_REQUEST['no_pembayaran'];
		$nis = $_REQUEST['id'];
		$byr = $this->m_pembayaran->get_pembayaran($no);
		$this->m_pembayaran->printed($no);
		$row = $this->m_siswa->get_one_siswa($nis);
		$dsp = $this->m_siswa->get_sisa_dsp($nis);
		$tahunan = $this->m_siswa->get_sisa_tahunan($nis);
		$data['no_pembayaran'] = $no;
		$data['kelas'] = $row->nama_kelas;
		$data['nama'] = $row->nama_siswa;
		$data['spp'] = $byr->jml_spp ? $byr->jml_spp : 0;
		$data['dsp'] = $byr->jml_dsp ? $byr->jml_dsp : 0;
		$data['tahunan'] = $byr->jml_tahunan ? $byr->jml_tahunan : 0;
		$data['rows_tahunan'] = $tahunan->sisa_tahunan ? $tahunan->sisa_tahunan : $tahunan->tahunan;
		$data['rows_dsp'] = $dsp->sisa_dsp ? $dsp->sisa_dsp : $dsp->dsp;
		$data['rows_spp'] = $this->m_spp->get_one($nis);
		$data['terbilang'] = ucwords($this->Terbilang($data['spp'] + $data['dsp'] + $data['tahunan']));
		$this->print_note($data);
	}

	function Terbilang($x) {
		$abil = array (
			"",
			"satu",
			"dua",
			"tiga",
			"empat",
			"lima",
			"enam",
			"tujuh",
			"delapan",
			"sembilan",
			"sepuluh",
			"sebelas"
		);
		if ($x < 12)
			return " " . $abil[$x];
		elseif ($x < 20) return $this->Terbilang($x -10) . "belas";
		elseif ($x < 100) return $this->Terbilang($x / 10) . " puluh" . $this->Terbilang($x % 10);
		elseif ($x < 200) return " seratus" . $this->Terbilang($x -100);
		elseif ($x < 1000) return $this->Terbilang($x / 100) . " ratus" . $this->Terbilang($x % 100);
		elseif ($x < 2000) return " seribu" . $this->Terbilang($x -1000);
		elseif ($x < 1000000) return $this->Terbilang($x / 1000) . " ribu" . $this->Terbilang($x % 1000);
		elseif ($x < 1000000000) return $this->Terbilang($x / 1000000) . " juta" . $this->Terbilang($x % 1000000);
	}

	public function print_note($data) {
		// Load library FPDF 
		$this->load->library('fpdf');
		// Load Database

		/* buat konstanta dengan nama FPDF_FONTPATH, kemudian kita isi value-nya
		   dengan alamat penyimpanan FONTS yang sudah kita definisikan sebelumnya.
		   perhatikan baris $config['fonts_path']= 'system/fonts/'; 
		   didalam file application/config/config.php
		*/
		define('FPDF_FONTPATH', $this->config->item('fonts_path'));

		/* Kita akses function get_all didalam karyawan_model
		   function get_all merupakan fungsi yang dibuat untuk mengambil
		   seluruh data karyawan didalam database.
		*/

		// Load view "pdf_report" untuk menampilkan hasilnya   

		$this->load->view('pdf_report', $data);
	}
}