<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class conf {
	
	protected $CI;
	
	private $f = '';		// filter
	private $o = '';		// order
	
	private $q = Null;		// query
	private $rs = Null;	// record set 
	private $r = Null;		// current record
	private $c = 0;		// record count
	
	private $attributes = array();
	
	private $conflict_attributes = array();
	
	public function __construct()
	{
		
		if(!isset($this->CI)){
			$this->CI =& get_instance();
			$this->CI->load->database();
		}

		if(!isset($_SESSION['_configuration'])){
			$_SESSION['_configuration'] = array();
		}
		
		$_SESSION['_configuration']['login_url'] = 'http://www.trackmycars.net/api/v1/login';
		$_SESSION['_configuration']['track_url'] = 'http://www.trackmycars.net/api/v1/track';

		$_SESSION['_configuration']['login_log_file'] = dirname(__FILE__).'/../../log/login.log';
		$_SESSION['_configuration']['track_log_file'] = dirname(__FILE__).'/../../log/track.log';
		$_SESSION['_configuration']['fetch_log_file'] = dirname(__FILE__).'/../../log/fetch.log';
		
		//$this->load_from_database();
		
		//$this->load_constants();
	}	
	
	private function load_from_database()
	{
		if(isset($_REQUEST['_change_evaluation_circle'])){
			list($_SESSION['_configuration']['term'], $_SESSION['_configuration']['year']) = explode('/', $_REQUEST['_change_evaluation_circle']);
		}
		
		$sql = "
	SELECT cnf.`key`, cnf.`value`
	FROM `s_configuration` cnf"; 
		$this->q = $this->CI->db->query($sql);
		
		$this->rs = $this->q->result();
		foreach($this->rs as $r){
			if(!isset($_SESSION['_configuration'][$r->key]))
				$_SESSION['_configuration'][$r->key] = $r->value;
		}
	
		// Evaluation Circle
		if($this->year==null){
			$_SESSION['_configuration']['year'] = $this->current_year;
			$_SESSION['_configuration']['term'] = $this->current_term;
		}
		if($this->term==null){
			$_SESSION['_configuration']['term'] = $this->current_term;
		}
	}

	private function save_to_database($k='')
	{
		if($k==''){
			foreach($this->attributes as $k){
				$sql = "
	SELECT cnf.`key`, cnf.`value`
	FROM `s_configuration` cnf
	WHERE cnf.`key`='$k'"; 
				$this->CI->db->query($sql);
				
				if($this->CI->db->affected_rows()){
					$sql = "
	UPDATE `s_configuration`
	SET `value` = '$this->configuration->$k'
	WHERE `key`='$k'"; 
				}else{
					$sql = "
	INSERT INTO `s_configuration`
	VALUES ('$k', '$this->configuration->$k', '')";
				}
				$this->CI->db->query($sql);				
			}
		}else{
			$sql = "
	SELECT cnf.`key`, cnf.`value`
	FROM `s_configuration` cnf
	WHERE cnf.`key`='$k'"; 
			$this->CI->db->query($sql);
			
			if($this->CI->db->affected_rows()){
				$sql = "
	UPDATE `s_configuration`
	SET `value` = '$v'
	WHERE `key`='$k'"; 
			}else{
				$sql = "
	INSERT INTO `s_configuration`
	VALUES ('$k', '$v', '')";
			}
			$this->CI->db->query($sql);				
		}
	}

	private function load_constants()
	{
		// Organization Types
		$sql = "
	SELECT ozt.`id`, ozt.`constant`
	FROM `s_organization_type` ozt"; 
		$this->q = $this->CI->db->query($sql);
		
		$this->rs = $this->q->result();
		foreach($this->rs as $r){
			if(!defined($r->constant))
				define($r->constant, $r->id);
		}
		
		// Management Types
		$sql = "
	SELECT mgt.`id`, mgt.`constant`
	FROM `s_management_type` mgt"; 
		$this->q = $this->CI->db->query($sql);
		
		$this->rs = $this->q->result();
		foreach($this->rs as $r){
			if(!defined($r->constant))
				define($r->constant, $r->id);
		}

		// Employment
		$sql = "
	SELECT em.`id`, em.`constant`
	FROM `s_employment` em"; 
		$this->q = $this->CI->db->query($sql);
		
		$this->rs = $this->q->result();
		foreach($this->rs as $r){
			if(!defined($r->constant))
				define($r->constant, $r->id);
		}

		// Permission
		$sql = "
	SELECT pm.`id`, pm.`constant`
	FROM `s_permission_type` pm"; 
		$this->q = $this->CI->db->query($sql);
		
		$this->rs = $this->q->result();
		foreach($this->rs as $r){
			if(!defined($r->constant))
				define($r->constant, $r->id);
		}
		
	}

	public function __get($k)
	{
		if($k=='attributes'){
			if(isset($_SESSION['_configuration'])){
				return array_keys($_SESSION['_configuration']);
			}
		}elseif(isset($_SESSION['_configuration'])){
			if(array_key_exists($k, $_SESSION['_configuration'])){
				return $_SESSION['_configuration'][$k];
			}
		}
		return null;
	}
	
	public function __set($k, $v)
	{
		//if(!is_object($v)){
			if(array_key_exists($k, $_SESSION['_configuration'])){
				$_SESSION['_configuration'][$k] = $v;
			}
		//}
		return $v;
	}
}
