<?php
error_reporting(E_ERROR | E_PARSE);

require('../db_connection.php');

if(count($argv) != 3){

	die("The correct format for running the script: php gnomad.php <start_index> <end_index>");
}

$urlEndpoint = 'http://gnomad-api.broadinstitute.org/';

$listOfGenes = explode("\n", file_get_contents('./ensembl_gene_ids.csv'));

for ($i=$argv[1]; $i < $argv[2]; $i++) { 
	$query = '{"query":"{\n    gene(gene_id:\"'.$listOfGenes[$i].'\") {\n  canonical_transcript \n     gnomad_r2_1: variants(dataset: gnomad_r2_1) {\n        allele_count: ac\n        hemi_count: ac_hemi\n        hom_count: ac_hom\n        allele_num: an\n        allele_freq: af\n        consequence\n        isCanon: consequence_in_canonical_transcript\n        datasets\n        filters\n        flags\n        hgvs\n        hgvsc\n        hgvsp\n        pos\n        rsid\n        variant_id: variantId\n        xpos\n      }\n    }\n}\n","variables":null,"operationName":null}';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$urlEndpoint);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 15); //timeout in seconds

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json'                                                                                
	)); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$output = curl_exec($ch);

	if(curl_errno($ch))
	{
	    echo 'Curl error: ' . curl_error($ch) . 'Index:' . $i . ' ' ;
	}

	curl_close ($ch);
	$rawVariants = json_decode($output);
	
	
	if(! isset($rawVariants->data)){
		echo "Completed genes:". ($i-$argv[1]) . " # of genes to fetch:". ($argv[2]-$i).	"\n";
		continue;
	}

	$canonicalTranscript = $rawVariants->data->gene->canonical_transcript;

	$variants = [];

	foreach ($rawVariants->data->gene->gnomad_r2_1 as $value) {
		if(empty($value->hgvsp)){
			continue;
		}
		preg_match("@(p\.)(.*?)([0-9]+)@", $value->hgvsp, $matches);
		
		$variant = [
			'position' => count($matches) >= 3 ? $matches[3] : 0,
			'rs_number' => $value->rsid,
			'consequence'=>$value->consequence,
			'canonical_transcript'=>$canonicalTranscript,
			'variation'=>$value->hgvsp,
			'variant_id'=>$value->variant_id,
			'filters'=>implode(',', $value->filters),
			'allele_count'=>$value->allele_count,
			'allelle_frequency'=>$value->allele_freq,
			'allele_number'=>$value->allele_num,
			'flags'=>implode(',', $value->flags),
			'datasets'=>implode(',', $value->datasets)
		];
		$query= "INSERT INTO gnomad ( " . implode(', ',array_keys($variant)) . ") VALUES (\"" .
		 implode('", "',array_values($variant)) . '")';
		$db_connection->query($query);
	}

	
 	echo "Completed genes:". ($i-$argv[1]) . " # of genes to fetch:". ($argv[2]-$i).	"\n";
}