# Symfony Admin Panel Skeleton Project

## Description

A minimal yet robust Symfony foundation for mastering admin panels and role management. This skeleton leverages Symfony Flex for quick setup and includes only the essential, battle-tested components:

* **Symfony MakerBundle** for code generation
* **Doctrine ORM** for database abstraction
* **Symfony Security** for authentication and role-based access control
* **Tailwind CSS** via SymfonyCasts Tailwind Bundle for styling
* A single **example** `Article` entity (purely as a demonstration)

> The `Article` entity is just a placeholder. Replace or extend it to fit your real data model as you focus on admin roles and panel integration.

## Prerequisites

* PHP 8.1 or higher with the following extensions enabled:

  * `pdo` and your database driver (e.g., `pdo_mysql`)
  * `ctype`, `iconv`, and `json` (typically enabled by default)
* Composer
* Node.js & npm

## Installation

1. **Create the project and enter the directory**:

   ```bash
   composer create-project symfony/skeleton admin_panel_skeleton
   cd admin_panel_skeleton
   ```

2. **Install and configure essential bundles**:

   ```bash
   # Database & ORM: Doctrine ORM & DBAL
   composer require symfony/orm-pack

   # Code generator (dev): Symfony MakerBundle
   composer require --dev symfony/maker-bundle

   # Security: Symfony SecurityBundle
   composer require symfony/security-bundle

   # Tailwind CSS (comfortable setup): SymfonyCasts Tailwind Bundle
   composer require symfonycasts/tailwind-bundle
   # Scaffold Tailwind configuration
   php bin/console tailwind:init
   ```

3. **Initialize npm and install JS dependencies**:

   ```bash
   npm init -y          # create package.json if missing
   npm install          # installs Tailwind and PostCSS (configured by the bundle)
   ```

4. **Build Tailwind CSS and watch for changes**:

   ```bash
   # Single build
   php bin/console tailwind:build

   # Watch mode (rebuilds on change)
   php bin/console tailwind:build -w
   ```


```

## Next Steps

> **Note:** Ensure you are in your project's root directory (e.g., `cd admin_panel_skeleton`) before running any `php bin/console` commands, so that the `bin/console` file can be found.

5. **Configure your database connection**:

6. **Configure your database connection**:

   1. Create a new MySQL database (e.g., `admin_panel_skeleton`) via phpMyAdmin or CLI.
   2. In your `.env` file, set:

      ```dotenv
      DATABASE_URL="mysql://root:@127.0.0.1:3306/admin_panel_skeleton?serverVersion=5.7"
      ```
   3. Create the database:

      ```bash
      php bin/console doctrine:database:create
      ```

7. **Generate the example `Article` entity, create migration, and update schema**:

   ```bash
   php bin/console make:entity Article
   # follow prompts: e.g. title:string(255), content:text
   php bin/console make:migration
   php bin/console doctrine:migrations:migrate
   ```

8. **Select and install your admin panel bundle**:

   ```bash
   # Install EasyAdminBundle
   composer require easycorp/easyadmin-bundle

   # Generate dashboard controller
   php bin/console make:admin:dashboard

   # Scaffold CRUD interface (interactive)
   php bin/console make:admin:crud
   ```

   **Interactive prompts:**

   * Choose `App\Entity\Article` when asked for the Doctrine entity.
   * Press ENTER to accept default directory (`src/Controller/Admin/`).
   * Press ENTER to accept default namespace (`App\Controller\Admin`).

9. **Prepare cache and launch the dev server**:

   If you see errors like "The name of the route associated to "App\Controller\Admin\DashboardController::index" cannot be determined", you need to clear and warm up the cache before running the server:

   ```bash
   # Clear existing cache and regenerate route data
   php bin/console cache:clear
   php bin/console cache:warmup
   ```

   Then launch the server using one of the following methods:

   * **Symfony CLI (recommended):**

     ```bash
     symfony server:start
     ```

     * Symfony welcome page: `http://127.0.0.1:8000/`
     * Admin panel: `http://127.0.0.1:8000/admin`

   * **Built‑in PHP server:**

     ```bash
     php -S 127.0.0.1:8000 -t public
     ```

   * **WebServerBundle (legacy):**

     ```bash
     composer require symfony/web-server-bundle --dev
     php bin/console server:run
     ```

10. **Customize menus, fields, and access controls** in your admin controllers:

* **DashboardController** (`src/Controller/Admin/DashboardController.php`):

  ```php
  namespace App\Controller\Admin;

  use App\Entity\Article;
  use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
  use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
  use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;

  class DashboardController extends AbstractDashboardController
  {
      #[Route('/admin', name: 'admin')]
      public function index(): Response
      {
          // Redirect to the Article CRUD page
          \$adminUrlGenerator = \$this->container->get(AdminUrlGenerator::class);
          return \$this->redirect(
              \$adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl()
          );
      }

      public function configureMenuItems(): iterable
      {
          yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
          yield MenuItem::linkToCrud('Manage Articles', 'fa fa-file-text', Article::class);
          // e.g., yield MenuItem::linkToRoute('Back to website', 'fa fa-arrow-left', 'app_home');
      }
  }
  ```

* **CRUD Controller** (`src/Controller/Admin/ArticleCrudController.php`): adjust fields in `configureFields()`:

  ```php
  use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
  use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
  use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

  public function configureFields(string \$pageName): iterable
  {
      return [
          TextField::new('title', 'Title'),
          TextareaField::new('content', 'Content'),
          DateTimeField::new('createdAt', 'Created At')->onlyOnIndex(),
          DateTimeField::new('updatedAt', 'Updated At')->hideOnForm(),
      ];
  }
  ```

* **Access Control** in `security.yaml`:

  ```yaml
  security:
      # ...
      access_control:
          - { path: '^/admin', roles: ROLE_ADMIN }
  ```

---

10. **Implement User Authentication & Roles**:

    1. Generate a `User` entity:

       ```bash
       php bin/console make:user
       # follow prompts: e.g. identifier=email, add roles and password fields
       ```
    2. Generate login form and security setup:

       ```bash
       php bin/console make:auth
       # choose "Login Form authenticator" and enter paths (e.g., /login)
       ```
    3. Generate and execute migrations to create the `user` table:

       ```bash
       php bin/console make:migration
       php bin/console doctrine:migrations:migrate
       ```

       This will create the `user` table in your database, including fields for email, roles, and password.
    4. Hash a password and add a `ROLE_ADMIN` user directly (via fixtures or SQL):

       ```bash
       php bin/console security:hash-password 'YourPlainPassword'
       ```

       Insert into the database (e.g., via phpMyAdmin):

       ```sql
       INSERT INTO `user` (email, roles, password)
       VALUES (
         'admin@example.com',
         JSON_ARRAY('ROLE_ADMIN'),
         '$2y$...YourHashedPassword...'
       );
       ```
    5. Configure `security.yaml` to use your `User` entity as provider and protect routes:

       ```yaml
       security:
           providers:
               app_user_provider:
                   entity:
                       class: App\Entity\User
                       property: email
           firewalls:
               main:
                   anonymous: lazy
                   provider: app_user_provider
                   form_login:
                       login_path: login
                       check_path: login
                   logout:
                       path: logout
                       target: /
           access_control:
               - { path: '^/admin', roles: ROLE_ADMIN }
       ```
    6. Ensure your user has `ROLE_ADMIN` to access `/admin`.

11. **Test Authentication**:

    * Visit `/login`, authenticate with an admin user.
    * Confirm that `/admin` requires login and only users with `ROLE_ADMIN` can access.


