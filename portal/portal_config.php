<?php

// Setup database for IsharePortal
if (!defined('DB_SERVER')) {	define('DB_SERVER', 'localhost'); }
if (!defined('DB_USERNAME')) {	define('DB_USERNAME', 'root'); }
if (!defined('DB_PASSWORD')) {	define('DB_PASSWORD', 'toor'); }
if (!defined('DB_NAME')) {	define('DB_NAME', 'eoffice'); }

// Setup site
if (!defined('BASE_URL')) {	define('BASE_URL', 'http://localhost:8080/eoffice/portal/'); }
if (!defined('KEEP_SHOUTS')) {	define('KEEP_SHOUTS', 7); } // default 7 days
if (!defined('KEEP_UPDATES')) {	define('KEEP_UPDATES', 7); } // default 7 days
if (!defined('KEEP_REQUESTS')) {	define('KEEP_REQUESTS', 7); } // default 7 days

?>