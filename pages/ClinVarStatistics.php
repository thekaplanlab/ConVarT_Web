<!DOCTYPE html>
<?php require("../header.php"); ?>
<style type="text/css">
  #materialbox-overlay {background: #d6d6d6 !important;}
</style>

<div class="container pageBox white z-depth-2">
<div class="row specialTextBox">
    <h4 class="blue-text text-darken-1 center">ClinVar Statistics</h4>
    <p>In order to recieve or download the high-resolution image of graphs, please click the graphs you interested in.</p>
    <br><hr><br>
    <div class="row">
      
      <div class="col s12 m12 l6 white"><b>Distribution of Whole ClinVar Data </b>
        <div class="material-placeholder"><img class="materialboxed" width="98%" src="../files/img/clinvarWhole.png" data-caption="Distribution of Whole ClinVar Data"></div>
        <p>*VUS: Variants of Uncertain Significance</p>
        <hr>
        <table>
          <tr>
            <th>Key</th>
            <th>Value</th>
          </tr>
          <tr>
            <td>Benign</td>
            <td>149,336</td>
          </tr> 
          <tr>
            <td>Pathogenic</td>
            <td>100,229</td>
          </tr>
          <tr>
            <td>Others</td>
            <td>13,563</td>
          </tr> 
          <tr>
            <td>VUS</td>
            <td>203,746</td>
          </tr>
          <tr>
            <td>Conflict</td>
            <td>20,838</td>
          </tr>
          <tr>
            <td><b>Total</b></td>
            <td><b>487,712</b></td>
          </tr>
        </table>
      </div>

      <div class="col s12 m12 l6 white"><b>Distribution of ClinVar A.acid substitutions</b>
      	<div class="material-placeholder"><img class="materialboxed" width="98%" src="../files/img/clinvarAacid.png" data-caption="Distribution of ClinVar A.acid substitutions"></div>
        <p>*VUS: Variants of Uncertain Significance</p>
        <hr>
        <table>
          <tr>
            <th>Key</th>
            <th>Value</th>
          </tr>
          <tr>
            <td>Benign</td>
            <td>75,894</td>
          </tr> 
          <tr>
            <td>Pathogenic</td>
            <td>76,671</td>
          </tr>
          <tr>
            <td>Others</td>
            <td>9,571</td>
          </tr> 
          <tr>
            <td>VUS</td>
            <td>150,088</td>
          </tr>
          <tr>
            <td>Conflict</td>
            <td>17,114</td>
          </tr>
          <tr>
            <td><b>Total</b></td>
            <td><b>329,338</b></td>
          </tr>
        </table>
      </div>

      <div class="col s12 m12 l12">
        <br>
        <div class="material-placeholder"><img class="materialboxed" width="98%" src="../files/img/aaConservation.png" data-caption="Distribution of amino acids across the clinical significance of the variations in ClinVar."></div>
        <span class="flow-text">Distribution of amino acids across the clinical significance of the variations in <a href="https://www.ncbi.nlm.nih.gov/clinvar/" target="_blank">ClinVar</a>.</span>
      </div>

    </div>
</div>
</div><br>

<?php require("../footer.php"); ?>
