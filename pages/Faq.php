<!DOCTYPE html>
<?php require("../header.php"); ?>

<br>
<div class="container">
  <div class="col s12 m12 l12"><h4 class="blue-text text-darken-1 center">Frequently Asked Questions (FAQs)</h4></div>
    <input type="text" class="quick_search faq" id="quick_search_faq" placeholder="Search in the frequently asked questions">

	<div class="col s12 m12 l12 grey white-text" id="noResultQS_faq"><div class="collapsible-header"><span class="flow-text">No Question or Answer Found. <br> You can search again using different keywords or check the previously asked question below manually.</span></div></div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>What is ConVarT?</div>
      <div class="collapsible-body"><span>The conserved clinical variation visualization tool (ConVarT) is an online visualization resource developed by the Kaplan Lab, with the goal of displaying the evolutionary conservation of amino acid substitutions associated with diseases and non-clinically significant amino acid substitutions from ClinVar, COSMIC and gnomAD databases, and allowing easy access to collections of conserved genes and conserved variants associated with human diseases for the model organism research community. In addition, PTMs (post-translational modifications) are also integrated into the corresponding positions of amino acids on the human protein sequences.</span></div><br>
    </div> 

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>What does ConVarT stand for?</div>
      <div class="collapsible-body"><span>The Conserved clinical Variation visualization Tool</span></div><br>
    </div> 

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>How may I use ConVarT?</div>
      <div class="collapsible-body"><span>
        Visualizing the presence of human genetic variants in the corresponding positions of the protein-encoding regions in other organisms currently remains to be difficult to handle with the available databases. ConVarT has integrated systematically human genetic variants from ClinVar, gnomAD and COSMIC into the corresponding positions of the protein-encoding regions in other organisms including Chimps, Macaca, Mice, Rat, Zebrafish, Xenopus, Drosophila, and <i>C. elegans</i>. In addition, PTMs (post-translational modifications) are also incorporated into the protein sequences and protein domains were generated from Pfam and joined with multiple sequence alignments. <br><br>On ConVarT, there are two different methods to make a search. Firstly, gene identifiers such as gene name, synonyms or species-specific gene-identifiers like MGI ID, FlyBase ID, etc. or "rs" number can be used simply in the search box on ConVarT. The detailed list of identifiers that can be used as an input in the search box is listed below table.
        <br><br><table class="special_table"> <tbody><tr><th>Identifier Name</th> <th>Description</th> </tr><tr> <td>NCBI Gene ID</td> <td>NCBI Gene ID (e.g. 6301)</td> </tr> <tr> <td>Gene Symbol</td> <td>Current Gene Symbol (e.g. <i>SARS</i>, <i>sars-1</i>)</td> </tr> <tr> <td>Gene Synonmys</td> <td>Previous Gene Symbols (e.g. <i>SERRS</i>)</td> </tr> <tr> <td>HGNC</td> <td>HUGO Gene Nomenclature Committee (e.g. HGNC:10537)</td> </tr> <tr> <td>ENSEMBL GENE ID</td> <td>ENSEMBL Gene IDs for any specie in our database <br> (e.g. ENSG00000031698, ENSMUSG00000068739, ENSRNOG00000020255,<br> ENSDARG00000008237, ENSPTRG00000001043, ENSMMUG00000021837)</td> </tr> <tr> <td>RS Number</td> <td>Reference SNP ID (e.g. rs1553178049)</td> </tr> <tr> <td>Protein Acc. Number</td> <td>NCBI Protein Accession Numbers (e.g. NP_006504.2, XP_006233198.1)</td> </tr> <tr> <td>MGI ID</td> <td>MGI-Mouse Genome Informatics (e.g. MGI:102809)</td> </tr> <tr> <td>ZFIN ID</td> <td>ZFIN The Zebrafish Information Network (e.g. ZDB-GENE-040831-1)</td> </tr> <tr> <td>FB Gene ID</td> <td>FlyBase Gene ID (e.g. FBgn0031497)</td> </tr> <tr> <td>WB Gene ID</td> <td>WormBase Gene ID(e.g. WBGene00005663)</td> </tr> </tbody></table>
        <br><br>Secondly, we have developed a novel and innovative way to integrate unknown protein sequences into the ConVarT. Simply, any protein sequences without knowing gene identifiers can be copied and pasted into the search box on the ConVarT. This will use the protein blast (BLASTp) find the closest corresponding human protein sequence with the alignment of these two sequences, followed by the integration of relevant human genetic variants and PTMs. Protein domains from Pfam will be displayed together with human genetic variants and PTMs.
      </span><br><br>
      <div class="material-placeholder"><img class="materialboxed " width="48%" src="../files/img/searchScreen.png" data-caption="Search screen in ConVarT"></div> 
    </div> <br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>Which model organisms are included in the database?</div>
      <div class="collapsible-body"><span>Human, chimp, macaca, zebrafish, mouse, rat, Xenopus, Drosophila and <i>C. elegans</i>.
        <br> However, with the new search feature, it is no longer limited to these model systems and any protein sequence from model systems can be searched with the visualization of human genetic variants and PTMs. </span></div> <br>
    </div>

	<div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>Which protein database is used in protein sequences to identify domains?</div>
      <div class="collapsible-body"><span>ConVarT has used the Pfam protein family database to automatically generate from human sequence data with a threshold of 1e-01 and they are then systematically integrated into the corresponding regions. Protein domains for human are illustrated in the multiple sequence alignments. In addition, the Pfam protein domain page can be accessed by clicking the domain illustration over the sequence or the links listed in the domain table.</span></div><br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>Is it possible to retrieve the protein sequence used in multiple sequence alignments?</div>
      <div class="collapsible-body"><span>Yes, multiple sequence alignment section has a fixed species names on the left side. Clicking on the name of each species will direct you to the sequence and protein ID used.</span></div><br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>How the disease-associated genes data was generated?</div>
      <div class="collapsible-body"><span>The disease-associated genes were obtained from <a href="http://disgenet.org/downloads" target="_blank">DisGeNET</a> database.</span></div><br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>What is the 'Conservation Scores' ?</div>
      <div class="collapsible-body"><span>
       Protein sequences of a gene have undergone constant evolutionary changes, although certain amino acids at critical positions have been preserved throughout evolution. For example, while most protein sequences encoded by histone genes are highly preserved throughout evolution, protein sequences of some proteins have undergone dramatic changes. A number of the amino acid substitutions may alter the protein functions (decrease or increase in function of proteins) and can not be tolerated due to the functional significance of these residues, thus leading a range of genetic diseases. Sequence alignment is one of the key bioinformatics techniques for revealing evolutionary relationships between the sequences. With the sequence alignment, the percentage of identical and similar amino acids can be identified. 
       <br><br>Here, ConVarT has provided two different score types namely identity score and identity score for variation associated amino acids. Firstly, the identity score represents the percentage of identical amino acids of a gene in a species as compared to the human protein sequence. The protein sequences of each human gene were aligned with protein sequences of ortholog genes from other species using ClustalW sequence alignment algorithm. Afterward, the identity score for each protein transcript is determined by dividing the total number of identically matching aligned amino acids into the total number of amino acids. Secondly, the identity score for variation associated amino acids is calculated by the same approach. Simply, the number of amino acids with variation(s) was used instead of the number of all amino acids per number of all amino acids with variation(s).

        <br><br> 
        <b>Here is the interactive bar chart of conservation scores per gene on the gene page in ConVarT:</b>
      </span>
      <div class="material-placeholder"><img class="materialboxed " width="98%" src="../files/img/conScoresPage.png" data-caption="Conservation Scores"></div>
    </div><br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>How to obtain all the orthologous genes between human and other species?</div>
      <div class="collapsible-body"><span>
      	We compiled Chimp, Macaca, Mouse, Rat, and Xenopus orthologous proteins from 
      	<a href="http://www.informatics.jax.org/homology.shtml" target="_blank">MGI Homology List</a>. When we noticed that many Chimp and Macaca genes do not have human counterparts, we performed BLASTp analysis for the remaining genes. <a href="https://www.flyrnai.org/cgi-bin/DRSC_orthologs.pl" target="_blank">DIOPT (DRSC Integrative Ortholog Prediction Tool)</a> was used to find human orthologs in Drosophila while human orthologs for <i>C. elegans</i> were generated with an in-house BLASTp pipeline together with the integration of literature search. For Zebrafish and human orthology matching, <a href="https://zfin.org/downloads/human_orthos.txt" target="_blank">https://zfin.org/downloads/human_orthos.txt</a> database was used. All the resulting the orthology gene list was compiled to achieve high accuracy in orthology, which is essentially needed for comparative analyses. 
      </span></div><br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>Can we access ConVarT via mobile phones or tablets?</div>
      <div class="collapsible-body"><span>Yes, they can be used but a laptop or desktop will provide a better visualization and access to data. </span></div> <br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i> Can we make search with our own protein sequence to visualize the human genetic variants?</div>
      <div class="collapsible-body"><span>Yes, ConVarT enables researchers to search any protein sequence from any species and rapidly combines search with the integration of human genetic variants. For example, <i>S. cerevisiae</i> is not currently represented in the ConVarT. However, when <a href="https://www.ncbi.nlm.nih.gov/protein/NP_013890.1?report=fasta" target="_blank">the protein sequence of <i>S. cerevisiae MLH1</i></a>, the human homolog of MLH1, is copied and pasted on the ConVarT, ConVarT will discover human homolog of the protein sequence you've searched for and then enable you to view the pairwise sequence alignment of human homolog and the protein sequence you searched with integration of human genetic variants and PTMs.</span>
      <br><br>
      <video class="responsive-video" controls> <source src="../files/img/seqSearchVideo.mp4" type="video/mp4"> </video>  
      </div> <br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>I am facing a problem on the result page. What can I do?</div>
      <div class="collapsible-body"><span>We would be happy if you can fill "Feedback Form" via the feedback button on the right bottom corner of the result page.
      </span></div> <br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>How often do you update the data sets used in ConVarT?</div>
      <div class="collapsible-body"><span>We are currently in the process of automating the update of the data sets from ClinVar, gnomAD, COSMIC and PTMs.</span></div> <br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>What if my variation does not appear in ConVarT?</div>
      <div class="collapsible-body"><span>It probably implies that the variant you have searched for may not be in protein-encoding regions, and ConVarT displays only the variations on the protein-encoding sequences. However, if your variant is in the protein encoding region and does not appear in ConVarT, please do not hesitate to contact us through our <a href="Contact.php">Contact page</a>. </span></div> <br>
    </div>

    <div class="col s12 m12 l6 faq_question">
      <div class="collapsible-header"><i class="material-icons">question_answer</i>Will there be a development process in ConVarT ?</div>
      <div class="collapsible-body"><span>Yes, we are currently integrating additional resources that are publicly available and please feel free to contact us if you have any suggestions. For collaboration, please reach us out at 
      <a href="mailto:oktay.kaplan@agu.edu.tr">oktay.kaplan (at)agu.edu.tr</a> .</span></div> <br>
    </div>

</div>
<br><br>

<?php require("../footer.php"); ?>
