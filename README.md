Task Management Web Application

This a simple task management application built with Vue 3 (Composition API) and Laravel 12 backend. Users can create projects, add tasks, organize them by projects, and reorder tasks via drag-and-drop . 



Features

Create, edit, and delete tasks

Create and manage projects

Tasks can belong to a project or exist independently

Drag-and-drop task reordering (priority saved in database)

Responsive UI with Tailwind CSS 



Technologies Used

Frontend: Vue 3, Composition API, Vite, Tailwind CSS, vuedraggable

Backend: Laravel 12, MySQL

HTTP Client: Axios



Setup Instructions

1. Clone the repository
   git clone https://github.com/jesutobi/task_management.git
   cd task-manager

2. Install Backend Dependencies
   composer install

3. Install Frontend Dependencies
   npm install

4. Set up Environment Variables

Copy .env.example to .env and configure:

cp .env.example .env

Set your database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=


5. Run Database Migrations
   php artisan migrate

6. Run the Backend Server
   php artisan serve unless laravel wont run

7. Run the Frontend Development Server
   npm run dev

Build the frontend:

npm run build

Make sure your database is running and .env credentials are correct before running migrations.

Drag-and-drop task reordering updates the priority in the database automatically.

Tailwind CSS is already configured.
