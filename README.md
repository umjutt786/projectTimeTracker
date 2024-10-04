# Timer Tracker API

## Overview

The Timer Tracker API is a Laravel-based application designed to help users efficiently manage their time on various projects. It allows users to start, pause, and resume timers for their assigned projects, as well as capture and upload screenshots every 10 minutes to document their work.

## Features

- **User Authentication**: Secure login and registration using Laravel Sanctum.
- **Project Management**: Create and manage projects with associated timers.
- **Timer Functionality**:
  - Start, pause, resume, and stop timers for projects.
- **Screenshot Capture**: Upload screenshots every 10 minutes to document work progress.
- **Swagger Documentation**: Comprehensive API documentation for easy integration.

## API Endpoints

### User Authentication

- **Login**
  - `POST /api/auth/login`
  
- **Register**
  - `POST /api/auth/register`

### Project Management

- **Get Projects**
  - `GET /api/projects`
  
- **Create Project**
  - `POST /api/projects`
  
- **Get Project Details**
  - `GET /api/projects/{id}`

### Timer Management

- **Start Timer**
  - `POST /api/worklog/start`
  
- **Pause Timer**
  - `POST /api/worklog/{id}/pause`
  
- **Resume Timer**
  - `POST /api/worklog/{id}/resume`
  
- **Stop Timer**
  - `POST /api/worklog/{id}/stop`

### Screenshot Management

- **Upload Screenshot**
  - `POST /api/worklog/screenshot`

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/yourusername/timer-tracker-api.git
   cd timer-tracker-api
   cp .env.example .env
   php artisan key:generate
   php artisan serve

The API documentation is generated using Swagger. To access the documentation, navigate to:
    http://localhost:8000/api/documentation

