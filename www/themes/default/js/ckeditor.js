/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

        config.toolbar = 'Iconito';

	config.toolbar_full =
	[
	    ['Source','-','Save','NewPage','Preview','-','Templates'],
	    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
	    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	    ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
	    ['BidiLtr', 'BidiRtl'],
	    '/',
	    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
	    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['Link','Unlink','Anchor'],
	    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
	    '/',
	    ['Styles','Format','Font','FontSize'],
	    ['TextColor','BGColor'],
	    ['Maximize', 'ShowBlocks','-','About']
	];

            config.toolbar_Iconito =
        [
            ['Styles'],
            ['Bold','Italic','Underline'],
            ['NumberedList','BulletedList'],
            ['TextColor','BGColor'],
            ['Link','Unlink'],
            ['RemoveFormat'],
            '/',
            ['Undo','Redo'],
            ['Image','Table','HorizontalRule'],
            ['Cut','Copy','Paste','PasteText','PasteFromWord','-'],
            ['Source','Preview'],
            ['Maximize','-','About']
        ];

      config.toolbar_IconitoBasic =
        [
	    ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','PasteText', 'PasteFromWord', 'RemoveFormat'],
	    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],['Format','FontSize']
        ];

          config.toolbar_IconitoBlog =
        [
            ['Styles'],
            ['Bold','Italic','Underline'],
            ['NumberedList','BulletedList'],
            ['TextColor','BGColor'],
            ['Link','Unlink'],
            ['RemoveFormat'],
            '/',
            ['Undo','Redo'],
            ['Image','Table','HorizontalRule'],
            ['Cut','Copy','Paste','PasteText','PasteFromWord','-'],
            ['Source','Preview'],
            ['Maximize','-','About']
        ];
};


CKEDITOR.addStylesSet( 'Iconito',
[
    // Block Styles
    { name : 'Titre', element : 'h1', styles : { 'color' : '#01B2FF' } },
    { name : 'Sous-titre' , element : 'h2', styles : { 'color' : '#B0CB56' } },
    { name : 'Paragraphe' , element : 'p', styles : { } },
    { name : 'Machine a ecrire',element:'code'}
]);


