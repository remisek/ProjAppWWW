<!DOCTYPE html>
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
                $(".flip2").click(function(){
                    $(".panel2").slideDown("slow");
                });
                $(".flip3").click(function(){
                    $(".panel3").slideDown("slow");
                });
            });
        </script>
    </head>
    <body>
		<?php
			echo 'Wersja v1.7';
			include('admin/cfg.php');

			error_reporting(E_ALL);
			ini_set('display_errors', 1);

			$strona = 'html/glowna.html';

			$validPages = [
				'' => 'html/glowna.html',
				'basics-of-computer-vision-convolutional-neural-networks' => 'html/basics-of-computer-vision-convolutional-neural-networks.html',
				'contact' => 'html/contact.html',
				'first_jquery' => 'html/first_jquery.html',
				'generative-adversarial-networks-gans' => 'html/generative-adversarial-networks-gans.html',
				'history-of-computer-vision-architectures-reviwing-the-architectures' => 'html/history-of-computer-vision-architectures-reviwing-the-architectures.html',
				'implementation' => 'html/implementation.html',
				'javascript-exercise' => 'html/javascript-exercise.html',
				'second_jquery' => 'html/second_jquery.html',
				'third_jquery' => 'html/third_jquery.html',
				'timedatejs' => 'html/timedatejs.html',
				'what-is-computer-vision' => 'html/what-is-computer-vision.html',
				'filmy' => 'html/filmy.html',
			];

			$idp = $_GET['idp'] ?? '';

			if (array_key_exists($idp, $validPages)) {
				$strona = $validPages[$idp];
			}

			echo "Resolved path: " . htmlspecialchars($strona) . "<br>";

			if (file_exists($strona)) {
				include($strona);
			} else {
				echo "The requested page could not be found.";
			}
        ?>
    </body>
</html>
