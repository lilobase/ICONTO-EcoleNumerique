/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.toolbar = 'Iconito';

    config.toolbar_Iconito =
    [
        ['Source','Preview'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-'],
        ['Undo','Redo','-','Find','Replace','-','RemoveFormat'],
        '/',
        ['Styles'],
        ['Bold','Italic','Strike'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['Link','Unlink','Anchor'],
        ['Maximize','-','About']
    ];

};
