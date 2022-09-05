<?php
//======================================================================
// VARIABLES
//======================================================================
$errorAvatar = 0;
$avatar = null;
$rutaThumbnail = null;

// Definir directorio donde se guardará
define('PATH_AVATAR', './subidos/');
define('PATH_AVATAR_THUMBNAIL', './subidos/thumbnails/');

// Tamanyo max avatar: 2 Mb
define('MAX_SIZE_AVATAR_MB', 2);
define('MAX_SIZE_AVATAR', MAX_SIZE_AVATAR_MB * 1024 * 1024);
// En /etc/php/7.4/cli/php.ini edita las siguientes variables
// upload_max_filesize=100Mb
// post_max_size=100Mb

// Anchura miniatura
define('WIDTH_THUMBNAIL', 100);


