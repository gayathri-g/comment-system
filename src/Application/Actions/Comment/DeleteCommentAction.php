<?php

declare(strict_types=1);

namespace App\Application\Actions\Comment;
use App\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteCommentAction extends CommentAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
		 try {

	  $comment_id = (int) $this->request->getAttribute('id');
	  $this->validateCommentExist($comment_id);
      $this->logger->info("Deleteing an comment", ['comment_id' => $comment_id]);
      $sql = "UPDATE comments SET status= 0 WHERE comment_id = :comment_id";
      $conn = $this->_db->connect();
      $stmt =  $conn->prepare($sql);
      $stmt->bindParam("comment_id", $comment_id);
      $stmt->execute();
      $responsedata=['status'=>'success','message'=>'Comment Deleted Successfully'];
      return $this->respondWithData($responsedata);
     } catch (PDOException $e) {
       $error = array(
         "message" => $e->getMessage()
       );
        return $this->respondWithData($error);
     }
   
    }
}
