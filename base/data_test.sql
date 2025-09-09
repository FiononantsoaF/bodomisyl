use App\Models\User;

User::create([
    'name' => 'fy',
    'email' => 'fy.rakotojaona@groupe-syl.com',
    'password' => bcrypt('123'),
]);

php artisan l5-swagger:generate
php artisan make:migration service_session
php artisan migrate
php artisan make:model WaitingList -cr
-m, --migration Create a new migration file for the model.
-c, --controller Create a new controller for the model.
-r, --resource Indicates if the generated controller should be a resource controller


php artisan make:controller Api/SubscriptionController
php artisan make:model Services


add column existing table
php artisan make:migration add_image_url_service_category_table --table=service_category

php artisan make:migration change_description_duration_servic_table --table=services


php artisan make:migration category

<>
->

php artisan make:migration creneau


{
  "clients":
    {
      "name": "fred",
      "email": "freddyhat122s9@gmail.com",
      "phone": "0349405019",
      "address": "addr"
    }
  ,
  "employee_id": 2,
  "service_id": 10,
  "start_times": "2025-06-21 12:00:00",
  "comment": "comm"
}

composer require ibex/crud-generator --dev
php artisan vendor:publish --tag=crud
php artisan make:crud {table_name}
php artisan make:crud users

php artisan make:crud creneau
                                
git clone https://github.com/FiononantsoaF/bodomisyl.git

bdd onlinet: 
 fuzo7250_fred / M4J!!8HmsMJ(
 
 
 ---- accès o2switckh ------------
 https://mois.o2switch.net:2083/
 fuzo7250 / F68q-cySj-yUN!
 
 ------------ bdd prod bo ----------------
APP_NAME=Laravel
APP_ENV=prod
APP_KEY=base64:L8qtPxdg1fA+ExwpdJjrjj+lexSNvwmV9nyI1wRXhMQ=
APP_DEBUG=false
APP_URL=https://bodomisyl.groupe-syl.com


DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fuzo7250_domisyl
DB_USERNAME=fuzo7250_fred
DB_PASSWORD=M4J!!8HmsMJ(

-------------------
 
 - calendrier : rendez-vous déjà pris
 - gestion depuis BO : créneau 
 - 
 ----------------
 
 https://bodomisyl.groupe-syl.com/
 
 -----livraison prod
  baseURL: 'https://domisyl.groupe-syl.com/bodomisyl/public/api', 

php artisan make:migration service_session