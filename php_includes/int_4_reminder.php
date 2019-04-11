<!DOCTYPE html>
<br />
<hr />
<?php
  //Get the images for the confusion matrix
  $dir_contents = scandir($_SESSION['matrix_directory']);
  //Remove the current and parent directories from the array...
  //Default sorting of scandir() returns these as elements 0 & 1
  array_splice($dir_contents, 0, 2);
?>
<div class="row">
  <div class="col-md-12">
    <h4>How the Model Works</h4>
  </div> <!-- /column -->
</div> <!-- /row -->

<!-- Confusion Matrix of Performance -->
<div class="row">
  <div class="col-md-12">
    <div class="table-responsive">
      <table class="table borderless">
        <tbody>
          <tr class="text-center">
            <th style="width: 12%">
            </th>
            <th style="width: 44%">
            </th>
            <th style="width: 44%">
            </th>
          </tr>
          <tr class="text-center">
            <td>
              <!-- Unused Cell -->
            </td>
            <td>
              <h5>True Value = Dog</h5>
            </td>
            <td>
              <h5>True Value = Cat</h5>
            </td>
          </tr>
          <tr class="text-center">
            <td style="vertical-align: middle;">
              <h5>Model Predicted Dog</h5>
            </td>
            <td  style="background-color: rgba(69, 128, 74, 0.4);">
              <?php
                //Show ALL dogs here (all dogs classified correctly)
                $count = 0;
                for ($i = 0; $i < sizeof($dir_contents); $i++) {
                  //Get whether dog or cat
                  $dc_char = substr($dir_contents[$i], 1, 1);
                  $dc_int = intval($dc_char);
                  //If dog, display in this cell
                  if ($dc_int % 2 == 0) {
                    $count++;
                    //Output cat image
                    echo "<img src=\"".$_SESSION['matrix_directory']."/".$dir_contents[$i]."\" width=\"120\" style=\"margin: 4px 4px 4px 4px\" />";
                  }
                }
              ?>
            </td>
            <td style="background-color: rgba(237, 32, 37, 0.4);">
              <?php
                //Show outdoor cats here (cats mis-classified as dogs)
                $count = 0;
                for ($i = 0; $i < sizeof($dir_contents); $i++) {
                  //Get whether dog or cat
                  $dc_char = substr($dir_contents[$i], 1, 1);
                  $dc_int = intval($dc_char);
                  //If dog, display in this cell
                  if ($dc_int % 2 == 1) {
                    $cio_char = substr($dir_contents[$i], 2, 1);
                    $cio_int = intval($cio_char);
                    if ($cio_int %2 == 0) {
                      $count++;
                      //Output cat image
                      echo "<img src=\"".$_SESSION['matrix_directory']."/".$dir_contents[$i]."\" width=\"120\" style=\"margin: 4px 4px 4px 4px\" />";
                    }
                  }
                }
              ?>
            </td>
          </tr>
          <tr class="text-center">
            <td style="vertical-align: middle;">
              <h5>Model Predicted Cat</h5>
            </td>
            <td style="background-color: rgba(237, 32, 37, 0.4);">
              <!-- None should show here (no dogs mis-classified) -->
            </td>
            <td style="background-color: rgba(69, 128, 74, 0.4);">
              <?php
                //Show indoor cats here (cats correctly classified)
                $count = 0;
                for ($i = 0; $i < sizeof($dir_contents); $i++) {
                  //Get whether dog or cat
                  $dc_char = substr($dir_contents[$i], 1, 1);
                  $dc_int = intval($dc_char);
                  //If dog, display in this cell
                  if ($dc_int % 2 == 1) {
                    $cio_char = substr($dir_contents[$i], 2, 1);
                    $cio_int = intval($cio_char);
                    if ($cio_int %2 == 1) {
                      $count++;
                      //Output cat image
                      echo "<img src=\"".$_SESSION['matrix_directory']."/".$dir_contents[$i]."\" width=\"120\" style=\"margin: 4px 4px 4px 4px\" />";
                    }
                  }
                }
              ?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div> <!-- /column -->
</div> <!-- /row -->
