<?php
	require_once('config.php');
	require_once('handler.php');

	$title = $XML->rdf_RDF->owl_Ontology->rdfs_Comment;
?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<title><?= $title ?></title>
</head>

<body>
	<?php
		$select_array = array();

		foreach ($XML->owl_Class as $class) {
			foreach ($class->rdfs_subClassOf as $subClassOf) {
				$select_array[] = str_replace(PREFIX, '', $subClassOf['rdf_resource']);
			}
		}

		$unique_select_array = array_unique($select_array);

		foreach ($XML->owl_Class as $class) :
			$rdf_about = str_replace(PREFIX, '', $class['rdf_about']);
	?>
	<h2><?= $rdf_about ?></h2>
	<form action="/" class="owl_Class update" method="POST">
		<input type="hidden" name="id" value="<?= $rdf_about; ?>">
		<label for="">rdf_about</label><br>
		<input type="text" name="rdf_about" value="<?= $rdf_about; ?>"><br>
		<label for="">subClassOf</label><br>
		<?php
			$rdf_resource = str_replace(PREFIX, '', $class->rdfs_subClassOf['rdf_resource']);
			$select_html = '<select name="subClassOf">';
	
			foreach ($unique_select_array as $unique) {
				$select_html .= '<option value="' . $unique . '"';

				if ($rdf_resource == $unique) {
					$select_html .= ' selected="">' . $unique . '</option>';
				} else {
					$select_html .= '>' . $unique . '</option>';
				}
			}

			$select_html .= '</select>';
			
			echo $select_html;
		?>
		<br>
		<label for="">comment</label><br>
		<textarea name="rdfs_comment" id="" cols="30" rows="10"><?= $class->rdfs_comment ?></textarea>
		<br>
		<input type="hidden" name="action" value="update">
		<button type="submit">Обновить</button>
	</form>
	<form action="/" class="remove" method="POST">
		<input type="hidden" name="id" value="<?= $rdf_about; ?>">
		<input type="hidden" name="action" value="remove">
		<button type="submit">Удалить</button>
	</form>
	<?php endforeach; ?>
	<h2>Добавить определение</h2>
	<form action="/" class="add" method="POST">
		<label for="">Термин</label><br>
		<input type="text" name="rdf_about" placeholder="Термин"><br>
		<label for="">Категория</label><br>
		<?= $select_html ?><br>
		<label for="">Определение</label><br>
		<textarea name="rdfs_comment" id="" cols="30" rows="10"></textarea><br>
		<input type="hidden" name="action" value="add">
		<button type="submit">Добавить</button>
	</form>
</body>

</html>