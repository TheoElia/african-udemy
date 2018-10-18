<?php   

    include_once dirname(__DIR__) . '/config/config.php';
    include API_DIR . 'controller/controllers.php';
    include API_DIR . 'router-response.php';
    
    $method = $_SERVER['REQUEST_METHOD'];
    header('Content-Type: application/json');
    
    if ($method == "POST") {
        $input  = json_decode(file_get_contents('php://input'), true);
        $action = (int) $input["action"];   

        $data   = (isset($input["data"])) ? $input["data"] : null;
        error_log("");
        error_log("---------------------------------------- API STARTS ----------------------------------------");
        error_log('action : ' . $action);

        switch ($action) {

            //--------------------------
            // AUTH ACTIONS
            //--------------------------
            case 100: // login
                $response = ControllerAuth::login($data);
                return $response;
                break;

            default: // unknown action
                ResponseRouter::sendResponse(1003);
                break;

        }
    }





 ?>
