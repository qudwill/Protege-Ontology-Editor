<?php
	define('FILE_NAME', '_db.owl');
	define('PREFIX', 'http://www.semanticweb.org/qudwill/ontologies/2017/1/untitled-ontology-2#');

	function updateXML($SimpleXMLObject) {
		$owl = $SimpleXMLObject->asXML();
		$owl = str_replace('rdf_', 'rdf:', $owl);
		$owl = str_replace('owl_', 'owl:', $owl);
		$owl = str_replace('rdfs_', 'rdfs:', $owl);
		$XML = simplexml_load_string($owl);

		$XML->asXml(FILE_NAME);
	}
?>