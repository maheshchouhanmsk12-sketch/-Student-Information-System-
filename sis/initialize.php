<?php
$dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');

// Dynamic base_url detection or environment variable override
if (!defined('base_url')) {
    $env_base_url = getenv('BASE_URL');
    if (!empty($env_base_url)) {
        define('base_url', rtrim($env_base_url, '/') . '/');
    } else {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        // Auto-detect subfolder if running in subfolder (e.g., /sis/) or at root
        $doc_root = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])) : '';
        $app_dir = str_replace('\\', '/', __DIR__);
        $subfolder = '';
        if (!empty($doc_root) && strpos($app_dir, $doc_root) === 0) {
            $subfolder = trim(substr($app_dir, strlen($doc_root)), '/');
        }
        $subpath = !empty($subfolder) ? '/' . $subfolder . '/' : '/';
        define('base_url', $protocol . $host . $subpath);
    }
}

if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
if(!defined('dev_data')) define('dev_data',$dev_data);

// Database configuration supporting Environment Variables for Cloud Deployments (Render + Aiven MySQL)
$db_host = getenv('DB_SERVER') ?: (getenv('DB_HOST') ?: "localhost");
$db_user = getenv('DB_USERNAME') ?: (getenv('DB_USER') ?: "root");
$db_pass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : "");
$db_name = getenv('DB_NAME') ?: (getenv('DB_DATABASE') ?: "sis_db");
$db_port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;
$db_ssl  = (getenv('DB_SSL') === 'true' || getenv('DB_SSL') === '1');

if(!defined('DB_SERVER')) define('DB_SERVER', $db_host);
if(!defined('DB_USERNAME')) define('DB_USERNAME', $db_user);
if(!defined('DB_PASSWORD')) define('DB_PASSWORD', $db_pass);
if(!defined('DB_NAME')) define('DB_NAME', $db_name);
if(!defined('DB_PORT')) define('DB_PORT', $db_port);
if(!defined('DB_SSL')) define('DB_SSL', $db_ssl);
?>