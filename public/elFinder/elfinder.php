<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>elFinder 2.1.x source version with PHP connector</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script>
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", '/cms/check-auth', false );
            xmlHttp.send( null );

            if (xmlHttp.response !== 'true') {
                window.location.href = '/';
            }
        </script>
		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" href="css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script src="js/elfinder.min.js"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<script src="js/i18n/elfinder.ru.js"></script>

		<!-- elFinder initialization (REQUIRED) -->
        <script type="text/javascript" charset="utf-8">
            // Helper function to get parameters from the query string.
            function getUrlParam(paramName) {
                var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
                var match = window.location.search.match(reParam) ;

                return (match && match.length > 1) ? match[1] : '' ;
            }

            $(document).ready(function() {
                var funcNum = getUrlParam('CKEditorFuncNum');

                var elf = $('#elfinder').elfinder({
                    url : 'php/connector.php',
                    getFileCallback : function(file) {
                        window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
                        window.close();
                    },
                    resizable: true,
					width: window.screen.availWidth,
					height : window.screen.availHeight,
					contextmenu : {
						// navbarfolder menu
						navbar : ['open', '|', 'info'],
						// current directory menu
						cwd    : ['reload', 'back', '|', 'upload', '|', 'info'],
						// current directory file menu
						files  : [
							'getfile', '|','open', 'quicklook', '|', 'download', '|', 'edit', 'resize', '|', 'info'
						]
					},
					commands : [
						'open', 'reload', 'home', 'up', 'back', 'forward', 'getfile', 'quicklook',
						'download', 'rm', 'duplicate', 'rename', 'mkdir', 'mkfile', 'upload', 'copy',
						'cut', 'paste', 'edit', 'extract', 'archive', 'search', 'info', 'view', 'help',
						'resize', 'sort'
					]
                }).elfinder('instance');
            });
        </script>
	</head>
	<body>

		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>

	</body>
</html>
