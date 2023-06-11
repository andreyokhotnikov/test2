<?php

class AjaxSession extends Session {
#########################################################################
	public function __construct(){//
			parent::__construct();
		}
#########################################################################
	public function __destruct(){
		parent::__destruct();
	}
#########################################################################
	public function processRequest(){
				
		include("mysqlconfig.php");
		//include("mysqlconfig_local.php");
		$this->conn = mysqli_connect($hostname, $username, $password, $dbname);
		mysqli_set_charset($this->conn,"utf8");
		
		if(array_key_exists('req', $_REQUEST)){
			if($_REQUEST['req'] == 'session'){
				if(array_key_exists('aj_action', $_REQUEST)){

					if($_REQUEST['aj_action'] == 'getUsers'){
						$this->getUsers();
					}
					else if($_REQUEST['aj_action'] == 'getEducations'){
						$this->getEducations();
					}						
					else if($_REQUEST['aj_action'] == 'getCities'){
						$this->getCities();
					}
					else if($_REQUEST['aj_action'] == 'editUser'){
						$this->editUser();
					}								
					else{
						//action is unknown therefore invalid request
						$this->ajaxResponse(1005,"action is unknown","ERR");
					}
				}// array_key_exists
				else{
					//action is not set therefore invalid request
					$this->ajaxResponse(1004,"action is not set","ERR");
				}

			}// $_REQUEST['req'] == 'session'
			else{
				//req is not session therefore invalid request
				$this->ajaxResponse(1002,"req is not session","ERR");
			}
		}// array_key_exists
		else{
			//req is not set therefore invalid request
			$this->ajaxResponse(1001,"req is not set","ERR");
		}
	}

	private function getUsers(){ 
	
		$idUser = isset($_REQUEST['idUser']) ? $_REQUEST['idUser'] : 0;
		$filter_name = isset($_REQUEST['filter_name']) ? $_REQUEST['filter_name'] : '';
		$filter_city = isset($_REQUEST['filter_city']) ? $_REQUEST['filter_city'] : '';
		$filter_education = isset($_REQUEST['filter_education']) ? $_REQUEST['filter_education'] : '';
		
		$andUser = '';
		if ($idUser > 0) {
			$andUser = " and users.idUser = $idUser ";
		}
		$andName = '';
		if ($filter_name != '') {
			$andName = " and lcase(users.userName) like lcase('%$filter_name%') ";
		}	
		$andCity = '';
		if ($filter_city != '') {
			$andCity = " and lcase(userscity.city) like lcase('$filter_city') ";
		}	
		$andEducation = '';
		if ($filter_education != '') {
			$andEducation = " and lcase(usersinfo.education) like lcase('$filter_education') ";
		}		
		
		$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 100;
		$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
		
        $response = array();	

		$sqlText = " 
			SELECT 
				users.idUser,
				users.userName,
				userscity.city,
				usersinfo.education,
				usersinfo.email,
				usersinfo.phone,
				usersinfo.comments
			FROM users
			INNER JOIN userscity on users.idUser = userscity.idUser
			INNER JOIN usersinfo on users.idUser = usersinfo.idUser
			WHERE users.idUser > 0 
			$andUser
			$andName
			$andCity
			$andEducation
		";

		$result = mysqli_query($this->conn, $sqlText);
		$nodes = array();
		if($result and mysqli_num_rows($result) > 0){
			while ($row = mysqli_fetch_assoc($result)) {
				$nodes[] = array(
					"idUser"=>$row['idUser'],
					"userName"=>$row['userName'], 
					"city"=>$row['city'],
					"education"=>$row['education'],
					"email"=>$row['email'],
					"phone"=>$row['phone'],
					"comments"=>$row['comments']
				);
			}

			$response['data'] = $nodes;
			$this->ajaxResponse(0,"","DATA", $nodes,$response);
		}
		else{
			$this->ajaxResponse(1,"no result","DATA", null, $response);
		}	
	}

	private function getEducations(){ 
		$response = array();	
		$sqlText = " 
			SELECT distinct education FROM usersinfo
		";
							
		$result = mysqli_query($this->conn, $sqlText);

		$nodes = array();
		if($result and mysqli_num_rows($result) > 0){
			while ($row = mysqli_fetch_assoc($result)) {
				$nodes[] = array("education"=>$row['education']);
			}
			$this->ajaxResponse(0,"","DATA", $nodes,$response);
		}
		else{
			$this->ajaxResponse(1,"no result","DATA", null, $response);
		}	
	}	
	
	private function getCities(){ 
		$response = array();	
		$sqlText = " 
			SELECT distinct city FROM userscity
		";
							
		$result = mysqli_query($this->conn, $sqlText);

		$nodes = array();
		if($result and mysqli_num_rows($result) > 0){
			while ($row = mysqli_fetch_assoc($result)) {
				$nodes[] = array("city"=>$row['city']);
			}
			$this->ajaxResponse(0,"","DATA", $nodes,$response);
		}
		else{
			$this->ajaxResponse(1,"no result","DATA", null, $response);
		}	
	}
	
	
	private function editUser(){ 

	
		$idUser = isset($_REQUEST['idUser']) ? $_REQUEST['idUser'] : 0;
		//$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
		$education = isset($_REQUEST['education']) ? $_REQUEST['education'] : '';
		
        $response = array();	
		$response['success'] = false;

		$sqlText = "							
			UPDATE usersinfo SET education = '$education' WHERE idUser = $idUser
		";
		

		$result = mysqli_query($this->conn, $sqlText);
		
		if($result){
			
			$response['success'] = true;
			$this->ajaxResponse(0,"","DATA", '',$response);
			
		}
		else{
			$this->ajaxResponse(1,"error","DATA", null, $response);
		}	
	}

}
?>
