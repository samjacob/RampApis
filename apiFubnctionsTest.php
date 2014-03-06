<?php
require_once("apiFunctions.php");

Class ApiFunctionsTest extends PHPUnit_Framework_TestCase{
	var $testobj;
	
	function setUp(){
		$this->testobj = new API1();
	}
	/**
     * @dataProvider provider
     */
    

    function testvalidatelogin($a,$b){		
		$res = "Success";
		$result = $this->testobj->login($a, $b);
		$this->assertEquals($res, $result);			
	}
	
	public function Provider()
	{
		return array(
				array('samjacob.vethanayagam@cognizant.com','jaaneman79'),
				array('VinodKumar.Radhakrishnan@cognizant.com','vinod')
		);
		
	}	
}


?>
