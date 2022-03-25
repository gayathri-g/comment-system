<?php

declare(strict_types=1);

namespace App\Application\Actions\Comment;

use Psr\Http\Message\ResponseInterface as Response;

class ViewCommentAction extends CommentAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
      try {
          $this->validateCommentExist($comment_id);
          $commentId = (int) $this->resolveArg('id');
          $sql = "SELECT * FROM comments where parent_id IS NULL and comment_id=".$commentId;
          $conn = $this->_db->connect();
          $stmt =  $conn->prepare($sql);
          $stmt->execute();
          $comments = $stmt->fetchObject();
          $this->logger->info("Comment of id `${commentId}` was viewed.");
          return $this->respondWithData($comments);
      } catch (PDOException $e) {
          $error = array(
          "message" => $e->getMessage()
          );
          return $this->respondWithData($error);
      }

    }

}