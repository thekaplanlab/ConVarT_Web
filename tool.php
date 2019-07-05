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
</head>
<body>

    <!-- Current Project Tool -->
    <section id="CurrentProjectTool">
            <!--Current Project | Domains and Sequences Parts -->
            <section id="Domain-Sequence">
                
                <!-- Current Project | Protein Domains for Human -->
                <section id="prLength">
                    <?php echo $domainsResult; ?>
                </section> <!-- end of domains -->

                <!-- Current Project | Protein Sequences -->
                <section id="sequence">
                    <div id="aaInfo"></div>
                </section> <!-- end of protein sequences -->

            </section> <!-- end of domain and sequences parts -->

            <!--Gene name and specie names-->
            <section id="specieAndGenNameContainer">
                <div id="geneName"><br>Human: <?php echo @$geneSymbol; ?></div>
                <div id="specieNames"></div>
            </section>
            <section id="GoToPosition">
                Search # <input type="number" placeholder="Type a human position" name="position"> <br>
                <script type="text/javascript">
                  function positionKeyUp(){
                      var position =$('input[name=position]').val();
                      var alignmentPosition = getAminoacidPositionInViewport(0, position-1);
                   
                      $('#position-number').remove();
                      $('.highlight-column').removeClass('highlight-column');
                      $('.ptmHighlighted').removeClass('ptmHighlighted');
                      $('#protein0').append('<div class="highlight-column" id="position-number" style="left:'+(alignmentPosition*20)+'px">'+position+'</div>');

                      $('#CurrentProjectTool').scrollLeft(alignmentPosition*20 - ($('#CurrentProjectTool').width()-160)/2)

                      setTimeout(function() {
                        $('.i-'+alignmentPosition).addClass('highlight-column');
                        $('#protein0 .ptm.i-'+alignmentPosition).addClass('ptmHighlighted');
                      }, 75);
                  
                  }
                  $('input[name=position]').on("keyup", positionKeyUp);
                </script>
            </section>
            <br><br>
    </section><!-- end of Current Project tool -->

   
 

    <!-- Current Project Tool -->
    <script type="text/javascript">

