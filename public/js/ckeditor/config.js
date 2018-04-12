/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function( config ) {
	
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];
	
	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';
	
	// Se the most common block elements.
	config.format_tags = 'p;h1;h2';
	
	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced';
	
	config.language = 'ru';
	config.filebrowserBrowseUrl = '/elFinder/elfinder.php';
	// config.uiColor = '#AADC6E';
	//config.stylesCombo_stylesSet = 'my_styles:http://draftcms2008.web-top.com.ua/cms/ckeditor/styles.js';
	config.skin = 'moonocolor';
	config.extraPlugins = 'slideshow';
	config.extraPlugins = 'youtube';
	config.contentsCss = '/css/stylecmsfckeditor.css';
	// config.stylesSet ='default:/cms/js/ckeditor/styles.js'; не робит

    config.stylesSet = [
        /* Block Styles */
        // Block Styles
        // { name : 'Чистая таблица' , element : 'table', attributes : { 'class' : 'table_clear' } },
        { name : 'Таблица с бордером' , element : 'table', attributes : { 'class' : 'table_border' } },
        // { name : 'Таблица с нижним бордером' , element : 'table', attributes : { 'class' : 'table_border_bottom' } },

        // Inline Styles
        { name : 'Заголовок 1' , element : 'h1' },
        { name : 'Заголовок 2' , element : 'h2' },
        { name : 'Заголовок 3' , element : 'h3' },
        { name : 'Заголовок 3-1' , element : 'h3', attributes : { 'class' : 'title3_01' } },
        { name : 'Заголовок 3-2' , element : 'h3', attributes : { 'class' : 'title3_02' } },
        { name : 'Заголовок 3-3' , element : 'h3', attributes : { 'class' : 'title3_03' } },
        // Style

        { name : 'Выделенный блок' , element : 'div', attributes : { 'class' : 'important-style' } },
    ];
    
//	config.enterMode = CKEDITOR.ENTER_BR; 
	config.shiftEnterMode = CKEDITOR.ENTER_P; 
	config.templates_files = ['/cms/js/ckeditor/plugins/templates/templates/custom.js'];
	config.allowedContent = true;
	config.autoParagraph = false;
    config.templates_replaceContent = false;
};

$.each(CKEDITOR.dtd.$removeEmpty, function (i, value) {
	CKEDITOR.dtd.$removeEmpty[i] = false;
});
