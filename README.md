# 💼 Mini LinkedIn — Recruitment Platform API

> A RESTful API backend for a recruitment platform built with Laravel. Connects candidates and recruiters through job offers, applications, and role-based access control.

---

## 🛠️ Stack

| Tool | Role |
|------|------|
| **Laravel** | PHP backend framework |
| **MySQL** | Relational database |
| **JWT (tymon/jwt-auth)** | Stateless authentication |
| **Eloquent ORM** | Database modeling and relationships |
| **Laravel Events & Listeners** | Decoupled application logic and logging |

---

## 🏗️ Architecture

Three user roles with isolated permissions:

- **Candidate** — creates a profile, adds skills, applies to job offers
- **Recruiter** — posts job offers, reviews incoming applications, updates application status
- **Admin** — manages all users and controls offer visibility

Authentication is JWT-based and stateless. Every protected route requires a valid Bearer token. Role-based authorization is enforced via middleware — any unauthorized access returns a 403 error.

Application events are decoupled using Laravel's Events & Listeners system: when a candidate applies or a recruiter updates a status, an event fires and a listener logs it independently to storage/logs/candidatures.log.

---

## 🗄️ Data Model

users           → id, name, email, password, role (candidat | recruteur | admin)
profils         → id, user_id, titre, bio, localisation, disponible
competences     → id, nom, categorie
profil_competence → profil_id, competence_id, niveau (débutant | intermédiaire | expert)
offres          → id, user_id, titre, description, localisation, type (CDI | CDD | stage), actif
candidatures    → id, offre_id, profil_id, message, statut (en_attente | acceptee | refusee)

---

## 🚀 Installation

### Prerequisites
- PHP 8.1+
- Composer
- MySQL
- Laravel 10+

### Steps

git clone https://github.com/myyyyriaammm/Mini_linkedIn.git
cd Mini_linkedIn
composer install
cp .env.example .env

Configure your database in .env:
DB_DATABASE=mini_linkedin
DB_USERNAME=your_username
DB_PASSWORD=your_password

php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
php artisan serve

The seeder creates: 2 admins, 5 recruiters with 2–3 job offers each, and 10 candidates with profiles and skills.

---

## 🔐 Authentication

All routes are protected. Include the token in every request:
Authorization: Bearer <your_token>

POST /api/auth/register — Register a new user
POST /api/auth/login    — Login and receive JWT token
POST /api/auth/logout   — Invalidate token
GET  /api/auth/me       — Get authenticated user

---

## 📡 API Endpoints

### Profile (Candidates only)
POST   /api/profil                              — Create profile (once)
GET    /api/profil                              — View own profile
PUT    /api/profil                              — Update profile
POST   /api/profil/competences                  — Add a skill with level
DELETE /api/profil/competences/{competence}     — Remove a skill

### Job Offers
GET    /api/offres              — List active offers (filter by localisation, type)
GET    /api/offres/{offre}      — Get offer details
POST   /api/offres              — Create offer (recruiter only)
PUT    /api/offres/{offre}      — Update offer (owner only)
DELETE /api/offres/{offre}      — Delete offer (owner only)

Offers are paginated (10 per page) and sortable by creation date.

### Applications
POST   /api/offres/{offre}/candidater              — Apply to an offer (candidate)
GET    /api/mes-candidatures                       — View own applications
GET    /api/offres/{offre}/candidatures            — View received applications (recruiter)
PATCH  /api/candidatures/{candidature}/statut      — Update application status (recruiter)

### Administration (Admin only)
GET    /api/admin/users              — List all users
DELETE /api/admin/users/{user}       — Delete a user account
PATCH  /api/admin/offres/{offre}     — Activate or deactivate an offer

---

## 📋 Ownership Rules

- A recruiter can only modify or delete their own offers
- A candidate can only view their own applications
- Any attempt to access another user's resource returns 403 Forbidden

---

## ⚡ Events & Listeners

CandidatureDeposee — fired when a candidate applies to an offer. Logs the date, candidate name, and offer title to storage/logs/candidatures.log.

StatutCandidatureMis — fired when a recruiter updates an application status. Logs the old status, new status, and timestamp to the same log file.

---

## 📁 Postman Collection

A complete Postman collection is available in the postman/ folder covering all endpoints including error cases (401, 403, 422).

---

## 👩‍💻 Author

Maryam Biby — AI & Computer Engineering Student, ENSAM Casablanca
