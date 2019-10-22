
###Creación Crud Api

```batch
php artisan crud:api [NombreModelo] --fields_from_file="app/generators/[archivo.json]" --module-name=[NombreModulo]
```

###Comandos a ejecutar luego de hacer PULL

```batch
npm install
composer install
composer update
composer dump-autoload
```
**Se debe eliminar el archivo "storage/app/modules.json"**

```batch
php artisan module:optimize
```

###Comando para la documentaciòn Backend

```batch
php artisan api:docs 
```

###Comando para la documentaciòn FrontEnd

```batch
npm run doc:build
```

###Comando para la ejecución de pruebas unitarias

```batch
php vendor/phpunit/phpunit/phpunit
```
