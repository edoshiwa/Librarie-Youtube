<?php
/*
 * Function for the GET method of the REST API
 * If there is no given parameters it will return
 * The username who have a library
 */
function get_users() {
    $users_array = array();
    foreach (glob("*.json") as $filename) {
        $path_parts = pathinfo($filename);
        $users_array[] = $path_parts['filename'];
    }
    header('Content-Type: application/json');
    echo json_encode($users_array);
}

/*
 * Function for the GET method of the REST API
 * If the username input is correct it will return a json
 * of the user's video library
 */
function get_library($username)
{
    if (file_exists($username . '.json')) {
        $data = json_decode(file_get_contents($username . '.json'));
        $updated_json = json_encode($data->videos);
        header('Content-Type: application/json');
        echo $updated_json;
    } else {
        header("HTTP/1.0 404 Username Not Found");
    }
}

/*
 * Function for the PUT method of the REST API
 * If the username input is correct it will add
 * a video (title + id) to the user's library
 */
function add_to_library($username, $video_id, $video_title)
{
    if (file_exists($username . '.json')) {
        $data = json_decode(file_get_contents($username . '.json'));
        $data->videos[] = ['title' => $video_title, 'id' => $video_id];
        $updated_json = json_encode($data);
        file_put_contents($username . '.json', $updated_json);
    } else {
        header("HTTP/1.0 404 Username Not Found");
    }
}

/*
 * Function for the POST method of the REST API
 * If the username input doesn't already exists
 * it will create a new library
 */
function new_library($username)
{
    if (true) {
        $json_string = '{"name":"","videos":[]}';
        $data = json_decode($json_string);
        $data->name = $username;
        $updated_json = json_encode($data);
        file_put_contents($username . '.json', $updated_json);
    } else {
        header("HTTP/1.0 412 Username Already Exists");
    }
}

/*
 * Function for the DELETE method of the REST API
 * If the username input is correct it will delete
 * the user's library
 */
function delete_library($username)
{
    if (file_exists($username . '.json')) {
        unlink($username . '.json');
    } else {
        header("HTTP/1.0 404 User\' library Not Found");
    }
}

/*
 * Function for the DELETE method of the REST API
 * If the username input is correct, exists and the
 * video id is correct and exists in the users library
 * it will delete the video from the user's library
 */
function delete_from_library($username, $video_id)
{
    if (file_exists($username . '.json')) {
        $data = json_decode(file_get_contents($username . '.json'));
        $key_video_id = -1; //If it's the first video in the library the key is 0
        $i = 0;
        foreach ($data->videos as $video) {
            if ($video_id == $video->id && $key_video_id == -1) {
                echo($i);
                $key_video_id = $i;
            }
            $i++;
        }
        if ($key_video_id != -1) {
            array_splice($data->videos, $key_video_id, 1);
            $updated_json = json_encode($data);
            file_put_contents($username . '.json', $updated_json);
        } else {
            header("HTTP/1.0 404 Video Not Found");
        }
    } else {
        header("HTTP/1.0 404 User Not Found");
    }

}

/*
 * Function for the GET method of the REST API
 * If the username input is correct, exists and contains a video with the title given
 * It will return a JSON of the video with the title and the ID of the video
 */
function search_video($username, $title)
{
    if (file_exists($username . '.json')) {
        $data = json_decode(file_get_contents($username . '.json'));
        $video_found = false;
        foreach ($data->videos as $video) {
            if ($title == $video->title && $video_found == false) {
                header('Content-Type: application/json');
                echo(json_encode(['title' => $video->title, 'id' => $video->id]));
                $video_found = true;
            }
        }
        if (!$video_found) header("HTTP/1.0 404 Video Not Found");
    }
    else {
        header("HTTP/1.0 404 User Not Found");
    }
}

/*
 * Switch case for the supported method in the REST API
 * Currently supported METHOD : GET/PUT/POST/DELETE
 */
$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        // Retrieve user's video library or search for specific video in an user library
        if (!empty($_GET["username"])) {
            if (!empty($_GET["title"])) {
                search_video($_GET["username"], $_GET["title"]);
            } else {
                get_library($_GET['username']);
            }
        } elseif (empty($_GET["title"])) {
            get_users();
        }
        else {
            header("HTTP/1.0 400 Empty username parameter");
        }
        break;
        // Create a new library
    case 'POST':
        // Use of regex for newly created username only letters and number are accepted
        if (!empty($_POST["username"]) && (!preg_match('/[^A-Za-z0-9]/', $_POST["username"]))) {
            new_library($_POST["username"]);
        } else {
            header("HTTP/1.0 400 Invalid username parameter");
        }
        break;
        // Update a current library by adding new video to the playlist
    case 'PUT':
        parse_str(file_get_contents('php://input'), $_PUT);
        if (!empty($_PUT["username"]) && !empty($_PUT["v"]) && !empty($_PUT["title"])) {
            add_to_library($_PUT["username"], $_PUT["v"], $_PUT["title"]);
        } else {
            header("HTTP/1.0 400 Invalid parameters");
        }
        //Delete a library or a video from a library of a specific user
        break;
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $_DEL);
        if (!empty($_DEL["username"])) {
            if (!empty($_DEL["v"])) {
                delete_from_library($_DEL["username"], $_DEL["v"]);
            } else {
                delete_library($_DEL["username"]);
            }
        } else {
            header("HTTP/1.0 400 Invalid parameters");
        }
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>

