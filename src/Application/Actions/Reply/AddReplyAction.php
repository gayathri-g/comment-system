<?php

declare(strict_types=1);

namespace App\Application\Actions\Reply;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exception\ValidationException;
class AddReplyAction extends ReplyAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
		 try {
			$data = $this->getFormData();
			$this->validateNewReply($data);
			$message = $data["message"];
			$parent_id = $data["parent_id"];
			$user_id = $data["user_id"];
            $created_at=date('Y-m-d H:i:s');
            $this->validateCommentExist((int) $parent_id);
            $this->validateMaxReply((int) $parent_id);
			$sql = "INSERT INTO comments (message, parent_id, user_id, status, created_at) VALUES (:message, :parent_id, :user_id, 1 , :created_at)";
			$conn = $this->_db->connect();
			$stmt =  $conn->prepare($sql);
			$stmt->bindParam("message", $message);
			$stmt->bindParam("parent_id", $parent_id);
			$stmt->bindParam("user_id", $user_id);
			$stmt->bindParam("created_at", $created_at);
			$stmt->execute();
			$last_id = $conn->lastInsertId();
			$this->logger->info("New Reply Added - Comment ID is `${last_id}`");
			$responsedata=['status'=>'success','message'=>'Reply Added Successfully',
	'comment_id'=>$last_id];
			return $this->respondWithData($responsedata);
		 } catch (PDOException $e) {
		   $error = array(
		     "message" => $e->getMessage()
		   );
		    return $this->respondWithData($error);
		 }
   
    }
    private function validateNewReply(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (!isset($data['message'])) {
            $errors['message'] = 'Input required';
        }

        if (!isset($data['parent_id'])) {
            $errors['parent_id'] =  'Input required';
        } 
        if (!isset($data['user_id'])) {
            $errors['user_id'] = 'Input required';
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
    public function validateMaxReply(int $id){
    	$sql = "SELECT count(*) as replycount FROM comments where parent_id =". $id;
          $conn = $this->_db->connect();
          $stmt =  $conn->prepare($sql);
          $stmt->execute();
          $reply = $stmt->fetchObject();
          if(($reply->replycount)>=50){
               $responsedata=array('status'=>'failure','message'=>'Reply maximum limit Exceed');
                throw new ValidationException('Reply maximum limit Exceed', $responsedata);
          }
    }
}
