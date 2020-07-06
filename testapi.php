<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Youtube Library EasyVista</title>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="testapi.js"></script>
<body>
<label for="name">Add a new user</label>
<input type="text" id="name" name="name" placeholder="username">
<button id="New User" onclick="new_user()">Add</button>
<p>List of users</p>
<div id="list_users"></div>
<label for="select_users">Select an user to interact with his library</label><select id="select_users"></select>

<button id="delete_user" onclick="delete_user()">Delete user</button>
<button id="show_user" onclick="show_user()">Show user</button>
<button id="hide_user" onclick="hide_user()">Hide user</button>
<label for="search_video">Search a specific video</label>
<input type="text" id="search_video" name="search_video" placeholder="Title">
<button id="search_video_btn" onclick="search_video()">Search</button>
<div id="search_result"></div>
<div id="video_library"></div>

</body>
<script type="text/javascript">
    $(document).ready(function () {
        user_list();
    })
</script>
</html>
