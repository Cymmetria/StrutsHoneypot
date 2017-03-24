<?php 

$NEVER_USED_NAME_CHARS_REGEX = "/(%{|#)/";
$CONTENT_TYPE_DISPOSITION_INDICATOR_REGEX = "/multipart\/form-data;\s+?boundary=(.+?)$/";
$CONTENT_DISPOSITION_PARSING_REGEX_END = "((?!(\r\n\r\n|\n\n)).)*?(\r\n|\n){0,1}Content-Disposition: (.*?)filename *?=(.*?)(\n|\n\n|\r\n|\r\n\r\n)/si";

$hdrs = apache_request_headers();

$ct = ""; $ua = ""; $con_disp = "";
$match = 0; $raw_post_data = "";

//for debugging purposes: print("<!doctype html><html><body><pre>\n");
//check case insensitive headers.
foreach ($hdrs as $name => $value) {
	if ('content-type' == strtolower($name)) {
		$ct = $hdrs[$name];
		$boundary = "";

		if (preg_match($NEVER_USED_NAME_CHARS_REGEX, $ct)) {
			$match = 1;
		}
		elseif (preg_match($CONTENT_TYPE_DISPOSITION_INDICATOR_REGEX, $ct, $matches)) {
			$boundary = $matches[1];

			$post_data = file_get_contents("php://input");

			//search for the content disposition header,and look for the filename= field.
			// if it exists, check the validity of the name.
			if (preg_match_all("/" . $boundary . $CONTENT_DISPOSITION_PARSING_REGEX_END, $post_data, $matches)) {
				foreach ($matches[5] as $cd) {
					if (preg_match($NEVER_USED_NAME_CHARS_REGEX, $cd)) {
						$match = 1;
						$con_disp = $cd;
					}
				}
			}
		}

	}
	elseif ('user-agent' == strtolower($name)) {
		$ua = $hdrs[$name];
	}
}

if ($match == 1) {
	//create log JSON
	$marr = [ "src" => $_SERVER['REMOTE_ADDR'] , "sport" => $_SERVER['REMOTE_PORT'], "dst" => $_SERVER['SERVER_NAME'], "dport" => $_SERVER['SERVER_PORT'],
		"uri" => $_SERVER['REQUEST_URI'], "method" => $_SERVER['REQUEST_METHOD'], "ua" => $ua, "ctype" => $ct, "cdisposition" => $con_disp];

	//encode as JSON
	$msg = json_encode($marr);
	//send to apache/php default error log
	error_log($msg);
}
//for debugging purposes: print("</pre></body></html>\n");
//exit(0);
?>

<!doctype html>
<html>
<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="cover.css" rel="stylesheet">
</head>
<body>
    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand">Uploader</h3>
              <nav>
                <ul class="nav masthead-nav">
                  <li class="active"><a href="#">Home</a></li>
                  <li><a href="#">Gallery</a></li>
                  <li><a href="#">Videos</a></li>
                </ul>
              </nav>
            </div>
          </div>

          <div class="inner cover">
            <h1 class="cover-heading">Upload video to storage</h1>
                <p class="lead">
		    Select image to upload:
		  <form action="upload.php" method="post" enctype="multipart/form-data">
		 	<span class="btn btn-file btn-default btn-lg">
			    <input type="file" name="fileToUpload" id="fileToUpload">
		        </span><br/><br/>
		    <input type="submit" class="btn btn-lg btn-default" value="Upload Image" name="submit">
		  </form>
            	</p>
          </div>

          <div class="mastfoot">
            <div class="inner">
		<p>
		Powered by <img src="apache.png" style="height: 3em;"/><br/>
		Powered by <img src="struts.svg" style="height: 3em;"/>
		</p>
            </div>
          </div>

        </div>

      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>
</html>
