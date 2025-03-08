<?php
$filecode = "colinkaepernick";
$filetype = "jpg";
$line1 = "Believe in something.";
$line2 = "Even if it means sacrificing everything.";
$slogan = "Just Do It.";

$strmessage="";

if (isset($_POST["btSubmit"]))
{
	$line1 = $_POST["txtLine1"];
	$line2 = $_POST["txtLine2"];
	$slogan = $_POST["txtSlogan"];	

	if (basename($_FILES["flUpload"]["name"]) != "")
	{
	    $uploadsize = intval($_POST["hidUploadSize"]);
	    $filetype = pathinfo($_FILES["flUpload"]["name"],PATHINFO_EXTENSION);
	    $filetype = strtolower($filetype);

	    if ($_FILES["flUpload"]["size"] > $uploadsize)
	    {
	        $strmessage = "Error was encountered while uploading file. File cannot exceed " . ($uploadsize/1000) . "kb";
	    }
	    else
	    {
	    	if (!is_array(getimagesize($_FILES["flUpload"]["tmp_name"])))
	    	{
	    		$strmessage = "File type invalid";
	    	} 
	    	else 
	    	{
		        $filecode=strtotime("now").rand();
		        
		        if (move_uploaded_file($_FILES["flUpload"]["tmp_name"], "uploads/" . $filecode . "." . $filetype))
		        {
		        	$strmessage = "File uploaded.";
		        }
		        else
		        {
		            $strmessage = "Error was encountered while uploading file.";
		        }   		
	    	}
	    }
	}
	else
	{
	    $strmessage="No file selected.";
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Nike Meme Generator</title>

		<style>
			#pnlMessage
			{
				width: 100%;
				height: 50px;
				color: #FF0000;
				outline: 0px solid #DDDDDD;
			}

			#formContainer
			{
				width: 400px;
				padding: 5px;
				margin: 5px;
				float: left;
				outline: 0px solid #DDDDDD;
			}

			#memeContainer
			{
				width: 500px;
				height: 500px;
				padding: 5px;
				margin: 5px;
				float: left;
				outline: 1px solid #DDDDDD;
				background: url(<?php echo "uploads/" . $filecode . "." . $filetype; ?>) center center no-repeat;
				background-size: cover;
				font-family: georgia;
				color: #FFFFFF;
				font-size: 25px;
			    -webkit-filter: grayscale(100%);
			    filter: grayscale(100%);
			    text-align: center;
			}

			@media print 
			{
			    #formContainer, #pnlMessage
			    {
			    	display: none;
			    }

			    #memeContainer
				{
					margin: 10% auto 0 auto;
					float: none;
				}
			}
		</style>
	</head>

	<body>
        <div id="pnlMessage"><?php echo $strmessage; ?></div>

        <div id="formContainer">       	
	        <form id="frmUpload" name="frmUpload" action="" method="POST" enctype="multipart/form-data">
	            <label for="flUpload">File</label>
	            <input type="file" name="flUpload" id="flUpload">
	            <input type="hidden" name="hidUploadSize" id="hidUploadSize" value="50000000">
	            <br /><br />
	           	<label for="txtLine1">Line 1</label>
	            <input name="txtLine1" id="txtLine1" maxlength="50" value="<?php echo $line1; ?>" />
	            <br /><br />
	           	<label for="txtLine2">Line 2</label>
	            <input name="txtLine2" id="txtLine2" maxlength="50" value="<?php echo $line2; ?>" />
	            <br /><br />
	           	<label for="txtSlogan">Slogan</label>
	            <input name="txtSlogan" id="txtSlogan" maxlength="20" value="<?php echo $slogan; ?>" />
	            <br /><br />
	            <input type="submit" name="btSubmit" id="btSubmit" value="Create your Meme!">
			</form>
        </div>

        <div id="memeContainer">
        	<p style="margin-top:50%"><?php echo $line1;?><br /><?php echo $line2;?></p>
        	<p style="margin-top:30%"><img src="nikelogo.png"> <?php echo $slogan;?></p>
        </div>
	</body>
</html>