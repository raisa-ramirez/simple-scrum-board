# Descripción
- La carpeta **backend** contiene la API de la aplicación, desarrollada con **Laravel**
- Carpeta **frontend-vue** contiene las vistas de la aplicación, desarrollada con **Vue.js**

### Configuraciones del backend
1. Configura las variables (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME y DB_PASSWORD) de conexión a la base de datos MySQL en el archivo .env (Puedes hacer una copia del .env.example)
2.	Instala las dependencias con: 
```
composer install
```
3.	Genera la clave de cifrado (APP_KEY) con el comando:
```
php artisan key:generate
```
5.	Ejecuta las migraciones y seeders
```
php artisan migrate –seed
```
7.	Ejecuta la aplicación
```
php artisan serve
```
### Configuraciones del frontend
1.	Instala las dependencias
```
npm i
```
2.	Crea el archivo **.env** en la raíz del proyecto
3.	Crea la variable de entorno que apunte a la url en la que se ejecutó el backend, por ejemplo.
```
VITE_API_URL = 'http://127.0.0.1:8000/api/'
```
5.	Ejecuta la aplicación
```
npm run dev
```