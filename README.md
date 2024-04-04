# LB2 Applikation

Diese Applikation ist bewusst unsicher programmiert und sollte nie in produktiven Umgebungen zum Einsatz kommen. Ziel der Applikation ist es, Lernende für mögliche Schwachstellen in Applikationen zu sensibilisieren, diese anzuleiten, wie die Schwachstellen aufgespürt und geschlossen werden können.

Die Applikation wird im Rahmen der LB2 im [Modul 183](https://gitlab.com/ch-tbz-it/Stud/m183/m183) durch die Lernenden bearbeitet.

## Applikation laufen lassen

1. Die [.env.example](./.env.example) Datei duplizieren und zu `.env` umbenennen
2. `docker compose up -d` ausführen

3. Die Applikation sollte nun auf [http://localhost:80](http://localhost:80) aufrufbar sein

4. Auf [http://localhost:5601](http://localhost:5601) können alle Elastic Services gefunden werden
   - Username und Password ist `elastic`

## Security Fixes Documentation

### Authentication and Authorization

- **Unauthorised Access and Permission Verification:**
  - **admin/users.php:** Improved authentication by checking if the user is logged in before accessing user-related features. Added permission checks to ensure only users with admin roles can access certain data. Utilized environment variables for sensitive information and implemented proper error logging with ElasticSearchLogger.
  - **Various files:**
    Ensured that all user actions require the user to be logged in by checking the existence of a valid php sessions in favor of cookies. Unauthorized attempts are logged and redirected to the login page. Added or improved checks for user roles where necessary to enforce proper authorization.

### SQL Injection

- **Prepared Statements:**
  - **edit.php, savetask.php:** Switched from direct SQL queries to prepared statements to prevent SQL injection. Ensured that user input is properly sanitized before being used in database operations.

### Cross-Site Scripting (XSS)

- **Output Encoding:**
  - **admin/users.php, search/v2/index.php, user/backgroundsearch.php, user/tasklist.php:** Applied `htmlspecialchars` to user-generated content before echoing it back to the user, preventing potential XSS attacks by encoding special characters.

### Sensitive Data Exposure

- **Environment Variables:**
  - **.env:** Introduced an `.env` file to store sensitive information such as database credentials and Elasticsearch credentials securely. Updated files to load sensitive data from environment variables instead of hardcoding them.
  - **Dockerfile, compose.yaml:** Configured services to use environment variables for sensitive settings, ensuring that credentials are not exposed in the source code or docker configuration files.
- **Password Hashing:**
  - **Database:** Passwords stored in the database are now hashed. This measure significantly increases the security of stored passwords, making them resistant to theft even in the event of direct access to the database.

### Logging and Monitoring

- **Logging:**
  - **fw/ElasticSearchLogger.php:** Implemented a centralized logging mechanism using Elasticsearch. All critical operations, errors, and warnings are now logged with appropriate context, improving the ability to monitor and react to potential security issues.

### Code Quality and Security Best Practices

- **General Code Improvements:**
  - **Various Files:** Refactored code for better readability, maintainability, and security. Removed unused code and files, such as `config.php`, which was replaced by environment variables. Ensured consistent use of security practices across the application.
