https://www.webslesson.info/2019/11/autocomplete-textbox-with-multiple-field-using-jquery-in-php.html


<?php

if(isset($_POST['color'])){
    // echo "okk";
    // echo $_POST['color'];
    var_dump($_POST['color']);
    unset($_POST['color']);
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Jquery Suggestags - Amsify42</title>

<!-- CDN links -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="multiselectlibrary/amsify.suggestags.css">

<!-- Amsify Plugin -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script type="text/javascript" src="multiselectlibrary/jquery.amsify.suggestags.js"></script>
</head>
<body>
<header>
	<div class="container">
		<h1 class="text-center">Jquery Suggestags</h1>
	</div>
</header>
<div class="container" style="min-height:360px;">
	<div class="row"></div>
        <div class="col-md-12 text-center">
            <h4>Already Selected(Suggestions)</h4>
            <form action="" method="post">
                <div class="form-group">
                    <input id="data" type="text" class="form-control" name="color" placeholder="e.g. Java, React" value=""/>
                </div>
                <input type="submit" value="submit">
            </form>
        </div>
	</div>
</div>

<script type="text/javascript">

	$('input[name="color"]').amsifySuggestags({
		suggestions: ['Black', 'White', 'Red', 'Blue', 'Green', 'Orange'],
		//classes: ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info'],
		//showAllSuggestions: false,
        //keepLastOnHoverTag: false,
        autoTagLastText: true,
        // showPlusAfter: 2
        // tagLimit: 5
	});

    $(document).ready(function(){
        var list = $("#data").val();
        console.log(list);
    });

</script>
</body>
</html>