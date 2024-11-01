/**
 * Email Attachments for WooCommerce
 * https://pluginever.com
 *
 * Copyright (c) 2024 PluginEver
 * Licensed under the GPLv2+ license.
 */
(function ($) {
	'use strict';
	$(document).ready(function () {
		var eafw_media_uploader;
		$('#eafw_email_attachments_add_files').click(function(e) {
			e.preventDefault();
			// If the uploader object has already been created, reopen the dialog.
			if (eafw_media_uploader) {
				eafw_media_uploader.open();
				return;
			}
			// Extend the wp.media object.
			eafw_media_uploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose or Upload Attachment File(s)',
				button: {
					text: 'Attach File(s)',
				},
				multiple: true,
			});
			// When a file is selected, grab the URL and set it as the text field's value.
			eafw_media_uploader.on('select', function() {
				var attachments = eafw_media_uploader.state().get('selection').toJSON();
				var attachments_url;
				$.each( attachments, function( item, attachment ){
					if ( 0 === item ) {
						attachments_url = attachment.url;
					} else {
						attachments_url +=  ',' + attachment.url;
					}
				});
				var eafw_email_attachments = $('.eafw_email_attachments');
				var attachment_files = eafw_email_attachments.val();
				if ( '' === attachment_files ) {
					eafw_email_attachments.val( attachments_url );
				} else {
					eafw_email_attachments.val( attachment_files + ',' + attachments_url );
				}
			});
			// Open the uploader dialog.
			eafw_media_uploader.open();
		});
		// Resting the attachments input field.
		$('#eafw_email_attachments_reset_files').click(function(e) {
			e.preventDefault();
			$('.eafw_email_attachments').val('');
		});
	});
}(jQuery));
