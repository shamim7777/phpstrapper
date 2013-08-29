<?php

class Models_Update extends Application {

     
    public function Process($data, $table, $id) {
    	$f = 'id';
    	if($table == 'login_users')
    	$f = 'user_id';
    		
        $res = $this->db->update($table,$data, "$f='$id'");

        return $res;
    }
 
    public function Delete($table, $id) {
 
 		$f = 'id';
    	if($table == 'login_users')
    	$f = 'user_id';

        $res = $this->db->delete($table,"$f=$id", $f);

        return $res;
    }
 
}
