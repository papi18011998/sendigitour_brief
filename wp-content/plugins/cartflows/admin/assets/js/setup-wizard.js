( function ( $ ) {
	CartFlowsWizard = {
		init: function () {
			this._bind();
		},

		/**
		 * Bind
		 */
		_bind: function () {
			$( document ).on(
				'click',
				'.wcf-install-plugins',
				CartFlowsWizard._installNow
			);
			//$( document ).on(
			//	'click',
			//	'.wcf-usage-tracking',
			//	CartFlowsWizard._usage_tracking
			//);
			$( document ).on(
				'click',
				'.wcf-install-wc',
				CartFlowsWizard._installWc
			);
			$( document ).on(
				'wp-plugin-installing',
				CartFlowsWizard._pluginInstalling
			);
			$( document ).on(
				'wp-plugin-install-error',
				CartFlowsWizard._installError
			);
			$( document ).on(
				'wp-plugin-install-success',
				CartFlowsWizard._installSuccess
			);
			$( document ).on(
				'click',
				'.sendinblue-form-submit',
				CartFlowsWizard._onSendinblueSubmit
			);
			$( document ).on(
				'change',
				'.page-builder-list',
				CartFlowsWizard._onChangePagebuilder
			);
		},

		_onChangePagebuilder: function ( event ) {
			var page_builder = $( this ).val(),
				plugin_slug =
					$( '.page-builder-list option:selected' ).data( 'slug' ) ||
					'',
				new_url = 'https://wordpress.org/plugins/' + plugin_slug;

			$( '.cartflows-setup-extra-notice' ).show();
			if ( 'other' === plugin_slug || 'divi' === plugin_slug ) {
				$( '.cartflows-setup-extra-notice' ).hide();
				return;
			}

			plugin_slug = plugin_slug.replace( /-/gi, ' ' );
			$( '#wcf-page-builder' ).attr( 'href', new_url );
			$( '#wcf-page-builder' ).html( plugin_slug );
		},

		_usage_tracking: function ( event ) {
			var allow_usage_tracking = document.getElementById(
				'cartflows-usage-tracking-option'
			);

			if ( allow_usage_tracking && allow_usage_tracking.checked ) {
				allow_usage_tracking = true;
			} else {
				allow_usage_tracking = false;
			}

			$.ajax( {
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'usage_tracking_option',
					allow_usage_tracking: allow_usage_tracking,
					security:
						cartflows_setup_vars.wcf_usage_tracking_option_nonce,
				},
			} )
				.done( function ( response ) {
					if ( response.success ) {
						console.log( 'Option Updated.' );
					}
				} )
				.fail( function () {
					console.log( 'error' );
				} )
				.always( function () {
					console.log( 'complete' );
				} );
		},

		_onSendinblueSubmit: function ( event ) {
			event.preventDefault();
			event.stopPropagation();

			var form 			= $( this ).closest( 'form' ),
				name_field 		= form.find( '#cartflows_onboarding_name').val() ? form.find( '#cartflows_onboarding_name').val() : '',
				email_field 	= form.find( '#cartflows_onboarding_email'),
				submit_button 	= $( this ),
				nonce 			= form.find('#wcf_user_onboarding_nonce').val(),
				error_field		= form.find('.onboarding-error'),
				success_field	= form.find('.onboarding-message'),
				reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,14})$/;

			if ( reg.test( email_field.val() ) == false ) {
				email_field.addClass( 'wcf-error' );
				return false;
			} else {
				email_field.removeClass( 'wcf-error' );
			}

			submit_button.attr( 'disabled', 'disabled' );
			
			$.ajax( {
				type: 'POST',
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'wcf_user_onboarding',
					user_email: email_field.val(),
					user_fname : name_field,
					security: nonce,
				},
				// async: false,
				success: function (response) {
					console.log( 'in success' );

					if( response.data.success ){
						console.log( response.data.message );
						var redirect_link =	$( '.wcf-redirect-link' ).data( 'redirect-link' ) || '';
						window.location = redirect_link;
					}else{
						console.log( response.data.message );
						error_field.text( response.data.message );
						submit_button.removeAttr( 'disabled' );
						return false;
					}
					
				},
				error: function (response) {
					console.log( 'in error' );
					console.log( response );
				},
			} );

			/* Do not execute anything here */
		},

		/**
		 * Installing Plugin
		 */
		_pluginInstalling: function ( event, args ) {
			event.preventDefault();
			console.log( 'Installing..' );
		},

		/**
		 * Install Error
		 */
		_installError: function ( event, args ) {
			event.preventDefault();
			console.log( 'Install Error!' );

			var redirect_link =
				$( '.wcf-redirect-link' ).data( 'redirect-link' ) || '';
			console.log( redirect_link );
			if ( '' !== redirect_link ) {
				window.location = redirect_link;
				console.log( 'redirecting..' );
			}
		},

		/**
		 * Install Success
		 */
		_installSuccess: function ( event, args ) {
			event.preventDefault();

			var plugin_init = args.slug + '/' + args.slug + '.php';
			var plugin_slug = args.slug;

			console.log( plugin_slug );

			if ( 'woocommerce' === plugin_slug ) {
				CartFlowsWizard._activateWc();
				return;
			}

			CartFlowsWizard._activatePlugin( plugin_init, plugin_slug );
		},

		_activatePlugin: function ( plugin_init, plugin_slug ) {
			var redirect_link =
				$( '.wcf-redirect-link' ).data( 'redirect-link' ) || '';
			var save_builder_option =
				'1' == $( '#save-pb-option' ).val() || false;

			$.ajax( {
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'page_builder_step_save',
					page_builder: plugin_slug,
					plugin_init: plugin_init,
					save_builder_option: save_builder_option,
					security:
						cartflows_setup_vars.wcf_page_builder_step_save_nonce,
				},
			} )
				.done( function ( response ) {
					if ( response.success ) {
						if ( '' !== redirect_link ) {
							window.location = redirect_link;
						}
					}
				} )
				.fail( function () {
					console.log( 'error' );
				} )
				.always( function () {
					console.log( 'complete' );
				} );
		},

		/**
		 * Install Now
		 */
		_installNow: function ( event ) {
			event.preventDefault();

			var $button = $( this ),
				$document = $( document ),
				plugin_slug =
					$( '.page-builder-list option:selected' ).data( 'slug' ) ||
					'',
				install =
					$( '.page-builder-list option:selected' ).data(
						'install'
					) || 'no',
				plugin_init =
					$( '.page-builder-list option:selected' ).data( 'init' ) ||
					'',
				redirect_link =
					$( '.wcf-redirect-link' ).data( 'redirect-link' ) || '';

			if (
				$button.hasClass( 'updating-message' ) ||
				$button.hasClass( 'button-disabled' )
			) {
				return;
			}

			$button.addClass( 'updating-message' );

			if ( 'yes' === install ) {
				CartFlowsWizard._activatePlugin( plugin_init, plugin_slug );
			} else if ( 'no' === install ) {
				console.log( 'plugin_slug ' + plugin_slug );

				CartFlowsWizard._installPlugin( plugin_slug );
			} else {
				$.ajax( {
					url: ajaxurl,
					method: 'POST',
					data: {
						action: 'page_builder_save_option',
						page_builder: plugin_slug,
					},
				} )
					.done( function ( data ) {
						if ( data.success ) {
							if ( '' !== redirect_link ) {
								window.location = redirect_link;
							}
						}
					} )
					.fail( function () {
						console.log( 'error' );
					} )
					.always( function () {
						console.log( 'complete' );
					} );
			}
		},

		_installWc: function ( event ) {
			event.preventDefault();

			var $button = $( this );

			if (
				$button.hasClass( 'updating-message' ) ||
				$button.hasClass( 'button-disabled' )
			) {
				return;
			}

			$button.addClass( 'updating-message' );
			var redirect_link =
				$( '.wcf-redirect-link' ).data( 'redirect-link' ) || '';

			if ( $( '.wcf-install-wc-input' ).prop( 'checked' ) == true ) {
				var woo_installed = $( '.wcf-install-wc-input' ).data(
					'woo-install'
				);
				var wcf_ca_installed = $( '.wcf-install-wc-input' ).data(
					'wcf-ca-install'
				);

				if ( 'yes' === woo_installed && 'yes' === wcf_ca_installed ) {
					CartFlowsWizard._activateWc();
				}

				if ( 'no' == woo_installed ) {
					CartFlowsWizard._installPlugin( 'woocommerce' );
				}

				if ( 'no' == wcf_ca_installed ) {
					CartFlowsWizard._installPlugin(
						'woo-cart-abandonment-recovery'
					);
				}
			} else {
				window.location = redirect_link;
			}
		},

		_activateWc: function () {
			var redirect_link =
				$( '.wcf-redirect-link' ).data( 'redirect-link' ) || '';

			$.ajax( {
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'wcf_activate_wc_plugins',
					security:
						cartflows_setup_vars.wcf_wc_plugins_activate_nonce,
				},
			} )
				.done( function ( response ) {
					if ( response.success && '' !== redirect_link ) {
						window.location = redirect_link;
					}
				} )
				.fail( function () {
					console.log( 'error' );
				} )
				.always( function () {
					console.log( 'complete' );
				} );
		},

		_installPlugin: function ( plugin_slug ) {
			if (
				wp.updates.shouldRequestFilesystemCredentials &&
				! wp.updates.ajaxLocked
			) {
				wp.updates.requestFilesystemCredentials( event );

				$document.on( 'credential-modal-cancel', function () {
					var $message = $( '.install-now.updating-message' );

					$message
						.removeClass( 'updating-message' )
						.text( wp.updates.l10n.installNow );

					wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
				} );
			}

			wp.updates.installPlugin( {
				slug: plugin_slug,
			} );
		},
	};

	$( function () {
		CartFlowsWizard.init();
	} );
} )( jQuery );
