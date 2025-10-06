# Windows Setup Guide for Tailoring Management System

This guide will help you set up and run the project on Windows after cloning from the repository.

## Prerequisites

Before you begin, ensure you have the following installed on your Windows machine:

- **PHP 8.2 or higher** (Download from [php.net](https://windows.php.net/download/) or use [XAMPP](https://www.apachefriends.org/))
- **Composer** (Download from [getcomposer.org](https://getcomposer.org/download/))
- **Node.js 18.x or higher** (Download from [nodejs.org](https://nodejs.org/))
- **Git** (Download from [git-scm.com](https://git-scm.com/download/win))

## Installation Steps

### 1. Clone the Repository

```bash
git clone <repository-url>
cd Tailoring
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Configure Environment

Copy the `.env.example` file to `.env`:

```bash
copy .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 5. Set Up Database

The project uses SQLite by default. Create the database file:

```bash
type nul > database\database.sqlite
```

Or if using PowerShell:

```powershell
New-Item database\database.sqlite
```

Run migrations:

```bash
php artisan migrate
```

### 6. Build Frontend Assets

Build the CSS and JavaScript assets:

```bash
npm run build
```

For development with hot reload:

```bash
npm run dev
```

### 7. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Common Windows-Specific Issues

### Issue: Line Endings

If you encounter issues with line endings, Git is configured to handle this automatically via `.gitattributes`. The file sets `* text=auto eol=lf` which ensures consistent line endings across platforms.

### Issue: SQLite Not Working

If SQLite doesn't work, you can switch to MySQL by:

1. Update `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tailoring
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. Create the database in MySQL
3. Run migrations: `php artisan migrate`

### Issue: Permission Errors

On Windows, you might need to run your terminal as Administrator for certain operations.

### Issue: npm/Node Errors

If you encounter node-gyp or build errors:
1. Install Windows Build Tools: `npm install --global windows-build-tools`
2. Clear npm cache: `npm cache clean --force`
3. Delete `node_modules` and `package-lock.json`
4. Run `npm install` again

### Issue: Vite Build Errors

If Vite fails to build:
1. Check that Node.js version is 18.x or higher: `node --version`
2. Clear Vite cache: Remove `node_modules/.vite` folder
3. Rebuild: `npm run build`

## Styling & Layout

The project has been optimized for cross-platform compatibility. The CSS uses native browser scrollbar behavior, which means:

- **Windows**: Scrollbars will appear using Windows native styling when content overflows
- **macOS**: Scrollbars will use overlay style (hidden until scrolling)

This is the correct behavior and ensures consistent layout across both platforms.

## Development Workflow

### Running Development Server

For the best development experience, run both the PHP server and Vite dev server:

Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
npm run dev
```

### Building for Production

Before deploying or committing changes:

```bash
npm run build
```

This generates optimized CSS and JavaScript files in `public/build/`.

## Troubleshooting

### CSS Not Loading

1. Ensure you've run `npm run build` or `npm run dev`
2. Clear Laravel cache: `php artisan cache:clear`
3. Clear view cache: `php artisan view:clear`
4. Check that `public/build/manifest.json` exists

### Database Connection Errors

1. Verify `database/database.sqlite` file exists
2. Check file permissions (should be writable)
3. Verify `.env` has correct `DB_CONNECTION=sqlite`

### Application Key Error

Run: `php artisan key:generate`

## Additional Commands

- **Clear all caches**: `php artisan optimize:clear`
- **Run tests**: `php artisan test`
- **Check platform requirements**: `composer check-platform-reqs`
- **Code style check**: `./vendor/bin/pint`

## Support

If you encounter any issues not covered in this guide, please check:
1. The main `README.md` file
2. Laravel documentation: [laravel.com/docs](https://laravel.com/docs)
3. Project issue tracker

## Version Information

- Laravel: 12.x
- PHP: 8.2+
- Node.js: 18.x+
- Tailwind CSS: 3.x
- Vite: 7.x
