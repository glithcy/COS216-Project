callback();

function getCookie(name)
{
    var re = new RegExp(name + "=([^;]+)");
    var value = re.exec(document.cookie);
    return (value != null) ? unescape(value[1]) : null;
}

function swap() {
    var main = document.getElementsByTagName("main")[0];
    var cover = document.getElementById("cover");
    main.style.display = "block";
    cover.style.display = "none";

}

function callback()
{
    var rows = document.getElementsByClassName("row");
    var key = getCookie("key");

    let formData = {
        "key": key,
        "type": "info",
        "title": "*",
        "ranking": "",
        "return": ["title","artwork","artist","rating", "ranking", "preview","duration", "album_type", "release"]
    };

    $.ajax({
        url: './php/api.php',
        type: 'post',
        dataType: 'json',
        contentType: 'application/json',
        success: function(res) {
            //console.log(data)
            // var length = ;
            console.log(res.data[0].album)
            for(let i=0; i<res.data.length; i++)
            {
                var n = res.data[i];

                if(n.preview == null)
                {
                    rows[0].innerHTML += '<div class="column" onclick="rate(this)"> <div class = "container"> <img src="' + n.artwork + '" alt="' + n.title + " album cover" + '" style="width:100%"> </div> <div class="text"> <audio controls> <source src="' + n.preview + '" type="audio/mp3"></audio> <p>(no preview available for this song)</p> <h1 class="titl" >' + n.title + '</h1><p style="font-weight: bold">' + n.artist + ' </p><p>Release: ' + n.release + '</p><p>Duration: ' + n.duration + 's</p><p></p>  </div> </div>';

                }else
                {
                    rows[0].innerHTML += '<div class="column" onclick="rate(this)"> <div class = "container"> <img src="' + n.artwork + '" alt="' + n.title + " album cover" + '" style="width:100%"> </div> <div class="text"> <audio controls> <source src="' + n.preview + '" type="audio/mp3"></audio> <h1 class="titl" >' + n.title + '</h1><p style="font-weight: bold">' + n.artist + ' </p><p>Release: ' + n.release + '</p><p>Duration: ' + n.duration + 's</p><p>Rating: '+ n.rating +'</p>  </div> </div>';
                }
            }
            swap();
        },
        error: function(data) {
            var modal = document.getElementById("myModal");
            var q = document.getElementById("question");
            var h = document.getElementById("heading");
            var l = document.getElementById("input");
            var l2 = document.getElementById("input2");

            l2.style.display = "none";
            modal.style.display = "block";
            h.innerHTML = "Sorry..";
            q.innerHTML = "You're not Logged in.";
            l.innerHTML = "Login";

            l.onclick = function() {
                window.location.href = './login.php';
            }
        },
        data: JSON.stringify(formData)
    });
}