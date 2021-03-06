<?php
class API_Student extends API {
    public function index() {
        $student = new Student();
        return $student->userData();
    }
    public function login() {
        
        if (isset($_POST['Email']))
            $username = $_POST['Email'];
        else $username = '';
        
        if (isset($_POST['Password']))
            $password = $_POST['Password'];
        else $password = '';
        
        if (isset($_POST['auto']))
            $auto = $_POST['auto'];  //To remember user with a cookie for autologin
        else $auto = false;  //To remember user with a cookie for autologin

        $student = new Student();

        //Login with credentials
        $student->login($username,$password,$auto);
        
        $return_arr = array();
        
        //not required, just an example usage of the built-in error reporting system
        if($student->isSigned()){
            $return_arr = ['status' => "success"];
        }else{
            //Display Errors
            $errors = array();
            foreach($student->log->getErrors() as $err){
                $errors[] = $err;
            }
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        
        return $return_arr;
    }
    public function sendActivated($email) {
        $student = new Student();
        $user = $student->table->getRow(array('Email' => $email));
        sendMail::send($user->Email, 'Activated your account', 'Hello ' . $user->first_name . ', Welcome to madeinJLM Jobs! <br>Please click on the link at the bottom to activated your account.<br>Click <a href="http://job.madeinjlm.org/MadeinJLM-students/mockup/API/Student/activated?c=' . $user->Confirmation . '">here</a>.<br>If you didn\'t signed out for this website, please notify us at: madeinjlm.jce@gmail.com');
    }
    public function register() {
        
        $student = new Student();
        
        $return_arr = array();
        
        $input = new Collection($_POST);

        $registered = $student->register(array(
                'Email'     => $input->email,
                'Password'  => $input->passsword,
                'Password2' => $input->passsword2,
                'first_name'  => $input->first_name,
                'last_name'  => $input->last_name,
            ),true);

        if($registered){
			$this->sendActivated($input->email);
            $return_arr = ['status' => "success"];
        }else{
            //Display Errors
            $errors = array();
            foreach($student->log->getErrors() as $err){
                $errors[] = $err;
            }
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        
        return $return_arr;
    }
    public function activated() {
        
        $student = new Student();
        
        $return_arr = array();
        
        if ($student->activate($_GET['c'])) 
            $return_arr = ['status' => "success"];
        else {
            
            //Display Errors
            $errors = array();
            foreach($student->log->getErrors() as $err){
                $errors[] = $err;
            }
            $return_arr =  ['status' => "error",'errors' => $errors];
            
        } 
        
        return $return_arr;
    }
    public function logOut() {
        
        $student = new Student();
        
        $student->logout();
        return ['status' => "success"];
        
    }
    public function resetPassword() {
        if (isset($_POST['Email']))
            $email = $_POST['Email'];
        else $email = '';
        
        $student = new Student();

        //Login with credentials
        if ($data = $student->resetPassword($email)) {
			// send email to $data->Email whith the confirmation $data->Confirmation
			sendMail::send($data->Email, 'Reset your password','Hello ' . $user->first_name . ' please click on the link at the bottom to reset your password.<br>click <a href="http://job.madeinjlm.org/MadeinJLM-students/mockup/#new-password/' . $data->Confirmation . '">here</a>');
			$return_arr = ['status' => "success"];
		} else {
            //Display Errors
            $errors = array();
            foreach($student->log->getErrors() as $err){
                $errors[] = $err;
            }
            $return_arr =  ['status' => "error",'errors' => $errors];
		}
        
        return $return_arr;
    }
    public function newPassword() {
        if (isset($_POST['hash']))
            $hash = $_POST['hash'];
        else $hash = '';
        
        if (isset($_POST['newPass']))
            $newPass = $_POST['newPass'];
        else $newPass = '';
        
        $student = new Student();
		
        //Login with credentials
        if ($data = $student->newPassword($hash,$newPass)) {
			$return_arr = ['status' => "success",$newPass];
		} else {
            //Display Errors
            $errors = array();
            foreach($student->log->getErrors() as $err){
                $errors[] = $err;
            }
            $return_arr =  ['status' => "error",'errors' => $errors];
		}
        
        return $return_arr;
    }
    public function changePassword() {
        $student = new Student();
        
        if ($data = $student->update($_POST)) {
            $return_arr = ['status' => "success"];
        } else {
             $errors = array();
            foreach($student->log->getErrors() as $err){
                $errors[] = $err;
            }
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        return $return_arr;

    }
    public function changeStatus()
    {
		$student = new Student();
		if($student->isSigned()){
			if ($data = $student->changeStatus($_POST)) {
				$return_arr = ['status' => "success"];
			} else {
				$errors = array();
				foreach($student->log->getErrors() as $err){
					$errors[] = $err;
				}
				$return_arr =  ['status' => "error",'errors' => $errors];
			}
        }else{
			$errors = array('User not connected');
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        
        return $return_arr;
    }
    public function update() {
        $student = new Student();
        
        if ($data = $student->update($_POST)) {
            $return_arr = ['status' => "success"];
        } else {
             $errors = array();
            foreach($student->log->getErrors() as $err){
                $errors[] = $err;
            }
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        return $return_arr;
    }
    public function managementTableInfo() {

    }
    public function deleteTableInfo() {

    }
    public function addSkill() {
		$student = new Student();
		if($student->isSigned()){
			
			if ($data = $student->addSkill($_POST)) {
				$return_arr = ['status' => "success",'data' => $data];
			} else {
				$errors = array();
				foreach($student->log->getErrors() as $err){
					$errors[] = $err;
				}
				$return_arr =  ['status' => "error",'errors' => $errors];
			}
			
        }else{
			$errors = array('User not connected');
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        return $return_arr;
    }
    public function deleteSkill() {
		$student = new Student();
		if (!isset($_POST['id'])) {
			$errors = array('Error');
            $return_arr =  ['status' => "error",'errors' => $errors];
			return $return_arr;
		}
		if($student->isSigned()){
			
			if ($data = $student->deleteSkill($_POST['id'])) {
				$return_arr = ['status' => "success"];
			} else {
				$errors = array();
				foreach($student->log->getErrors() as $err){
					$errors[] = $err;
				}
				$return_arr =  ['status' => "error",'errors' => $errors];
			}
			
        }else{
			$errors = array('User not connected');
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        return $return_arr;
    }
    public function uploadProfile() {
		$student = new Student();
		
		if($student->isSigned()){
			
			if ($data = $student->uploadProfile($_POST['picture'])) {
				$return_arr = ['status' => "success"];
			} else {
				$errors = array();
				foreach($student->log->getErrors() as $err){
					$errors[] = $err;
				}
				$return_arr =  ['status' => "error",'errors' => $errors];
			}
        }else{
            //Display Errors
			$errors = array('User not connected');
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        
        return $return_arr;
		
    }
    public function uploadCV() {
		$student = new Student();
		
		if($student->isSigned()){
			if(!isset($_FILES['file'])){
				$return_arr =  ['status' => "nofile"];
			} else if ($data = $student->uploadCV($_FILES['file'])) {
				$return_arr = ['status' => "success",'new_cv' => $data];
			} else {
				$errors = array();
				foreach($student->log->getErrors() as $err){
					$errors[] = $err;
				}
				$return_arr =  ['status' => "error",'errors' => $errors];
			}
        }else{
            //Display Errors
			$errors = array('User not connected');
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        
        return $return_arr;
    }
    public function myCV() {
		$student = new Student();
		
		if($student->isSigned()){
			header("Content-Type: application/octet-stream");

			$file = 'uploads/cv/' . $student->cv;

			header("Content-Disposition: attachment; filename=CV.".pathinfo($file, PATHINFO_EXTENSION));   
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Description: File Transfer");            
			header("Content-Length: " . filesize($file));
			flush(); // this doesn't really matter.
			$fp = fopen($file, "r");
			while (!feof($fp))
			{
				echo fread($fp, 65536);
				flush(); // this is essential for large downloads
			} 
			fclose($fp); 
			die();
        }else{
            //Display Errors
			$errors = array('User not connected');
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        
        return $return_arr;
		
    }
    public function deleteCV() {
		$student = new Student();
		
		if($student->isSigned()){
			
			if ($data = $student->deleteCV()) {
				$return_arr = ['status' => "success"];
			} else {
				$errors = array();
				foreach($student->log->getErrors() as $err){
					$errors[] = $err;
				}
				$return_arr =  ['status' => "error",'errors' => $errors];
			}
        }else{
			$errors = array('User not connected');
            $return_arr =  ['status' => "error",'errors' => $errors];
        }
        
        return $return_arr;
		
    }
}
