<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;
use App\Models\DBConnection;
use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;

abstract class AuthAction extends Action
{
    //protected CommentRepository $commentRepository;
    public function __construct(LoggerInterface $logger,DBConnection $_db)
    {
        parent::__construct($logger,$_db);
        //$this->commentRepository = $commentRepository;
    }


    public function hashPassword($password)
    {
        return password_hash($password,PASSWORD_DEFAULT);
    }

    public function EmailExist($email)
    {
          $email="'".$email."'";
          $sql = "SELECT name FROM user where email = ".$email;
          $conn = $this->_db->connect();
          $stmt =  $conn->prepare($sql);
          $stmt->execute();
          $users = $stmt->fetchObject();
        if(empty($users->name))
        {
      return true;
        }
        else{
          return false;
        }
        
    }

}
