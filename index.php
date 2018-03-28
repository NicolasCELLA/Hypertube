<?php include_once('php/connexion.php'); ?>
<!DOCTYPE html>
<html lang="<?php echo $lang['html'] ?>">
<head>
	<?php include_once('meta.php'); ?>

<!-- ******* CSS ***************** -->
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/form.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">
<!-- ******* SLIDER ***************** -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<style type="text/css">
		.background_image{
			background: #292929;
		    width: 200px;
		    height: 80px;
		    text-align: center;
		    justify-content: center;
		    -webkit-justify-content: center;
		    align-items: center;
		    -webkit-align-items: center;
		    display: -webkit-flex;
		}
	</style>

</head>

<body onload="search_movie();">

<?php include_once('header.php'); ?>

<!-- ******* NAVBAR FILTER ***************** -->

<section class="navbar_filter">
	<div class="theme">
		<?php
		$req = $bdd->prepare('SELECT genre FROM genres');
		$req->execute(array());
		while ($data = $req->fetch())
		{
			?>
			<div id="<?php echo $data['genre'] ?>" onclick="setGenre('<?php echo $data['genre'] ?>')"><p><?php echo strtoupper($data['genre']) ?></p></div>
			<?php
		}
		?>
	</div>

	<form action="#" onsubmit="return false" accept-charset="utf-8" id="form_filter">
		
		<input type="text" id="years" name="years" readonly/>
	
		<div id="slider-years" class="slider"></div>

			<input type="text" id="score" name="score" readonly/>
		
		<div id="slider-score" class="slider"></div>

		<input type="submit" value="<?php echo $lang['index_filter'] ?>" class="submit_filter transition" onclick="login()" />
	</form>

</section>

<section id="movies"></section>
<!-- ******* JAVASCRIPT ***************** -->
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript">
	
	let genre = "";
	let page = 1;
	let scroll = 1;

	$(document).ready(function ()
	{
		$('#movies').bind('scroll', check_scroll);
	});

	function check_scroll(e)
	{
		var elem = $(e.currentTarget);
		if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight() && scroll == 1)
		{
			scroll = 0;

			var formData = {
				'movie'		: $('input[name=search]').val(),
				'genre'		: genre,
				'page'		: page,
				'submit'	: "search"
			};

			$.ajax({
				type		: 'POST',
				url			: 'php/search.php',
				data		: formData,
				encode		: true,
				success		: function(data){
					$('#movies').append(data);
					scroll = 1;
				}
			});

			page++;
		}
	}

	function setGenre(name)
	{
		if ($("#" + genre))
			$("#" + genre).css('background-color', '');
		if (genre == name)
		{
			genre = '';
		}
		else
		{
			genre = name;
			if ($("#" + genre))
				$("#" + genre).css('background-color', '#353535');
		}
		search_movie();
	}

	function search_movie(){
		page = 1;

		var formData = {
			'movie'		: $('input[name=search]').val(),
			'genre'		: genre,
			'page'		: page,
			'submit'	: "search"
		};

		$.ajax({
			type		: 'POST',
			url			: 'php/search.php',
			data		: formData,
			encode		: true,
			success		: function(data){
				$('#movies').html(data);
			}
		});

		page++;
	}

	// SLIDER YEARS
		$("#slider-years").slider({
			range: true,
			min: 1990,
			max: 2018,
			values: [ 1990, 2018 ],
			slide: function( event, ui ) {
				$( "#years" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			}
		});
		$( "#years" ).val($("#slider-years").slider("values", 0) + " - " + $( "#slider-years" ).slider("values", 1));
		
	// SLIDER SCORE
		$("#slider-score").slider({
			range: true,
			min: 0,
			max: 10,
			values: [0, 10],
			slide: function( event, ui ) {
				$( "#score" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			}
		});
		$( "#score" ).val($("#slider-score").slider("values", 0) + " - " + $( "#slider-score" ).slider("values", 1));
</script>
<!-- <script type="text/javascript" src="js/jquery.js"></script> -->
</body>
</html>