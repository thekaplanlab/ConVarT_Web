<!DOCTYPE html>
<?php require("../header.php"); ?>
<style type="text/css">
  #materialbox-overlay {background: #d6d6d6 !important;}
</style>

<div class="container pageBox white z-depth-2">
<div class="row specialTextBox">
    <h4 class="blue-text text-darken-1 center">ClinVar and Gene Homology</h4>
    <p>In order to recieve or download the high-resolution image of graphs, please click the graphs you interested in.</p>
    <br><hr><br>
    <div class="row">
      
      <div class="col s12 m12 l6 white">
        <b>Distribution of the homologs of the genes in ClinVar across the species: </b><br><br>
        <p>Firstly, we compiled Chimp, Macaca, Mouse, Rat, and Xenopus orthologous proteins from 
        <a href="http://www.informatics.jax.org/homology.shtml" target="_blank">MGI Homology List</a>. When we noticed that many Chimp and Macaca genes do not have human counterparts, we performed BLASTp analysis for the remaining genes. <a href="https://www.flyrnai.org/cgi-bin/DRSC_orthologs.pl" target="_blank">DIOPT (DRSC Integrative Ortholog Prediction Tool)</a> was used to find human orthologs in Drosophila while human orthologs for <i>C. elegans</i> were generated with an in-house BLASTp pipeline together with the integration of literature search. For Zebrafish and human orthology matching, <a href="https://zfin.org/downloads/human_orthos.txt" target="_blank">https://zfin.org/downloads/human_orthos.txt</a> database was used. All the resulting the orthology gene list was compiled to achieve high accuracy in orthology, which is essentially needed for comparative analyses. 
        <br><br> 
        We then obtained all human gene IDs from the ClinVar file <a href="ftp://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/" target="_blank">downloaded</a> in February 2019 and matched them in other species with their counterparts using the homology curation table explained above. We are currently in the process of automating the update of the data sets from ClinVar, gnomAD, COSMIC and PTMs. The codes are also available in <a href="#GithubLink" target="_blank">our GitHub repository</a>. 
        </p>
      </div>

       <div class="col s12 m12 l6 white">
        <div class="material-placeholder"><img class="materialboxed" width="98%" src="../files/img/clinvarByOrganisms.png" data-caption="Distribution of the homologs of the genes in ClinVar across the species."></div>
      </div>

    </div>
</div>
</div><br>

<?php require("../footer.php"); ?>
