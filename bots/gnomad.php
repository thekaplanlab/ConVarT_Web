<?php
error_reporting(E_ERROR | E_PARSE);

require('../db_connection.php');

if(count($argv) != 3){

	die("The correct format for running the script: php gnomad.php <start_index> <end_index>");
}

$urlEndpoint = 'https://gnomad.broadinstitute.org/api2';

$file = file_get_contents('./ensembl_gene_ids.csv');
$listOfGenes = explode("\n", $file);
//print_r($listOfGenes); 
for ($i=(int)$argv[1]; $i < (int)$argv[2]; $i++){ 
	$geneId = explode(',', trim($listOfGenes[$i]))[0];
	$enstId = explode(',', trim($listOfGenes[$i]))[1];

	if(empty($geneId))
		continue;
	if(empty($enstId))
		continue;

	if($db_connection->query("SELECT * FROM gnomad WHERE canonical_transcript='{$enstId}' LIMIT 1")->num_rows > 0){
		continue;
	}

	$query = '{"query":"{ gene(gene_id: \"----____----\") {variants(dataset: gnomad_r2_1, transcriptId: \"____----____\") {
      consequence
      isCanon: consequence_in_canonical_transcript
      flags
      hgvs
      hgvsc
      hgvsp
      pos
      rsid
      variant_id: variantId
      xpos
      
      flags
      genome {
        allele_count:ac
        hemi_count:ac_hemi
        hom_count:ac_hom
        allele_num:an
        allele_freq:af
        filters
      }
    }
  }
}","variables":{}}';
	$query = str_replace("\n", "\\n", $query);
	$query = str_replace("----____----", $geneId, $query);
	$query = str_replace("____----____", $enstId, $query);
	//$query = '{"query":"{\n    gene(gene_id:\"'.$listOfGenes[$i].'\") {\n  canonical_transcript \n     gnomad_r2_1: variants(dataset: gnomad_r2_1) {\n        allele_count: ac\n        hemi_count: ac_hemi\n        hom_count: ac_hom\n        allele_num: an\n        allele_freq:: af\n        consequence\n        isCanon: consequence_in_canonical_transcript\n        datasets\n        filters\n        flags\n        hgvs\n        hgvsc\n        hgvsp\n        pos\n        rsid\n        variant_id: variantId\n        xpos\n      }\n    }\n}\n","variables":null,"operationName":null}';
	$reconnect = 0;
	do{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$urlEndpoint);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 45); //timeout in seconds

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json'                                                                                
		)); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$output = curl_exec($ch);
		$error = curl_errno($ch);

		curl_close ($ch);
		$reconnect++;

	} while($error && $reconnect < 3);

	//$rawVariants = json_decode($output);

	if($error){
		echo $listOfGenes[$i].",curlError\n";
		continue;
	} elseif(strpos($output, 'error') !== false){
		echo $listOfGenes[$i].",queryError\n";
		continue;
	}
	//echo $output."\n";

	$rawVariants = json_decode($output);

	if(! isset($rawVariants->data)){
		echo "notok\n";
		//echo "Completed genes:". ($i-$argv[1]) . " # of genes to fetch:". ($argv[2]-$i).	"\n";
		continue;
	}

	$canonicalTranscript = $rawVariants->data->gene->canonical_transcript;

	$numberOfVariants = 0;
	$batchQuery = '';
	foreach ($rawVariants->data->gene->variants as $value) {
		if(empty($value->hgvsp) /*|| $value->isCanon == 0*/){
			continue;
		}
		$numberOfVariants++;
		preg_match("@(p\.)(.*?)([0-9]+)@", $value->hgvsp, $matches);

		$variant = [
			'position' => count($matches) >= 3 ? $matches[3] : 0,
			'rs_number' => $value->rsid,
			'consequence'=>$value->consequence,
			'ensg_id'=>$geneId,
			'canonical_transcript'=>$enstId,
			'variation'=>$value->hgvsp,
			'variant_id'=>$value->variant_id,
			'filters'=>implode(',', $value->genome->filters),
			'allele_count'=>$value->genome->allele_count,
			'allelle_frequency'=>$value->genome->allele_freq,
			'allele_number'=>$value->genome->allele_num,
			'flags'=>implode(',', $value->flags),
			'datasets'=>'gnomad_r2_1',
			'is_canon'=>$value->isCanon
		];
			
		$query= "INSERT INTO gnomad ( " . implode(', ',array_keys($variant)) . ") VALUES (\"" .
		 implode('", "',array_values($variant)) . '");';
		$db_connection->query($query);
		//$batchQuery .= $query;
		//$variants[] = $variant;
	}
	echo "$geneId $enstId {$numberOfVariants}\n";

	//$db_connection->query($batchQuery);

	//echo "Ok".count($variants)."\n";
 	//echo "Completed genes:". ($i-$argv[1]) . " # of genes to fetch:". ($argv[2]-$i).	"\n";
}
