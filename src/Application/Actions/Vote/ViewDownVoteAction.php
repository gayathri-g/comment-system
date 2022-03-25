<?php

declare(strict_types=1);

namespace App\Application\Actions\Vote;

use Psr\Http\Message\ResponseInterface as Response;

class ViewDownVoteAction extends VoteAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
      try {
          $commentId = (int) $this->resolveArg('id');
           $this->validateCommentExist((int) $commentId);
          $sql = "SELECT * FROM comments where downvote <> '' and comment_id=".$commentId;
          $conn = $this->_db->connect();
          $stmt =  $conn->prepare($sql);
          $stmt->execute();
          $comments = $stmt->fetchAll();
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