<?php
$filecode = "angelinajolie";
$filetype = "jpg";

$space_from_top = "20";

$movie_title = "My Pefect Woman";
$movie_title_color = "#FFFFFF";
$movie_title_size = "30";

$movie_tagline = "You just can't beat this";
$movie_tagline_color = "#FFFFFF";
$movie_tagline_size = "15";

$movie_starring = "Angelina Jolie";
$movie_starring_color = "#FFFFFF";
$movie_starring_size = "10";

$reviews = [];
$reviews_color = "#FFFFFF";
$reviews_bgcolor = "#000000";

$strmessage="";

if (isset($_POST["btSubmit"]))
{
	$movie_title = $_POST["txtMovie_title"];
	$movie_tagline = $_POST["txtMovie_tagline"];
	$movie_starring = $_POST["txtMovie_starring"];	

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

	$space_from_top = $_POST["txtSpace_from_top"];

	$movie_title = $_POST["txtMovie_title"];
	$movie_title_color = $_POST["txtMovie_title_color"];
	$movie_title_size = $_POST["txtMovie_title_size"];

	$movie_tagline = $_POST["txtMovie_tagline"];
	$movie_tagline_color = $_POST["txtMovie_tagline_color"];
	$movie_tagline_size = $_POST["txtMovie_tagline_size"];

	$movie_starring = $_POST["txtMovie_starring"];
	$movie_starring_color = $_POST["txtMovie_starring_color"];
	$movie_starring_size = $_POST["txtMovie_starring_size"];

	$reviews = [];
	$reviews_color = $_POST["txtReviews_color"];
	$reviews_bgcolor = $_POST["txtReviews_bgcolor"];

	//connect to ChatGPT in any case
	$key = "xxx";
	$org = "xxx";
	$url = 'https://api.openai.com/v1/chat/completions';  

	$headers = [
	    "Authorization: Bearer " . $key,
	    "OpenAI-Organization: " . $org, 
	    "Content-Type: application/json"
	];

	$messages = [];
	$obj = [];
	$obj["role"] = "user";
	$obj["content"] = "Give me a JSON object with one property. The property should be named 'FakeReviews', and should be an array of ten objects. Each object should have the property 'review', which is a random fictional complimentary about the movie '" . $movie_title . "'" . ($movie_starring == "" ? "" : " or celebrity '" . $movie_starring . "'") .  " (range between three to ten words) sentence in a string, and the property 'critic' which contains the fictional publication for that quote.";
	$messages[] = $obj;
		
	$data = [];
	$data["model"] = "gpt-3.5-turbo";
	$data["messages"] = $messages;
	$data["max_tokens"] = 1000;

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$result = curl_exec($curl);
	if (curl_errno($curl)) {
	    echo 'Error:' . curl_error($curl);
	} 

	curl_close($curl);	

	$result = json_decode($result);
	$content = $result->choices[0]->message->content;
	$content = json_decode($content);

	$reviews = $content->FakeReviews;	
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Fake Reviews Generator</title>

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

			label
			{
				display: inline-block;
				font-family: arial;
				font-size: 12px;
				width: 10em;
			}

			#posterContainer
			{
				width: 800px;
				height: 500px;
				margin: 5px;
				float: left;
			    text-align: center;
			}


			#left, #right
			{
				width: 180px;
			}

			#middle
			{
				width: 380px;
				background: url(<?php echo "uploads/" . $filecode . "." . $filetype; ?>) center center no-repeat;
				background-size: cover;
			}

			#left
			{
				text-align: right;
			}

			#right
			{
				text-align: left;
			}

			.overlay
			{
				height: 480px;
				float: left;
				padding: 10px;
			}

			@media print 
			{
			    #formContainer, #pnlMessage
			    {
			    	display: none;
			    }

			    #posterContainer
				{
					margin: 10% auto 0 auto;
					float: none;
				}
			}
		</style>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>
			function useVariables() 
			{
				$("#title_and_tagline").attr("style", "margin-top:" + $("#txtSpace_from_top").val() + "px");
				$("#left, #right").attr("style", "color:" + $("#txtReviews_color").val() + ";background-color: " + $("#txtReviews_bgcolor").val());
			}
		</script>
	</head>

	<body>
        <div id="pnlMessage"><?php echo $strmessage; ?></div>

        <div id="formContainer">       	
	        <form id="frmUpload" name="frmUpload" action="" method="POST" enctype="multipart/form-data">
	            <label for="flUpload">File</label>
	            <input type="file" name="flUpload" id="flUpload">
	            <input type="hidden" name="hidUploadSize" id="hidUploadSize" value="50000000">
	            <br /><br />
	            <label for="txtSpace_from_top">Space From Top</label>
	            <input type="range" min="10" max="250" name="txtSpace_from_top" id="txtSpace_from_top" value="<?php echo $space_from_top; ?>" oninput="useVariables()" />
	            <br /><br />
	           	<label for="txtMovie_title">Movie Title</label>
	            <input name="txtMovie_title" id="txtMovie_title" maxlength="50" value="<?php echo $movie_title; ?>" oninput="useVariables()" />
	            <br />
	            <label for="txtMovie_title_color">Movie Title Color</label>
	            <input type="color" name="txtMovie_title_color" id="txtMovie_title_color" value="<?php echo $movie_title_color; ?>" oninput="useVariables()" />
	            <br />
	            <label for="txtMovie_title_size">Movie Title Size</label>
	            <input type="range" min="10" max="50" name="txtMovie_title_size" id="txtMovie_title_size" value="<?php echo $movie_title_size; ?>" oninput="useVariables()" />
	            <br /><br />
	           	<label for="txtMovie_tagline">Movie Tagline</label>
	            <input name="txtMovie_tagline" id="txtMovie_tagline" maxlength="50" value="<?php echo $movie_tagline; ?>" oninput="useVariables()" />
	            <br />
	            <label for="txtMovie_tagline_color">Movie Tagline Color</label>
	            <input type="color" name="txtMovie_tagline_color" id="txtMovie_tagline_color" value="<?php echo $movie_tagline_color; ?>" oninput="useVariables()" />
	            <br />
	            <label for="txtMovie_tagline_size">Movie Tagline Size</label>
	            <input type="range" min="10" max="50" name="txtMovie_tagline_size" id="txtMovie_tagline_size" value="<?php echo $movie_tagline_size; ?>" oninput="useVariables()" />
	            <br /><br />
	           	<label for="txtMovie_starring">Starring</label>
	            <input name="txtMovie_starring" id="txtMovie_starring" maxlength="20" value="<?php echo $movie_starring; ?>" oninput="useVariables()" />
	            <br />
	            <label for="txtMovie_starring_color">Starring Color</label>
	            <input type="color" name="txtMovie_starring_color" id="txtMovie_starring_color" value="<?php echo $movie_tagline_color; ?>" oninput="useVariables()" />
	            <br />
	            <label for="txtMovie_starring_size">Starring Size</label>
	            <input type="range" min="10" max="50" name="txtMovie_starring_size" id="txtMovie_starring_size" value="<?php echo $movie_starring_size; ?>" oninput="useVariables()" />
	            <br /><br />
	            <label for="txtReviews_color">Reviews Color</label>
	            <input type="color" name="txtReviews_color" id="txtReviews_color" value="<?php echo $reviews_color; ?>" oninput="useVariables()" />
	            <br />
	            <label for="txtReviews_bgcolor">Reviews Background Color</label>
	           	<input type="color" name="txtReviews_bgcolor" id="txtReviews_bgcolor" value="<?php echo $reviews_bgcolor; ?>" oninput="useVariables()" />
	            <br /><br />
	            <input type="submit" name="btSubmit" id="btSubmit" value="Create your Fake Movie Poster!">
			</form>
        </div>

        <div id="posterContainer">
        	<div id="left" class="overlay" style="color:<?php echo $reviews_color;?>;background-color:<?php echo $reviews_bgcolor;?>">
        		<?php
        		if (count($reviews) == 10) {
        			for ($i = 0; $i < 5; $i++) 
        			{
        		?>
						<span style="font-size:<?php echo (1.5 - (strlen($reviews[$i]->review) / 10)); ?>em"><b>&ldquo;<?php echo htmlspecialchars($reviews[$i]->review); ?>&rdquo;</b>
						</span>
						<br />
						<small>
							<i><?php echo $reviews[$i]->critic; ?></i>
							&nbsp;&#9733;&#9733;&#9733;<?php for ($j = 0; $j <= 1; $j++)
							{
								if (rand(1, 2) == 1) echo "&#9733;";
							}
							?>										
						</small>
						<br />
						<br />
				<?php
        			}
        		}
        		?>
        	</div>
        	<div id="middle" class="overlay">
        		<p id="title_and_tagline" style="margin-top:<?php echo $space_from_top;?>px">
        			<span style="color:<?php echo $movie_title_color;?>;font-size:<?php echo $movie_title_size;?>px"><?php echo $movie_title;?></span>
        			<br />
        			<span style="color:<?php echo $movie_tagline_color;?>;font-size:<?php echo $movie_tagline_size;?>px"><?php echo $movie_tagline;?></span>
        		</p>
        		<p id="starring" style="color:<?php echo $movie_starring_color;?>;font-size:<?php echo $movie_starring_size;?>px">
        			<?php echo ($movie_starring == "" ? "" : "starring ");?>
        			<?php echo $movie_starring;?></span>
        		</p>
        	</div>
        	<div id="right" class="overlay" style="color:<?php echo $reviews_color;?>;background-color:<?php echo $reviews_bgcolor;?>">
        		<?php
        		if (count($reviews) == 10) {
        			for ($i = 5; $i < 10; $i++) 
        			{
        		?>
						<span style="font-size:<?php echo (2 - (strlen($reviews[$i]->review) / 10)); ?>em"><b>&ldquo;<?php echo htmlspecialchars($reviews[$i]->review); ?>&rdquo;</b>
						</span>
						<br />
						<small>
							<i><?php echo $reviews[$i]->critic; ?></i>
							&nbsp;&#9733;&#9733;&#9733;<?php for ($j = 0; $j <= 1; $j++)
							{
								if (rand(1, 2) == 1) echo "&#9733;";
							}
							?>										
						</small>
						<br />
						<br />
				<?php
        			}
        		}
        		?>
        	</div>
        </div>
	</body>
</html>