# API de Gestión de Citas Médicas

Esta API permite gestionar la información de citas, pacientes, médicos, especialidades, horarios disponibles, y roles (paciente, médico y administrador). La API está construida con **Laravel** y usa **Sanctum** para la autenticación.

## Roles de Usuario
Los roles disponibles en el sistema son:

- **Admin**: Acceso completo para gestionar citas, especialidades, horarios, médicos y pacientes.
- **Médico**: Gestiona sus citas, especialidades, horarios y pacientes asociados.
- **Paciente**: Consulta especialidades, médicos, horarios y gestiona sus citas.

---

## Autenticación

### Registrar Usuario

`POST /registrar`

Permite registrar un nuevo usuario.

**Campos:**

- `name` (string)
- `email` (string)
- `password` (string)

**Respuesta:**

- `token` (string)

### Login

`POST /login`

Permite autenticar al usuario y obtener un token de acceso.

**Campos:**

- `email` (string)
- `password` (string)

**Respuesta:**

- `token` (string)

### Obtener Información del Usuario

`GET /me`  
Obtiene la información del usuario autenticado.

**Headers:**

- `Authorization: Bearer {token}`

---

## Endpoints para Administradores (`role:admin`)

### Citas

- **Listar Citas**  
  `GET /citas`

- **Crear Cita**  
  `POST /crearCitas`

- **Ver Cita**  
  `GET /citas/{id}`

- **Actualizar Cita**  
  `PUT /editarCitas/{id}`

- **Eliminar Cita**  
  `DELETE /eliminarCitas/{id}`

### Especialidades

- **Listar Especialidades**  
  `GET /especialidades`

- **Crear Especialidad**  
  `POST /crearEspecialidades`

- **Actualizar Especialidad**  
  `PUT /editarEspecialidades/{id}`

- **Eliminar Especialidad**  
  `DELETE /eliminarEspecialidades/{id}`

### Horarios Disponibles

- **Listar Horarios**  
  `GET /horarios`

- **Crear Horario**  
  `POST /crearHorarios`

- **Actualizar Horario**  
  `PUT /editarHorarios/{id}`

- **Eliminar Horario**  
  `DELETE /eliminarHorarios/{id}`

### Médicos

- **Listar Médicos**  
  `GET /medicos`

- **Crear Médico**  
  `POST /crearMedico`

- **Actualizar Médico**  
  `PUT /editarMedico/{id}`

- **Eliminar Médico**  
  `DELETE /eliminarMedico/{id}`

### Pacientes

- **Listar Pacientes**  
  `GET /pacientes`

- **Crear Paciente**  
  `POST /crearPacientes`

- **Actualizar Paciente**  
  `PUT /editarPacientes/{id}`

- **Eliminar Paciente**  
  `DELETE /eliminarPacientes/{id}`

---

## Endpoints para Médicos (`role:medico`)

### Citas

- **Listar Citas**  
  `GET /citas`

- **Crear Cita**  
  `POST /crearCitas`

- **Ver Cita**  
  `GET /citas/{id}`

- **Actualizar Cita**  
  `PUT /editarCitas/{id}`

- **Eliminar Cita**  
  `DELETE /eliminarCitas/{id}`

### Especialidades

- **Crear Especialidad**  
  `POST /crearEspecialidades`

- **Actualizar Especialidad**  
  `PUT /editarEspecialidades/{id}`

- **Eliminar Especialidad**  
  `DELETE /eliminarEspecialidades/{id}`

### Horarios Disponibles

- **Crear Horario**  
  `POST /crearHorarios`

- **Actualizar Horario**  
  `PUT /editarHorarios/{id}`

- **Eliminar Horario**  
  `DELETE /eliminarHorarios/{id}`

### Pacientes

- **Listar Pacientes**  
  `GET /pacientes`

- **Crear Paciente**  
  `POST /crearPacientes`

- **Actualizar Paciente**  
  `PUT /editarPacientes/{id}`

- **Eliminar Paciente**  
  `DELETE /eliminarPacientes/{id}`

---

## Endpoints para Pacientes (`role:paciente`)

### Especialidades

- **Listar Especialidades**  
  `GET /especialidades`

- **Ver Especialidad**  
  `GET /especialidades/{id}`

### Médicos

- **Listar Médicos**  
  `GET /medicos`

- **Ver Médico**  
  `GET /medicos/{id}`

### Horarios Disponibles

- **Listar Horarios**  
  `GET /horarios`

### Pacientes

- **Ver Paciente**  
  `GET /pacientes/{id}`

### Citas

- **Crear Cita**  
  `POST /crearCitas`

- **Ver Cita**  
  `GET /citas/{id}`

- **Actualizar Cita**  
  `PUT /editarCitas/{id}`

- **Eliminar Cita**  
  `DELETE /eliminarCitas/{id}`

---

## Endpoints Públicos (sin autenticación requerida)

- **Citas con Médicos**  
  `GET /citasConMedicos`

- **Citas Pendientes**  
  `GET /citasPendientes`

- **Citas Completadas**  
  `GET /citasCompletadas`

- **Citas por Fecha**  
  `GET /citasPorFecha/{fecha}`

- **Horarios Disponibles por Médico**  
  `GET /horariosDisponiblesPorMedico`

- **Médicos con Especialidad**  
  `GET /medicosConEspecialidad`

- **Médicos con Horarios**  
  `GET /medicosConHorarios`

- **Pacientes con Citas**  
  `GET /pacientesConCitas`

- **Pacientes por EPS**  
  `GET /pacientesPorEPS/{eps}`

- **Contar Citas de un Paciente**  
  `GET /contarCitasPaciente/{id}`

---

## Requisitos

- **PHP 8.x**  
- **Laravel 9.x o superior**  
- **MySQL 5.x o superior**

## Instalación

1. Ejecutar `composer install` para instalar las dependencias de Laravel.  
2. Configurar el archivo `.env` con sus credenciales de base de datos (DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.).  
3. Generar la clave de la aplicación: `php artisan key:generate`.  
4. Ejecutar las migraciones para crear las tablas: `php artisan migrate`.  
5. Para crear un modelo junto con su controlador y modelo se usa:  
   `php artisan make:model NombreModelo -mc`  
6. Si se necesita un middleware personalizado:  
   `php artisan make:middleware NombreMiddleware`  
7. Inicia el servidor de desarrollo con: `php artisan serve`

---

## Hecho por:

Juan Pablo Barrera Caipa
