## Follow up Project

This is a follow-up test for CT.

### Setup process

- clone this project
- rename `.env.example` to `.env`
- for database, we are using sqlite, so create an sqlite file in the database subdirectory of the project `touch database/database.sqlite`
- change the db connection in the `.env` file to `sqlite`
- ensure you copy the full path to the location of your sqlite file created in step III above and set the value to db_database.
- install the project dependencies using composer `composer install`
- migrate and seed the data `php artisan migrate --seed`
- open the entry url to test.
