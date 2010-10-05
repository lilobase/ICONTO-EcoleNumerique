/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
  
  // Voir ici pour la doc :
  // http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar
  
	config.toolbar = 'Iconito';

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
        ['Bold','Italic','Underline'],
        ['NumberedList','BulletedList'],
        ['TextColor','BGColor'],
        ['RemoveFormat']
    ];
  

	config.stylesCombo_stylesSet = 'Iconito';
	//config.enterMode = CKEDITOR.ENTER_BR;
	//config.shiftEnterMode = CKEDITOR.ENTER_P;
	//config.resize_enabled = false;
	//config.height = '500px';
  //config.width = '600';
};

CKEDITOR.addStylesSet( 'Iconito',
[
    // Block Styles
    { name : 'Titre', element : 'h1', styles : { 'color' : '#01B2FF' } },
    { name : 'Sous-titre' , element : 'h2', styles : { 'color' : '#B0CB56' } },
    { name : 'Paragraphe' , element : 'p', styles : { } },
    { name : 'Machine a ecrire',element:'code'}
]);


