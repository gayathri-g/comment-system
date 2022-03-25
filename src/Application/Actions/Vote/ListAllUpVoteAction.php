<?php

declare(strict_types=1);

namespace App\Application\Actions\Vote;

use Psr\Http\Message\ResponseInterface as Response;

class ListAllUpVoteAction extends VoteAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
		 try {
			$sql = "SELECT * FROM comments where status = 1 and upvote <> '' ";
			$conn = $this->_db->connect();
			$stmt =  $conn->prepare($sql);
			$stmt->execute();
			$comments = $stmt->fetchAll();
			$this->logger->info("Reply list was viewed.");
			return $this->respondWithData($comments);
		 } catch (PDOException $e) {
		   $error = array(
		     "message" => $e->getMessage()
		   );
		    return $this->respondWithData($error);
		 }
   
    }
}
