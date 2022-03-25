<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;
use App\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;

class RegisterAuthAction extends AuthAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
		 try {
  	  $data = $this->getFormData();
  	  $this->validateNewRegister($data);
      $name = $data["name"];
      $email = $data["email"];
      $datapassword = $data["password"];
      $verify = $this->EmailExist($email);
      if(!$verify){
      $responsedata=['status'=>'success','message'=>'Email ID already Exist'];
                return $this->respondWithData($responsedata);  
      }
      else{
      $password = $this->hashPassword($datapassword);
      $sql = "INSERT INTO user (name, email, password) VALUES (:name, :email, :password)";
      $conn = $this->_db->connect();
      $stmt =  $conn->prepare($sql);
      $stmt->bindParam("name", $name);
      $stmt->bindParam("email", $email);
      $stmt->bindParam("password", $password);
      $stmt->execute();
      $responsedata=array('status'=>'success','message'=>'Registered successfully');
      return $this->respondWithData($responsedata);
      }

     } catch (PDOException $e) {
       $error = array(
         "message" => $e->getMessage()
       );
        return $this->respondWithData($error);
     }
   
    }

    private function validateNewRegister(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (!isset($data['email'])) {
            $errors['email'] = 'Input required';
        }

        if (!isset($data['password'])) {
            $errors['password'] =  'Input required';
        } 
        if (!isset($data['name'])) {
            $errors['name'] =  'Input required';
        } 
        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}
