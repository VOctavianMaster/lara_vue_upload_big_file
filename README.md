# Project Setup and Running Instructions

This guide will walk you through the steps to get the project up and running on your local machine after downloading it from GitHub. This project is built using **Laravel 11**, **Vue 3**, **Vite**

## Prerequisites

Before getting started, make sure you have the following software installed on your machine:

- **PHP 8.3 or higher**
- **Composer** (for managing PHP dependencies)
- **Node.js 16.x or higher**
- **NPM/Yarn** (for managing frontend dependencies)
- **MySQL** (or any other supported database)
- **Git** (to clone the repository)

## Steps to Run the Project Locally

### 1. Clone the repository

Start by cloning the project from GitHub to your local machine.

```bash
git clone https://github.com/yourusername/your-repository-name.git
cd your-repository-name
```
### 3. Configure the Environment
Copy the example environment file and configure it:

```bash
cp .env.example .env
```
Edit .env to set up your database credentials:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```
### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Database Migrations and Seeders (if applicable)

```bash
php artisan migrate
php artisan db:seed  # Optional, if seeders are available
```

### 6. Set Up Queues
Update the .env file to use the database for queues:

```bash
QUEUE_CONNECTION=database
```
Generate the necessary queue tables and migrate them:
```bash
php artisan queue:table
php artisan migrate
```
Start the queue worker:
```bash
php artisan queue:work
```


### 7. Install Frontend Dependencies

```bash
npm install
```

### 8. Build Frontend Assets for Production

```bash
npm run build
```
For development mode, you can start the Vite development server:
```bash
npm run dev
```

### 9. Serve the Laravel Application
Run the development server for the Laravel application:
```bash
php artisan serve
```
By default, the app will be accessible at http://localhost:8000.
## Troubleshooting
### Common Issues and Fixes
1. File Upload or Storage Issues: Ensure the storage directory has the correct permissions:
```bash
chmod -R 775 storage
```
2. Dependency Conflicts: If you encounter dependency issues during npm install, try:
```bash
npm install --legacy-peer-deps
```
3. Queue Not Processing: Ensure the queue worker is running:
```bash
php artisan queue:work
```
4. Environment Variables Not Loaded: Clear the configuration cache:
```bash
php artisan config:clear
```

## Access the Application

Laravel Backend: http://localhost:8000
Frontend Vite Development Server: http://localhost:5173 (if applicable)
