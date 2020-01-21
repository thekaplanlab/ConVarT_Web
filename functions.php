<?php

require_once("config.php");

require_once("db_connection.php");

$clinicalSignificanceMapping = [
        'Benign' => ["Benign","Benign, association","Benign/Likely benign","Benign/Likely benign, Affects","Benign/Likely benign, association","Benign/Likely benign, drug response","Benign/Likely benign, drug response, risk factor","Benign/Likely benign, other","Benign/Likely benign, protective","Benign/Likely benign, protective, risk factor","Benign/Likely benign, risk factor","Benign, other","Benign, risk factor","Likely benign","Likely benign, drug response, other","Likely benign, other","Likely benign, risk factor"],
        'Pathogenic' => ["Pathogenic","Pathogenic, Affects","Pathogenic, association, protective","Pathogenic, drug response","Pathogenic/Likely pathogenic","Pathogenic/Likely pathogenic, drug response","Pathogenic/Likely pathogenic, other","Pathogenic/Likely pathogenic, risk factor","Pathogenic, other","Pathogenic, other, risk factor","Pathogenic, protective","Pathogenic, risk factor","Likely pathogenic","Likely pathogenic, association","Likely pathogenic, drug response","Likely pathogenic, other","Likely pathogenic, risk factor"],
        'VUS' => ["Uncertain significance","Uncertain significance, drug response","Uncertain significance, other","Uncertain significance, risk factor"],
        'Conflicting' => ["conflicting data from submitters","Conflicting interpretations of pathogenicity","Conflicting interpretations of pathogenicity, Affects","Conflicting interpretations of pathogenicity, Affects, association, drug response, other","Conflicting interpretations of pathogenicity, Affects, association, risk factor","Conflicting interpretations of pathogenicity, Affects, other","Conflicting interpretations of pathogenicity, association","Conflicting interpretations of pathogenicity, association, other, risk factor","Conflicting interpretations of pathogenicity, drug response","Conflicting interpretations of pathogenicity, other","Conflicting interpretations of pathogenicity, other, risk factor","Conflicting interpretations of pathogenicity, protective","Conflicting interpretations of pathogenicity, risk factor"],
         'Others' => ["drug response","drug response, protective, risk factor","drug response, risk factor","no interpretation for the single variant","not provided","other","Affects","Affects, association","Affects, risk factor","association","association, protective","association, risk factor","protective","protective, risk factor","risk factor"]
    ];

$disease_cat_mapping = ['C01' => 'Bacterial Infections and Mycoses ','C02' => 'Virus Diseases ','C03' => 'Parasitic Diseases ','C04' => 'Neoplasms ','C05' => 'Musculoskeletal Diseases ','C06' => 'Digestive System Diseases ','C07' => 'Stomatognathic Diseases ','C08' => 'Respiratory Tract Diseases ','C09' => 'Otorhinolaryngologic Diseases ','C10' => 'Nervous System Diseases ','C11' => 'Eye Diseases ','C12' => 'Male Urogenital Diseases ','C13' => 'Female Urogenital Diseases and Pregnancy Complications ','C14' => 'Cardiovascular Diseases ','C15' => 'Hemic and Lymphatic Diseases ','C16' => 'Congenital, Hereditary, and Neonatal Diseases and Abnormalities ','C17' => 'Skin and Connective Tissue Diseases ','C18' => 'Nutritional and Metabolic Diseases ','C19' => 'Endocrine System Diseases ','C20' => 'Immune System Diseases ','C21' => 'Disorders of Environmental Origin ','C22' => 'Animal Diseases ','C23' => 'Pathological Conditions, Signs and Symptoms ','C24' => 'Occupational Diseases ','C25' => 'Chemically-Induced Disorders ','C26' => 'Wounds and Injuries ','F01' => 'Behavior and Behavior Mechanisms ','F02' => 'Psychological Phenomena ','F03' => 'Mental Disorders '];

function arrayFind($needle, $haystack)
{
   foreach ($haystack as $key=>$item)
   {
      if (@strpos($item, $needle) !== FALSE)
      {
         return $key;
         break;
      }
   }
}

