/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	
	// %REMOVE_START%
	// The configuration options below are needed when running CKEditor from source files.
	config.plugins = 'dialogui,dialog,a11yhelp,dialogadvtab,basicstyles,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,colorbutton,colordialog,templates,menu,contextmenu,div,resize,toolbar,elementspath,enterkey,entities,popup,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,forms,format,horizontalrule,htmlwriter,iframe,wysiwygarea,image,indent,indentblock,indentlist,smiley,justify,menubutton,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,scayt,stylescombo,tab,table,tabletools,undo,wsc,about,tableresize';
	config.skin = 'office2013';
	config.width = 650;
	config.toolbar = "Tu";
	config.toolbar_Basic =
	[
		{ name: 'document', items : [ 'Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','-','Undo','Redo' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
		'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-' ] },
		{ name: 'editing', items : [ 'Find','Replace'] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','Strike','Subscript','Superscript' ] },
		{ name: 'insert', items : [ 'Table','-','Image','Flash','Smiley','-','SpecialChar','PageBreak' ] },
		{ name: 'links', items : [ 'Link','Unlink' ] },
		{ name: 'forms', items : [ 'TextField' ] },
		{ name: 'tools', items : [ 'ShowBlocks','-','About' ] },
		'/',
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] }
	];
	config.removeButtons = 'SpellChecker,Scayt,Checkbox,Radio,Anchor,Button,ImageButton,CreateDiv,Iframe';
	
	 config.title = ' ';
	 
	config.toolbar_Kasie =
	[
		{ name: 'document', items : [ 'Source','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','-','Undo','Redo' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
		'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-' ] },
		{ name: 'editing', items : [ 'Find','Replace'] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','Strike','Subscript','Superscript' ] },
		{ name: 'insert', items : [ 'Table','-','Image','Flash','Smiley','-','SpecialChar','PageBreak' ] },
		{ name: 'links', items : [ 'Link','Unlink' ] },
		{ name: 'forms', items : [ 'TextField' ] },
		{ name: 'tools', items : [ 'ShowBlocks','-','About' ] },
		'/',
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] }
	];
	 
	config.toolbar_Tu =
	[
		['Smiley', 'Table' , 'Print', '-', 'Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Font','FontSize','Styles','-','About']
	];
	 
	config.toolbar_Rekap =
	[
		['Smiley', 'Image', 'Table' , '-', 'Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Font','FontSize','Styles','-','About']
	];
	 
	config.toolbar_Full =
	[
		['Smiley', 'Image', 'Table', '-', 'Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', '-','Font','FontSize','Styles','-','About']
	];
	// %REMOVE_END%

	// Define changes to default configuration here. For example:
	config.language = 'id';
	// config.uiColor = '#AADC6E';
};
