<div id="service_request_form">

  <div class="row">
    <div class="col" id="request_form">
      <h3 id="service_request_title">New Service Request</h3>
      <form name="service_form" id="service_form" class="form">
        <input type="hidden" id="type" name="type" value="New">
        <input type="hidden" id="id" name="id" value="">
        <select name="agency_name" id="agency_name">
          <option value="" selected>Select Agency</option>
          <option value="McDonalds">McDonalds</option>
          <option value="Burger King">Burger King</option>
          <option value="Ford">Ford</option>
          <option value="Apple">Apple</option>
        </select>

        <input type="text" name="project_name" id="project_name" class="form-control" placeholder="Project Name">

        <input type="text" name="budget" id="budget" class="form-control" placeholder="Budget">

        <input type="text" name="primary_sales_contact" id="primary_sales_contact" class="form-control" placeholder="Primary Sales Contact">

        <input type="text" name="customer_contact" id="customer_contact" class="form-control" placeholder="Customer Contact">

        <!-- <textarea name="general_notes" placeholder="General Notes"></textarea> -->

        <p>Is the supplier already working with customers?<p/>
        <input type="radio" name="supplier_customers" value="Yes">Yes
        <input type="radio" name="supplier_customers" value="No">No

        <p>Where are the corp headquarters located?<p/>
        <input type="text" name="hq_city" id="hq_city" class="form-control" placeholder="City">
        <select name="hq_state" id="hq_state">
          <option value="" selected>Select State</option>
          <?php 
            $usStateAbbrevNames = array(
              'AL'=>'Alabama',
              'AK'=>'Alaska',
              'AZ'=>'Arizona',
              'AR'=>'Arkansas',
              'CA'=>'California',
              'CO'=>'Colorado',
              'CT'=>'Connecticut',
              'DE'=>'Delaware',
              'DC'=>'District Of Columbia',
              'FL'=>'Florida',
              'GA'=>'Georgia',
              'HI'=>'Hawaii',
              'ID'=>'Idaho',
              'IL'=>'Illinois',
              'IN'=>'Indiana',
              'IA'=>'Iowa',
              'KS'=>'Kansas',
              'KY'=>'Kentucky',
              'LA'=>'Louisiana',
              'ME'=>'Maine',
              'MD'=>'Maryland',
              'MA'=>'Massachusetts',
              'MI'=>'Michigan',
              'MN'=>'Minnesota',
              'MS'=>'Mississippi',
              'MO'=>'Missouri',
              'MT'=>'Montana',
              'NE'=>'Nebraska',
              'NV'=>'Nevada',
              'NH'=>'New Hampshire',
              'NJ'=>'New Jersey',
              'NM'=>'New Mexico',
              'NY'=>'New York',
              'NC'=>'North Carolina',
              'ND'=>'North Dakota',
              'OH'=>'Ohio',
              'OK'=>'Oklahoma',
              'OR'=>'Oregon',
              'PA'=>'Pennsylvania',
              'RI'=>'Rhode Island',
              'SC'=>'South Carolina',
              'SD'=>'South Dakota',
              'TN'=>'Tennessee',
              'TX'=>'Texas',
              'UT'=>'Utah',
              'VT'=>'Vermont',
              'VA'=>'Virginia',
              'WA'=>'Washington',
              'WV'=>'West Virginia',
              'WI'=>'Wisconsin',
              'WY'=>'Wyoming',
            );
            foreach ($usStateAbbrevNames as $key => $value) {
              echo "<option value=".$key.">".$value."</option>";
            };
          ?>
        </select>

        <p>Will the contract be executed by an outside entity?<p/>
        <input type="radio" name="outside_entity" value="Yes">Yes
        <input type="radio" name="outside_entity" value="No">No

        <p>Is foreign currency payment a requirement at any of the locations?<p/>
        <input type="radio" name="foreign_currency" value="Yes">Yes
        <input type="radio" name="foreign_currency" value="No">No

        <p>Do you have a network diagram available of current and desired solution?<p/>
        <input type="radio" name="network_diagram" value="Yes">Yes
        <input type="radio" name="network_diagram" value="No">No

        <p>Is a backup and/or diverse connection required?<p/>
        <input type="radio" name="backup_diverse" value="Backup">Backup
        <input type="radio" name="backup_diverse" value="Diverse">Diverse
        <input type="radio" name="backup_diverse" value="Both">Both

        <p>Do you require any Voice (SIP/TDM) to be deployed with this solution?<p/>
        <input type="radio" name="voice" value="Yes">Yes
        <input type="radio" name="voice" value="No">No

        <input type="submit" id="service_request_submit" class="form-control submit" value="Submit">

      </form>
    </div>
  </div>

</div>