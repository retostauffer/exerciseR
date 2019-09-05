<?php
// Loading required config
function __autoload($name) {
    $file = sprintf("../php/%s.php", $name);
    try {
        require($file);
    } catch (Exception $e) {
        throw new MissingException("Unable to load \"" . $file . "\".");
    }
}
# Loading required config
$config = new ConfigParser("../../files/config.ini", "..");

# Loading the exercise class
$HandlerOptions = array("js"=>array("../lib/exr_admin.js",
                                    "../lib/simpleUpload-1.1.js",
                                    "../lib/datatables.1.10.18.min.js"),
                        "css"=>array("../css/datatables.1.10.18.min.css"));

$Handler = new AdmineR($config, $HandlerOptions, true);
$Handler->site_show_header();

# Used to load files from the "files" folder ont accessible
# by the webserver.
require_once("../php/FileHandler.php");
?>

  <!-- CodeMirror -->
  <script>
    // http://simpleupload.michaelcbrook.com/
    $(document).ready(function(){
        $("#admin-table-exercises").admin_table_exercises();
    });
  </script>

    <div class="container" id="admin-add-users">

        <h3 style="padding-bottom: 1em;">Administrate Exercises</h3>

        <!-- tab navigation -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-exercises">Exercises</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-single">Add new exercise</a>
            </li>
        </ul>
        <br />

        <!-- tab panes -->
        <div class="tab-content">
            <!-- existing users -->
            <div class="tab-pane container active" id="tab-exercises">
                <p><b>Existing Exercises</b></p>
                <div id="admin-table-exercises"></div>
            </div>
            <!-- single user, defined via form -->
            <div class="tab-pane container" id="tab-single">
                <p><b>Add single user</b></p>
                <form class="form-horizontal">
                  <div class="form-group">
                    <label class="control-label col-sm-4" for="form-username">Username:</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="username" maxlength="20"
                             id="form-username" placeholder="Enter username">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-4" for="form-displayname">Displayname:</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="displayname" maxlength="50"
                             id="form-displayname" placeholder="Enter displayname">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-4" for="form-email">E-Mail address:</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" name="email"
                             id="form-email" placeholder="Enter email">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-8">
                      <button type="submit"
                              class="btn btn-primary submit">Submit form</button>
                    </div>
                  </div>
                </form>

                <div class="col-md-12 admin-message" style="padding-top: 1em;">
                    <div class="alert alert-info">Currently no panic message ...</div>
                </div>
            </div>

        </div>

    </div>

<?php $Handler->site_show_footer(); ?>

