<?php 
    require_once("db_connection.php"); 
    require_once("functions.php");
    #Capture the term searched
    $searchText = trim($_GET['q']);
    $searchText = preg_replace("#[^0-9a-z_\-.:]i#","", $searchText);
    if($searchText == ""){
        exit;
    }
       #Query Set | Dummy way , find a new path!!!!!!
    if(!isset($searchText)) {
        exit;
    }
        #Human Search Block
        #$queryForHuman = mysqli_query($db_connection, "SELECT gene_id, gene_symbol, protein_numbers FROM mapping_human WHERE gene_id='$searchText' OR gene_symbol='$searchText' OR FIND_IN_SET('$searchText', gene_synonyms) OR FIND_IN_SET('$searchText', protein_numbers) OR FIND_IN_SET('$searchText', other_ids)") or die ("Could not search");
    $geneId = null;

    if(strstr($searchText, 'NM_')){
        $geneId = getGeneIdbyDBId(explode('.', $searchText)[0]);

    } else if (strstr($searchText, 'NP_') && hasMSA($searchText)){
        $geneId = $searchText;
    }else if (substr($searchText, 0, 2) == 'rs'){

        $query = getClinvarData("'$searchText'", 'rs_number');
        if($query == null){
            $query = getGnomadData("'$searchText'", 'rs_number');
        }

        $row = @mysqli_fetch_assoc($query);
        if(isset($row['nm_id'])){
            $geneId = $row['np_id']; 

        } else if (isset($row['canonical_transcript'])) {
           $geneId = $row['canonical_transcript'];    
        }
    }

    if($geneId != NULL){
        header("location:{$GLOBALS['base_url']}msa?id={$geneId}");
        exit;
    }

    $proteinsBySpecies = searchProteinNumbers($searchText);

    #$countForHuman = mysqli_num_rows($queryForHuman);
    #if($countForHuman == 0) {$preResultForHuman='<a href="#" class="btn waves-effect waves-light preResultBtnEmpty">No finding for "'.$searchText.'"</a>';}


    #$searchText="INS";

    #SELECT DISTINCT gnomad.canonical_transcript,mapping_human.gene_symbol, mapping_human.gene_id, mapping_human.protein_numbers FROM mapping_human INNER JOIN genes_unique ON mapping_human.gene_id=genes_unique.gene_id INNER JOIN gnomad ON genes_unique.canonical_transcript=gnomad.canonical_transcript WHERE FIND_IN_SET('HEL-S-87p', mapping_human.gene_synonyms)

    #

    require("header.php"); 

?>


<!-- CurrentProject PreResult Page -->
<div class="container pageBox">
    <!-- <p class="purple-text text-lighten-1">*Select an organism to continue. If they are homolog, they will be demonstrated on the same page.</p>-->
    <?php if(empty($proteinsBySpecies)):?>
        <a href="#" class="btn waves-effect waves-light preResultBtnEmpty">No finding for "<?= $searchText; ?>"</a>
    <?php endif;?>
    
    <?php foreach($proteinsBySpecies as $species=>$proteinsInSpecies): ?>
        <div class="row">
            <div class="pageTitle">Results for '<i><?php echo $searchText; ?></i> ' in <i> <?= $species; ?> </i>
            	<?php if ($species=="Homo sapiens") {echo '<a href="#preResult_help" class="modal-trigger tooltipped" data-position="right" data-delay="0" data-tooltip="Searching Result and Alignments"><i class="material-icons blue-text text-darken-1">help</i></a>';} ?>
            	<hr></div>
            <div class="col s12 m12 l12">
                <?php foreach($proteinsInSpecies as $data): ?>
                    <a target="_blank" href="<?= $GLOBALS['base_url']; ?>msa?id=<?= $data['transcript_id']; ?>" class="btn waves-effect waves-light preResultBtn"><?= $data['transcript_id']; ?> | GeneID: <?= $data['ncbi_gene_id']; ?> | <i><?= $data['gene_symbol']; ?></i></a>
                <?php endforeach;?>
            </div>
            
            <div class="col s12 m12 l2"></div>
        </div>
    <?php endforeach;?>
</div> <!-- end of preResult page -->

<!-- Modal Structure -->
<div id="preResult_help" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h4>Searching Result and Alignments</h4>
      <p>
        When a human gene was assigned to multiple orthologs in other organisms, protein sequences from other organisms with higher levels of sequence identity were displayed in multiple species alignment of orthologs. For example, <i>H3F3A</i> has multiple orthologs in <i>C. elegans</i> which are <i>his-69, his-70, his-71, his-72, his-73, cpar-1, his-74,</i> and <i>F20D6.9</i>. <i>C. elegans</i> his-71 possessing the higher levels of sequence identity with H3F3A among other <i>C. elegans</i> H3F3A orthologs will be displayed when you enter H3F3A. However,  you can also enter <i>his-69, his-70, his-72,his-73, cpar-1, his-74, or F20D6.9 </i> genes  individually for multiple species alignment with H3F3A.  
      </p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat blue white-text">Agree</a>
    </div>
</div>

<script type="text/javascript">
	$('.modal').modal();
</script>


<?php require("footer.php"); ?>