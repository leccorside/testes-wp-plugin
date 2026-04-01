<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LFS_Form_Handler {

	public function __construct() {
		add_action( 'wp_ajax_lfs_submit_form', array( $this, 'handle_form_submission' ) );
		add_action( 'wp_ajax_nopriv_lfs_submit_form', array( $this, 'handle_form_submission' ) );
	}

	public function handle_form_submission() {
		check_ajax_referer( 'lfs_submit_nonce', 'nonce' );

		$form_id = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : 0;
		if ( ! $form_id ) {
			wp_send_json_error( array( 'message' => 'ID do formulário inválido.' ) );
		}

		$webhook_url = get_post_meta( $form_id, '_lfs_webhook_url', true );
		$redirect_url = get_post_meta( $form_id, '_lfs_redirect_url', true );

		if ( empty( $webhook_url ) ) {
			wp_send_json_error( array( 'message' => 'Este formulário não possui uma URL de Webhook configurada.' ) );
		}

		// Coletar dados do POST
		$data = array(
			'timestamp'       => current_time( 'mysql' ),
			'empresa'         => sanitize_text_field( $_POST['empresa'] ),
			'tema'            => sanitize_text_field( $_POST['tema'] ?? '' ),
			'nome'            => sanitize_text_field( $_POST['nome'] ?? '' ),
			'whatsapp'        => sanitize_text_field( $_POST['whatsapp'] ?? '' ),
			'estabelecimento' => sanitize_text_field( $_POST['estabelecimento'] ?? '' ),
			'form_id'         => $form_id,
			'source_url'      => esc_url( $_SERVER['HTTP_REFERER'] )
		);

		// Configurações da requisição como POST padrão (mais estável)
		$response = wp_remote_post( $webhook_url, array(
			'method'      => 'POST',
			'timeout'     => 30,
			'redirection' => 0, // Não seguir o redirecionamento final (o Google já processou o POST)
			'httpversion' => '1.0',
			'blocking'    => true,
			'body'        => $data, 
		) );

		if ( is_wp_error( $response ) ) {
            error_log( 'LFS Connection Error: ' . $response->get_error_message() );
			wp_send_json_error( array( 'message' => 'Erro na conexão: ' . $response->get_error_message() ) );
		}

        $code = wp_remote_retrieve_response_code( $response );

        // No Google Apps Script, 302 significa que o POST foi recebido com sucesso e ele está tentando redirecionar para a página de resultado.
        if ( $code < 200 || ( $code >= 400 && $code !== 302 ) ) {
            // Se for 302 ou 200, consideramos SUCESSO. Caso contrário, erro.
            if ( $code !== 302 && $code !== 200 ) {
                error_log( 'LFS Remote Error Code: ' . $code . ' - Response: ' . wp_remote_retrieve_body( $response ) );
                wp_send_json_error( array( 'message' => 'O Google Sheets retornou erro ' . $code . '. Verifique a implantação.' ) );
            }
        }

		// Sucesso
		wp_send_json_success( array(
			'message'      => 'Enviado com sucesso para a planilha!',
			'redirect_url' => $redirect_url ? $redirect_url : ''
		) );
	}
}
