markdown
# Task Management API

## Overview
This API allows users to manage
 tasks, including creating, updating, deleting, and searching ,and view all deleted and undeleted tasks .

## Setup 
cd C:\laragon\www
Clone this repository:
git clone https://github.com/enass2023/test.git

## Open project in terminal
 Execution of orders:

composer install
npm install
cp .env.example .env

php artisan key:generate --env=testing
php artisan passport:client --personal :  
    Take the data that was displayed (like the PASSPORT_PERSONAL_ACCESS_CLIENT_ID and PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET) and add it to  .env.testing file like :
    PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
    PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=SQIwSYukBxJJktsLR3vtF3gx6YXhIASm8iuwTjuX.

php artisan migrate --env=testing 
php artisan serve

## Running Tests

php artisan test

`markdown
## API Endpoints
- POST /register: register a user.
- POST /login: Login a user.
- POST /task_search: search for tasks .

All the endpoints listed below are protected by the auth middleware. Users must be authenticated and provide a valid token in the Authorization header to access these endpoints.
- POST /tasks: Create a new task.
- GET /tasks: Retrieve all tasks.
- GET /tasks/{id}: Retrieve a specific task.
- PUT /tasks/{id}: Update a specific task.
- DELETE /tasks/{id}: Delete a specific task.
- POST /logout: Logout a user.