<!DOCTYPE html>
<?php require("../header.php"); ?>

<div class="container pageBox white z-depth-2">
<div class="row specialTextBox">
    <h4 class="blue-text text-darken-1 center">PTM, gnomAD and COSMIC Statistics</h4>
    <p>In order to recieve or download the high-resolution image of graphs, please click the graphs you interested in.</p>
    <br><hr><br>
    <div class="row">
      
		<div class="col s12 m12 l6">
			<span class="flow-text">Distribution of post-translational modifications (PTMs) from <a href="https://www.phosphosite.org/" target="_blank"> PhosphoSitePlus. </a></span>
			<p>"Others" include 2122 PTMs in O-GalNAc and 429 in O-GlcNAc. </p>
		  	<div class="material-placeholder"><img class="materialboxed" width="98%" src="../files/img/ptm.png" data-caption="Distribution of post-translational modifications (PTMs) from PhosphoSitePlus."></div>
	    </div>  

		<div class="col s12 m12 l6">
			<span class="flow-text">Distribution of FATHMM predictions of human genetic variations from <a href="https://cancer.sanger.ac.uk/cosmic" target="_blank"> COSMIC. </a></span>
	  		<div class="material-placeholder"><img class="materialboxed" width="98%" src="../files/img/cosmic.png" data-caption="Distribution of FATHMM predictions of human genetic variations from COSMIC."></div>
	    </div>

	    <div class="col s12 m12 l12">
			<span class="flow-text">Venn diagram of overlapping human genetic variation catalogs (<a href="https://www.ncbi.nlm.nih.gov/clinvar/" target="_blank">ClinVar</a>, <a href="https://cancer.sanger.ac.uk/cosmic" target="_blank">COSMIC</a>, and <a href="http://gnomad.broadinstitute.org" target="_blank">gnomAD</a>.) by investigating their both positions and amino acid substitutions.</span>
	  		<div class="material-placeholder"><img class="materialboxed" width="38%" src="../files/img/variantCatalogs.png" data-caption="Venn diagram of overlapping human genetic variation catalogs (ClinVar, COSMIC and gnomAD) by investigating their both positions and amino acid substitutions."></div>
	    </div>

    </div>
</div>
</div><br>

<?php require("../footer.php"); ?>