/*

    
   _____                          _     _____           _           _   
  / ____|                        | |   |  __ \         (_)         | |  
 | |    _   _ _ __ _ __ ___ _ __ | |_  | |__) | __ ___  _  ___  ___| |_ 
 | |   | | | | '__| '__/ _ \ '_ \| __| |  ___/ '__/ _ \| |/ _ \/ __| __|
 | |___| |_| | |  | | |  __/ | | | |_  | |   | | | (_) | |  __/ (__| |_ 
  \_____\__,_|_|  |_|  \___|_| |_|\__| |_|   |_|  \___/| |\___|\___|\__|
                                                      _/ |              
                                                     |__/               
    CURRENT PROJECT v0.1
    Jan, 2019

*/

    // ClinVar Box Position

    var clinvarNotes = {};
    var ptmNotes = {};

    function ClinVar(protein, aminoacid, variationNote, source) {
      let prNumber = protein - 1; // the proteins start from 0
      let aaNumber = aminoacid - 1; // the aacids start from 0

      if(clinvarNotes[aaNumber] == undefined) {

         clinvarNotes[aaNumber] = {};
         clinvarNotes[aaNumber][source] = "";
        
         //selectAA.addEventListener("mouseout", closeClinVarBox);

      }  else if(clinvarNotes[aaNumber][source] == undefined) {
        clinvarNotes[aaNumber][source] = "";
      }

      clinvarNotes[aaNumber][source] += "<br>" + variationNote;

      if (source == "PTM") {
        ptmNotes[aaNumber] +=  aminoacid + 1;
        //console.log(ptmNotes);
      }
      
    }

    // ClinVar Box Info
    function ClinVarInfo(prNumber, aaNumber) {
      let ClinVarTextBox = document.createElement("div");
      let ClinVarInnerTextBox = document.createElement("div"); // YENI -> ClinVar Notu Uzunsa Scroll Yaptırmak için
      ClinVarTextBox.setAttribute("id", "ClinVarTextBox");
      ClinVarInnerTextBox.setAttribute("id", "ClinVarInnerTextBox");

      for(var source in clinvarNotes[aaNumber]) {
        ClinVarInnerTextBox.innerHTML += "<h3>"+source+"</h3>"  + clinvarNotes[aaNumber][source];
        //console.log(source);
      }
      var aaInfoBox = document.getElementById('aaInfo');
      aaInfoBox.innerHTML = '';
      aaInfoBox.appendChild(ClinVarTextBox).appendChild(ClinVarInnerTextBox); // time to insert the ClinVarTextBox into aaInfoBox | eski: aaInfoBox.appendChild(ClinVarTextBox)
      $("#ClinVarInnerTextBox").mouseleave(function(e) {
        closeClinVarBox();
      });    

      offsetX = getOffset(prNumber, aaNumber);
      var container = document.getElementById("protein0");

      if(container.scrollWidth < (offsetX +600) ) {
        offsetX = offsetX - 340;

        ClinVarTextBox.className = "rightArrow";
      }
      let specificPositionforCVBox = "top: " + (prNumber * 20 - 13) + "px;" + "left: " + (offsetX) + "px;  box-shadow:#555 1px 1px 5px 3px;";
      document.getElementById('aaInfo').childNodes[0].style.cssText = specificPositionforCVBox;

    }

    function getOffset(prNumber, aaNumber){
      var indexOfAA = getAminoacidPositionInViewport(prNumber, aaNumber);
      var container = document.getElementById("protein0");

      var offsetX = indexOfAA * 20 + 30;

      return offsetX;
    }
    /* Close ClinVar Box */
    function closeClinVarBox(ClinVarNote) {
      var aaInfo = document.getElementById('aaInfo');
      aaInfo.innerHTML = "";
      
    } 
    var rawSequence = '<?= str_replace("\n", "\\n", $fasta); ?>';

    // Load Alignment File
    function loadAlignmentTXT() {
      
      loadSeq(rawSequence);

    }
    var isInitialized = false;
    var processedSequences = [];
    var loadedPositions = [];
    var viewportToAANumber = []
    // Core Code
    function loadSeq(txt) {
      let startPr, endPr, startPr2 = -1;
      let j = 0;

      do {
          startPr = txt.indexOf(">", startPr + 1);
          endPr = txt.indexOf("\n", startPr + 1);
          startPr2 = txt.indexOf(">", startPr + 1);
          let seq1 = txt.slice(endPr + 1, startPr2);
          //removing new line characters inside seq1
          seq1 = seq1.replace(/\s/g, "");
          let proteinId = "protein" + [j];

          if(j == 0){
            aa_ind = 0;
            for(ind = 0; ind < seq1.length; ind++) {
              
              if(seq1.charAt(ind) == '-'){
                viewportToAANumber.push(-1);
                continue;
              } else {
                viewportToAANumber.push(aa_ind);
                aa_ind++;
              }
            }
          }

          processedSequences[j] = seq1;
          //Protein Name-Identifier
          let prName = txt.slice(startPr+1, endPr + 1);
          let prID = prName.split(" ")[0]; // 7 MART
          let species = prName.split("[").slice(-1)[0].split("]")[0];  
          let species_by_word = species.split(" ");
          species = species_by_word[0][0]+". " + species_by_word[1]; 
          //Protein sequences slicing
          
          //console.log(seq1.length);
          var proteinLengthforDomain = "width:" + seq1.length*20 + "px;"; // every aacid inside a box in 20x20 size
          //console.log(proteinLengthforDomain);
          document.getElementById("prLength").style=proteinLengthforDomain;
          //creating flex container for proteins
          let protein = document.createElement("section");
          document.getElementById("sequence").appendChild(protein); 
          protein.id = proteinId;
          protein.className="protein";
          j++;
          // write the name of the species and genes
          var SpecieName = document.createElement("div");
          var SpecieNameLink = document.createElement("a");
          var prType = prName.substring(0, 2);
          console.log('hey', prType);
          if (prType == "NP" || prType == "XP" ) {
          	SpecieNameLink.setAttribute("href", "https://www.ncbi.nlm.nih.gov/protein/" + prID);
          }
          if (prType == "EN") {
          	SpecieNameLink.setAttribute("href", "https://www.ensembl.org/id/" + prID);
          }
          regexPattern = "[OPQ][0-9][A-Z0-9]{3}[0-9]|[A-NR-Z][0-9]([A-Z][A-Z0-9]{2}[0-9]){1,2}"
          if (prName.search(regexPattern) != "-1") {
		  	prID = prName.split(" ")[1];
		  	SpecieNameLink.setAttribute("href", "https://www.uniprot.org/uniprot/" + prID);
		  	species_by_word = prName.split("=")[1].split(" ");
		  	species = species_by_word[0][0]+". " + species_by_word[1];
		  }

          regexPattern = "[OPQ][0-9][A-Z0-9]{3}[0-9]|[A-NR-Z][0-9]([A-Z][A-Z0-9]{2}[0-9]){1,2}"
          if (prName.search(regexPattern) != "-1") {
		  	prID = prName.split(" ")[1];
		  	SpecieNameLink.setAttribute("href", "https://www.uniprot.org/uniprot/" + prID);
		  	species_by_word = prName.split("=")[1].split(" ");
		  	species = species_by_word[0][0]+". " + species_by_word[1];
		  }

          SpecieNameLink.setAttribute('target', '_blank');
          SpecieNameLink.setAttribute('class','tooltipped');
          SpecieNameLink.setAttribute('data-position','right');
          SpecieNameLink.setAttribute('data-tooltip',prName);

          document.getElementById("specieNames").appendChild(SpecieName).appendChild(SpecieNameLink);
          SpecieName.className = "specieName";
          SpecieNameLink.appendChild(document.createTextNode(species)); //// exception case olacak 
        
      } while (startPr2 != -1);
      for(i = 0; i < processedSequences[0].length; i++){
        loadedPositions.push(false);
      }
      // TODO: Load these things from AJAX
       
       /* List the ClinVar Data */
        <?php

        if(isset($geneInfoHuman['dbs']['NP'])):    
          $ClinVarQuery = getClinvarData($geneInfoHuman['dbs']['NP'], 'np_id');

          while ($row = @mysqli_fetch_array($ClinVarQuery)): 
              $position=$row['position'];
              $variation=$row['variation'];
              $rs_number = $row['rs_number'];
              if($rs_number=="rs-1") {$rs_number="N/A";}
              $new_variation = str_replace(array('(',')'), '', $variation);
              $clinvarnote = 'Variation ID: '.$row['variation_id'].' <br> rsNumber: '.$rs_number.'<br> '.$new_variation.' <br> '.$row['variant_type'].' <br> '.$row['clinical_significance'].'<br>'.$row['phenotypes'];
        ?>
              ClinVar(1, <?php echo $position; ?>, ' <?php echo str_replace("'", "\\'",$clinvarnote); ?>', 'ClinVar');
        <?php endwhile; ?>
        <?php endif;?>
       
       /* List the gnomAD Data */
        <?php
        if(isset($geneInfoHuman['dbs']['ENST'])):

            $gnomADQuery = getGnomADData($geneInfoHuman['dbs']['ENST'], 'canonical_transcript', 'position, variation, variant_id'.
                ',rs_number,consequence, allele_count, allele_number');

            while ($row = @mysqli_fetch_array($gnomADQuery)):
                $position=$row['position'];
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
            ClinVar(1, <?php echo $position; ?>, ' <?php echo $gnomADnote; ?>', 'gnomAD');
        <?php endwhile; ?>
        <?php endif; ?>
       
        /* List the PTM Data */
        <?php

        if(isset($geneInfoHuman['dbs']['UNIPROT'])):
            $ptmQuery = getPtmData($geneInfoHuman['dbs']['UNIPROT'], 'acc_id', 'mod_rsd, ptm_type, position');

            while ($row = @mysqli_fetch_array($ptmQuery)):
                $position=$row['position'];
                $PTMnote = 'Mod_rsd: '.$row['mod_rsd'].'<br>PTM Type: '.$row['ptm_type'];
        ?>
            ClinVar(1, <?php echo $position; ?>, '<?php echo $PTMnote; ?>', 'PTM');
        <?php endwhile; ?>
        <?php endif; ?> 

        /* List the COSMIC Data */
 		<?php
        if(isset($geneInfoHuman['dbs']['ENST'])):
            $cosmicQuery = getCosmicData($geneInfoHuman['dbs']['ENST'], 'accession_number', 'position, mutation_aa, mutation_id'.',fathmm_prediction, fathmm_score, primary_site');

            while ($row = @mysqli_fetch_array($cosmicQuery)):
                $position=$row['position'];
                $COSMICnote = 'Mutation: '.$row['mutation_aa'].'<br>'.$row['mutation_id'].' <br>'.$row['primary_site'].'<br>Fathmm:'.$row['fathmm_prediction'].'|'.$row['fathmm_score'].'<br>';
        ?>
            ClinVar(1, <?php echo $position; ?>, '<?php echo str_replace("'", "\\'", $COSMICnote); ?>', 'COSMIC');
        <?php endwhile; ?>
        <?php endif; ?>    

    }

    function loadDivsInViewport(){
          var viewportOffset = document.getElementById('sequence').getBoundingClientRect();

          var aminoacid_index = 0;
         
          startX = parseInt((Math.abs(viewportOffset.left) - document.getElementById('specieAndGenNameContainer').clientWidth)/20 - window.innerWidth/40) ;
          if(startX < 0){
            startX = 0;
          }
          endX = parseInt(startX+(document.getElementById('specieAndGenNameContainer').clientWidth)/20 + 3*window.innerWidth/40 + 20) ;
          
          if(processedSequences[0].length <= endX) {
            endX = processedSequences[0].length-1;
          }

          
          for(j = 0; j < processedSequences.length; j++){
            seq1 = processedSequences[j];
             var documentFragment = document.createDocumentFragment();
            for (i = startX; i < endX; i++) {

              if(loadedPositions[i] && seq1.length < 5000){
                  continue;
              }
              let aaBox = document.createElement("div");
              //reading protein sequence letter by letter
              var aaLetter = seq1.charAt(i);
              //creating amino acid boxes
              
              if(aaLetter != '-'){
                  aminoacid_index+= 1;
                  
                  aaBox.className = "i-"+i;  
              }
              if(aaLetter == '-'){
                continue;
              }

              if(j == 0 && viewportToAANumber[i] != -1 && viewportToAANumber[i] in clinvarNotes){
                 aaBox.className += " specialAa";
              }

              //console.log(ptmNotes);

              if(j == 0 && viewportToAANumber[i] != -1 && viewportToAANumber[i] in ptmNotes){
                 aaBox.className += " ptm";
              }



              aaBox.innerHTML = aaLetter;
              aaBox.style.cssText  = 'left:'+(i*20)+'px;';
              //giving the proper color to each amino acid
              if (aaLetter.includes("M") === true || aaLetter.includes("C") === true) {aaBox.style.cssText += "background-color:#a5a513"};
              if (aaLetter.includes("A") === true) {aaBox.style.cssText += "background-color:#C8C8C8"};
              if (aaLetter.includes("L") === true || aaLetter.includes("V") === true || aaLetter.includes("I") === true) {aaBox.style.cssText += "background-color:#0F820F"};
              if (aaLetter.includes("D") === true || aaLetter.includes("E") === true) {aaBox.style.cssText += "background-color:#E60A0A"};
              if (aaLetter.includes("K") === true || aaLetter.includes("R") === true) {aaBox.style.cssText += "background-color:#145AFF"};
              if (aaLetter.includes("S") === true || aaLetter.includes("T") === true) {aaBox.style.cssText += "background-color:#FA9600"};
              if (aaLetter.includes("F") === true || aaLetter.includes("Y") === true) {aaBox.style.cssText += "background-color:#3232AA"};
              if (aaLetter.includes("N") === true || aaLetter.includes("Q") === true) {aaBox.style.cssText += "background-color:#00DCDC"};
              if (aaLetter.includes("G") === true) {aaBox.style.cssText += "background: #EBEBEB; color: #777;"};
              if (aaLetter.includes("W") === true) {aaBox.style.cssText += "background-color:#B45AB4"};
              if (aaLetter.includes("-") === true) {aaBox.style.cssText += "background-color:#333"};
              if (aaLetter.includes("H") === true) {aaBox.style.cssText += "background-color:#8282D2"};
              if (aaLetter.includes("P") === true) {aaBox.style.cssText += "background-color:#DC9682"};
              documentFragment.appendChild(aaBox);
              aaBox = null;
          }
              let element = document.getElementById('protein'+j);
              if(seq1.length >= 5000){
                element.innerHTML = '';  
              }
              element.appendChild(documentFragment);
              documentFragment.innerHTML='';
          }
          
          for(i = 0; i < seq1.length; i++){
            if(i >= startX && i < endX)
              loadedPositions[i] = true;
            else if(seq1.length >= 5000)
              loadedPositions[i] = false;
          }  
          
          
    }
    // Time To Run
    loadAlignmentTXT();
    loadDivsInViewport();
    $('#CurrentProjectTool').scroll(function() {
      loadDivsInViewport();
    })
    function getAminoacidPositionInViewport(species_id, position){
      var sequence = processedSequences[species_id];
      var aminoacid_index = 0;
      for(i = 0; i< sequence.length; i++){
        if(sequence.charAt(i) == '-')
          continue;
        if(aminoacid_index == position){
          return i;
        }
        if(sequence.charAt(i) != '-'){
          aminoacid_index++;
        }
        
      }
      return -1;
    }

    function scrollIfNeeded(element, container) {

      const halfClientWidth = container.clientWidth / 2;
      if (element.offsetLeft < container.scrollLeft-200) {
        container.scrollLeft = element.offsetLeft - halfClientWidth;
      } else {
        const offsetRight = element.offsetLeft + element.offsetWidth;
        const scrollRight = container.scrollLeft + container.offsetWidth;
        if (offsetRight+200 > scrollRight) {
          container.scrollLeft = offsetRight - halfClientWidth;
        }
      }
    }

    $('.domain').each(function() {
      //console.log($(this).data('start-point'), );
      startPosition = getAminoacidPositionInViewport(0, parseInt($(this).data('start-point'))-1);

      width = getAminoacidPositionInViewport(0, parseInt($(this).data('end-point'))-1) - startPosition;

      $(this).css('display', 'flex');
      $(this).css('left', (startPosition*20)+'px');
      $(this).width((width*20)+'px');
    })
    $(document).on('mouseover', '.specialAa', function(){
        ClinVarInfo(0, viewportToAANumber[parseInt($(this).attr('class').split(' ')[0].split('-')[1])]);
     });
    $('.protein').width(($('#prLength').width())+'px');
    $('#sequence').width(($('#prLength').width())+'px');
    </script>

</body>
</html>
