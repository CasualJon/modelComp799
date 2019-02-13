# modelComp799
<p>
  <b>CS799:</b> Model Trust and Understanding<br />
  Site code for MTurk use to collect experiment data on model comprehensibility
</p>

<p>
  Student: Jon Cyrus, Spring 2019<br />
  Principal Investigator: Mike Gleicher (http://pages.cs.wisc.edu/~gleicher/)
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
          <td>ip_address</td>
          <td>varchar (54)</td>
          <td>primary key</td>
        </tr>
        <tr>
          <td>visit_date</td>
          <td>date</td>
        </tr>
        <tr>
          <td>start_time</td>
          <td>varchar (24)</td>
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
          </tr>
        </tbody>
      </table>
  </ul>
</p>
