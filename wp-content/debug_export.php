[26-Mar-2026 20:19:26 UTC] PHP Fatal error:  Cannot redeclare composerRequire74da323fdee1fb5e92b0c5723e907303() (previously declared in /var/www/html/wp-content/plugins/wpforms/vendor/composer/autoload_real.php:73) in /var/www/html/wp-content/plugins/leccorforms/vendor/composer/autoload_real.php on line 73
[26-Mar-2026 20:24:09 UTC] PHP Fatal error:  Trait "LeccorForm\Forms\Fields\Traits\MultiFieldMenu" not found in /var/www/html/wp-content/plugins/leccorforms/includes/fields/class-base.php on line 20
[26-Mar-2026 22:29:32 UTC] PHP Warning:  mysqli_real_connect(): php_network_getaddresses: getaddrinfo for mysql failed: Este host nÒo Ú conhecido.  in D:\0000000-PROJETOS\teste-wordpress\html\wp-includes\class-wpdb.php on line 1994
[26-Mar-2026 22:29:32 UTC] PHP Warning:  mysqli_real_connect(): (HY000/2002): php_network_getaddresses: getaddrinfo for mysql failed: Este host nÒo Ú conhecido.  in D:\0000000-PROJETOS\teste-wordpress\html\wp-includes\class-wpdb.php on line 1994
[26-Mar-2026 22:38:57 UTC] PHP Fatal error:  Uncaught TypeError: array_keys(): Argument #1 ($array) must be of type array, null given in /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier/HTMLModule/SafeScripting.php:34
Stack trace:
#0 /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier/HTMLModule/SafeScripting.php(34): array_keys(NULL)
#1 /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier/HTMLModuleManager.php(235): LeccorForm\Vendor\HTMLPurifier_HTMLModule_SafeScripting->setup(Object(LeccorForm\Vendor\HTMLPurifier_Config))
#2 /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier/HTMLDefinition.php(194): LeccorForm\Vendor\HTMLPurifier_HTMLModuleManager->setup(Object(LeccorForm\Vendor\HTMLPurifier_Config))
#3 /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier/HTMLDefinition.php(172): LeccorForm\Vendor\HTMLPurifier_HTMLDefinition->processModules(Object(LeccorForm\Vendor\HTMLPurifier_Config))
#4 /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier/Definition.php(48): LeccorForm\Vendor\HTMLPurifier_HTMLDefinition->doSetup(Object(LeccorForm\Vendor\HTMLPurifier_Config))
#5 /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier/Config.php(453): LeccorForm\Vendor\HTMLPurifier_Definition->setup(Object(LeccorForm\Vendor\HTMLPurifier_Config))
#6 /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier/Config.php(356): LeccorForm\Vendor\HTMLPurifier_Config->getDefinition('HTML', false, false)
#7 /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier/Generator.php(67): LeccorForm\Vendor\HTMLPurifier_Config->getHTMLDefinition()
#8 /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier.php(140): LeccorForm\Vendor\HTMLPurifier_Generator->__construct(Object(LeccorForm\Vendor\HTMLPurifier_Config), Object(LeccorForm\Vendor\HTMLPurifier_Context))
#9 /var/www/html/wp-content/plugins/leccorforms/includes/functions/escape-sanitize.php(577): LeccorForm\Vendor\HTMLPurifier->purify('3')
#10 /var/www/html/wp-content/plugins/leccorforms/includes/functions/escape-sanitize.php(590): {closure}('3')
#11 /var/www/html/wp-content/plugins/leccorforms/includes/functions/escape-sanitize.php(538): leccorforms_sanitize_field(Array)
#12 /var/www/html/wp-content/plugins/leccorforms/includes/admin/ajax-actions.php(40): leccorforms_sanitize_form_data(Array)
#13 /var/www/html/wp-includes/class-wp-hook.php(341): leccorforms_save_form('')
#14 /var/www/html/wp-includes/class-wp-hook.php(365): WP_Hook->apply_filters('', Array)
#15 /var/www/html/wp-includes/plugin.php(522): WP_Hook->do_action(Array)
#16 /var/www/html/wp-admin/admin-ajax.php(192): do_action('wp_ajax_leccorf...')
#17 {main}
  thrown in /var/www/html/wp-content/plugins/leccorforms/vendor_prefixed/ezyang/htmlpurifier/library/HTMLPurifier/HTMLModule/SafeScripting.php on line 34
