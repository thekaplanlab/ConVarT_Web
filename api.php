<?php
#$seconds_to_cache = 3600*48;
#$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
#header("Expires: $ts");
#header("Pragma: cache");
#header("Cache-Control: max-age=$seconds_to_cache");

require_once 'libs/ssp.class.php';

if(!isset($_GET['action']) || !isset($_GET['id'])){
    echo json_encode(['data'=>[]]);
    exit;
}

$invalidCharacters = trim(preg_replace("@([\",a-z0-9\._])@i", "", $_GET['id']));

if(!empty($invalidCharacters)){
    echo json_encode(['data'=>[]]);
    exit;
}

$id = $_GET['id'];

ob_start();

require_once('db_connection.php');
require_once('functions.php');
$value = ob_get_contents();
ob_end_clean();


function utf8ize( $mixed ) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
    }
    return $mixed;
}

switch ($_GET['action']) {
    case 'clinvar':
        $columns = [];
        $colToInd = [];

        $i = 0;
        foreach (['variation_id', 'allele_id', 'rs_number', 'variant_type', 'name',
            'variation', 'position', 
            'clinical_significance', 'last_updated', 'phenotypes', 
            'cytogenetic', 'review_status',
            'rcv_accession'] as $column) {
            $columns[] = ['db' => $column, 'dt'=>$i];
            $colToInd[$column] = $i;
            $i++;
        } 

        $output = SSP::complex($_GET, $dbh, 'clinvar', 'clinvar_id', $columns , null, 'np_id IN ('.$id.')');

        #$clinvarQuery = getClinvarData($id, 'nm_id');
        
        foreach ($output['data'] as $i=>$row) {
            $rs_number = $row[$colToInd['rs_number']];
            if($rs_number=="rs-1") {
                $rs_number="N/A"; 
            }

            $output['data'][$i] = [
                $row[$colToInd['variation_id']],
                $row[$colToInd['allele_id']],
                $rs_number,
                $row[$colToInd['variant_type']],
                $row[$colToInd['name']],
                $row[$colToInd['variation']]. '---' .$row[$colToInd['position']],
                $row[$colToInd['clinical_significance']],
                $row[$colToInd['last_updated']],
                str_replace(';', '; ', $row[$colToInd['phenotypes']]),
                $row[$colToInd['cytogenetic']],
                $row[$colToInd['review_status']],
                str_replace(';', '; ', $row[$colToInd['rcv_accession']])
            ];

        }


        $output['data'] = utf8ize($output['data']);
        echo json_encode($output);
        exit;
        break;
    
    case 'gnomad':
        $alreadyLookedIds = [];
        $ids = explode(',', $id);
        $data = [];

        foreach($ids as $id){
            $id = explode('.', $id)[0];
            
            if(isset($alreadyLookedIds[$id]))
               continue;
            else
               $alreadyLookedIds[$id] = true;

            $gnomadQuery = getGnomADData($id);

            while($row = @mysqli_fetch_assoc($gnomadQuery)){
                $data[] = [
                    $row['variant_id'],
                    $row['canonical_transcript'],
                    $row['variation'] . '---' .$row['position'], 
                    ucwords(str_replace("_", " ", $row['consequence'])),
                    $row['allele_count'],
                    $row['allele_number'],
                    round($row['allele_count'] / ($row['allele_number'] > 0 ? $row['allele_number'] : 1  ) , 6), #for frequency data 
                    $row['rs_number'],
                    $row['flags'],
                    $row['filters']
                ];
            }
        }
        break;

    case 'ptm':
        $ptmQuery = getPtmData($id);
        $data = [];

        while($row = mysqli_fetch_assoc($ptmQuery)){
            $data[] = [
                $row['gene'],
                $row['acc_id'],
                $row['ptm_type'],
                $row['position'],
                $row['mod_rsd'],
                $row['site_+/-7_aa'],
                $row['mw_kd'],
                $row['site_grp_id'],
            ];
        }
        break;

    case 'mouseVariants':
        $mouseQuery = getMouseVariantsData($id);
        $data = [];

        while($row = mysqli_fetch_assoc($mouseQuery)){
            $data[] = [
                $row['ensembl_gene_id'],
                $row['gene_symbol'],
                $row['ensembl_transcript_id'],
                $row['aa_change'],
                $row['mutation_type'],
                $row['Position'],
            ];
        }
        break;


    case 'dbSNP':
        $dbSNPQuery = getdbSNPData($id);
        $data = [];

        while($row = mysqli_fetch_assoc($dbSNPQuery)){
            $data[] = [
                $row['Gene'],
                $row['Feature'],
                $row['Consequence'],
                $row['Protein_position'] . '---' .$row['HGVSp'], 
                $row['HGVSp'],
                $row['Impact'],
                $row['Uploaded_variation']
            ];
        }
        break;

    // case 'tubulin':
    //     $tubulinQuery = getTubulinData($id);
    //     $data = [];

    //     while($row = mysqli_fetch_assoc($tubulinQuery)){
    //         $data[] = [
    //             $row['gene_tag'],
    //             $row['transcript'],
    //             $row['aa_change'],
    //             $row['position'],
    //             $row['phenotype']
    //         ];
    //     }
    //     break;

    case 'tubulin':
        $alreadyLookedIds = [];
        $ids = explode(',', $id);
        $data = [];

        foreach($ids as $id){
            if(isset($alreadyLookedIds[$id]))
               continue;
            else
               $alreadyLookedIds[$id] = true;

            $tubulinQuery = getTubulinData($id);

            while($row = @mysqli_fetch_assoc($tubulinQuery)){
                $data[] = [
                    $row['gene_tag'],
                    $row['transcript'],
                    $row['aa_change'],
                    $row['position'],
                    $row['phenotype']
                ];
            }
        }
        break;

    case 'cosmic':
       
        $data = [];

        $columns = [];
        $colToInd = [];

        $i = 0;
        foreach ([
            'gene_name',
            'accession_number',
            'sample_name',
            'primary_site',
            'primary_histology',
            'mutation_id',
            'mutation_cds',
            'mutation_aa',
            'mutation_description',
            'fathmm_prediction',
            'fathmm_score',
            'mutation_somatic_status',
            'pubmed_pmid',
            'tumour_origin',
            'position'
        ] as $column) {
            $columns[] = ['db' => $column, 'dt'=>$i];
            $colToInd[$column] = $i;
            $i++;
        } 

        $output = SSP::complex($_GET, $dbh, 'CosmicMutantExport', 'cme_id', $columns , null, 'accession_number IN ('.$id.')');

        #$clinvarQuery = getClinvarData($id, 'nm_id');
        foreach ($output['data'] as $i=>$row) {
            
            $output['data'][$i][$colToInd['mutation_aa']] = $row[$colToInd['mutation_aa']] . '---' .$row[$colToInd['position']];
        }
        echo json_encode(utf8ize($output));
        exit;

        break;
}

echo json_encode(['data'=>utf8ize($data)]);
