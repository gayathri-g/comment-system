<?php

declare(strict_types=1);

namespace App\Application\Actions\Comment;
use App\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Carbon\Carbon;

class EditCommentAction extends CommentAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
       try {
      $data = $this->getFormData();
      $this->validateUpdateComment($data);
      $message = $data["message"];
      $comment_id = $data["comment_id"];
      $moderator = $data["moderator"];
      $updated_at=date('Y-m-d H:i:s');
      $commentcheck=$this->validateCommentExist((int) $comment_id);
          if($commentcheck){
              $date= Carbon::createFromFormat('Y-m-d H:i:s', $commentcheck, 'Asia/Kolkata');
              $date->setTimezone('IST');
              $mins=Carbon::parse($date)->diffInMinutes();
              if(($mins>=5) && ($moderator!=1)){
              $responsedata=['status'=>'success','message'=>'Comment Exceed Timelimit to Edit'];
                return $this->respondWithData($responsedata);
              }
          }
      $this->logger->info("Updating an comment", ['comment_id' => $comment_id, 'data' => $data]);
      $sql = "UPDATE comments SET message= :message, updated_at= :updated_at WHERE comment_id = :comment_id and status = 1";
      $conn = $this->_db->connect();
      $stmt =  $conn->prepare($sql);
      $stmt->bindParam("message", $message);
      $stmt->bindParam("comment_id", $comment_id);
      $stmt->bindParam("updated_at", $updated_at);
      $stmt->execute();
      $responsedata=['status'=>'success','message'=>'Comment Updated Successfully'];
      return $this->respondWithData($responsedata);
     } catch (PDOException $e) {
       $error = array(
         "message" => $e->getMessage()
       );
        return $this->respondWithData($error);
     }

    }

        private function validateUpdateComment(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (!isset($data['comment_id'])) {
            $errors['comment_id'] = 'Input required';
        }

        if (!isset($data['message'])) {
            $errors['message'] =  'Input required';
        } 
        if (!isset($data['moderator'])) {
            $errors['moderator'] =  'Input required';
        }
        //$diff_time = Carbon::parse($updated_at)->diffInMinutes();


        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }

}