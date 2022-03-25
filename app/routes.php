<?php

declare(strict_types=1);

use App\Application\Actions\Comment\ListAllCommentsAction;
use App\Application\Actions\Comment\ViewCommentAction;
use App\Application\Actions\Comment\AddCommentAction;
use App\Application\Actions\Comment\EditCommentAction;
use App\Application\Actions\Comment\DeleteCommentAction;

use App\Application\Actions\Reply\ListAllReplyAction;
use App\Application\Actions\Reply\ViewTopReplyAction;
use App\Application\Actions\Reply\AddReplyAction;


use App\Application\Actions\Report\AddReportAction;

use App\Application\Actions\Vote\AddDownVoteAction;
use App\Application\Actions\Vote\AddUpVoteAction;
use App\Application\Actions\Vote\ListAllDownVoteAction;
use App\Application\Actions\Vote\ListAllUpVoteAction;
use App\Application\Actions\Vote\ViewDownVoteAction;
use App\Application\Actions\Vote\ViewUpVoteAction;


use App\Application\Actions\Auth\LoginAuthAction;
use App\Application\Actions\Auth\RegisterAuthAction;


use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });


$app->get('/api/route-csrf',function ($request, $response, $args) {
    $csrf = $this->get('csrf');
    $nameKey = $csrf->getTokenNameKey();
    $valueKey = $csrf->getTokenValueKey();
    $name = $request->getAttribute($nameKey);
    $value = $request->getAttribute($valueKey);

    $tokenArray = [
        $nameKey => $name,
        $valueKey => $value
    ];
    
    $response->getBody()->write(json_encode($tokenArray));
    return $response;
});

$app->group('/api', function (Group $group) {
$group->get('/users', ListUsersAction::class);
$group->get('/users/{id}', ViewUserAction::class);

$group->get('/comments', ListAllCommentsAction::class);
$group->get('/comment/{id}', ViewCommentAction::class);
$group->post('/comment/add', AddCommentAction::class);
$group->post('/comment/update', EditCommentAction::class);
$group->get('/comment/delete/{id}', DeleteCommentAction::class);


$group->get('/replies', ListAllReplyAction::class);
$group->get('/reply/{id}', ViewTopReplyAction::class);
$group->post('/reply/add', AddReplyAction::class);

$group->post('/upvote/add', AddUpVoteAction::class);
$group->post('/downvote/add', AddDownVoteAction::class);
$group->get('/upvote/{id}', ViewUpVoteAction::class);
$group->get('/downvote/{id}', ViewDownVoteAction::class);
$group->get('/upvote', ListAllUpVoteAction::class);
$group->get('/downvote', ListAllUpVoteAction::class);


$group->post('/report/add', AddReportAction::class);


});
$app->group("/auth",function($app)
{
   $app->post("/login",LoginAuthAction::class);
    $app->post("/register",RegisterAuthAction::class);
});

};
