/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.allowedContent = true;

    config.forcePasteAsPlainText = false;
    config.pasteFromWordRemoveFontStyles = false;
    config.pasteFromWordRemoveStyles = false;
    config.extraAllowedContent = 'p(mso*,Normal)';
    config.pasteFilter = null;
    config.font_style =
    {
        element: 'span',
        styles: {'font-family': 'Roboto,sans-serif'},
        overrides: [{element: 'font', attributes: {'face': null}}]
    };

};
