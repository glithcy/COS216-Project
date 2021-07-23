const socket = io('http://localhost:3000')


socket.on("END", ()=> {
    //send modal message to the users they are disconnected
    var modal = document.getElementById("myModal");
    var q = document.getElementById("question");
    var h = document.getElementById("heading");
    var l = document.getElementById("input");
    var l2 = document.getElementById("input2");

    l2.style.display = "none";

    //its already selected in the header as modal
    modal.style.display = "block";
    h.innerHTML = "Hey dude";
    q.innerHTML = "The server shut down soz.";
    l.innerHTML = "Okay";

    l.onclick = function() {
        modal.style.display = "none";
    }
});

socket.on("RECONNECT", ()=> {
    //reestablish a connection
    var modal = document.getElementById("myModal");
    var q = document.getElementById("question");
    var h = document.getElementById("heading");
    var l = document.getElementById("input");
    var l2 = document.getElementById("input2");

    l2.style.display = "none";

    //its already selected in the header as modal
    modal.style.display = "block";
    h.innerHTML = "Hey dude";
    q.innerHTML = "You were disconnected... The page will refresh and you will be right back on that beat.";
    l.innerHTML = "Okay";

    l.onclick = function() {
        modal.style.display = "none";
    }

    setTimeout(function(){
        window.location.href = window.location.href; //reload
    }, 3000);


});


//for reloads
socket.on("dead", ()=> {
    //find the socket to disconnect and remove from clients list (make null)
    alert("you disconnected from server");
    pause(); //stop the streaming
})

socket.on('LATEST', (updated, ts) => { //two playing at the same time
     if(audio.currentTime !== updated)
     {
        audio.currentTime = updated;

        var modal = document.getElementById("myModal");
        var q = document.getElementById("question");
        var h = document.getElementById("heading");
        var l = document.getElementById("input");
        var l2 = document.getElementById("input2");

        l2.style.display = "none";

        //its already selected in the header as modal
        modal.style.display = "block";
        h.innerHTML = "Just a headsup";
        q.innerHTML = "The song has been updated to "+updated+" seconds";
        l.innerHTML = "Okay";

        l.onclick = function() {
            modal.style.display = "none";
        }
     }
})

window.onload = function () {
    var m = document.getElementById("message");
    m.innerHTML = "";

    var audio = document.getElementById("audio");
    let title = audio.getAttribute("name");
    let duration;


    audio.addEventListener("play", play);
    audio.addEventListener("pause", pause);

    if(getCookie("key")==null)
    {
        window.location.href = 'index.php';
    }
    else
    {

        duration = audio.duration;

        var deets = {
            key: getCookie("key"),
            title:title,
            duration:duration,
            timestamp:0,
            progress:0 //dubbed

        }
        socket.emit("new-user", deets)

        //get the current timestamp
        socket.on("INITIAL",data => {
            //setting the audio element with progress
            clearInterval(x);
            if(data-1 >= duration)
            {
                audio.currentTime = 0; //progress in seconds returned for this user
            }else
            {
                audio.currentTime = data; //progress in seconds returned for this user
            }

        })
    }
}

var x;

function play(){
    console.log("start streaming");
    var audio = document.getElementById("audio");
    let title = audio.getAttribute("name");
    let duration;
    //duration
    duration = audio.duration;

    console.log("duration "+duration);
    x = setInterval(() => {
        sendChange(title, duration);
    }, 500); //half a second

}

function pause(){
    console.log("stop streaming");
    clearInterval(x);
}


function sendChange(title, duration)
{
    var audio = document.getElementById("audio");
    let progress = audio.currentTime;

    var ts = Math.round((new Date()).getTime() / 1000); //unix epoch time

    console.log("sending update at "+ ts);
    var newDeets = {
        "key": getCookie("key"),
        "progress":progress,
        "title":title,
        "duration":duration,
        "timestamp":ts
    }
    socket.emit("UPDATE",newDeets);
}


function getCookie(name)
{
    var re = new RegExp(name + "=([^;]+)");
    var value = re.exec(document.cookie);
    return (value != null) ? unescape(value[1]) : null;
}