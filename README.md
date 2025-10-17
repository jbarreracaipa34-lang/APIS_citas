#  API de Gestión de Citas Médicas

Esta API ha sido desarrollada para la **gestión de citas médicas** por parte de los usuarios.  

Permite realizar operaciones de registro, consulta, modificación y eliminación de citas, además de gestionar médicos, pacientes y especialidades.

---

## Características principales
- Registro de pacientes y médicos.
- Creación, consulta, actualización y cancelación de citas.
- Gestión de especialidades médicas.
- Autenticación de usuarios mediante **sanctum**
- Arquitectura basada en **MVC (Modelo-Vista-Controlador)**.
- Implementado con **laravel**.

---

## Autenticación con Laravel Sanctum

Para garantizar la seguridad de la API de citas médicas, se implementa **Laravel Sanctum**.  

Esto permite a los usuarios autenticarse mediante **tokens personales** y acceder solo a los recursos autorizados.

---

###  Instalación de Sanctum
1. Instalar Sanctum:

```Json
composer require laravel/sanctum
```

## Endpoints de la API

La API está protegida con **Laravel Sanctum** y un middleware de roles.  
Dependiendo del rol (**admin, medico, paciente**) se habilitan diferentes operaciones.

---

###  Autenticación
| Método | Endpoint     | Descripción                  |
|--------|-------------|------------------------------|
| POST   | `/registrar` | Registro de usuario          |
| POST   | `/login`    | Inicio de sesión             |
| GET    | `/me`       | Ver perfil del usuario logueado *(requiere token)* |
| POST   | `/logout`   | Cerrar sesión *(requiere token)* |

---

###  Endpoints para **Admin**
*(requiere rol: admin)*

#### Citas
| Método | Endpoint              | Descripción             |
|--------|-----------------------|-------------------------|
| GET    | `/citas`              | Listar todas las citas |
| POST   | `/crearCitas`         | Crear una nueva cita   |
| GET    | `/citas/{id}`         | Ver detalle de una cita|
| PUT    | `/editarCitas/{id}`   | Editar cita existente  |
| DELETE | `/eliminarCitas/{id}` | Eliminar cita          |

#### Especialidades
| Método | Endpoint                       | Descripción                 |
|--------|--------------------------------|-----------------------------|
| POST   | `/crearEspecialidades`         | Crear nueva especialidad    |
| PUT    | `/editarEspecialidades/{id}`   | Editar especialidad         |
| DELETE | `/eliminarEspecialidades/{id}` | Eliminar especialidad       |

#### Horarios
| Método | Endpoint                 | Descripción                 |
|--------|--------------------------|-----------------------------|
| POST   | `/crearHorarios`         | Crear horario disponible    |
| PUT    | `/editarHorarios/{id}`   | Editar horario              |
| DELETE | `/eliminarHorarios/{id}` | Eliminar horario            |

#### Médicos
| Método | Endpoint               | Descripción              |
|--------|------------------------|--------------------------|
| POST   | `/crearMedico`         | Registrar nuevo médico   |
| PUT    | `/editarMedico/{id}`   | Editar médico            |
| DELETE | `/eliminarMedico/{id}` | Eliminar médico          |

#### Pacientes
| Método | Endpoint                   | Descripción              |
|--------|----------------------------|--------------------------|
| GET    | `/pacientes`              | Listar pacientes         |
| POST   | `/crearPacientes`         | Registrar paciente       |
| PUT    | `/editarPacientes/{id}`   | Editar paciente          |
| DELETE | `/eliminarPacientes/{id}` | Eliminar paciente        |

---

###  Endpoints para **Médico**
*(requiere rol: medico)*

| Método | Endpoint                           | Descripción                             |
|--------|------------------------------------|-----------------------------------------|
| GET    | `/horarios`                        | Listar todos los horarios               |
| GET    | `/horarios/{id}`                   | Ver horario específico                  |
| GET    | `/horariosDisponiblesPorMedico`    | Listar horarios disponibles del médico  |
| GET    | `/medicosConEspecialidad`          | Médicos con especialidad asignada       |
| GET    | `/medicosConHorarios`              | Médicos con horarios disponibles        |

---

###  Endpoints para **Paciente**
*(requiere rol: paciente)*

#### Especialidades y médicos
| Método | Endpoint               | Descripción              |
|--------|------------------------|--------------------------|
| GET    | `/especialidades`      | Listar especialidades    |
| GET    | `/especialidades/{id}` | Ver especialidad         |
| GET    | `/medicos`             | Listar médicos           |
| GET    | `/medicos/{id}`        | Ver detalle de médico    |

#### Pacientes
| Método | Endpoint                       | Descripción                     |
|--------|--------------------------------|---------------------------------|
| GET    | `/pacientes/{id}`              | Ver información de paciente     |
| GET    | `/pacientesConCitas`           | Listar pacientes con citas      |
| GET    | `/pacientesPorEPS/{eps}`       | Buscar pacientes por EPS        |

---

###  Endpoints Públicos (sin autenticación)
| Método | Endpoint                       | Descripción                    |
|--------|--------------------------------|--------------------------------|
| GET    | `/citasConMedicos`             | Listar citas con médicos       |
| GET    | `/citasPendientes`             | Listar citas pendientes        |
| GET    | `/citasCompletadas`            | Listar citas completadas       |
| GET    | `/citasPorFecha/{fecha}`       | Buscar citas por fecha         |
| GET    | `/horariosDisponiblesPorMedico`| Horarios disponibles por médico|
| GET    | `/medicosConEspecialidad`      | Médicos con especialidad       |
| GET    | `/medicosConHorarios`          | Médicos con horarios           |
| GET    | `/pacientesConCitas`           | Pacientes con citas agendadas  |
| GET    | `/pacientesPorEPS/{eps}`       | Pacientes filtrados por EPS    |
| GET    | `/contarCitasPaciente/{id}`    | Contar citas de un paciente    |

---

##  Roles y permisos
- **Admin** → Control total sobre citas, médicos, pacientes, especialidades y horarios.  
- **Médico** → Consulta de sus horarios y asignaciones.  
- **Paciente** → Consulta de médicos, especialidades y su información personal.  
- **Público** → Consulta de información general (citas pendientes, completadas, médicos disponibles).  

### Autor
Juan Pablo Barrera Caipa