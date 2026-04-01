<?php
// Script de depuração para verificar a URL de autenticação do Google Sheets
define('WP_USE_THEMES', false);
require_once('wp-load.php');

$args = [
    'return' => admin_url('admin.php?page=leccorforms-settings&view=integrations&leccorforms-integration=google-sheets'),
];

$client = leccorforms_google_sheets()->get('client');
$url = $client->get_auth_url($args, 'custom');

echo "URL Gerada:\n" . $url . "\n";
echo "\nArgumentos Preparados:\n";
$reflection = new ReflectionClass(get_class($client));
$method = $reflection->getMethod('prepare_auth_args');
$method->setAccessible(true);
$prepared_args = $method->invoke($client, $args);
print_r($prepared_args);
