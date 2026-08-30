# IT Support Ticket Dashboard

## Overview

IT Support Ticket Dashboard is a web-based application developed to help organizations manage and monitor internal IT support requests efficiently. The system provides ticket tracking, category management, status monitoring, and role-based access control to ensure a structured support workflow.

---

## Technology Stack

* Laravel 12
* PHP 8.2+
* MySQL
* Bootstrap 5
* jQuery
* Yajra DataTables
* Spatie Laravel Permission
* Chart.js

---

## Features

### Dashboard

The dashboard provides a quick overview of ticket activities through the following statistics:

* Total Tickets
* Open Tickets
* In Progress Tickets
* High Priority Tickets
* Closed Tickets

Additional dashboard components:

* Ticket Status Distribution Chart
* Ticket Category Distribution Chart
* Recent Tickets List

---

### Category Management

Administrators can manage ticket categories through:

* Create Category
* Update Category
* Delete Category
* View Category List

Example Categories:

* Network
* Hardware
* Software
* Email
* Printer

---

### Ticket Management

The system provides complete ticket management functionality:

* Create Ticket
* Edit Ticket
* Delete Ticket
* View Ticket List
* Assign Ticket to User
* Search and Filter Tickets
* Pagination using DataTables

Ticket Information:

* Ticket Number
* Ticket Title
* Category
* Priority
* Status
* Assigned Person
* Description
* Notes
* Created Date

---

## Ticket Workflow

Each ticket follows the support workflow below:

Open → In Progress → Resolved → Closed

This workflow helps support teams track ticket progress from creation until completion.

---

## Role & Permission Management

The application implements Role-Based Access Control (RBAC) using Spatie Laravel Permission.

### Available Roles

* Super Admin
* IT Administrator
* User

### Available Permissions

* tickets.view
* tickets.create
* tickets.update
* tickets.delete
* tickets.update-status

### Status Update Authorization

Ticket status updates are restricted to authorized users only.

Users who possess the `tickets.update-status` permission can update ticket status directly from the ticket list.

| Role             | Can Update Status            |
| ---------------- | ---------------------------- |
| Super Admin      | Yes                          |
| IT Administrator | Yes (if permission assigned) |
| User             | No                           |

Unauthorized users cannot access the ticket status update functionality through either the user interface or direct URL requests.

---

## Database Structure

### Categories

| Field       | Type      |
| ----------- | --------- |
| id          | bigint    |
| name        | varchar   |
| description | text      |
| created_at  | timestamp |
| updated_at  | timestamp |

### Tickets

| Field            | Type      |
| ---------------- | --------- |
| id               | bigint    |
| ticket_number    | varchar   |
| title            | varchar   |
| category_id      | bigint    |
| assigned_user_id | bigint    |
| priority         | enum      |
| status           | enum      |
| description      | text      |
| notes            | text      |
| created_date     | datetime  |
| created_at       | timestamp |
| updated_at       | timestamp |

---

## Installation

```bash
git clone <repository-url>

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

php artisan serve
```

---

## Default Accounts

### Super Admin

Email: [super@example.com]

Password: password

### admin

Email: [admin@example.com]

Password: password

---

## Application Highlights

* Responsive User Interface
* Role-Based Access Control (RBAC)
* Ticket Status Workflow
* Dashboard Analytics
* Category Management
* Ticket Assignment
* Status-Based Monitoring
* Permission-Based Actions
* Server-Side DataTables

---

## Conclusion

The IT Support Ticket Dashboard provides a simple yet effective solution for managing internal IT support requests. By combining ticket tracking, workflow management, dashboard analytics, and permission-based access control, the system helps organizations improve support operations and monitor ticket progress efficiently.
