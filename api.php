<?php    
	/* 
	* API Class for login validation and preference details 
	*/
	
	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";
		
		const DB_SERVER = "localhost";
		const DB_USER = "root";
		const DB_PASSWORD = "root";
		const DB = "ramp";
		
		private $db = NULL;
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db)
				mysql_select_db(self::DB,$this->db);
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$email = $_POST['username'];		
			$pswd = $_POST['password'];
			
			// Input validations
			if(!empty($email) and !empty($pswd)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$sql = mysql_query("select USER_ID,FIRST_NAME, LAST_NAME from ramp.RAMP_USERS where EMAIL='$email' and PWD='$pswd' and ACT_IND=1 LIMIT 1", $this->db);
					if(mysql_num_rows($sql) > 0){
						$result = mysql_fetch_array($sql,MYSQL_ASSOC);
						$results = array('status' => "Success", "data" => $result);
						
						// If success everythig is good send header as "OK" and user details
						$this->response($this->json($results), 200);
					}
					$res=array('status' => "failed", "msg" => "No data found");
					$this->response($this->json($res), 400);	// If no records "No Content" status
				}
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
			$this->response($this->json($error), 400);
		}
		
		
		# Preference API
		private function preferences(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$email = $_POST['usern'];		
			//$pswd = $_POST['pswd'];
			
			// Input validations
			if(!empty($email)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$sql = mysql_query("SELECT W.WIDGET_NAME,W.WIDGET_TYPE_ID FROM ramp.WIDGET W 
WHERE W.WIDGET_ID IN(SELECT P.WIDGET_ID FROM ramp.PREFERENCES P WHERE P.USER_ID = (SELECT  U.USER_ID FROM  ramp.RAMP_USERS U WHERE U.EMAIL = '$email' and U.ACT_IND=1) and P.ACT_IND=1)", $this->db);
					
					if(mysql_num_rows($sql) > 0){
						//$dbres = array();
						$i = 0;
						while($result = mysql_fetch_array($sql,MYSQL_ASSOC)){
							$dbres[$i]['wn']=$result['WIDGET_NAME'];
							$dbres[$i]['wtid']=$result['WIDGET_TYPE_ID'];
							$typid = $result['WIDGET_TYPE_ID'];
							$sql1 = mysql_query("SELECT `WIDGET_TYPE_NAME` FROM ramp.WIDGET_MASTER WM WHERE WM.WIDGET_TYPE_ID = $typid");
							$value = mysql_fetch_array($sql1);
							$dbres[$i]['wtname'] = $value['WIDGET_TYPE_NAME'];
							$i++;
						}
						
						//$result = array('status' => "Success", "msg" => "valid Email address and Password");
						$result = $this->json($dbres);
						// If success everythig is good send header as "OK" and user details
						$this->response($result, 200);
					}
					$res=array('status' => "failed", "msg" => "No data found");
					$this->response($this->json($res), 400);	// If no records "No Content" status
				}
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Preference details");
			$this->response($this->json($error), 400);
		}
		
		
		# API for all widgets
		
		private function widgetlist(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
						
				/*$sql = mysql_query("SELECT `widgetsName`,`widgetsTypeid` FROM `widgets` 
										WHERE status=1", $this->db);
					if(mysql_num_rows($sql) > 0){
						//$dbres = array();
						$i = 0;
						while($result = mysql_fetch_array($sql,MYSQL_ASSOC)){
							$dbres[$i]['wn']=$result['widgetsName'];
							$dbres[$i]['wtid']=$result['widgetsTypeid'];
							$typid = $result['widgetsTypeid'];
							$sql1 = mysql_query("SELECT `widgetstypeName` FROM `widgets_master` WHERE `widgetsTypeid` = $typid");
							$value = mysql_fetch_array($sql1);
							$dbres[$i]['wtname'] = $value['widgetstypeName'];
							$i++;
						}
						
						//$result = array('status' => "Success", "msg" => "valid Email address and Password");
						$result = $this->json($dbres);
						// If success everythig is good send header as "OK" and user details
						$this->response($result, 200);
					}
					$res=array('status' => "failed", "msg" => "No data found");
					$this->response($this->json($res), 400);	// If no records "No Content" status*/
					$qry = "Select widget_type_id, widget_type_name from WIDGET_MASTER where act_ind=1";

					$exqry = mysql_query($qry);
					$arr1 =array();
					$i =0;
					while($row = mysql_fetch_array($exqry)){
 					$wtid = $row['widget_type_id'];
 					$subqry = "select widget_id, widget_name from WIDGET where widget_type_id = $wtid and act_ind=1";
 					$exqry1 = mysql_query($subqry);
 					$arr11 = array();
 					$k=0;
 					while($row1 = mysql_fetch_array($exqry1)){
 						$arr11[$k] = array('wid'=>$row1['widget_id'],'widname'=>$row1['widget_name']);
 						$k++;
 					}
 					$arr1[$i]=array('widgetTypeName'=>$row['widget_type_name'],'widgets'=>$arr11);
 					$i++; 	
 				} 
 				$resp = $arr1;
 				//$result = array('status' => "Success", "msg" => "valid Email address and Password");
				$result = $this->json($resp);
				// If success everythig is good send header as "OK" and user details
				$this->response($result, 200);
			//}
			//$res=array('status' => "failed", "msg" => "No data found");
			//$this->response($this->json($res), 400);	// If no records "No Content" status
		}
		
		# SetFavorite API
		private function setFavorite(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$email = $_POST['usern'];		
			$widgetid = $_POST['wid'];
			
			// Input validations
			if(!empty($email)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$sql = mysql_query("SELECT `id` FROM `ramp_users` WHERE `email` = '$email' and status=1", $this->db);
					if(mysql_num_rows($sql) > 0){
						$value = mysql_fetch_array($sql);
						$userid = $value['id'];
						
						$updsql = "INSERT into preferences (`userid`,`widgetsid`,`status`) values($userid,$widgetid,1)";
						$updqry = mysql_query($updsql, $this->db);
						if($updqry === true){ 
						
						$result = array('status' => "Success", "msg" => "Widget set as favorite");
						//$result = $this->json($dbres);
						// If success everythig is good send header as "OK" and user details
						$this->response($this->json($result), 200);
						}
						else {
							$res=array('status' => "failed", "msg" => "Not able to set favorite, check the data");
							$this->response($this->json($res), 400);
						}
					}
					$res=array('status' => "failed", "msg" => "No data found");
					$this->response($this->json($res), 400);	// If no records "No Content" status
				}
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Preference details");
			$this->response($this->json($error), 400);
		}
		
		
		
		
		private function users(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$sql = mysql_query("SELECT user_id, first_name, last_name, email FROM RAMP_USERS WHERE act_ind = 1", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>
