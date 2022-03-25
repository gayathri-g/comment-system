<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use App\Models\DBConnection;
use App\Exception\ValidationException;
abstract class Action
{
    protected LoggerInterface $logger;

    protected Request $request;

    protected Response $response;

    protected array $args;
    protected DBConnection $_db;

    public function __construct(LoggerInterface $logger,DBConnection $_db)
    {
        $this->logger = $logger;
       $this->_db=$_db;
  
   
    }

    /**
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->action();
        } catch (DomainRecordNotFoundException $e) {
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }

    /**
     * @throws DomainRecordNotFoundException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return array|object
     */
    protected function getFormData()
    {
        return $this->request->getParsedBody();
    }

    /**
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param array|object|null $data
     */
    protected function respondWithData($data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    protected function respond(ActionPayload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus($payload->getStatusCode());
    }
    public function validateCommentExist(int $id)
    {
          $sql = "SELECT created_at,status FROM comments where comment_id=".$id;
          $conn = $this->_db->connect();
          $stmt =  $conn->prepare($sql);
          $stmt->execute();
          $comments = $stmt->fetchObject();
          
          if($comments==""){
               $responsedata=array('status'=>'failure','message'=>'Comment Not Found');
                throw new ValidationException('Comment Not Found', $responsedata);
          }
          else if(isset($comments->status)==0){
                 $responsedata=array('status'=>'failure','message'=>'Comment Already Deleted');
                throw new ValidationException('Comment Already Deleted', $responsedata);
          }
          else{
            return $comments->created_at;
          }
    }
    Public function validateVote(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (!isset($data['comment_id'])) {
            $errors['comment_id'] =  'Input required';
        } 

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
    Public function validateVoteModerator(int $id)
    {

        $sqluid = "SELECT user_id FROM comments where comment_id =".$id;
        $connuid = $this->_db->connect();
        $stmtuid =  $connuid->prepare($sqluid);
        $stmtuid->execute();
        $uid = $stmtuid->fetchObject();


        $sql = "SELECT SUM(upvote) as totalupvote,user_id FROM comments where user_id =".$uid->user_id;
        $conn = $this->_db->connect();
        $stmt =  $conn->prepare($sql);
        $stmt->execute();
        $comments = $stmt->fetchObject();

          if($comments->totalupvote>=10){
               $sql = "UPDATE user SET moderator = 1 WHERE id = ".$comments->user_id;
      $conn = $this->_db->connect();
      $stmt =  $conn->prepare($sql);
      $stmt->execute();
          }
    }
}
