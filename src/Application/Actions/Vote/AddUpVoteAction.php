<?php

declare(strict_types=1);

namespace App\Application\Actions\Vote;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exception\ValidationException;
class AddUpVoteAction extends VoteAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
		 try {
			$data = $this->getFormData();
			$this->validateVote($data);
			$comment_id = $data["comment_id"];
            $updated_at=date('Y-m-d H:i:s');
            $this->validateCommentExist((int) $comment_id);
            $this->validateVoteModerator((int) $comment_id);
			$sql = "UPDATE comments  SET `upvote` = CASE
    WHEN `upvote` IS NULL THEN 1
    ELSE `upvote` + 1
    END WHERE comment_id =".$comment_id;
			$conn = $this->_db->connect();
			$stmt =  $conn->prepare($sql);
			$stmt->execute();
			$this->logger->info("Upvote Added - Comment ID is `${comment_id}`");
			$responsedata=['status'=>'success','message'=>'Upvote Added Successfully'];
			return $this->respondWithData($responsedata);
		 } catch (PDOException $e) {
		   $error = array(
		     "message" => $e->getMessage()
		   );
		    return $this->respondWithData($error);
		 }
   
    }
}
