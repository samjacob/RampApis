<?php
require_once("api.php");

Class validationApiTest extends PHPUnit_Framework_TestCase{
	var $testobj;
	
	function setUp(){
		$this->testobj = new validationApi();
	}
	
	
	function testvalidatelogin(){
		//$x = $a[0];
		//$y = $a[1];
		//$data = self::$dataSet;
		$res = 1;
		$result = $this->testobj->login('samjacob', '12345');
		$this->assertEquals($res, $result['status']);	
		$this->assertEquals(1, $result['userid']);
		$this->assertEquals('admin', $result['type']);
		$this->assertEquals('Sam Jacob Vethanayagam', $result['fullName']);
		//assertEquals(Array(),$result);*/
		//$this->expected = 1;
		//$this->actual = $this->testobj->validateUser($x,$y);
		//$this->assertEquals($this->expected, $this->actual);
	}
	
	/*public function Provider()
	{
		return array(
				array('samjacob','12345'),
				array('vinod','12345')
		);
		
	}*/
	
	
	function testgetPreference(){
		$useriden = $this->testobj->getPreference(1);
		if(is_array($useriden)){
			$this->assertEquals(2,$useriden[0]);
			$this->assertEquals(3,$useriden[1]);
		}else{
			$this->assertEquals(3,$useriden);
		}		
	}
	
}


?>
