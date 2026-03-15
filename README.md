# Thiva Engineering - Laravel 12 API

A sophisticated, high-performance RESTful API built with **Laravel 12**. This backend serves as the core engine for the Thiva Engineering platform, managing dynamic content, secure authentication, and complex business workflows.

---

## 🛠️ Advanced Technical Implementation

### 🔐 Security & Authentication (OAuth2)
* **Laravel Passport:** Implemented full OAuth2 server capabilities to issue access tokens, handle refresh tokens, and manage secure API authentication.
* **Granular Authorization:** Utilized **Policies** and **Gates** to ensure users can only interact with authorized resources.
* **Data Integrity:** Used **Middleware** and **Form Requests** to validate all incoming data and sanitize inputs.

### 🏗️ Architectural Patterns
* **Service Layer Pattern:** Logic is extracted from controllers into dedicated services for better maintainability and testing.
* **Event-Driven Design:** Implemented **Events & Observers** to trigger logic automatically, keeping the codebase decoupled and clean.
* **Asynchronous Processing:** Utilizes **Laravel Jobs** and **Queues** for background tasks like mail delivery and document processing.

### ⚡ Performance & Integrations
* **Third-Party Integrations:** Fully integrated with the **Dropbox API** for cloud file management.
* **Performance:** Strategic use of **Laravel Cache** to reduce database load and improve response times.
* **Notifications:** Real-time user alerts handled via **Laravel Mail** and **Event** systems.

### 🧪 Quality Assurance
* **Automated Testing:** Comprehensive suite of **Feature and Unit tests** (PHPUnit/Pest) ensuring API stability.
* **Global Exception Handling:** Custom handler for clean, predictable API error responses.

---

## 🚀 Tech Stack
* **Framework:** Laravel 12 (PHP 8.3+)
* **Authentication:** Laravel Passport
* **Database:** MySQL
* **Testing:** PHPUnit
* **Storage:** Dropbox API

---

## 🚦 Getting Started

1. **Clone the repo:**
   ```bash
   git clone [https://github.com/nishanthveemarasan/thiva-admin.git](https://github.com/nishanthveemarasan/thiva-admin.git)