<?php

declare(strict_types=1);

namespace App\Application\Actions\Comment;
use App\Models\DBConnection;
use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Exception\ValidationException;

abstract class CommentAction extends Action
{
    //protected CommentRepository $commentRepository;
    public function __construct(LoggerInterface $logger,DBConnection $_db)
    {
        parent::__construct($logger,$_db);
        //$this->commentRepository = $commentRepository;
    }
    public function validateCommentExist(int $id)
    {
          $sql = "SELECT created_at FROM comments where status = 1 and comment_id=".$id;
          $conn = $this->_db->connect();
          $stmt =  $conn->prepare($sql);
          $stmt->execute();
          $comments = $stmt->fetchObject();
          if($comments==""){
               $responsedata=array('status'=>'failure','message'=>'Comment Not Found');
                throw new ValidationException('Comment Not Found or Comment Deleted', $responsedata);
          }
          else{
          	return $comments->created_at;
          }
    }
}
