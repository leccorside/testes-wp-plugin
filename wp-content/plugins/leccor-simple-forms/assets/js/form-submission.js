jQuery(document).ready(function($) {
    $('.leccor-simple-form').each(function() {
        var $form = $(this);
        var $whatsappInput = $form.find('input[name="whatsapp"]')[0];
        
        // Inicializar intl-tel-input
        var iti = window.intlTelInput($whatsappInput, {
            initialCountry: "br",
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
        });

        // Mostrar/Esconder campo "Outro"
        $form.find('input[name="tema"]').on('change', function() {
            var $otherInput = $form.find('.lfs-input-other');
            if ($(this).val() === 'Outro') {
                $otherInput.show().focus().prop('required', true);
            } else {
                $otherInput.hide().prop('required', false);
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            
            var $submitBtn = $form.find('.lfs-submit-btn');
            var $loader = $form.find('.lfs-loader');
            var $message = $form.find('.lfs-message');
            
            // Somente desabilitar e mostrar loader
            $submitBtn.prop('disabled', true);
            $loader.show();
            $message.html('').removeClass('error success');

            // Pegar número completo formatado
            var phoneNumber = iti.getNumber();
            
            // Form Data
            var formData = new FormData(this);
            formData.append('action', 'lfs_submit_form');
            formData.append('nonce', lfs_ajax.nonce);
            formData.append('form_id', $form.data('id'));
            
            // Se "Outro" estiver selecionado, usar o valor do input de texto
            if (formData.get('tema') === 'Outro') {
                var outroValor = $form.find('input[name="tema_outro"]').val();
                if (outroValor) {
                    formData.set('tema', 'Outro: ' + outroValor);
                }
            }

            // Substituir WhatsApp pelo número completo
            formData.set('whatsapp', phoneNumber);

            $.ajax({
                url: lfs_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $loader.hide();
                    if (response.success) {
                        $message.addClass('success').html(response.data.message);
                        
                        // Redirecionamento
                        if (response.data.redirect_url) {
                            setTimeout(function() {
                                window.location.href = response.data.redirect_url;
                            }, 1500);
                        } else {
                            $form[0].reset();
                            $submitBtn.prop('disabled', false);
                        }
                    } else {
                        $message.addClass('error').html(response.data.message);
                        $submitBtn.prop('disabled', false);
                    }
                },
                error: function() {
                    $loader.hide();
                    $message.addClass('error').html('Ocorreu um erro ao processar sua solicitação. Tente novamente.');
                    $submitBtn.prop('disabled', false);
                }
            });
        });
    });
});
