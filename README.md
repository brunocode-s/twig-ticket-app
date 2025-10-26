# Ticket Easy App — Twig (PHP) Version

A **Ticket Management Web Application** built using **Twig templating with PHP**, matching the React and Vue.js versions in layout, logic, and user experience.

---

## Tech Stack

- **Templating:** Twig 3.0+
- **Language:** PHP 8+
- **Styling:** Tailwind CSS (CDN)
- **Runtime State:** LocalStorage for session + tickets
- **Session Emulation:** `localStorage.ticketapp_session`
- **Hosting:** Render or any PHP-capable host

## Core Features

### Landing Page
- Wavy background + circular decorations.
- “Login” and “Get Started” buttons.
- Fully responsive layout (max width: 1440px).

### Authentication
- Login/Signup forms with validation and error messages.
- After login, PHP sets both server session and:
  ```js
  localStorage.setItem('ticketapp_session', userObject)

   Setup & Run
# Install dependencies
composer install

# Run local server
php -S localhost:8000 -t api
