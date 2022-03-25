<?php

declare(strict_types=1);

namespace App\Application\Actions\Reply;

use Psr\Http\Message\ResponseInterface as Response;

class ViewTopReplyAction extends ReplyAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
      try {
          $commentId = (int) $this->resolveArg('id');
          $this->validateCommentExist($commentId);
          $sql = "SELECT * FROM comments where parent_id <> '' and parent_id=".$commentId." order by comment_id desc limit 5";
          $conn = $this->_db->connect();
          $stmt =  $conn->prepare($sql);
          /*print_r($stmt);
          exit;*/
          $stmt->execute();
          $comments = $stmt->fetchAll();
          $this->logger->info("Comment of id `${commentId}` replies was viewed.");
          return $this->respondWithData($comments);
      } catch (PDOException $e) {
          $error = array(
          "message" => $e->getMessage()
          );
          return $this->respondWithData($error);
      }

    }

}