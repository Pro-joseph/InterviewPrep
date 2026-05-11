# InterviewPrep

A Laravel application to help developers prepare for technical interviews by organizing knowledge and generating AI-powered interview questions.

## Features

### Authentication
- User registration / login / logout
- Email verification

### Domain Management
- Create, edit, delete technical domains
- Color-coded domain badges
- Progress tracking (mastered concepts count)

### Concept Management
- Create, edit, delete concepts within domains
- Difficulty levels: Junior, Mid, Senior
- Status tracking: À revoir, En cours, Maîtrisé
- Quick status update from list
- Filter by status and difficulty
- Soft delete with archive/restore

### AI Question Generation
- Generate 5 interview questions per concept using Groq API
- Store question history
- Delete unwanted generations

### Dashboard
- Overview stats (total, maîtrisés, en cours, à revoir)
- Domain progress visualization
- Best/worst performing domain insights

## Tech Stack

- **Backend**: Laravel 13
- **Database**: MySQL
- **Frontend**: Blade templates + Tailwind CSS
- **AI**: Groq API (Llama 3.1)
- **Authentication**: Laravel Breeze

## Setup

### Prerequisites
- PHP 8.4+
- Composer
- Node.js & npm
- MySQL

### Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Copy environment file:
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Configure database in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=interviewprep
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Add Groq API key in `.env`:
   ```
   GROQ_API_KEY=your_groq_api_key
   ```

7. Run migrations:
   ```bash
   php artisan migrate
   ```

8. Build assets:
   ```bash
   npm run build
   ```

9. Start the server:
   ```bash
   php artisan serve
   ```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/      # Business logic
│   └── Requests/        # Form validation
├── Models/              # Database models
├── Policies/            # Authorization
database/
└── migrations/          # Database schema
resources/
└── views/               # Blade templates
routes/
└── web.php              # Routes
specs/                   # Feature specifications
```

## API Integration

The application uses Groq API for AI question generation:
- Model: llama-3.1-8b-instant
- Endpoint: https://api.groq.com/openai/v1/chat/completions

## License

MIT License