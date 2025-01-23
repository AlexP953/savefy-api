WIP:

php artisan route:list

### Run the migrations and seed the data:

```
php artisan migrate --seed

PARA TESTING
Migraciones
php artisan migrate --env=testing

<!-- Seeders -->
<!-- php artisan db:seed --env=testing -->


```

In one terminal, run the following command to start the server:

```
php artisan serve
```



## WIP

## Endpoints Básicos

### /users 7/5
- **POST /users** → Crear un nuevo usuario. DONE
- **GET /users** → Obtener todos los usuarios (admin). DONE
- **GET /users/{id}** → Obtener un usuario por su ID. DONE
obtener usuario actual DONE
obtener usuario por email DONE
- **PUT /users/{id}** → Actualizar datos de un usuario. DONE
- **DELETE /users/{id}** → Eliminar un usuario (solo admin). DONE

### /income 0/5
- **POST /income** → Crear un nuevo ingreso.
- **GET /income** → Listar todos los ingresos del user.
- **GET /income/{id}** → Obtener un ingreso por ID.
- **PUT /income/{id}** → Actualizar un ingreso.
- **DELETE /income/{id}** → Eliminar un ingreso.

### /spent 0/5
- **POST /spent** → Crear un nuevo gasto.
- **GET /spent** → Listar todos los gastos del user.
- **GET /spent/{id}** → Obtener un gasto por ID.
- **PUT /spent/{id}** → Actualizar un gasto.
- **DELETE /spent/{id}** → Eliminar un gasto.

### /category 0/5
- **POST /category** → Crear una categoría.
- **GET /category** → Listar todas las categorías.
- **GET /category/{id}** → Obtener una categoría por ID.
- **PUT /category/{id}** → Actualizar una categoría.
- **DELETE /category/{id}** → Eliminar una categoría.


## Otra lógica 0/1
- **GET /reports/{year}/{month}** → Obtener un desglose detallado de ingresos y gastos para el mes.

### Estadísticas y Gráficos 0/2
- **GET /stats/categories** → Que porcentaje es cada gasto.
- **GET /stats/savings** → Calcular ahorro.

### Autenticación y Roles 0/4
- **POST /auth/login** → Iniciar sesión y recibir un token.
- **POST /auth/register** → Registrar nuevos usuarios.
- **POST /auth/logout** → Cerrar sesión (invalida el token actual).
- **GET /auth/profile** → Obtener información del usuario autenticado.


## Endpoints de Admin

### Gestión de Usuarios 0/1 (hecho, cambiar de lugar)
- **GET /admin/users** → Listar todos los usuarios del sistema.

### Categorías Globales 0/1
- **POST /admin/category** → Categorías globales disponibles para todos los usuarios.
