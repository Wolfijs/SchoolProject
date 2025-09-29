# Gaming Lobby & Chat Platform

A Laravel-based web application for creating gaming lobbies and real-time chat functionality. This project allows users to create, join, and manage gaming lobbies while providing a real-time chat system for communication.

## 🚀 Features

### Core Functionality
- **User Authentication & Registration** - Secure user management with Laravel's built-in auth system
- **Gaming Lobby System** - Create and manage gaming lobbies with customizable settings
- **Real-time Chat** - Live messaging using Pusher for real-time communication
- **Profile Management** - User profile editing and customization
- **Responsive Design** - Modern UI built with Bootstrap and custom CSS

### Lobby Features
- Create lobbies with game-specific details
- Set skill level requirements
- Define playstyle preferences
- Regional matchmaking
- Player capacity management (1-10 players)
- Join/leave lobby functionality
- Lobby deletion (creator only)

### Chat Features
- Real-time messaging
- Username display
- Message history
- Auto-scroll to latest messages
- Error handling and validation

## 🛠️ Tech Stack

### Backend
- **Laravel 11** - PHP framework
- **MySQL/SQLite** - Database
- **Pusher** - Real-time messaging
- **Laravel UI** - Authentication scaffolding

### Frontend
- **Bootstrap 5** - CSS framework
- **JavaScript (ES6+)** - Client-side functionality
- **Laravel Echo** - Real-time event listening
- **Pusher JS** - WebSocket communication
- **Vite** - Asset bundling

### Development Tools
- **Laravel Pint** - Code formatting
- **PHPUnit** - Testing framework
- **Laravel Sail** - Docker development environment

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL or SQLite
- Pusher account (for real-time features)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd SchoolProject
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   - Update `.env` with your database credentials
   - For SQLite: `touch database/database.sqlite`
   - For MySQL: Create a database and update `.env`

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Configure Pusher**
   - Get your Pusher credentials from [pusher.com](https://pusher.com)
   - Update `.env` with your Pusher configuration:
   ```env
   PUSHER_APP_ID=your_app_id
   PUSHER_APP_KEY=your_app_key
   PUSHER_APP_SECRET=your_app_secret
   PUSHER_APP_CLUSTER=your_cluster
   ```

9. **Build assets**
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

10. **Start the development server**
    ```bash
    php artisan serve
    ```

## 📁 Project Structure

```
SchoolProject/
├── app/
│   ├── Events/              # Real-time events
│   ├── Http/Controllers/    # Application controllers
│   ├── Models/              # Eloquent models
│   └── Notifications/       # Email/notification classes
├── database/
│   ├── migrations/          # Database schema migrations
│   └── seeders/             # Database seeders
├── public/
│   ├── css/                 # Compiled CSS
│   ├── js/                  # Compiled JavaScript
│   └── img/                 # Static images
├── resources/
│   ├── js/                  # Source JavaScript files
│   ├── sass/                # SCSS source files
│   └── views/               # Blade templates
└── routes/
    └── web.php              # Web routes
```

## 🎮 Usage

### Creating a Lobby
1. Register/Login to your account
2. Navigate to the "Find Your Team" (FYT) page
3. Click "Create Lobby"
4. Fill in lobby details:
   - Game name
   - Skill level
   - Playstyle
   - Region
   - Maximum players
5. Submit to create the lobby

### Joining a Lobby
1. Browse available lobbies on the FYT page
2. Click "Join" on a lobby that interests you
3. You'll be added to the lobby's player list

### Using the Chat
1. Navigate to the chat page
2. Type your message in the input field
3. Press Enter or click Send
4. Messages appear in real-time for all users

## 🔧 Configuration

### Pusher Setup
1. Create a Pusher account at [pusher.com](https://pusher.com)
2. Create a new app
3. Copy the credentials to your `.env` file
4. Update `config/broadcasting.php` if needed

### Database Configuration
- **SQLite**: No additional setup required
- **MySQL**: Create database and update `.env` with credentials

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 📝 API Endpoints

### Authentication
- `GET /login` - Login page
- `POST /login` - Process login
- `GET /signup` - Registration page
- `POST /signup` - Process registration
- `POST /logout` - Logout user

### Lobbies
- `GET /fyt` - List all lobbies
- `POST /lobby/store` - Create new lobby
- `POST /lobby/{id}/join` - Join lobby
- `DELETE /lobby/{id}` - Delete lobby

### Chat
- `GET /chat` - Chat interface
- `POST /chat/send` - Send message
- `GET /chat/messages` - Load message history

### Profile
- `GET /profile/edit` - Edit profile page
- `PUT /profile/update` - Update profile




**Built with ❤️ using Laravel**