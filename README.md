Standard Installation Instructions for Laravel 5.6:
https://laravel.com/docs/5.6/installation

Clone the Green Eye repo into your server and configure nginx or apache2 according to the installation docs provided above
Change directory into the newly cloned repo and run the following commands
cp .env.example .env
Make sure to update the env file to meet your environmental settings (database, etc)
php artisan key:generate
php artisan migrate
Once completed using your browser go to website
localhost:8000/setup-database
This will ensure the basic settings are instantiated in the database
Once the site is up and running, you will need to make sure the frontend components are all pointing to the correct url for their endpoints.

The API Documentation can be found here:

https://web.postman.co/collections/1213959-194560a7-90eb-45d5-b668-d30a198a7188?workspace=171b4b3d-2c68-4bee-850e-654423a680fa

Standard Installation Instructions to run our software:
Clone the repository in 3 Pis from https://github.com/manishshambu/InternetOfThings
Run the mosquitto broker on master. (sudo mosquitto)
Run each subscriber module in each Pi. (python3 light_subscriber.py, python3 music_subscriber.py, python3  sms_subscriber.py)
Run dcamera.py in the master.

