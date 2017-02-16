<?php

include 'FileIndexer.class.php';

$indexer = new FileIndexer();
$files = $indexer->GetFiles();
$names = $indexer->GetNames();

?>

<html>
	<head>
		<title>Nova WS - v1.0</title>
		<meta charset="UTF-8">
	</head>

	<body>
		<?php
			include 'cabecalho.php'
		?>

		<table border>
			<?php
				$tam = $indexer->GetLength();
				for ($i = $tam - 1; $i >= 0; $i--)
				{
					echo "<tr>\n";
					echo "    <td><a href='" . $files[$i] .  "'><b>" . $names[$i] . "</b></a></td>\n";
					echo "</tr>\n";
				}

				$indexer = null;
			?>
		</table>
	</body>
</html>
