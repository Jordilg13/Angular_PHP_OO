<?
include_once dirname(__FILE__).'/../../../paths.php';
include_once _PROJECT_PATH_.'/backend/model/autoload.php';
require(_PROJECT_PATH_.'/backend/lib/JWT.php');
use Firebase\JWT\JWT;

$method = $_SERVER['REQUEST_METHOD'];
$secret_key = parse_ini_file(_PROJECT_PATH_."/backend/keys/jwt_secret_key.ini")['secretkey'];

$_POST = json_decode(file_get_contents('php://input'),true); // true makes it parse as an array


session_start();
// POST = register/login/confirmemail/enableaccount
// PUT = update user
// DELETE = logout(i might add delete user if i had a crudSS)

if ($method == "POST") { // login or register
    switch ($_POST['op']) {

        case 'login':
            $method="GET"; //changed to get because i want to do a select, not an insert
            $object = new Login();
            include_once _PROJECT_PATH_.'/backend/model/ApiController.php';
            error_log(print_r($_POST,1));

            if (password_verify($_POST['data']['password'],$results[0]->password)) {
                $_SESSION['logged_user']=$results[0]->token;
                error_log($_SESSION['logged_user']);
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }            
            break;

        case 'register':
            $method="GET"; //changed to get because i want to do a select, not an insert
            $object = new Login();
            
            // check if user is already registred
            $_GET['username'] = $_POST['data']['username'];
            $emaildata["username"] = $_POST['data']['username'];
            error_log(print_r($_GET,1));
            include _PROJECT_PATH_.'/backend/model/ApiController.php';

            error_log(print_r($results,1));

            if (empty($results)) {
                error_log(print_r("if",1));
                // JWT
                $payload = array(
                    "message" => "abc",
                    "exp" => time() + 20
                ); // time in the future

                $method="POST"; // changed to post to do the insert
                $_POST['data']['password']=password_hash($_POST['data']['password'],PASSWORD_BCRYPT);
                $_POST['data']['token']=JWT::encode($payload,$secret_key);
                $emaildata["token"]= $_POST['data']['token'];
                $_POST['data']=json_encode($_POST['data']);

                // $emaildata= array("username" => $_POST['data']['username'], "token" => $_POST['data']['token']);

                include _PROJECT_PATH_.'/backend/model/ApiController.php';

                error_log(print_r($results,1));
                error_log(print_r("asdf",1));

                if ($results == 1) {

                    // sending confirmation email
                    include_once _PROJECT_PATH_."/backend/utils/sendemails/sendemail.php";

                    $json = send_mailgun("jordillopis00@gmail.com","Confirm Account","Welcome ".$emaildata['username'].", we sent you this message to confirm your account. Please click <a href='http://localhost/angular/#/confirmaccount/".$emaildata['username']."/".$emaildata['token']."'>here</a> to confirm your account.");
                    echo json_encode($json);

                } else {
                    echo json_encode(false);
                }
            } else {
                echo json_encode(false);
            }
            break;

        case 'loggeduser':
            if (isset($_SESSION['logged_user'])) {
                echo json_encode($_SESSION['logged_user']);
            } else {
                echo json_encode(false);
            }
            break;
        case 'enableaccount':
            $object = new Login();
            $method="GET";
            $results=false;

            include _PROJECT_PATH_.'/backend/model/ApiController.php';

            // error_log(print_r($results,1));
            // error_log(print_r($results[0]->token,1));
            // error_log(print_r($_POST['token'],1));

            if (isset($results[0]->token) && $results[0]->token == $_POST['token']) {
                $method="PUT";
                $_POST =[];
                $_POST['fromphp']=true;
                $_POST['data']['enabledAccount']=1;
                include _PROJECT_PATH_.'/backend/model/ApiController.php';
            }
            
            echo json_encode($results);
            break;
        default:
            # code...
            break;
    }
} elseif ($method == "GET") {
    $object = new Login();
    include_once _PROJECT_PATH_.'/backend/model/ApiController.php';

    echo json_encode($results);
} elseif ($method == "DELETE") {
    unset($_SESSION['logged_user']);
    if (isset($_SESSION['logged_user'])) {
        echo json_encode(false);
    } else {
        echo json_encode(true);
    }

} 
// elseif ($method == "PUT") {
//     # code...
// }