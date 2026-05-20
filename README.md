# Kronet — Sprint 4

Aplicación web de banco de tiempo donde los usuarios intercambian servicios
usando el tiempo como moneda en vez de dinero.

## Instalación

1. Clonar este repositorio en `C:\xampp\htdocs\kronet` (o equivalente).
2. Arrancar Apache + MySQL en XAMPP.
3. Crear una base de datos llamada `kronet_db` en phpMyAdmin.
4. Importar `database/schema.sql` dentro de `kronet_db`.
5. Acceder a `http://localhost/kronet/public/`.

## Estructura

```
app/
  controllers/        Controladores (rutas → lógica)
  helpers/            Categorías y calculadora de precios
  models/             Acceso a BD por entidad
  views/              Plantillas PHP organizadas por área
    partials/         head, navbar, footer reutilizables
config/
  conexion_db.php     Conexión PDO a MySQL
database/
  schema.sql          Esquema completo
public/
  index.php           Front controller
  assets/css/         kronet.css (estilos globales)
  assets/js/          kronet.js, login.js, register.js
routes/
  web.php             Definición de rutas
```

### Algoritmo de precio (PrecioCalculator)
La fórmula es `créditos = round(duración_horas × multiplicador_categoría)` con un mínimo de 1.

| Categoría        | Multiplicador | Justificación                         |
| ---------------- | ------------- | ------------------------------------- |
| Reparaciones     | 2.5           | Oficio técnico (fontanería, etc.)     |
| Tecnología       | 2.0           | Conocimiento especializado            |
| Salud            | 2.0           | Cualificación profesional             |
| Asesoramiento    | 1.8           | Conocimiento profesional              |
| Educación        | 1.5           | Preparación previa                    |
| Idiomas / Música | 1.5           | Habilidad adquirida                   |
| Arte / Cuidados  | 1.3           | Habilidad media                       |
| Deporte          | 1.2           | Cualificación ligera                  |
| Cocina           | 1.1           | Habilidad doméstica                   |
| Resto            | 1.0           | Sin cualificación específica          |

## Vista general de rutas

| URL                                            | Descripción                       |
| ---------------------------------------------- | --------------------------------- |
| `/`                                            | Home con hero + dashboard         |
| `/login` / `/register`                         | Autenticación                     |
| `/anuncios/buscar`                             | Explorar con filtros y paginación |
| `/anuncios/crear`                              | Publicar servicio                 |
| `/anuncios/{id}`                               | Ficha de anuncio + chat directo   |
| `/anuncios/{id}/editar`                        | Edición                           |
| `/anuncios/{id}/destacar`                      | Destacar (gratis o de pago)       |
| `/anuncios/mis-anuncios`                       | Mis anuncios                      |
| `/intercambios/mis-intercambios`               | Historial                         |
| `/intercambios/ofertas-recibidas`              | Bandeja de solicitudes            |
| `/mensajes`                                    | Chat estilo Wallapop              |
| `/mensajes?anuncio=X&usuario=Y`                | Conversación concreta             |
| `/perfil`                                      | Mi perfil                         |
| `/perfil/{id}`                                 | Perfil ajeno                      |
| `/valoraciones/crear?id_usuario=X`             | Dejar valoración                  |
| `/valoraciones/mis-valoraciones`               | Mis valoraciones recibidas        |
| `/suscripcion`                                 | Gestión Premium                   |
| `/notificaciones`                              | Bandeja de notificaciones         |

## Tecnologías

- PHP 8 (PDO + MySQL)
- HTML/CSS/JS vanilla con FontAwesome
- Arquitectura MVC simple sin frameworks
