<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    //$app->add(SessionMiddleware::class);
    // $app->addRoutingMiddleware();
  $app->add(
      new \Tuupola\Middleware\JwtAuthentication([
        "ignore"=>["/auth/login","/auth/register","/api/route-csrf","/api/users","/api/users/{id}"],
          "secret"=>\App\Interfaces\SecretKeyInterface::JWT_SECRET_KEY,
          "error"=>function($response,$arguments)
          {
              $data["success"] = false;
              $data["response"]=$arguments["message"];
              $data["status_code"]= "401";

              return $response->withHeader("Content-type","application/json")
                  ->getBody()->write(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ));
          }
      ])
  );
  //$app->addErrorMiddleware(true,true,true);
};
