<?php 
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

$seconds_to_cache = 3600*48;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Pragma: cache");
header("Cache-Control: max-age=$seconds_to_cache");

    require_once("db_connection.php"); 
    require_once("functions.php");

    $available_species = ["human", "chimp", "macaque", "rat", "mouse", "zebrafish", "frog", "fruitfly", "worm", "yeast"];

    // GET QUERY ID
    $msaId = (int) $_GET['msa_id'];

    #$source = $_REQUEST['source'];

    $msa = getMSAById($msaId);
    $fasta = $msa['fasta'];
    $mouse_transcript_id = $msa['mouse_transcript_id'];
    $worm_transcript_id = $msa['worm_transcript_id'];
    
    $domainsResult="";
    


      #Gene Info
      $geneInfoHuman = getGeneDetailsById($msa['human_transcript_id']);
      $geneId = $geneInfoHuman['dbs']['NCBI'];
      
      if($geneInfoHuman == null) {
      	//exit;
      } else {
      	$geneSymbol = $geneInfoHuman['gene_symbol'];
      }
  
      #Domains
      $domainsQuery = mysqli_query($db_connection, "SELECT * FROM domains WHERE transcript_id LIKE '{$msa['human_transcript_id']}%' AND evalue < 1e-01");
      $countForDomains = mysqli_num_rows($domainsQuery);
      if ($countForDomains == 0) {$domainsResult=""; }
          else {
              while ($row = mysqli_fetch_array($domainsQuery)) {
                  $length = $row['end_point'] -$row['start_point'];
                 $domainsResult .= '
                      <a href="https://pfam.xfam.org/family/'.$row['pfam_id'].'" target="_blank">
                      <div class="domain" data-start-point="'.$row['start_point'].'" data-end-point="'.$row['end_point'].'"
                      style="display:none">
					            <div id="domain_sp">'.$row['start_point'].'</div><p>'
                      .$row['pfam_name'].' ('.$row['start_point'].' - '.$row['end_point'].'</p>)
					            <div id="domain_ep">'.$row['end_point'].'</div>
				               </div></a>';
              }
      }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ConVarT</title>
    <meta name="description" content="ConVarT">
    <meta name="title" content="ConVarT">
    <meta name="keywords" content="ConVarT"/>
    <meta name="author" content="ConVarT">
    <meta name="copyright" content="ConVarT">
    <link rel="icon" href="<?= $GLOBALS['base_url']; ?>files/img/convart_black.png">
    <style type="text/css">.material-tooltip { padding: 10px 8px; font-size: 1rem; z-index: 2000; background-color: transparent; border-radius: 2px; color: #fff; min-height: 24px; line-height: 120%; opacity: 0; position: absolute; text-align: center; max-width: calc(100% - 4px); overflow: hidden; left: 0; top: 0; pointer-events: none; visibility: hidden; background-color: #323232; } .backdrop { position: absolute; opacity: 0; height: 7px; width: 14px; border-radius: 0 0 50% 50%; background-color: #323232; z-index: -1; transform-origin: 50% 0%; visibility: hidden; }</style>
    <link type="text/css" rel="stylesheet" href="<?= $GLOBALS['base_url']; ?>files/css/tool.css?id=<?php echo uniqid(); ?>" media="screen"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="<?= $GLOBALS['base_url']; ?>files/js/js.js"></script>
    <script type="text/javascript">$('.tooltipped').tooltip();</script>
    <script type="text/javascript" src="libs/msa_viewer.js?id=<?= rand(); ?>"></script>
</head>
<body>

    <!-- Current Project Tool -->
    <section id="CurrentProjectTool">
    </section><!-- end of Current Project tool -->

    <!-- Current Project Tool -->
    <script type="text/javascript">
    var id = 'CurrentProjectTool';
    var fasta = '<?= str_replace("\n", "\\n", $fasta); ?>';
    var geneSymbol = '<?= @$geneSymbol; ?>';
    var domains = `<?= $domainsResult?>`;
    var variations = [

    ]

    var viewer = new MSAViewer(id, fasta, geneSymbol, variations, domains);

      // TODO: Load these things from AJAX
       
       /* List the viewer.addVariation Data */
        <?php

        if(isset($geneInfoHuman['dbs']['NP'])):    
          $ClinVarQuery = getClinvarData($geneInfoHuman['dbs']['NP'], 'np_id');

          while ($row = @mysqli_fetch_array($ClinVarQuery)): 
              $position=$row['position'];
              if ($position == "" || $position == NULL) {
                	$position  = 0;
                }
              $variation=$row['variation'];
              $rs_number = $row['rs_number'];
              if($rs_number=="rs-1") {$rs_number="N/A";}
              $new_variation = str_replace(array('(',')'), '', $variation);
              $clinvarnote = 'Variation ID: '.$row['variation_id'].' <br> rsNumber: '.$rs_number.'<br> '.$new_variation.' <br> '.$row['variant_type'].' <br> '.$row['clinical_significance'].'<br>'.$row['phenotypes'];
        ?>
              viewer.addVariation(0, <?php echo $position; ?>, ' <?php echo str_replace("'", "\\'",$clinvarnote); ?>', 'ClinVar');
        <?php endwhile; ?>
        <?php endif;?>

       /* List the gnomAD Data */
        <?php
        if(isset($geneInfoHuman['dbs']['ENST'])):

            $gnomADQuery = getGnomADData($geneInfoHuman['dbs']['ENST'], 'canonical_transcript', 'position, variation, variant_id'.
                ',rs_number,consequence, allele_count, allele_number');

            while ($row = @mysqli_fetch_array($gnomADQuery)):
                $position=$row['position'];
                if ($position == "" || $position == NULL) {
                	$position  = 0;
                }
                $variation=$row['variation'];
                $new_variation = str_replace(array('(',')'), '', $variation);
                
                if($row['allele_count'] == 0) {
                	$freq = 0;
                }
                else {
                	$freq = round(($row['allele_count'] / $row['allele_number']), 6);	
                }

                $gnomADnote = 'Variation ID:<a style="color:#1976D2 !important; font-size:15px !important;" href="https://gnomad.broadinstitute.org/variant/'.$row['variant_id'].'" target="_blank"> '.$row['variant_id'].'</a> <br> Frequency: '.$freq.' <br> rsNumber: '.$row['rs_number'].'<br> '.$new_variation.' <br> '.$row['consequence'];
        ?>
            viewer.addVariation(0, <?php echo $position; ?>, ' <?php echo $gnomADnote; ?>', 'gnomAD');
        <?php endwhile; ?>
        <?php endif; ?>
       
        /* List the PTM Data */
        <?php

        if(isset($geneInfoHuman['dbs']['UNIPROT'])):
            $ptmQuery = getPtmData($geneInfoHuman['dbs']['UNIPROT'], 'acc_id', 'mod_rsd, ptm_type, position');

            while ($row = @mysqli_fetch_array($ptmQuery)):
                $position=$row['position'];
                if ($position == "" || $position == NULL) {
                	$position  = 0;
                }
                $PTMnote = 'Mod_rsd: '.$row['mod_rsd'].'<br>PTM Type: '.$row['ptm_type'];
        ?>
            viewer.addVariation(0, <?php echo $position; ?>, '<?php echo $PTMnote; ?>', 'PTM');
        <?php endwhile; ?>
        <?php endif; ?> 

        /* List the COSMIC Data */
 		<?php
        if(isset($geneInfoHuman['dbs']['ENST'])):
            $cosmicQuery = getCosmicData($geneInfoHuman['dbs']['ENST'], 'accession_number', 'position, mutation_aa, mutation_id'.',fathmm_prediction, fathmm_score, primary_site');

            while ($row = @mysqli_fetch_array($cosmicQuery)):
                $position=$row['position'];
                if ($position == "" || $position == NULL) {
                	$position  = 0;
                }
                $COSMICnote = 'Mutation: '.$row['mutation_aa'].'<br>'.$row['mutation_id'].' <br>'.$row['primary_site'].'<br>Fathmm:'.$row['fathmm_prediction'].'|'.$row['fathmm_score'].'<br>';
        ?>
            viewer.addVariation(0, <?php echo $position; ?>, '<?php echo str_replace("'", "\\'", $COSMICnote); ?>', 'COSMIC');
        <?php endwhile; ?>
        <?php endif; ?>   

      /* List the dbSNP Data */
 		<?php
        if(isset($geneInfoHuman['dbs']['ENST'])):
            $dbSNPQuery = getdbSNPData($geneInfoHuman['dbs']['ENST'], 'Feature', 'Protein_position, HGVSp, Impact, Consequence');

            while ($row = @mysqli_fetch_array($dbSNPQuery)):
                $position=$row['Protein_position'];
                if ($position == "" || $position == NULL) {
                	$position  = 0;
                }
                $proteinChanges = $row['HGVSp'];
                $clearProteinChange = explode(":", $proteinChanges)[1];
                $dbSNPnote = 'Mutation: '.$clearProteinChange.'<br>'.$row['Consequence'].' <br> Impact: '.$row['Impact'].'<br>';
        ?>
            viewer.addVariation(0, <?php echo $position; ?>, '<?php echo $dbSNPnote; ?>', 'dbSNP');
        <?php endwhile; ?>
        <?php endif; ?>  

      /* List the Mouse Variants */
      <?php
        $mouseQuery = getMouseVariantsData($mouse_transcript_id);

        while ($row = @mysqli_fetch_array($mouseQuery)):
        $position=$row['Position'];
        if ($position == "" || $position == NULL) {
        $position  = 0;
        }
        $mouseNote = 'Mutation: '.$row['aa_change'].' at ' .$position. '<br> Mutation Type: '.$row['mutation_type'] .'<br>Note:'.$row['pred_text'];
        ?>
        viewer.addVariation(1, <?php echo $position; ?>, '<?php echo $mouseNote; ?>', 'Mouse Variant');
      <?php endwhile; ?>
      
      viewer.loadDivsInViewport(true);

    </script>

</body>
</html>