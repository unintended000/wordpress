<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'wordpress');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'EM)v9iKXyakN]7{j2b2KPUT<|ofp5)D!xYQMdw$H)>)rJNJcJSr$%l,&,=mDpp3=');
define('SECURE_AUTH_KEY',  'B|BRH?7^n])W^3@#^[6+KpxOY#c`-{[gUqYorhSQTS|~IcMCP96+U!fH/qOhQ%%g');
define('LOGGED_IN_KEY',    '0zuHn=vuHawcXF_G>jja~64_vLK2+I}i8$uR#B`vxh&LGbJ-cF4lxl+ykd>2a-La');
define('NONCE_KEY',        '*?s>HS2eyPf!3qkkYj[zt&u{HqP`pcTM-wI|HFUCVHQUM)FjOKnzFF2m#LJJ~mv0');
define('AUTH_SALT',        '3nE&_w0bL?ctqkQ1S_zn/jw#!M)NP-v|q:FG 51?=$|WCvFeLaTHTUm`s$6C7TyC');
define('SECURE_AUTH_SALT', '{%{/*g%)p^JQWz}Y%$P$ojV?w-DanuNW{|L!~)^Z<FGgg4 4}+E2DWNcYjFelRky');
define('LOGGED_IN_SALT',   'W<~EDK]=v]N =H+j#QBwM{Bj.xGXG%lS^LChRb>j=6gCGu-![| <SVS I5.c<#AB');
define('NONCE_SALT',       'R-6IN~^O%lA]>k6yi3[=oY$W7W]_$N]T=I=aWOgcv/)Ss 6>ycs[GCP3`Wc`W)$R');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 * 
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
