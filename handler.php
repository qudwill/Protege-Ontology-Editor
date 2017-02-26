<?php
	if ( !file_exists(FILE_NAME) ) {
		exit('Не удалось открыть файл' . FILE_NAME . '. Пожалуйста, загрузите файл ' . FILE_NAME . ' в корневую директорию.');
	} else {
    	$owl = file_get_contents(FILE_NAME);
    	$owl = str_replace('rdf:', 'rdf_', $owl);
		$owl = str_replace('owl:', 'owl_', $owl);
		$owl = str_replace('rdfs:', 'rdfs_', $owl);
		$XML = simplexml_load_string($owl);
	}

	if ($_POST['action'] === 'add') {
		$owl_Class = $XML->addChild('owl_Class');
		$rdf_About = str_replace(' ', '_', $_POST['rdf_about']);

		$owl_Class->addAttribute('rdf_about', PREFIX . $rdf_About);

		$rdfs_subClassOf = $owl_Class->addChild('rdfs_subClassOf');

		$rdfs_subClassOf->addAttribute('rdf_resource', PREFIX . $_POST['subClassOf']);

		$rdfs_comment = $owl_Class->addChild('rdfs_comment', $_POST['rdfs_comment']);
		$rdf_Description = $XML->rdf_Description->owl_members->addChild('rdf_Description');

		$rdf_Description->addAttribute('rdf_about', PREFIX . $rdf_About);

		updateXML($XML);

		echo "Определение $rdf_About успешно добавлено. Все изменения сохранены в файл" . UPDATED_FILE;
	}

	if ($_POST['action'] === 'remove') {
		foreach($XML->owl_Class as $owl_Class) {
    		if ($owl_Class['rdf_about'] == PREFIX . $_POST['id']) {
        		$DOM = dom_import_simplexml($owl_Class);

        		$DOM->parentNode->removeChild($DOM);
    		}
		}

		foreach($XML->rdf_Description->owl_members->rdf_Description as $rdf_Description) {
			if ($rdf_Description['rdf_about'] == PREFIX . $_POST['id']) {
        		$DOM = dom_import_simplexml($rdf_Description);

        		$DOM->parentNode->removeChild($DOM);
			}
		}

		updateXML($XML);

		echo 'Определение ' . $_POST['id'] . ' успешно удалено. Все изменения сохранены.';
	}

	if ($_POST['action'] === 'update') {
		$rdf_About = str_replace(' ', '_', $_POST['rdf_about']);

		foreach($XML->owl_Class as $owl_Class) {
    		if ($owl_Class['rdf_about'] == PREFIX . $_POST['id']) {
        		$DOM = dom_import_simplexml($owl_Class);

        		$DOM->setAttribute('rdf_about', PREFIX . $rdf_About);

        		foreach ($owl_Class->rdfs_subClassOf as $rdfs_subClassOf) {
        			$DOM = dom_import_simplexml($rdfs_subClassOf);
        			
        			$DOM->setAttribute('rdf_resource', PREFIX . $_POST['subClassOf']);
        		}

        		foreach ($owl_Class->rdfs_comment as $rdfs_comment) {
        			$DOM = dom_import_simplexml($rdfs_comment);

        			$DOM->nodeValue = $_POST['rdfs_comment'];
        		}
    		}
		}

		foreach($XML->rdf_Description->owl_members->rdf_Description as $rdf_Description) {
			if ($rdf_Description['rdf_about'] == PREFIX . $_POST['id']) {
        		$DOM = dom_import_simplexml($rdf_Description);

        		$DOM->setAttribute('rdf_about', PREFIX . $rdf_About);
			}
		}

		updateXML($XML);

		echo "Определение $rdf_About успешно обновлено. Все изменения сохранены.";
	}
?>