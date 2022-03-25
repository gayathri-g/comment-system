<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ListUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
            $sql = "SELECT * FROM user";
			$conn = $this->_db->connect();
			$stmt =  $conn->prepare($sql);
			$stmt->execute();
			$users = $stmt->fetchAll();
			$this->logger->info("User list was viewed.");
			return $this->respondWithData($users);
    }
}
