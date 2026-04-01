<?php
/**
 * Plugin Name: Leccor Simple Forms to Sheets
 * Plugin URI: https://leccorforms.com
 * Description: Plugin simples para criar formulários com redirecionamento e integração com Google Sheets.
 * Author: Johnathan Amorim (Antigravity Assistant)
 * Author URI: https://leccorforms.com
 * Version: 1.0.0
 * Text Domain: leccor-simple-forms
 * License: GPL v2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Definições de caminhos
define( 'LFS_PATH', plugin_dir_path( __FILE__ ) );
define( 'LFS_URL', plugin_dir_url( __FILE__ ) );
define( 'LFS_VERSION', '1.0.0' );

// Carregar classes
require_once LFS_PATH . 'includes/class-form-manager.php';
require_once LFS_PATH . 'includes/class-form-handler.php';

// Inicializar classes
function lfs_init_plugin() {
	new LFS_Form_Manager();
	new LFS_Form_Handler();
}
add_action( 'plugins_loaded', 'lfs_init_plugin' );

// Enfileirar Scripts e Estilos
function lfs_enqueue_assets() {
	// Somente enfileirar se o shortcode estiver na página (pode ser otimizado depois)
	wp_enqueue_style( 'intl-tel-input', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css' );
	wp_enqueue_script( 'intl-tel-input', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js', array( 'jquery' ), '17.0.19', true );
	wp_enqueue_script( 'intl-tel-input-utils', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js', array( 'intl-tel-input' ), '17.0.19', true );

	wp_enqueue_style( 'lfs-style', LFS_URL . 'assets/css/form-style.css', array(), LFS_VERSION );
	wp_enqueue_script( 'lfs-submission', LFS_URL . 'assets/js/form-submission.js', array( 'jquery', 'intl-tel-input' ), LFS_VERSION, true );

	wp_localize_script( 'lfs-submission', 'lfs_ajax', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'lfs_submit_nonce' )
	));
}
add_action( 'wp_enqueue_scripts', 'lfs_enqueue_assets' );
