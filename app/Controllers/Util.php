<?php

class Controllers_Util extends RestController {

    public function get() {

    }
    public function dict() {

    header('Content-type: text/javascript');

    echo "
     
     var my_dictionary = {
        'some text':      '"._l("Newest Videos")."',
        'Upload a file': '"._l("Upload a file")."',
        '1': '"._l("1")."',
        '2': '"._l("2")."',
        '3': '"._l("3")."',
        '4': '"._l("4")."',
        '5': '"._l("5")."',
        '6': '"._l("6")."',
        '7': '"._l("7")."',
        '8': '"._l("8")."',
        '9': '"._l("9")."',
        '0': '"._l("0")."',
        'Next »': '"._l("Next »")."',
        '« Previous': '"._l("« Previous")."',
        'January': '"._l("January")."',
        'February': '"._l("February")."',
        'March': '"._l("March")."',
        'April': '"._l("April")."',
        'May': '"._l("May")."',
        'June': '"._l("June")."',
        'July': '"._l("July")."',
        'August': '"._l("August")."',
        'September': '"._l("September")."',
        'October': '"._l("October")."',
        'November': '"._l("November")."',
        'December': '"._l("December")."'

    }
    $.i18n.setDictionary(my_dictionary)

    ";
         
    exit;   
    }
   
    public function getcategory() {

     $categories = (Models_Video::getCategoriesList()); 
     $this->response =  $categories;

  
     $this->responseStatus = 201;   
      
    }

    public function post() {
        $this->response = array('TestResponse' => 'I am POST response. Variables sent are - ' . http_build_query($this->request['params']));
        $this->responseStatus = 201;
    }

    public function put() {
        $this->response = array('TestResponse' => 'I am PUT response. Variables sent are - ' . http_build_query($this->request['params']));
        $this->responseStatus = 200;
    }

    public function delete() {
        $this->response = array('TestResponse' => 'I am DELETE response. Variables sent are - ' . http_build_query($this->request['params']));
        $this->responseStatus = 200;
    }

}
