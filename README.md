# modelComp799
<p>
  <b>CS799:</b> Model Trust and Understanding<br />
  Site code for MTurk use to collect experiment data on model comprehensibility
</p>

<p>
  Student: F<br />
  Principal Investigator:
</p>

<p>
  ------------------------------------------------------------------------<br />
  NOTE: Much of the configuration is controlled through the file
  <b>/php_includes/control_variables.php</b><br />
  <br />
  <i>DB Configuration currently expected by code:</i><br />
  <ul>
    <li>workers</li>
    <table>
      <tbody>
        <tr>
          <td>internal_identifier</td>
          <td>integer (10)</td>
          <td>primary key / auto increment</td>
        </tr>
        <tr>
          <td>ip_address</td>
          <td>varchar (54)</td>
          <td>index</td>
        </tr>
        <tr>
          <td>visit_date</td>
          <td>date</td>
          <td></td>
        </tr>
        <tr>
          <td>start_time</td>
          <td>varchar (24)</td>
          <td></td>
        </tr>
        <tr>
          <td>end_time</td>
          <td>varchar (24)</td>
          <td>default null</td>
        </tr>
        <tr>
          <td>hit_completion_code</td>
          <td>varchar (32)</td>
          <td>default null</td>
        </tr>
      </tbody>
    </table>
    <li>admin_config</li>
      <table>
        <tbody>
          <tr>
            <td>id</td>
            <td>integer (10)</td>
            <td>primary key</td>
          </tr>
          <tr>
            <td>password</td>
            <td>varchar (256)</td>
            <td></td>
          </tr>
        </tbody>
      </table>
  </ul>
</p>
