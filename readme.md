# ToDo API

## Installation

Follow these steps to install and set up the project:

1. Clone the repository:

   ```bash
   git clone git@github.com:Kutabarik/todo-api.git
   cd todo-api
   ```

2. Start Docker Compose:

   ```bash
   docker compose up -d
   ```

3. Enter the `php` container:

   ```bash
   docker exec -it todo-api-php-1 bash
   ```

4. Install Composer dependencies:

   ```bash
   composer install
   ```

5. Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

6. Run the database migrations:

   ```bash
   php bin/console doctrine:migrations:migrate
   ```

---


# Task API Documentation

**Base URL**: `/api/tasks`

## Endpoints

### 1. Get All Tasks

**Endpoint**: `GET /api/tasks`

**Description**: Returns a list of all tasks with optional filtering.

**Query Parameters**:

- `completed` (optional, boolean): Filters tasks by completion status (`true` for completed, `false` for incomplete).
- `priority` (optional, string): Filters tasks by priority, such as `low`, `medium`, or `high`.

**Response**:

- **Status**: `200 OK`
- **Body**:
  ```json
  [
    {
      "id": 1,
      "title": "Task 1",
      "description": "Description of task 1",
      "completed": false,
      "priority": "low",
      "createdAt": "2024-11-02T12:00:00Z"
    },
    ...
  ]
  ```

### 2. Create a New Task

**Endpoint**: `POST /api/tasks`

**Description**: Creates a new task.

**Request Body**:
  ```json
  {
    "title": "Task title",
    "description": "Description of the task",
    "completed": false,
    "priority": "medium"
  }
  ```

**Response**:

- **Status**: `201 Created`
- **Body**:
  ```json
  {
    "id": 1,
    "title": "Task title",
    "description": "Description of the task",
    "completed": false,
    "priority": "medium",
    "createdAt": "2024-11-02T12:00:00Z"
  }
  ```

**Errors**:

- **Status**: `400 Bad Request` (if required data is missing or priority is invalid)

### 3. Get a Task by ID

**Endpoint**: `GET /api/tasks/{id}`

**Description**: Returns information about a task by its ID.

**Path Parameter**:

- `id` (integer): The ID of the task.

**Response**:

- **Status**: `200 OK`
- **Body**:
  ```json
  {
    "id": 1,
    "title": "Task title",
    "description": "Description of the task",
    "completed": false,
    "priority": "medium",
    "createdAt": "2024-11-02T12:00:00Z"
  }
  ```

**Errors**:

- **Status**: `404 Not Found` (if the task with the specified ID is not found)

### 4. Update a Task

**Endpoint**: `PATCH /api/tasks/{id}`

**Description**: Updates an existing task by its ID. Can partially update one or more fields.

**Path Parameter**:

- `id` (integer): The ID of the task.

**Request Body** (partially or fully updates fields):
  ```json
  {
    "title": "Updated task title",
    "description": "Updated description",
    "completed": true,
    "priority": "high"
  }
  ```

**Response**:

- **Status**: `200 OK`
- **Body**:
  ```json
  {
    "id": 1,
    "title": "Updated task title",
    "description": "Updated description",
    "completed": true,
    "priority": "high",
    "createdAt": "2024-11-02T12:00:00Z"
  }
  ```

**Errors**:

- **Status**: `400 Bad Request` (if data is invalid)
- **Status**: `404 Not Found` (if the task with the specified ID is not found)

### 5. Mark Task as Completed

**Endpoint**: `PATCH /api/tasks/{id}/complete`

**Description**: Marks a task as completed.

**Path Parameter**:

- `id` (integer): The ID of the task.

**Response**:

- **Status**: `200 OK`
- **Body**:
  ```json
  {
    "id": 1,
    "title": "Task title",
    "description": "Description of the task",
    "completed": true,
    "priority": "medium",
    "createdAt": "2024-11-02T12:00:00Z"
  }
  ```

**Errors**:

- **Status**: `400 Bad Request` (if the task is already marked as completed)
- **Status**: `404 Not Found` (if the task with the specified ID is not found)

### 6. Delete a Task

**Endpoint**: `DELETE /api/tasks/{id}`

**Description**: Deletes a task by its ID.

**Path Parameter**:

- `id` (integer): The ID of the task.

**Response**:

- **Status**: `204 No Content` (no body in the response)

**Errors**:

- **Status**: `404 Not Found` (if the task with the specified ID is not found)

