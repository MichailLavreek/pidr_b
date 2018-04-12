/*
 Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
 */
CKEDITOR
		.addTemplates(
				"default",
				{
					imagesPath : CKEDITOR.getUrl(CKEDITOR.plugins
							.getPath("templates")
							+ "templates/images/"),
					templates : [
							{
								title : "Two colums",
								image : "template2.gif",
								description : "Two responsive colums.",
								html : '<div class="row"><div class="col-sm-6 col-xs-12"><h3 class="title3_02">Title 1</h3><p>Text 2</p></div><div class="col-sm-6 col-xs-12"><h3 class="title3_02">Title 2</h3><p>Text 2</p></div></div>'
							},
							{
								title : "Two colums with image",
								image : "template1.gif",
								description : "Two responsive colums with left image",
								html : '<div class="row margin-top-20 margin-bottom-20"><div class="col-sm-4"><img src="/img/design/default/image.jpg" alt="image"	class="img-responsive" /></div><div class="col-sm-8"><p>Text.... </p></div></div>'
							},
							{
								title : "Image with title",
								image : "template5.gif",
								description : "Image with title",
								html : '<div class="img-content"><div class="col-lg-9 col-md-12"><img src="/img/design/el-style-img1.jpg" alt=""/></div><div class="col-lg-3 col-md-12"><div class="important-style">TEXT</div></div></div>'
							},
						{
							title : "Responsive Table with border",
							image : "template6.gif",
							description : "Responsive Table with border",
							html : '<div class="table-wrapper-responsive"><table class="table_border"><tbody><tr><th>Table</th><th></th><th></th><th></th></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody></table></div>'
						},
						{
							title : "Responsive Table without border",
							image : "template7.gif",
							description : "Responsive Table without border",
							html : '<div class="table-wrapper-responsive"><table class="table_border_bottom"><tbody><tr><th>Table</th><th></th><th></th><th></th></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody></table></div>'
						},
						{
							title : "Code",
							image : "template5.gif",
							description : "Formated code block",
							html :
                            '<pre>' +
								'<code data-language="html">' +
									'<--' +
									'Код' +
									'-->' +
								'</code>' +
							'</pre>'
						},
					]
				});