/*
Function calling the API to create a new user and updating the webpage if successfully done
 */
function new_user() {
    let username = $("#name").val();
    $.ajax({
        method: 'POST',
        data: {
            username: username,
        },
        url:'./library.php',
        success: function (response) {
            user_list();
        },
        error: function(xhr, status, error) {
            alert(error);
        }
    })
}
/*
Function calling the API to get the list of all user who have a library on the server
It creates a list
 */
function user_list() {
    $.ajax({
        type: 'GET',
        url: './library.php',
        success: function (response){
            $("#select_users").empty();
            $("#list_users").empty();
            let ul = document.createElement('ul');
            let selectList = document.getElementById('select_users');
            document.getElementById('list_users').appendChild(ul);
            response.forEach(function(name){
                let li = document.createElement('li');
                ul.appendChild(li);
                li.innerHTML += name;

                let option = document.createElement("option");
                option.value = name;
                option.text = name;
                selectList.appendChild(option);
            });
        },
        error: function(xhr, status, error) {
            alert(error);
        }
    })
}
/*
Function deleting an user library by calling the API , if done successfully it will update the webpage
 */
function delete_user() {
    let username = $("#select_users").val();
    $.ajax({
        method: 'DELETE',
        data: {
            username: username,
        },
        url:'./library.php',
        success: function (response) {
            user_list();
            hide_user();
        },
        error: function(xhr, status, error) {
            alert(error);
        }
    })
}
/*
Function showing the library of the selected user and showing associated function such as deleting and adding video
 */
function show_user() {
    hide_user();
    let username = $("#select_users").val();
    $.ajax({
        method: 'GET',
        data: {
            username: username,
        },
        url:'./library.php',
        success: function (response) {
            let videoLib = $("#video_library");
            videoLib.append("<p>Library of "+ username +"<p>");
            let ul = document.createElement('ul');
            document.getElementById('video_library').appendChild(ul);
            response.forEach(function(name){
                let li = document.createElement('li');
                ul.appendChild(li);
                li.innerHTML += " <a href=\"https://www.youtube.com/watch?v=" + name.id +"\" target=\"_blank\">" + name.title;
                li.innerHTML += "<button id=\"delete_video\" onclick=\"delete_video(\'"+ name.id +"\')\">Delete</button>"
            });
            videoLib.append("<label for=\"video_title\">Add a new video to the playlist</label>\n" +
                "<input type=\"text\" id=\"video_title\" name=\"video_title\" placeholder=\"Title of the YT video\">"+
                "<label for=\"video_id\">ID of the video</label>\n" +
                "<input type=\"text\" id=\"video_id\" name=\"video_id\" placeholder=\"end of the link, example : tOANmMG-KQg\" size=\"50\">"+
                "<button id=\"add_video\" onclick=\"add_video()\">Add</button>");
        },
        error: function(xhr, status, error) {
            alert(error);
        }
    })
}
/*
Function hiding the expanded interface
 */
function hide_user() {
    $("#video_library").empty();
}

/*
Function deleting a specific video in a playlist of a specific selected user
It's done by calling the API
 */
function delete_video(video_id) {
    let username = $("#select_users").val();
    console.log(username);
    $.ajax({
        method: 'DELETE',
        data: {
            username: username,
            v: video_id,
        },
        url: './library.php',
        success: function (response) {
            show_user();
        },
        error: function (xhr, status, error) {
            alert(error);
        }
    })

}
/*
Function adding a video to an user library, it will add the video to the selected user
It take 2 other arguments, one for the video title
the other for the youtube video ID
 */
function add_video() {
    let username = $("#select_users").val();
    let title = $("#video_title").val();
    let video_id = $("#video_id").val();
    console.log(username);
    $.ajax({
        method: 'PUT',
        data: {
            username: username,
            v: video_id,
            title: title,
        },
        url:'./library.php',
        success: function (response) {
            console.log(response);
            show_user();
        },
        error: function(xhr, status, error) {
            alert(error);
        }
    })
}
/*
Function that search a video by title in a specific library
 */
function search_video() {
    $("#search_result").empty();
    let username = $("#select_users").val();
    let title = $("#search_video").val();
    console.log(title);
    $.ajax({
        type: 'GET',
        url: './library.php',
        data: {
            username: username,
            title: title,
        },
        success: function (response){
            console.log(response);
            let searchResult = $("#search_result");
            searchResult.append("<p> Video Found !</p>");
            searchResult.append("<a href=\"https://www.youtube.com/watch?v=" + response.id +"\" target=\"_blank\">"+ response.title);
            searchResult.append("")

        },
        error: function(xhr, status, error) {
            alert(error);
        }
    })
}