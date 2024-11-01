/*----------------------------------------------------------------
  Monitoring Input Text for Checked

  Args: input text value, default checked ID, monitoring input text ID
  Ex:
    ---
    <p>
      <input type="radio" id="current-password" name="select-password" value="checked-current-password" checked="checked">
      <label for="current-password">No Change (Use current password)</label>
    </p>
    <input type="radio" id="new-password" name="select-password" value="checked-new-password">
      <label for="new-password">New Password: </label>
      <input type="text" autocomplete="off" id="new-password-text" name="new-password-text" oninput="autoRadioButtonChecked(this.value ,'current-password', 'new-password');">
    ---
 ----------------------------------------------------------------*/
function autoRadioButtonChecked(str, default_id, monitoring_id) {
    if(str.length > 0 ) {
        document.getElementById(monitoring_id).checked = true;
    }
    else {
        document.getElementById(monitoring_id).checked = false;
        document.getElementById(default_id).checked = true;
    }
}
