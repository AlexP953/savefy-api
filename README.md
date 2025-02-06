WIP:

php artisan route:list

php artisan serve

### Run the migrations and seed the data:

```
php artisan migrate --seed
php artisan migrate:fresh --seed (restaura BD)
```

Generar un Password Grant Client
```
php artisan passport:client --password

```

PARA TESTING
Migraciones
```
php artisan migrate --env=testing
php artisan db:seed --env=testing
```
25/31

## WIP

## Endpoints Básicos

### /users 7/5✅✅  --- TEST ✅
- **POST /users** → Crear un nuevo usuario. ✅
- **GET /users** → Obtener todos los usuarios (admin). ✅
- **GET /users/{id}** → Obtener un usuario por su ID. ✅
obtener usuario actual ✅
obtener usuario por email ✅
- **PUT /users/{id}** → Actualizar datos de un usuario. ✅
- **DELETE /users/{id}** → Eliminar un usuario (solo admin). ✅

### /income 6/5 ✅ --- TEST ✅
- **POST /income** → Crear un nuevo ingreso.✅
- **GET /income** → Listar todos los ingresos del user.✅
obtener TODOS los ingresos ✅
- **GET /income/{id}** → Obtener un ingreso por ID.✅
- **PUT /income/{id}** → Actualizar un ingreso.✅
- **DELETE /income/{id}** → Eliminar un ingreso.✅

### /spent 6/5✅ --- TEST ✅
- **POST /spent** → Crear un nuevo gasto. ✅
- **GET /spent** → Listar todos los gastos del user. ✅
obtener TODOS los gastos ✅
- **GET /spent/{id}** → Obtener un gasto por ID. ✅
- **PUT /spent/{id}** → Actualizar un gasto. ✅
- **DELETE /spent/{id}** → Eliminar un gasto. ✅

### /category 5/5 ✅✅ --- TEST ✅
- **POST /category** → Crear una categoría. ✅
- **GET /category** → Listar todas las categorías. ✅
- **GET /category/{id}** → Obtener una categoría por ID. ✅
- **PUT /category/{id}** → Actualizar una categoría. ✅
- **DELETE /category/{id}** → Eliminar una categoría. ✅


## Otra lógica 0/1 --- TEST ✅
- **GET /reports/{year}/{month}** → Obtener un desglose detallado de ingresos y gastos para el mes.

### Estadísticas y Gráficos 0/2 --- TEST ✅
- **GET /stats/categories** → Que porcentaje es cada gasto. ✅
- **GET /stats/savings** → Calcular ahorro.✅

### Autenticación y Roles 2/4  --- TEST ✅
- **POST /auth/login** → Iniciar sesión y recibir un token. ✅
- **POST /auth/register** → Registrar nuevos usuarios. ✅
- **POST /auth/logout** → Cerrar sesión (invalida el token actual).
- **GET /auth/profile** → Obtener información del usuario autenticado. ✅


## Endpoints de Admin

### Gestión de Usuarios 1/1 (hecho, cambiar de lugar) ✅ --- TEST ✅
- **GET /admin/users** → Listar todos los usuarios del sistema.

### Categorías Globales 1/1 ✅ --- TEST ✅
- **POST /admin/category** → Categorías globales disponibles para todos los usuarios.
