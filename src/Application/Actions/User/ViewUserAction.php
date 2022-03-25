<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
       try {
          $userid = (int) $this->resolveArg('id');
          $sql = "SELECT * FROM user where id=".$userid;
          $conn = $this->_db->connect();
          $stmt =  $conn->prepare($sql);
          $stmt->execute();
          $users = $stmt->fetchObject();
          $this->logger->info("user of id `${userid}` was viewed.");
          return $this->respondWithData($users);
      } catch (PDOException $e) {
          $error = array(
          "message" => $e->getMessage()
          );
          return $this->respondWithData($error);
      }
    }
}
