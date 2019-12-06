-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 06-12-2019 a las 10:32:54
-- Versión del servidor: 5.7.23-23
-- Versión de PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `miscurso_cicom`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL COMMENT 'identificador del registro',
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Título',
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripción',
  `category_id` int(11) NOT NULL COMMENT 'identificado de la Categoría',
  `line_id` int(11) NOT NULL COMMENT 'identificador de la Línea de Investigación',
  `editorial` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Editorial',
  `publication_date` date NOT NULL COMMENT 'Fecha de Publicación',
  `file` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Archivo',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'usuario que sube el articulo',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de creado',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualizado',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'fecha de eliminacion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='informacion de los articulos subidos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombres',
  `lastname` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Apellidos',
  `article_id` int(11) NOT NULL COMMENT 'articulo al que pertenece',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha creado',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualizado',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'fecha eliminado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='datos de los  autores';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL COMMENT 'identificador',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre de la categoria',
  `state` smallint(6) NOT NULL COMMENT 'estado (0=>desactivado,1=>activado)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='almacena las categorias';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extensions`
--

CREATE TABLE `extensions` (
  `id` int(11) NOT NULL COMMENT 'identificador del registro',
  `name` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre de la extencion',
  `state` smallint(6) NOT NULL COMMENT 'estado (0=>desactivado,1=>activado)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de creacion',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'fecha de eliminacion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='extenciones del sistema';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gallery`
--

CREATE TABLE `gallery` (
  `id` int(10) UNSIGNED NOT NULL,
  `icon` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `images` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` int(11) NOT NULL,
  `binded` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `videos` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL COMMENT 'identificador',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre del archivo',
  `image` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'url de la imagen',
  `user_group_id` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha de creacion',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualizacion',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'fecha de eliminacion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='imagenes del sistema';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `last_congress`
--

CREATE TABLE `last_congress` (
  `id` int(11) NOT NULL COMMENT 'identificador',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre a mostrar',
  `url` text COLLATE utf8_unicode_ci COMMENT 'link de la web',
  `file` text COLLATE utf8_unicode_ci COMMENT 'url del archivo ',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha creado',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha actualizacion',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'fecha de elimininacion'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lines_investigation`
--

CREATE TABLE `lines_investigation` (
  `id` int(11) NOT NULL COMMENT 'identificador del registro',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre de la linea de investigacion',
  `state` smallint(6) NOT NULL COMMENT 'estado (0=>desactivado,1=>activado)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de creacion',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'fecha de actualizacion',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'fecha de eliminacion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Lineas de investigación';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'correo del usuario',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'token de seguridad',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'fecha creado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='almacena tokens, para restablecer contraseñas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'identificador del registro',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'nombre del permiso para programacion',
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nombre del permiso que se vera',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'descripcion del permiso',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'fecha creado',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'fecha actualizado',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'fecha eliminado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='permisos del sistema, para acceso';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL COMMENT 'identificador del permiso',
  `role_id` int(10) UNSIGNED NOT NULL COMMENT 'identificador del rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla pivot para relacinar roles con permisos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'identificador del registro',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'nombre de rol para programacion',
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nombre del rol que se vera',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'descripcion del rolfeca',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'fecha creado',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'fecha actualizado',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'fecha eliminado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='datos de los roles del sistema';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_user`
--

CREATE TABLE `role_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'identificador del usuario',
  `role_id` int(10) UNSIGNED NOT NULL COMMENT 'idenficadir del '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla pivot para relacinar roles con usuarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'identificador del registro',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'nombre del usuario',
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'apellido del usuario',
  `user` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'nombre de usuario unio',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'correo del usuaario, dato que no se repite',
  `email_verified_at` timestamp NULL DEFAULT NULL COMMENT 'fecha de verificacion del usuario',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'conraseña de de login',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'token para recordar inicio de sesion',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'fecha de creado',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'ultima fecha de actualizacion',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'fecha de eliminacion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='informacion de los usuarios';

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`,`line_id`),
  ADD KEY `fk_articles_line_inv` (`line_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `last_congress`
--
ALTER TABLE `last_congress`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lines_investigation`
--
ALTER TABLE `lines_investigation`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indices de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indices de la tabla `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del registro';

--
-- AUTO_INCREMENT de la tabla `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador';

--
-- AUTO_INCREMENT de la tabla `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del registro';

--
-- AUTO_INCREMENT de la tabla `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador';

--
-- AUTO_INCREMENT de la tabla `last_congress`
--
ALTER TABLE `last_congress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador';

--
-- AUTO_INCREMENT de la tabla `lines_investigation`
--
ALTER TABLE `lines_investigation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador del registro';

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'identificador del registro';

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'identificador del registro';

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'identificador del registro';

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_articles_line_inv` FOREIGN KEY (`line_id`) REFERENCES `lines_investigation` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_articles_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `authors`
--
ALTER TABLE `authors`
  ADD CONSTRAINT `fk_author_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
