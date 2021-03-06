<?php
/*
 * Created on Aug 22, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
	
class m_date extends CI_Model {
	
	public function get_serial($from,$to){
		$sql = "select c::date from generate_series('$from'::date,'$to'::date,'1 day') c";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	public function get_month() {
		$d = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		return $d;
	}
	
	public function month($dt=0) {
		$d = "<option value=''>Bulan</option>";
		for($m = 1;$m<=12;$m++){
			if($m == $dt)
				$d.= "<option selected='selected' value='".$m."'>".$m."</option>";
			else				
				$d.= "<option value='".$m."'>".$m."</option>";
		}
		return $d;
	}
	
	public function year($dt=0) {
		$date = date('Y');
		$d = "<option value=''>Tahun</option>";
		for($m = 1940;$m<=$date+1;$m++){
			if($m == $dt)
				$d.= "<option selected='selected' value='".$m."'>".$m."</option>";
			else				
				$d.= "<option value='".$m."'>".$m."</option>";
		}
		return $d;
	}
	
	public function day($dt=0) {
		$date = date('t');
		$d = "<option value=''>Tanggal</option>";
		for($m = 1;$m<=$date;$m++){
			if($m == $dt)
				$d.= "<option selected='selected' value='".$m."'>".$m."</option>";
			else				
				$d.= "<option value='".$m."'>".$m."</option>";
		}
		return $d;
	}
	
	public function merge($d,$m,$y) {
		$strdate = strtotime($y."-".$m."-".$d);
		$date = date('Y-m-d',$strdate);
		return $date;
	}
}
?>