$availableSpecies = ["human", "chimp", "macaque", "rat", "mouse", "zebrafish", "frog", "fruitfly", "worm", "yeast"];
function getHumanHomolog($geneId){
    if($geneId == null){
        return false;
    }
    global $db_connection;
    global $availableSpecies; 
    $geneId = str_replace('"', '', $geneId);
   
    for($i = 0; $i < count($availableSpecies); $i++){
        $availableSpecies[$i] = "FIND_IN_SET('{$geneId}', {$availableSpecies[$i]}_gene_id)";
    }


    $geneInfoHuman = mysqli_query($db_connection, 
                        "SELECT * FROM homology WHERE ".implode(' or ', $availableSpecies));

    if(mysqli_num_rows($geneInfoHuman) == 0){
        return null;
    }

    $homology = mysqli_fetch_assoc($geneInfoHuman);

    $speciesName = str_replace('_gene_id', '', arrayFind($geneId, $homology));        
    
    return ['human_gene_id' => $homology['human_gene_id'], 'species_name' => $speciesName, 'gene_id'=> $geneId];

}
function processMSA($row){
    $fasta = $row['fasta'];
    
    $fasta = explode("\n>", trim($fasta));
    $homo = @end(array_filter($fasta, function($x){return strpos($x, 'Homo sapiens') !== false; }));
    
    $homoId = array_search($homo, $fasta);
    if($homoId != 0){
        
        $fasta[$homoId] = $fasta[0];
        $fasta[0] = $homo;

    }

    $fasta = '>'.trim(implode("\n>", $fasta));
    $fasta = preg_replace('@>{2,}@', '>', $fasta);
    
    preg_match('@^(.*?) @', $homo, $npId);
    $npId = $npId[1];
    $transcriptId = str_replace('>', '', trim($npId));
    return ['id'=>$row['id'], 'fasta'=>$fasta, 'human_transcript_id'=>$transcriptId,
                'human_convart_gene_id'=> getConvartGeneIdByDbId($transcriptId)];
}

