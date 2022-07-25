P6_SnowTricks_Guillaume_De_Backre
# Snowtricks

Snowtricks is a community figure management site. Project 6 of the openclassrooms training.

## Retrieve the project

https://github.com/guillaumedbk/P6_SnowTricks_Guillaume_De_Backre

## Run the project
1. Modify the .env file and fill in the name and connection information of your database. Then, launch:
```bash
php bin/console doctrine:database:create
```
2. Database migration
```bash
php bin/console doctrine:migrations:migrate
```
3. Fixtures load
```bash
php bin/console hautelook:fixtures:load
```
4. Launch the Symfony Local Web Server
```bash
cd my-project/
symfony server:start

  [OK] Web server listening on http://127.0.0.1:....
  ...


symfony open:local
```
