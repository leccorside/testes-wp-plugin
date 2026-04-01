<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LFS_Form_Manager {

	public function __construct() {
		add_action( 'init', array( $this, 'register_form_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_form_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_form_meta_data' ) );
		add_shortcode( 'leccor_form', array( $this, 'render_form_shortcode' ) );

		// Colunas na listagem
		add_filter( 'manage_lfs_form_posts_columns', array( $this, 'add_shortcode_column' ) );
		add_action( 'manage_lfs_form_posts_custom_column', array( $this, 'display_shortcode_column' ), 10, 2 );
	}

	public function add_shortcode_column( $columns ) {
		$new_columns = array();
		foreach ( $columns as $key => $value ) {
			if ( $key === 'date' ) {
				$new_columns['shortcode'] = 'Shortcode';
			}
			$new_columns[ $key ] = $value;
		}
		return $new_columns;
	}

	public function display_shortcode_column( $column, $post_id ) {
		if ( $column === 'shortcode' ) {
			echo '<code style="background: #eee; padding: 3px 6px; border-radius: 3px;">[leccor_form id="' . $post_id . '"]</code>';
		}
	}

	public function register_form_post_type() {
		$args = array(
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'lfs_form' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
			'labels'             => array(
				'name'               => 'Leccor Forms',
				'singular_name'      => 'Leccor Form',
				'menu_name'          => 'Leccor Forms',
				'name_admin_bar'     => 'Leccor Form',
				'add_new'            => 'Adicionar Novo',
				'add_new_item'       => 'Adicionar Novo Formulário',
				'new_item'           => 'Novo Formulário',
				'edit_item'          => 'Editar Formulário',
				'view_item'          => 'Ver Formulário',
				'all_items'          => 'Todos os Formulários',
				'search_items'       => 'Buscar Formulários',
				'not_found'          => 'Nenhum formulário encontrado.',
				'not_found_in_trash' => 'Nenhum formulário na lixeira.',
			),
		);
		register_post_type( 'lfs_form', $args );
	}

	public function add_form_meta_boxes() {
		add_meta_box(
			'lfs_config',
			'Configurações do Formulário',
			array( $this, 'render_meta_box' ),
			'lfs_form',
			'normal',
			'high'
		);
	}

	public function render_meta_box( $post ) {
		$company  = get_post_meta( $post->ID, '_lfs_company_name', true );
		$redirect = get_post_meta( $post->ID, '_lfs_redirect_url', true );
		$webhook  = get_post_meta( $post->ID, '_lfs_webhook_url', true );

		echo '<div style="margin-bottom: 20px;">';
		echo '<label for="lfs_company_name"><b>Nome da Empresa (Hidden):</b></label><br>';
		echo '<input type="text" id="lfs_company_name" name="lfs_company_name" value="' . esc_attr( $company ) . '" class="widefat" placeholder="Ex: Nome da Empresa Ltd">';
		echo '<p class="description">Este nome será enviado de forma oculta para a planilha.</p>';
		echo '</div>';

		echo '<div style="margin-bottom: 20px;">';
		echo '<label for="lfs_redirect_url"><b>URL de Redirecionamento:</b></label><br>';
		echo '<input type="url" id="lfs_redirect_url" name="lfs_redirect_url" value="' . esc_attr( $redirect ) . '" class="widefat" placeholder="https://exemplo.com/sucesso">';
		echo '<p class="description">Página para onde o usuário será levado após o envio.</p>';
		echo '</div>';

		echo '<div style="margin-bottom: 20px;">';
		echo '<label for="lfs_webhook_url"><b>Google Sheets Webhook URL:</b></label><br>';
		echo '<input type="url" id="lfs_webhook_url" name="lfs_webhook_url" value="' . esc_url( $webhook ) . '" class="widefat" placeholder="URL gerada pelo Apps Script">';
		echo '<p class="description">Coloque aqui a URL do App da Web gerada no seu script do Google Sheets.</p>';
		echo '</div>';

		echo '<div style="background: #f0f0f1; border: 1px dashed #ccc; padding: 15px; margin-top: 20px;">';
		echo '<b>Shortcode do Formulário:</b><br>';
		echo '<code style="display: block; padding: 5px; background: #fff; border: 1px solid #ddd; margin-top: 5px;">[leccor_form id="' . $post->ID . '"]</code>';
		echo '</div>';
	}

	public function save_form_meta_data( $post_id ) {
		if ( isset( $_POST['lfs_company_name'] ) ) {
			update_post_meta( $post_id, '_lfs_company_name', sanitize_text_field( $_POST['lfs_company_name'] ) );
		}
		if ( isset( $_POST['lfs_redirect_url'] ) ) {
			update_post_meta( $post_id, '_lfs_redirect_url', esc_url_raw( $_POST['lfs_redirect_url'] ) );
		}
		if ( isset( $_POST['lfs_webhook_url'] ) ) {
			update_post_meta( $post_id, '_lfs_webhook_url', esc_url_raw( $_POST['lfs_webhook_url'] ) );
		}
	}

	public function render_form_shortcode( $atts ) {
		$atts = shortcode_atts( array( 'id' => '' ), $atts );

		if ( empty( $atts['id'] ) ) {
			return 'ID do formulário não especificado.';
		}

		$post = get_post( $atts['id'] );
		if ( ! $post || $post->post_type !== 'lfs_form' ) {
			return 'Formulário inválido.';
		}

		$company = get_post_meta( $post->ID, '_lfs_company_name', true );

		ob_start();
		?>
		<div class="lfs-form-wrapper" id="lfs-form-<?php echo $post->ID; ?>">
			<form class="leccor-simple-form" data-id="<?php echo $post->ID; ?>">
                <!-- Campo Hidden Empresa -->
                <input type="hidden" name="empresa" value="<?php echo esc_attr( $company ); ?>">

				<div class="lfs-field-group">
					<label>1. Qual tema você gostaria de ver na próxima palestra? <span class="required">*</span></label>
					<div class="lfs-radio-options">
						<label><input type="radio" name="tema" value="Como construir uma aposentadoria vitalícia em dólares" required> Como construir uma aposentadoria vitalícia em dólares</label>
						<label><input type="radio" name="tema" value="Como criar um plano inteligente para a faculdade dos seus filhos"> Como criar um plano inteligente para a faculdade dos seus filhos</label>
						<label><input type="radio" name="tema" value="Como proteger sua renda se ficar impossibilitado de trabalhar"> Como proteger sua renda se ficar impossibilitado de trabalhar</label>
						<label><input type="radio" name="tema" value="Como construir uma herança para seus filhos e proteger o patrimônio nos EUA"> Como construir uma herança para seus filhos e proteger o patrimônio nos EUA</label>
						<div class="lfs-radio-other-container">
                            <label><input type="radio" name="tema" value="Outro" class="lfs-radio-toggle-other"> Outro</label>
                            <input type="text" name="tema_outro" class="lfs-input-other" placeholder="Escreva a sugestão de tema?" style="display:none;">
                        </div>
					</div>
				</div>

				<div class="lfs-field-group">
					<label for="nome-<?php echo $post->ID; ?>">2. Qual é o seu nome? <span class="required">*</span></label>
					<input type="text" id="nome-<?php echo $post->ID; ?>" name="nome" placeholder="Seu nome completo" required class="lfs-input">
				</div>

				<div class="lfs-field-group">
					<label for="whatsapp-<?php echo $post->ID; ?>">3. Qual é o seu número de WhatsApp para ser avisado sobre o sorteio? <span class="required">*</span></label>
					<input type="tel" id="whatsapp-<?php echo $post->ID; ?>" name="whatsapp" placeholder="Seu Whatsapp" required class="lfs-input lfs-whatsapp">
				</div>

				<div class="lfs-field-group">
					<label for="estabelecimento-<?php echo $post->ID; ?>">4- Em que estabelecimento você escaneou esse QR code? <span class="required">*</span></label>
					<input type="text" id="estabelecimento-<?php echo $post->ID; ?>" name="estabelecimento" placeholder="Digite como nos conheceu" required class="lfs-input">
				</div>

				<div class="lfs-submit-container">
					<button type="submit" class="lfs-submit-btn">Enviar</button>
                    <span class="lfs-loader" style="display:none;">Enviando...</span>
				</div>

				<div class="lfs-message"></div>
			</form>
		</div>
		<?php
		return ob_get_clean();
	}
}
