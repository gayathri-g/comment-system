# Slim Framework 4 Comment Application

##  List of API'S: 

- Login api - return(token with permissions)
- ADD,LIST,DELETE(change status to hide),EDIT(only for 5mins) comments api for logged users
- List comments api for Logged out users
- ADD,List(top5 list,all replies) api for reply
- Upvote/Downvote - Add vote api (Need to add condition here for "user who has received a sum of 10 upvotes on their comments becomes a moderator")
- Nested replies api to reply of a comment.(max of 50 nested replies)
- Report comments api(Need to hide when 5unique users report the same comment)
- Create role api for moderator
- Register api

##  Start the Application 

Run this command for running this application. You will require PHP 7.4 or newer.

```bash
$ cd [my-app-name]; php -S localhost:8080 -t public public/index.php
```

## API Details

###  For CSRF Creation: [Get Method] 

* Create csrf details `csrf_name,csrf_value` using following api `http://localhost:8080/api/route-csrf`

###  For Register API: [Post Method] 
* Url - `http://localhost:8080/auth/register`
* Request Parameter - csrf_name,csrf_value,name,password
* Response - data(`JWT` token)


###  For Login API: [Post Method] 

* Url - `http://localhost:8080/auth/login`
* Request Parameter - csrf_name,csrf_value,name,password,email

For all Get API'S Use Authorization Bearer token in header 

For all Post API'S Use Authorization Bearer token in header and csrf_name,csrf_value in request with other parameters


##  Database Details 

Database name : tvstestjob

Run default.sql(DB Folder of project root path)

## Packages Used

For Authentication:         
"tuupola/slim-jwt-auth": "^3.5"
"firebase/php-jwt": "^5.2"

For CSRF
"slim/csrf": "^1.2"

For Dependency Injection
"php-di/php-di": "^6.3"

For file log
"monolog/monolog": "^2.3"