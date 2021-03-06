<?php

class LoginHandler {

    private $DbHandler = NULL;
    private $UserClass = NULL;
    private $_config = NULL;
    private $public  = false;

    function __construct($db, $config, $public = false, $post = NULL) {

        // Store database object
        $this->DbHandler = $db;
        $this->_config   = $config;

        // Start session
        session_start();

        // Random hash, used for public exercises
        if (!isset($_SESSION["sessionhash"])) {
            $_SESSION["sessionhash"] = bin2hex(random_bytes(10));
        }

        // Store post args
        if (is_null($post)) { $post = $_POST; }
        $post = is_null($post) ? (object)$_POST : (object)$post;

        // Logut
        if (property_exists($post, "logout")) {
            $this->logout();
            $this->show_login_form();
        }

        // If $request contains username and password:
        // check if login is valid
        if (property_exists($post, "username") & property_exists($post, "password")) {
            if (!strlen($post->username) == 0 & !strlen($post->password) == 0) {
                $user_id = $this->check_login($post);
            }
            // Invalid login
            if (is_bool($user_id)) {
                print("Invalid user name or passsword. Try again.\n");
            } else {
                $_SESSION["user_id"]     = (int)$user_id;
                $_SESSION["loggedin_as"] = (int)$user_id;
                $_SESSION["username"]    = $post->username;
            }
            Header(sprintf("Location: %s/", $this->_config->get("system", "url")));
        }

        if(!$public & !isset($_SESSION["user_id"])) { $this->show_login_form(); }

    }

    /* Displays a "login denied" page.
     */
    public function access_denied($reason = NULL) {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>exerciseR</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="../css/bootstrap.4.1.2.min.css">
    <link rel="stylesheet" href="../css/exerciseR.css">
    <script src="../lib/jquery-3.4.1.min.js"></script>
    <script src="../lib/bootstrap-4.2.1.min.js"></script>
</head>
<body>
    <nav id="top-nav" class="navbar navbar-expand-sm bg-primary navbar-light">
        <img id="exerciserlogo" src="../css/logo.svg"></img>
        <ul class="navbar-nav">
            <li class="nav-item">
                <?php $this->logout_form($this->UserClass); ?>
            </li>
        </ul>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>ExerciseR login</h1>
                <p>Access denied!</p>
                <p>
                    <b>Reason:</b>
                    <?php print(is_null($reason) ? "It is, as it is." : $reason); ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
        <?php
        die(0);
    }

    /* Display login form
     *
     * No input, no output, simply shows the login form and
     * stops script execution.
     * TODO: outputs a html document, I am sure there is a nicer way :).
     * Place login somewhere else or use header/footer from theme.
     */
    private function show_login_form() {
        Header(sprintf("Location: %s/login.php", $this->_config->get("system", "url")));
    }

    /* Check login credentials.
     *
     * Parameter
     * =========
     * post : object
     *     object containing the login credentials. 'username' and 'password'
     *     have to be defined.
     *
     * Returns
     * =======
     * Returns boolean false if login not allowed, else the user_id is returned.
     */
    public function check_login($post) {

        $sql = "SELECT user_id FROM users WHERE username = \"%s\" AND password = md5(\"%s\");";
        $res = (object)$this->DbHandler->query(sprintf($sql, $post->username, $post->password));
        return($res->num_rows == 0 ? false : $res->fetch_object()->user_id);

    }

    /* Display logout form.
     */
    public function logout_form($UserClass) {
        if ($_SESSION["user_id"] == $_SESSION["loggedin_as"]) {
            $val = "Logout";
        } else {
            $val = sprintf("Logout (logged in as %s)", $_SESSION["username"]);
        }
        ?>
        <form method="POST">
        <input type="hidden" value="logout" name="logout" />
        <input class="btn btn-info" type="submit" value="<?php print($val); ?>" name="submit" /><br />
        </form>
        <?php
    }

    /* Destroy current session */
    private function logout() {
        if ($_SESSION["user_id"] == $_SESSION["loggedin_as"]) {
            session_destroy();
        } else {
            $user = new UserClass($_SESSION["user_id"], $this->DbHandler);
            $_SESSION["loggedin_as"]  = $user->user_id();
            $_SESSION["username"] = $user->username();
            header("Location: ../index.php");
        }
    }

}
