# PEOPAYGO_TIMESHEETS
Prueba tecnica 



Video de funcionamiento:

[Watch the video on YouTube](https://youtu.be/gqcVp00PuB0)




Credenciales de Admin:

email: admi@system.com

password: nMlkj.,768-Ab



# FRONTEND
Acceder a la carpeta TIMESHEETS_FRONTEND

npm i --force

ng serve

http://localhost:4200/#/

# BACKEND

Deployed with EC2 AWS on http://54.160.199.184/

Acceder a la carpeta TIMESHEETS_BACKEND

composer update

composer install

(Set .env file)

php artisan migrate --seed

php artisan serve

http://127.0.0.1:8000


### CONNECTION TO REMOTE DATABASE (AWS RDS)

DB_CONNECTION=mysql

DB_HOST=database-1.c9a28weiie9f.us-east-1.rds.amazonaws.com

DB_PORT=3306

DB_DATABASE=timesheets

DB_USERNAME=admin

DB_PASSWORD=timesheetsRoot






