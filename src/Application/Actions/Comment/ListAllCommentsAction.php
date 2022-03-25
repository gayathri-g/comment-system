<?php

declare(strict_types=1);

namespace App\Application\Actions\Comment;

use Psr\Http\Message\ResponseInterface as Response;

class ListAllCommentsAction extends CommentAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
		 try {
			$sql = "SELECT * FROM comments where parent_id IS NULL ";
			$conn = $this->_db->connect();
			$stmt =  $conn->prepare($sql);
			$stmt->execute();
			$comments = $stmt->fetchAll();
			$this->logger->info("Comments list was viewed.");
			return $this->respondWithData($comments);
		 } catch (PDOException $e) {
		   $error = array(
		     "message" => $e->getMessage()
		   );
		    return $this->respondWithData($error);
		 }
   
    }
}
