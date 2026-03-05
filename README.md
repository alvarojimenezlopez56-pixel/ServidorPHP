**Laboratorio de Ciberseguridad: SQL Injection y Mitigación en PHP**

Este proyecto es un entorno de pruebas diseñado para analizar y comprender las vulnerabilidades de Inyección SQL (SQLi) en aplicaciones web. 
A través de este laboratorio, se exploran los métodos de ataque más comunes y se implementan las mejores prácticas de defensa en el desarrollo backend.

**Objetivos del Proyecto**

***Demostrar vulnerabilidades:** Análisis de cómo la concatenación directa de strings en consultas SQL permite el bypass de autenticación y el volcado de datos sensibles.
***Explotación controlada:** Ejecución de ataques tipo UNION SELECT para enumerar bases de datos y extraer información de usuarios.
***Implementación de defensas:** Uso de PDO (PHP Data Objects) con sentencias preparadas para neutralizar la inyección de código.
***Seguridad criptográfica:** Aplicación de algoritmos de hashing como bcrypt mediante password_hash() para proteger la privacidad de las credenciales, incluso ante filtraciones de la base de datos.

**Documentación del Laboratorio**

A continuación, se detallan los ejercicios realizados, desde la explotación inicial hasta la segurización total del sistema.
[📄 Descargar informe completo en PDF](./SQL_Injection_en_PHP.pdf)
