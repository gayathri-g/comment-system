<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exception\ValidationException;
use App\Controllers\GenerateTokenController;

class LoginAuthAction extends AuthAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
		 try {
			$data = $this->getFormData();
			$this->validateNewLogin($data);
			$email = $data["email"];
			$password = $data["password"];
			$verify=$this->EmailExist($email);
			if($verify){
				$responsedata=['status'=>'success','message'=>'Email ID not Exist'];
                return $this->respondWithData($responsedata);
			}
			else{
				    $email="'".$email."'";
					$sql = "SELECT password FROM user where email = ".$email;
					$conn = $this->_db->connect();
					$stmt =  $conn->prepare($sql);
					$stmt->execute();
					$users = $stmt->fetchObject();
		            $hashedPassword = $users->password;
		            $verifypswd = password_verify($password,$hashedPassword);
				            if($verifypswd==false)
				            {
				              $responsedata=['status'=>'success','message'=>'Password Incorrect'];
				             return $this->respondWithData($responsedata);	
				            }
				            else{
				            $responseMessage = GenerateTokenController::generateToken($email);
				            return $this->respondWithData($responseMessage);
				            }
			}
			
		 } catch (PDOException $e) {
		   $error = array(
		     "message" => $e->getMessage()
		   );
		    return $this->respondWithData($error);
		 }
   
    }
    private function validateNewLogin(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (!isset($data['email'])) {
            $errors['email'] = 'Input required';
        }

        if (!isset($data['password'])) {
            $errors['password'] =  'Input required';
        } 

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}