function getConvartGeneIdByDbId($transcriptId){
    global $db_connection;
    $transcriptId = explode('.', $transcriptId)[0];
    
    $query = mysqli_query($db_connection, "SELECT convart_gene_id FROM convart_gene_to_db WHERE db_id='$transcriptId'");
    $row = mysqli_fetch_assoc($query);

    return $row['convart_gene_id'];

}
function getMSAByGeneId($convartGeneId, $withFasta= true){
    global $db_connection;
    $withoutVersion = explode('.', $convartGeneId)[0];
    
    if($withFasta){
        $query = mysqli_query($db_connection, "SELECT id, fasta FROM msa AS m INNER JOIN msa_gene AS mg ON ".
                            "mg.msa_id = m.id LEFT JOIN convart_gene_to_db as gdb ON gdb.convart_gene_id=mg.convart_gene_id ".
                            "LEFT JOIN msa_best_combination as mb ON gdb.convart_gene_id=mb.convart_gene_id WHERE ".
                            "(mb.msa_id = m.id AND (mg.convart_gene_id='{$convartGeneId}' OR gdb.db_id='{$withoutVersion}')) OR 
                             (mg.convart_gene_id LIKE '{$convartGeneId}' OR gdb.db_id='{$withoutVersion}')
                             LIMIT 1");   
        if(mysqli_num_rows($query) == 0){
            return null;
        }
        $row = mysqli_fetch_assoc($query);
        
        return processMSA($row);
    } else {

        $query = mysqli_query($db_connection, "SELECT mg.msa_id AS id, mg_hum.convart_gene_id AS human_convart_gene_id,
                                ncbi_hum.ncbi_gene_id AS ncbi_gene_id 
                                FROM msa_gene AS mg ".
                            " INNER JOIN msa_gene AS mg_hum ON mg_hum.msa_id = mg.msa_id 
                              INNER JOIN msa_best_combination AS mb ON mg_hum.convart_gene_id=mb.convart_gene_id
                              LEFT JOIN convart_gene_to_db as gdb ON gdb.convart_gene_id=mg.convart_gene_id
                              INNER JOIN ncbi_gene_meta AS ncbi_hum ON 
                                  ncbi_hum.meta_value='{$withoutVersion}'
                              WHERE ".
                            "
                            (gdb.db_id='{$withoutVersion}')

                             LIMIT 1");   
        echo mysqli_error($db_connection);
        
        if(mysqli_num_rows($query) == 0){
            return null;
        }
        $row = mysqli_fetch_assoc($query);
        
        return $row;
    }
}
function hasMSA($convartGeneId){
    global $db_connection;
    $convartGeneIdWithoutVersion = explode('.', $convartGeneId)[0];

    $query = mysqli_query($db_connection, "SELECT msa_id FROM msa_gene AS mg LEFT JOIN convart_gene_to_db as gdb ON 
                        gdb.convart_gene_id=mg.convart_gene_id WHERE
                        mg.convart_gene_id='{$convartGeneId}' OR gdb.db_id='{$convartGeneIdWithoutVersion}'");

    return mysqli_num_rows($query) > 0;
}

function getMSAById($id){
    global $db_connection;

    $query = mysqli_query($db_connection, "SELECT id, fasta FROM msa AS m WHERE m.id='{$id}' LIMIT 1");   
    if(mysqli_num_rows($query) == 0){
        return null;
    }

    $row = mysqli_fetch_assoc($query);

    return processMSA($row);
}
function getGeneDetailsByENSTIdFromAPI(&$geneDetails, $transcriptId){
    $transcriptId = explode('.', $transcriptId)[0];
    $rawData = file_get_contents('https://rest.ensembl.org/lookup/id/'.$transcriptId.'?content-type=application/json;expand=0');
    $data = json_decode($rawData);
    $geneDetails['gene_symbol'] = $data->display_name;
    $geneDetails['gene_synonym'] = $data->display_name;
    $geneDetails['protein_number'] = $transcriptId;
    $geneDetails['ENSG'] = $data->Parent;
}
function getNCBIDetailsById($geneId){
    global $db_connection;

    $geneIdNoVersion = explode('.', $geneId)[0];
    $query = mysqli_query($db_connection, "SELECT nc0.ncbi_gene_id AS gene_id, nc0.meta_key, GROUP_CONCAT(nc0.meta_value) AS meta_value FROM  ncbi_gene_meta AS nc0 INNER JOIN ncbi_gene_meta AS nc1 ON (nc1.ncbi_gene_id='{$geneId}' OR nc1.meta_value='{$geneId}' OR nc1.meta_value='{$geneIdNoVersion}' ) WHERE nc0.ncbi_gene_id=nc1.ncbi_gene_id GROUP BY meta_key");
   
    $details = [];
    $details['dbs'] = [];
    $details['dbs']['ENST'] = [];

    while($row = @mysqli_fetch_assoc($query)){
        $values = explode(',', $row['meta_value']);
        $uniqueValues = array_unique($values);

        $details[$row['meta_key']] = implode(',', $uniqueValues);

        $details['dbs']['NCBI'] = $row['gene_id'];
    }
    if(strstr($geneId, 'ENST')){
        $details['dbs']['ENST'][] = explode('.', $geneId)[0];   
    }

    return $details;
}
function getGeneDetailsById($geneId, $withSequence = false){
    global $db_connection;
    global $availableSpecies;
    if(empty($geneId)){
        return null;
    }
    $geneIdNoVersion = explode('.', $geneId)[0];

    $details = getNCBIDetailsById($geneId);

    $query = mysqli_query($db_connection, "SELECT cg2.convart_gene_id, cg2.db, cg2.db_id FROM convart_gene_to_db AS cg1 ".
                    "INNER JOIN convart_gene_to_db AS cg2 ON cg2.convart_gene_id=cg1.convart_gene_id ".
                    "WHERE cg1.db_id='{$geneId}' or cg1.db_id='{$geneIdNoVersion}'");

    while ($db = mysqli_fetch_assoc($query)) {

        $details['convart_gene_id'] = $db['convart_gene_id'];
        $details['dbs'][$db['db']][] = explode('.', $db['db_id'])[0];
    }

    foreach ($details['dbs'] as $db => $ids) {
        if(!is_array($details['dbs'][$db])){
            $details['dbs'][$db] = [$details['dbs'][$db]];
        }
        $details['dbs'][$db] = '"'.implode('","', array_unique($details['dbs'][$db])) . '"';
    }

    if(isset($details['dbs']['ENST'])){
        $details['ENST'] = $details['dbs']['ENST']  ;   
    }

    if($withSequence){
        $query = mysqli_query($db_connection, "SELECT sequence FROM convart_gene WHERE id='{$details['convart_gene_id']}'");
        $row = mysqli_fetch_assoc($query);
        $details['sequence'] = $row['sequence'];
    }

    return $details;
}

function createMSA($fasta, $ids, $alignment_method){
    global $db_connection;
    if($stmt = mysqli_prepare ($db_connection, "INSERT INTO msa (fasta, alignment_method) VALUES(?, ?)")){
            mysqli_stmt_bind_param($stmt, "ss", $fasta, $alignment_method);
            mysqli_stmt_execute($stmt);
    }
    $msaId =  mysqli_insert_id($db_connection);
    
    foreach ($ids as $id) {
        mysqli_query($db_connection, "INSERT INTO msa_gene (msa_id, convart_gene_id) VALUES({$msaId}, {$id})");
    }
    if(mysqli_error($db_connection)){
        echo mysqli_error($db_connection);
        exit;
    }

    return $msaId;
}

function getGeneDetailsByConvartId($convartGeneId, $ncbiGeneId){
    global $db_connection;
    global $availableSpecies;
   
    $details = getNCBIDetailsById($ncbiGeneId);

    $details['dbs']['NCBI'] = [$ncbiGeneId];

    $query = mysqli_query($db_connection, "SELECT cg2.db, cg2.db_id FROM convart_gene_to_db AS cg1 ".
                    "INNER JOIN convart_gene_to_db AS cg2 ON cg2.convart_gene_id=cg1.convart_gene_id ".
                    "WHERE cg1.convart_gene_id='{$convartGeneId}'");

    while ($db = mysqli_fetch_assoc($query)) {
        $details['dbs'][$db['db']][] = explode('.', $db['db_id'])[0];
    }
    foreach ($details['dbs'] as $db => $ids) {
        $details['dbs'][$db] = '"'.implode('","', array_unique($details['dbs'][$db])) . '"';
    }

    if(isset($details['dbs']['ENST'])){
        $details['ENST'] = $details['dbs']['ENST']  ;   
    }

    return $details;
}
function getHumanProteinDomains($transcriptId) {
    global $db_connection;

    $domainsQuery = mysqli_query($db_connection, "SELECT * FROM domains LEFT JOIN domains_desc ON domains.pfam_name = domains_desc.pfam_name WHERE 
    transcript_id LIKE '{$transcriptId}.%' AND evalue < 1e-01 ORDER BY start_point ASC");

    $countForDomains = mysqli_num_rows($domainsQuery);
    if($countForDomains == 0) 
        return null;

    return $domainsQuery;
}

function getClinvarStatsBySignificance($id, $col='gene_id'){
    global $db_connection; 
    global $clinicalSignificanceMapping;

    #Statistics
    $ClinVarStatisticsQuery = mysqli_query($db_connection, "SELECT clinical_significance, COUNT(clinvar_id) as n_variations ".
        "FROM clinvar WHERE `{$col}` IN ({$id}) GROUP BY clinical_significance");
    $counts = [];
 
    while ($row = mysqli_fetch_array($ClinVarStatisticsQuery)) {
        foreach( $clinicalSignificanceMapping as $key => $listOfSignificances ) {
            if(in_array($row["clinical_significance"], $listOfSignificances)){
                if(isset($counts[$key])) {
                    $counts[$key] += $row["n_variations"];
                } else {
                    $counts[$key] = $row["n_variations"];
                }
            }
        }
    }

    return $counts;
}
function printify($data) {
    $data = normalizeIds($data);

    return str_replace('"', '', str_replace(',', ', ', $data));
}
function normalizeIds($transcriptId) {
    
    if(is_array($transcriptId)){
        $transcriptIds = array_map(function($x){return str_replace('"', '', $x); }, array_unique($transcriptId));

        $transcriptId = '"'.implode('","', $transcriptIds) . '"';
    }

    return $transcriptId;
}

#gnomAD Chart Stats
function getgnomADStats($transcriptId, $column='canonical_transcript'){
    global $db_connection;
    $transcriptId = normalizeIds($transcriptId);

    $gnomADStatisticsQuery = mysqli_query($db_connection, "SELECT consequence, COUNT(id) FROM gnomad WHERE gnomad.{$column} IN ({$transcriptId}) GROUP BY consequence");    
    $countsForgnomAD = [];
    
    while($row = mysqli_fetch_assoc($gnomADStatisticsQuery)){
        $countsForgnomAD[$row['consequence']] = $row['COUNT(id)'];
    }
    //print_r($countsForgnomAD);
    return $countsForgnomAD;
}

#COSMIC Chart Stats
function getCosmicStats($transcriptId, $column='accession_number'){
    global $db_connection;
    $transcriptId = normalizeIds($transcriptId);
    $CosmicStatisticsQuery = mysqli_query($db_connection, "SELECT mutation_description, COUNT(cme_id) FROM CosmicMutantExport WHERE CosmicMutantExport.{$column} IN ({$transcriptId}) GROUP BY mutation_description");    
    $countsForCosmic = [];
    
    while($row = mysqli_fetch_assoc($CosmicStatisticsQuery)){
        $countsForCosmic[$row['mutation_description']] = $row['COUNT(cme_id)'];
    }
    //print_r($countsForCosmic);
    return $countsForCosmic;
}

#dbSNP Chart Stats
function getdbSNPStats($transcriptId, $column='Feature'){
    global $db_connection;
    $transcriptId = normalizeIds($transcriptId);
    $dbSNPStatisticsQuery = mysqli_query($db_connection, "SELECT Impact, COUNT(dbsnp_index) FROM dbsnp WHERE dbsnp.{$column} IN ({$transcriptId}) GROUP BY Impact");    
    $countsFordbSNP = [];
    
    while($row = mysqli_fetch_assoc($dbSNPStatisticsQuery)){
        $countsFordbSNP[$row['Impact']] = $row['COUNT(dbsnp_index)'];
    }
    return $countsFordbSNP;
}

#PTM Chart Stats
function getPTMStats($transcriptId, $column='acc_id'){
    global $db_connection;
    $transcriptId = normalizeIds($transcriptId);

    $PtmStatisticsQuery = mysqli_query($db_connection, "SELECT ptm_type, COUNT(index_id) FROM ptm WHERE {$column} IN ({$transcriptId}) GROUP BY ptm_type");    
    $countsForPTM = [];
    
    while($row = mysqli_fetch_assoc($PtmStatisticsQuery)){
        $countsForPTM[$row['ptm_type']] = $row['COUNT(index_id)'];
    }
    //print_r($countsForPTM);
    return $countsForPTM;
}

function getClinvarData($transcriptId, $column='gene_id'){
    global $db_connection;
    if($transcriptId == null)
        return null;
    $transcriptId = normalizeIds($transcriptId);

    $query = mysqli_query($db_connection, "SELECT * FROM clinvar WHERE {$column} IN ({$transcriptId}) ORDER BY position");
    
    if(mysqli_num_rows($query) == 0)
        return null;

    return $query;
}

function getPtmData($transcriptId, $column='acc_id', $cols='*'){
    global $db_connection;
    if($transcriptId == null)
        return null;

    $query = mysqli_query($db_connection, "SELECT {$cols} FROM ptm WHERE {$column} IN ({$transcriptId}) ORDER BY position");
    
    if(mysqli_num_rows($query) == 0)
        return null;

    return $query;
}

function getCosmicData($transcriptId, $column='accession_number', $cols='*'){
    global $db_connection;

    if($transcriptId == null)
        return null;

    $query = mysqli_query($db_connection, "SELECT $cols FROM CosmicMutantExport WHERE {$column} IN ({$transcriptId}) ORDER BY position DESC");
    
    if(mysqli_num_rows($query) == 0)
        return null;

    return $query;
}

function getdbSNPData($transcriptId, $column='Feature', $cols='*'){
    global $db_connection;

    if($transcriptId == null)
        return null;

    $query = mysqli_query($db_connection, "SELECT $cols FROM dbsnp WHERE {$column} IN ({$transcriptId}) ORDER BY Protein_position DESC");
    
    if(mysqli_num_rows($query) == 0)
        return null;

    return $query;
}


function searchProteinNumbers($value){
    global $db_connection;
    $value = str_replace('"', '', $value);
    if (empty($value))
        return [];

    $ids = [];

    $ids[] = "SUBSTRING_INDEX(nc_prot.meta_value, '.', 1)";
    
    $ids = implode(',', $ids);
    $query = mysqli_query($db_connection, "SELECT GROUP_CONCAT(DISTINCT CONCAT(cdb.convart_gene_id, ',', cdb.db_id, ',', nc_sym.meta_value, ',', nc_spec.meta_value) SEPARATOR ';') 
                        AS data, nc_prot.ncbi_gene_id FROM ncbi_gene_meta AS nc_search INNER JOIN ncbi_gene_meta AS nc_spec ON 
                        nc_search.ncbi_gene_id=nc_spec.ncbi_gene_id AND nc_spec.meta_key='species_id' INNER JOIN ncbi_gene_meta AS nc_sym ON 
                        nc_search.ncbi_gene_id=nc_sym.ncbi_gene_id AND nc_sym.meta_key='gene_symbol' INNER JOIN ncbi_gene_meta AS nc_prot ON 
                        nc_search.ncbi_gene_id=nc_prot.ncbi_gene_id AND nc_prot.meta_key='protein_number' INNER JOIN convart_gene_to_db AS cdb ON 
                        cdb.db_id=nc_prot.meta_value
                        INNER JOIN msa_gene AS mg ON 
                        mg.convart_gene_id=cdb.convart_gene_id
                         WHERE nc_search.meta_value IN ('{$value}') OR nc_search.ncbi_gene_id IN ('{$value}')
                          GROUP BY nc_prot.ncbi_gene_id LIMIT 10");
    $searchResultBySpecies = [];

    while($row = mysqli_fetch_assoc($query)){
        

        $transcriptIds = explode(';', $row['data']);
        foreach ($transcriptIds as $data) {
            $data = explode(',', $data);
            $species = $data[3];
            if(! isset($searchResultBySpecies[$species])){
                $searchResultBySpecies[$species] = [];
            }

            $searchResultBySpecies[$species][] = ['convart_gene_id'=> $data[0], 
                                                  'transcript_id' => $data[1], 'gene_symbol' => $data[2],
                                                  'ncbi_gene_id' => $row['ncbi_gene_id']];
        }

    }
    return $searchResultBySpecies;

}


function search_spemud_proteins($spemud_value) {
    global $db_connection;
    if (empty($spemud_value))
        return null;

    $query_ids = mysqli_query($db_connection, "SELECT mapping_human.gene_id AS human_gene_id, homology.mouse_gene_id, homology.worm_gene_id FROM mapping_human INNER JOIN homology ON mapping_human.gene_id = homology.human_gene_id WHERE mapping_human.gene_symbol='$spemud_value' OR FIND_IN_SET('$spemud_value', mapping_human.gene_synonyms) OR FIND_IN_SET('$spemud_value', mapping_human.protein_numbers)");
    if(mysqli_num_rows($query_ids) == 0){
        return null;
    }
    
    $spemud_gene_id_array = [
        "human" => "",
        "mouse" => "",
        "worm" => "",
    ];

    $row = mysqli_fetch_assoc($query_ids);
    $spemud_gene_id_array["human"] = $row["human_gene_id"];
    $spemud_gene_id_array["mouse"] = $row["mouse_gene_id"];
    $spemud_gene_id_array["worm"] = $row["worm_gene_id"];

    $block_result = "";
    foreach ($spemud_gene_id_array as $key => $value) {
        $gene_id_array = explode(",", $value);
        $species = ucwords($key);
        $block_result .= "<div class='row pageTitle'>Results for $species <hr></div>";
        for ($i=0; $i < count($gene_id_array) ; $i++) { 
            $temp_gene_id = $gene_id_array[$i];
            $query_protein_ids = mysqli_query($db_connection, "SELECT nc1.ncbi_gene_id, nc2.meta_value AS gene_symbol, nc1.meta_value AS prot_ids, cdb.convart_gene_id FROM ncbi_gene_meta AS nc1 INNER JOIN ncbi_gene_meta AS nc2 ON nc1.ncbi_gene_id=nc2.ncbi_gene_id INNER JOIN convart_gene_to_db AS cdb ON nc1.meta_value=cdb.db_id WHERE nc1.ncbi_gene_id=$temp_gene_id AND nc1.meta_key='protein_number' AND nc2.meta_key='gene_symbol'");
            if(mysqli_num_rows($query_protein_ids) == 0){
                return null;
            }
            $row_prots = mysqli_fetch_assoc($query_protein_ids);
            // echo $key . " =>" . $gene_id_array[$i] ."<br><br>";
            $temp_gene_symbol = $row_prots['gene_symbol'];
            $temp_prot_ids = $row_prots['prot_ids'];
            $temp_convart_ids = $row_prots['convart_gene_id'];
            $spemud_radio_button = "<div class='convart-radio'><input id='$key-$temp_convart_ids' name='$key' value='$temp_convart_ids' type='radio' /> <label for='$key-$temp_convart_ids'>$temp_gene_symbol | $temp_prot_ids</label></div>";
            $block_result .= $spemud_radio_button;
        }
    }
    return $block_result;
}


function getGeneIdbyDBId($id, $db='NM'){
    global $db_connection;
    $idWithoutVersion =  explode('.', $id)[0];
    $versionOfId = explode('.', $id)[1];

    $query = mysqli_query($db_connection, "SELECT convart_gene_id FROM convart_gene_to_db WHERE db='{$db}' AND db_id='$idWithoutVersion'");
    
    if($query == null || mysqli_num_rows($query) == 0){
        return null;
    }

    return mysqli_fetch_assoc($query)['convart_gene_id'];
}

function getGeneByRSNumber($rs_number) {

}

function getGnomADData($transcriptId, $column='canonical_transcript', 
                $cols='*'){
    global $db_connection;
    $query = mysqli_query($db_connection, "SELECT {$cols} FROM gnomad WHERE gnomad.{$column} IN ({$transcriptId}) AND position > 0 ORDER BY position");    

    if(mysqli_num_rows($query) == 0)
        return null;

    return $query;
}

function diseaseFind($humanGeneId) {
    global $db_connection;
    $query = mysqli_query($db_connection, "SELECT disease_info.disease_id, disease_info.disease_name, disease_info.category, diseases_genes.dsi, diseases_genes.dpi FROM diseases_genes INNER JOIN disease_info ON diseases_genes.disease_id=disease_info.disease_id WHERE diseases_genes.gene_id='{$humanGeneId}'");
    
    if(mysqli_num_rows($query) == 0)
        return null;

    return $query;
}

function linkify($commaSeparatedIds) {
    $mapping = [
        'ZDB-GENE-' => 'https://zfin.org/',
        'ENSDARG'   => 'https://www.ensembl.org/Danio_rerio/Gene/Summary?db=core;g=',
        'WBGene'    => 'https://www.wormbase.org/species/c_elegans/gene/',
        'ENSRNOG'   => 'https://www.ensembl.org/Rattus_norvegicus/Gene/Summary?db=core;g=',
        'MGI:'      => 'http://www.informatics.jax.org/marker/',
        'ENSMUSG'   => 'https://www.ensembl.org/Mus_musculus/Gene/Summary?db=core;g=',
        'ENSMMUG'   => 'https://www.ensembl.org/Macaca_mulatta/Gene/Summary?db=core;g=',
        'ENSG'      => 'http://ensembl.org/Homo_sapiens/Gene/Summary?g=',
        'HGNC:'     => function($id) {return 'https://www.genenames.org/data/gene-symbol-report/#!/hgnc_id/'.str_replace('HGNC:', '', $id);},
        'FBgn'      => 'http://flybase.org/reports/',
        'XB-GENE-'  => 'http://www.xenbase.org/gene/showgene.do?method=display&geneId=',
        'ENSXETG'   => 'https://www.ensembl.org/xenopus_tropicalis/Gene/Summary?g=',
        'ENSPTRG'   => 'https://www.ensembl.org/Pan_troglodytes/Gene/Summary?db=core;g=',
        'NP_'       => 'https://www.ncbi.nlm.nih.gov/protein/',
        'XP_'       => 'https://www.ncbi.nlm.nih.gov/protein/',
        'OMIM:'     => function($id) {return 'https://omim.org/entry/'.str_replace('OMIM:', '', $id);},
        'ENST'      => 'http://www.ensembl.org/homo_sapiens/Transcript/Summary?db=core&t='
    ];
    if(!is_array($commaSeparatedIds)){
        $ids = explode(',', $commaSeparatedIds);
    } else{
        $ids = $commaSeparatedIds;
    }

    foreach ($ids as $i => $id) {
        $db = preg_replace('/^(.*?)\d(.*?)$/', '$1', $id); // Remove everything after the first number (including the first number)
            
        if(! isset($mapping[$db])) 
            continue;

        if(gettype($mapping[$db]) == 'string'){
            $link = $mapping[$db]. $id; 
        } else { // If it is not string, then it is a function which takes id as an input and returns the link
            $link = $mapping[$db]($id);
        }

        $ids[$i] = " <a href='{$link}' target='_blank'>$id</a>";
    }
    // merges ids back and return it
    return implode(',', $ids);
}

function getConservationScores($msaId) {
    global $db_connection;

    $scoreTypeToName = ['exact_match_score' => 'Total amino acid identity (%)', 
                        'polarity_match_score' => 'Total amino acid similarity (%)',
                        'variant_exact_match_score' => 'Variation associated amino acid identity (%)',
                        'variant_polarity_match_score'=> 'Variation associated amino acid similarity (%)'];

    $conservationScoreQuery = mysqli_query($db_connection, "SELECT GROUP_CONCAT(specie) AS species_list, score_type, 
                                    GROUP_CONCAT(ROUND(score*100/aminoacid_number)) AS score_list FROM conservation_scores WHERE msa_id = '{$msaId}' 
                                    GROUP BY score_type ORDER BY FIELD(specie, 'Pan troglodytes', 'Macaca mulatta', 'Mus musculus', 'Rattus norvegicus', 'Danio rerio', 'Xenopus tropicalis','Drosophila melanogaster','Caenorhabditis elegans')");

    $countForConScore = mysqli_num_rows($conservationScoreQuery);
    if($countForConScore == 0) 
        return null;

    $data = ['options'=> []];

    while ($row = mysqli_fetch_assoc($conservationScoreQuery)) {
    
        if(array_search($row['score_type'], ['exact_match_score', 'variant_exact_match_score',
                                             'polarity_match_score', 'variant_polarity_match_score'
            ]) === false )
            continue;

        $data['options'][] = ['name'=>$scoreTypeToName[$row['score_type']],
                        'data'=>explode(',', $row['score_list'])];
        $data['categories'] = explode(',', $row['species_list']);
    }
    return $data;
}
