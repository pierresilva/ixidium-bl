### Repositorio: BL

### Configuración:

| Requisitos   		  | Versión    			            |
| :------------- 	  | :--------- 			            |
| Sistema Operativo   | `Windows	>= Server 2008`     |
| Servidor Web        | `Apache >= 2.4`		            |
| PHP  				  | `PHP >= 7.1.3`				    |
|					  | `OpenSSL PHP Extension`		    |
|					  | `SQLSrv PHP Extension`		    |
|					  | `SQLSrv PDO PHP Extension`      |
|					  | `Mbstring PHP Extension`	    |
|					  | `Tokenizer PHP Extension`	    |
|					  | `XML PHP Extension`			    |
|					  | `Ctype PHP Extension`			|
|					  | `JSON PHP Extension`   		    |
| Base de datos       | `SQL Server >= 2012`		    |
| Node JS             | `NodeJS >= 8.* <= 10.*`         |
| Laravel             | `Laravel = 5.6`                 |
| Angular             | `Angular >= 7`                  |
| Angular Cli         |                                 |

.

### Archivos de configuración / conexión:

1. `.env.example` - Ejemplos de configuración de Laravel
2. `.env` - Para desarrollo
3. `.env.testing`  - Para pruebas unitarias
4. `resources/angular/environments/enviorenment.ts` - Desarrollo
5. `resources/angular/environments/enviorenment.prod.ts` - Producción
___
**NOTA:** Todos los archivos contenidos en este repositorio son confidenciales.

Instalación
-----------
__Esta guia de instalación presupone que usted tiene instaladas y configuradas las herramientas para el entorno de desarrollo__.

* __Asegurese de tener instalados los requisitos de configuración.__

* Clonar repositorio.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
git clone https://bitbucket.org/comfamiliarhuila/renova.git
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Posicionarse en la carpeta

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
cd renova
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Posicionarse en la rama develop

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
git checkout develop
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Instalar paquetes Laravel

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
composer install
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Instalar plugins javascript

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
npm install
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Crear el archivo .env

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
cp .env.example .env
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Crear la base de datos de desarrollo `renova` y la base de datos para pruebas unitarias `renova-testing`.

* Cambiar las configuraciones del entorno de desarrollo en el archivo `.env` y `.env.testing`

* Crear la clave de encriptación de la aplicación

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
php artisan key:generate
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Crear clave de encriptación de JWT

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
php artisan jwt:secret
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Correr migraciones y seeders

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
php artisan migrate
php artisan db:seed --class=SqlSeeder
php artisan db:seed --class=SqlSeederLast
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Correr el servidor de la applicación angular

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
npm run start
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

__NOTA__: Si su virtual host no es `http://renova.test` debe reemplazar el valor
de `api_url` en: `resources/angular/environments/environment.ts` por la url de su virtual host.

* Compilar a producción

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
npm run build:prod
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Actualizar a Angular 7

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
npm run reinstall
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
