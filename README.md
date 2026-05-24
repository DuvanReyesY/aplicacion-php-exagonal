# API de usuarios en PHP

API REST para gestión de usuarios construida con arquitectura hexagonal. Corre sobre PHP puro con MySQL, sin frameworks.

## Requisitos

- PHP 8.0 o superior
- MySQL 5.7 o superior
- Composer

Levantar el servidor:

```bash
php -S localhost:8000 -t public
```

La API esta disponible en `http://localhost:8000`.

## Endpoints

### Listar usuarios
```
GET /users
```
Devuelve un array con todos los usuarios registrados.

---

### Obtener un usuario
```
GET /users/{id}
```
Devuelve el usuario con ese ID. Si no existe, responde 404.

---

### Crear usuario
```
POST /users
```
```json
{
    "id": "1",
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "mipassword123",
    "role": "member"
}
```
Roles aceptados: `admin`, `member`, `reviewer`. El usuario se crea con estado `PENDING`.

---

### Actualizar usuario
```
PUT /users/{id}
```
```json
{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "nuevapassword123",
    "role": "admin",
    "status": "ACTIVE"
}
```
Si no quieres cambiar la contraseña, manda el campo vacío y se conserva la anterior.
Estados aceptados: `ACTIVE`, `INACTIVE`, `PENDING`, `BLOCKED`.

---

### Eliminar usuario
```
DELETE /users/{id}
```
Elimina el usuario. Si no existe, responde 404.

---

### Login
```
POST /login
```
```json
{
    "email": "juan@example.com",
    "password": "mipassword123"
}
```
Verifica las credenciales y que el usuario esté en estado `ACTIVE`. Si algo falla, responde 401.

## Estructura del proyecto

```
├── Aplication/
│   ├── Mappers/          # Conversión entre comandos y modelos
│   ├── Ports/
│   │   ├── In/           # Interfaces de casos de uso
│   │   └── Out/          # Interfaces de repositorio
│   └── Services/         # Lógica de cada caso de uso
│       └── DTO/          # Comandos y queries
├── Domain/
│   ├── Enums/            # Roles y estados válidos
│   ├── Exceptions/       # Excepciones del dominio
│   ├── Models/           # Modelo de usuario
│   └── ValueObjects/     # Id, nombre, email, contraseña
├── Infrastructure/
│   └── Adapters/Persistence/MySql/
│       ├── Config/       # Conexión PDO
│       ├── Entity/       # Entidad de base de datos
│       ├── Mapper/       # Conversión entidad ↔ modelo
│       └── Repository/   # Implementación real con SQL
├── config/
│   └── database.php      # Credenciales de BD
├── public/
│   └── index.php         # Entrada de la app y router
├── tests/
│   └── UserTest.php
└── database.sql          # Script para crear la tabla
```

## Tests

```bash
./vendor/bin/phpunit tests
```
