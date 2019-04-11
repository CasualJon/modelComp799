<!DOCTYPE html>
<br />
<hr />
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
            <th style="width: 22%">
            </th>
            <th style="width: 38%">
            </th>
            <th style="width: 38%">
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
            <td  style="vertical-align: middle; background-color: rgba(69, 128, 74, 0.4);">
              <h5>8</h5>
            </td>
            <td style="vertical-align: middle; background-color: rgba(237, 32, 37, 0.4);">
              <h5>4</h5>
            </td>
          </tr>
          <tr class="text-center">
            <td style="vertical-align: middle;">
              <h5>Model Predicted Cat</h5>
            </td>
            <td style="vertical-align: middle; background-color: rgba(237, 32, 37, 0.4);">
              <h5>0</h5>
            </td>
            <td style="vertical-align: middle; background-color: rgba(69, 128, 74, 0.4);">
              <h5>4</h5>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div> <!-- /column -->
</div> <!-- /row -->
