<?php

declare(strict_types=1);

namespace App\Application\Actions\Report;

use Psr\Http\Message\ResponseInterface as Response;
use App\Exception\ValidationException;
class AddReportAction extends ReportAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
		 try {
			$data = $this->getFormData();
			$this->validateNewReport($data);
			$comment_id = $data["comment_id"];
			$user_id = $data["user_id"];
            $created_at=date('Y-m-d H:i:s');
            $this->validateCommentExist((int) $comment_id);
            $this->validateReportCount((int) $comment_id);
			$sql = "INSERT INTO reports (comment_id, user_id,  created_at) VALUES (:comment_id, :user_id,  :created_at)";
			$conn = $this->_db->connect();
			$stmt =  $conn->prepare($sql);
			$stmt->bindParam("comment_id", $comment_id);
			$stmt->bindParam("user_id", $user_id);
			$stmt->bindParam("created_at", $created_at);
			$stmt->execute();
			$last_id = $conn->lastInsertId();
			$this->logger->info("New Report Added - Report ID is `${last_id}` and Comment ID is `${comment_id}`");
			$responsedata=['status'=>'success','message'=>'Report Added Successfully',
	'comment_id'=>$comment_id];
			return $this->respondWithData($responsedata);
		 } catch (PDOException $e) {
		   $error = array(
		     "message" => $e->getMessage()
		   );
		    return $this->respondWithData($error);
		 }
   
    }
    private function validateNewReport(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (!isset($data['comment_id'])) {
            $errors['comment_id'] = 'Input required';
        } 
        if (!isset($data['user_id'])) {
            $errors['user_id'] = 'Input required';
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
    private function validateReportCount(int $commentid)
    {
      
         $sql = "SELECT  COUNT(user_id) as usercount FROM reports where comment_id=".$commentid." group by user_id";
          $conn = $this->_db->connect();
          $stmt =  $conn->prepare($sql);
          $stmt->execute();
          $comment = $stmt->fetchObject();
          if(($comment->usercount)>=5){
				  	$sql = "UPDATE comments SET status= 0 WHERE comment_id =".$commentid;
				$conn = $this->_db->connect();
				$stmt =  $conn->prepare($sql);
				$stmt->execute();
				$responsedata=['status'=>'success','message'=>'Comment Hided Successfully',
				'comment_id'=>$commentid];
        $this->logger->info("Comment is `${commentid}` hided");
					//return $this->respondWithData($responsedata);
          }
    }
}
