<?php
//-----------------------------------------------------
// Funciones Para Validar
//-----------------------------------------------------

/**
 * Método que valida si un texto no esta vacío
 * @param {string} - Texto a validar
 * @return {boolean}
 */
function validar_requerido(string $texto): bool
{
    return (trim($texto) == '');
}

/**
 * Método que valida si el texto tiene un formato válido de E-Mail
 * @param {string} - Email
 * @return {bool}
 */
function validar_email(string $texto): bool
{
    return !filter_var($texto, FILTER_VALIDATE_EMAIL);
}

/**
 * Método para validar que la contraseña tenga de 4 a 8 carácteres, letra mayúscula, minúscula y al menos un número
 * @param string $password
 * @return bool
 */
function validar_contrasenya(string $password): bool
{
    return !preg_match('/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{4,8}$/', $password);
}

