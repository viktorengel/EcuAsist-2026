❌ El problema

En tu función sanitize() hay un error de escritura:

htmlspecian_chars()

🔧 Función corregida
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

4. IMPORTANTE: Cambiar la contraseña del administrador después del primer acceso, pude acceder pero no puedo cambiar la contraseña

PASO 5: CONFIGURACIÓN INICIAL
-----------------------------
1. Ingresar como administrador: Ok

2. Crear año lectivo activo (si no existe): No puedo

3. Crear cursos para el año lectivo: No puedo

4. Crear asignaturas: No puedo

5. Registrar usuarios (docentes, estudiantes, etc.): No puedo

6. Asignar roles a los usuarios creados: Si puedo asinar pero no puedo retirar un Rol a un usuario

7. Asignar docentes a asignaturas y cursos: No puedo

8. Matricular estudiantes en cursos: No puedo

9. No se puede recuperar la la clave de un usuario, muestra Token generado pero el email no pudo ser enviado. Contacta al administrador.

10. Cuando se crea un nuevo usuario el rol por defecto debe ser docente.