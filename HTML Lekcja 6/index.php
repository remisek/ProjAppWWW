<html>
	<head>
		<title>Computer Vision Notes</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<script>
			$(document).ready(function(){
				$(".flip1").click(function(){
					$(".panel1").slideDown("slow");
				});
			});
			$(document).ready(function(){
				$(".flip2").click(function(){
					$(".panel2").slideDown("slow");
				});
			});
			$(document).ready(function(){
				$(".flip3").click(function(){
					$(".panel3").slideDown("slow");
				});
			});
		</script>
</head>
	</head>
	<body>
		<nav>
			<ul>
				<li><a href="index.html">Main page</a></li>
			</ul>
			<h3 class="flip1">Lab 1</h3>
			<ul class="panel1">
				<li><a href="html/what-is-computer-vision.html">1. What is Computer Vision</a></li>
				<li><a href="html/basics-of-computer-vision-convolutional-neural-networks.html">2. Basics of Computer Vision - Convolutional Neural Networks (CNNs)</a></li>
				<li><a href="html/history-of-computer-vision-architectures-reviwing-the-architectures.html">3. History of Computer Vision architectures - reviewing the architectures</a></li>
				<li><a href="html/generative-adversarial-networks-gans.html">4. Generative Adversarial Networks (GANs)</a></li>
				<li><a href="html/implementation.html">5. Implementation</a></li>
				<li><a href="html/contact.html">Contact</a></li>
			</ul>
			<h3 class="flip2">Lab 2</h3>
			<ul class="panel2">
				<li><a href="html/javascript-exercise.html">JS Exercise</a></li>
				<li><a href="html/timedatejs.html">Timedate</a></li>
			</ul>
			<h3 class="flip3">Lab 3</h3>
			<ul class="panel3">
				<li><a href="html/first_jquery.html">First jQuery</a></li>
				<li><a href="html/second_jquery.html">Second jQuery</a></li>
				<li><a href="html/third_jquery.html">Third jQuery</a></li>
			</ul>				
		</nav>
		<h1>Computer Vision Notes</h1>
		<h2>Table of contents</h2>
		<ul>
			<li>1. What is Computer Vision</li>
			<li>2. Basics of Computer Vision - Convolutional Neural Networks (CNNs)</li>
			<li>3. History of Computer Vision architectures - reviewing the architectures</li>
			<li>4. Generative Adversarial Networks (GANs)</li>
			<li>5. Implementation</li>
		</ul>

		<footer>
			<p>This page is intended for HTML exercises. The knowledge was developed by Dawid Koterwas and Remigiusz Sęk, Computer Science students and members of the Student Artificial Intelligence Club "Czarna Magia" at UWM Olsztyn.</p>
			<p>Remigiusz Sęk, 02.10.2024, 14:00</p>
		</footer>
		<?
			$nr_indeksu = ‘1234567’;
			$nrGrupy = ‘X’;
			echo ‘Autor: Jan Kowalski ‘.$nr_indeksu.’ grupa ‘.$nrGrupy.’ <br /><br />’;
		?>
	</body>
</html>