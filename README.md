# Garappa

Garappa is a web application built with Laravel and Vue.js designed to manage curators, establishments, foods, and related awards. The project provides an admin interface for managing users, curators, their favorite foods, and other related entities.

## Features

- Curator management (CRUD)
- Establishment and category management
- Favorite foods selection for curators
- Award and cuisine management
- Firebase integration for authentication, database, and storage
- Admin dashboard for user and content management
- Localization support (Portuguese - Brazil)

## Project Structure

```
app/                # Laravel application core (models, controllers, etc.)
bootstrap/          # Laravel bootstrap files
config/             # Configuration files
database/           # Database migrations and seeds
public/             # Publicly accessible files (entry point)
resources/
  ├── assets/js/    # JavaScript assets (Vue.js, Firebase integration)
  ├── lang/         # Language files (pt-BR localization)
  └── views/        # Blade templates (admin/user interfaces)
routes/             # Route definitions
storage/            # Storage for logs, cache, etc.
tests/              # Automated tests
```

## Installation

1. **Clone the repository:**
   ```sh
   git clone https://github.com/yourusername/garappa.git
   cd garappa
   ```

2. **Install PHP dependencies:**
   ```sh
   composer install
   ```

3. **Install Node.js dependencies:**
   ```sh
   npm install
   ```

4. **Copy and configure environment variables:**
   ```sh
   cp .env.example .env
   # Edit .env to set your database and Firebase credentials
   ```

5. **Generate application key:**
   ```sh
   php artisan key:generate
   ```

6. **Run migrations:**
   ```sh
   php artisan migrate
   ```

7. **Build frontend assets:**
   ```sh
   npm run dev
   ```

8. **Start the development server:**
   ```sh
   php artisan serve
   ```

## Usage

- Access the admin dashboard at `http://localhost:8000/admin`
- Manage curators, establishments, foods, and awards via the web interface

## Localization

This project uses Brazilian Portuguese as the default language. See [resources/lang/pt-BR/README.md](resources/lang/pt-BR/README.md) for localization setup.

## License

This project is licensed under the MIT License.
