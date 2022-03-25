<?php

declare(strict_types=1);

namespace App\Application\Actions\Comment;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exception\ValidationException;
class AddCommentAction extends CommentAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
		 try {
			$data = $this->getFormData();
			$this->validateNewComment($data);
			$message = $data["message"];
			$blog_id = $data["blog_id"];
			$user_id = $data["user_id"];
            $created_at=date('Y-m-d H:i:s');
			$sql = "INSERT INTO comments (message, blog_id, user_id, status, created_at) VALUES (:message, :blog_id, :user_id, 1 , :created_at)";
			$conn = $this->_db->connect();
			$stmt =  $conn->prepare($sql);
			$stmt->bindParam("message", $message);
			$stmt->bindParam("blog_id", $blog_id);
			$stmt->bindParam("user_id", $user_id);
			$stmt->bindParam("created_at", $created_at);
			$stmt->execute();
			$last_id = $conn->lastInsertId();
			$this->logger->info("New Comment Added - Comment ID is `${last_id}`");
			$responsedata=['status'=>'success','message'=>'Comment Added Successfully',
	'comment_id'=>$last_id];
			return $this->respondWithData($responsedata);
		 } catch (PDOException $e) {
		   $error = array(
		     "message" => $e->getMessage()
		   );
		    return $this->respondWithData($error);
		 }
   
    }
    private function validateNewComment(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (!isset($data['message'])) {
            $errors['message'] = 'Input required';
        }

        if (!isset($data['blog_id'])) {
            $errors['blog_id'] =  'Input required';
        } 
        if (!isset($data['user_id'])) {
            $errors['user_id'] = 'Input required';
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}
