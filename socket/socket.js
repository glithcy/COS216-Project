let app = require('express')();
const cors = require('cors');
app.use(cors());
let server = require('http').Server(app);
const io = require('socket.io')(server)
const readline = require('readline');

const axios = require('axios')
//https://flaviocopes.com/node-http-post/

const clients = [];
const users = []; //gets API
let num =0;


//SENDS UPDATES TO API EVERY 5 SECONDS WHEN SERVER STARTS
setInterval(() => {
    const temp = [...clients]; //deep copy
    for (let i = 0; i < temp.length; i++) {
        let sock = temp[i];
        let c = 0;

        if(sock !== null && sock.s != null && sock.data !== null) //if this isn't run then the song hasnt been played for this client
        {
            let latestProgress = sock.data.progress;
            let latest = sock.data.timestamp;
            let winner = sock.s.id;
            while(c < temp.length)
            {
                if(temp[c] != null && sock.key === temp[c].key) //match all the keys
                {
                   //match the timestamps
                   //if data has been sent before to this socket (UPDATE emit)
                   // console.log("match"+sock.key+" "+sock.s.id);
                    if(temp[c].data !== null && sock.data.timestamp < temp[c].data.timestamp)
                    {
                        //console.log("update this socket: "+temp[c].key);
                        latestProgress = temp[c].data.progress;
                        latest = temp[c].data.timestamp;
                        winner = temp[c].s.id; //the most recent one so it wont change. stringify makes deep copy
                        //console.log("latest progress change: "+latestProgress);
                    }
                   // temp[c] = null; //so it doesn't run every time for the same key (why the second array is created)
                }
                c++;
            }
            //send highest timestamp for the user
            console.log(sock.data.key);
            console.log(latestProgress);
            console.log(sock.data.title);
            console.log(sock.data.duration);
            console.log(latest);

            axios.post('https://u19014938:blueshoe@wheatley.cs.up.ac.za/u19014938/COS216/HW/php/api.php', {
                key: sock.data.key,
                type: "track",
                info:"set",
                progress:latestProgress,
                title:sock.data.title,
                duration:sock.data.duration, //if not yet created for this user
                timestamp:latest,
                return: "*",
            })
                .then((res) => {
                    // console.log(`statusCode: ${res.status}`)
                    // console.log(`Data: ${res.status}`);
                    // sock.s.emit("LATEST", `${res.data.progress}`);
                    if(res == undefined)
                    {
                        console.log("undefined");
                    }
                    let prog = `${res.data.progress}`;
                    if(prog == -1)
                    {
                        //no update
                    }else
                    {
                        for (let j = 0; j < temp.length; j++) {
                            // Updating progress of the track for this ueer
                            if(temp[j] != null && temp[j].key === sock.key)
                            {
                                console.log("the temp id "+temp[j].s.id);
                                console.log("the winners id "+winner); // the user with the furthest listened on the same track
                               
                                if(temp[j].s.id !== winner) 
                                {
                                    //should update all cause we don't know which will be highest at this update
                                    temp[j].s.emit("LATEST", `${res.data.progress}`, `${res.data.timestamp}`);
                                }

                            }
                        }
                    }

                })
                .catch((error) => {
                    console.log("API ERROR");
                })

        }else
        {
            // the socket is null
        }

    }
}, 15000); //every 15 seconds check for updates



io.on("connection", socket =>{
   // new user connected

    socket.on("disconnect", function() {
        socket.emit("dead"); // socket kills itself
        for (let i = 0; i < clients.length; i++) {
            if(clients[i] != null && clients[i].s === socket)
            {
                clients[i]=null;
                console.log("this client left --> "+(i+1));
            }
        }
        //they will add themselves
        //socket.socket.reconnect(); //re-adds them to the array giving new ID
    })

    socket.on("new-user",deets=>{

        clients[num++] = {key:deets.key, s:socket, data:deets}; //data would be an array of the songs they are listening to if integrated in that way

        //API CALL
        axios.post('https://u19014938:blueshoe@wheatley.cs.up.ac.za/u19014938/COS216/HW/php/api.php', {
            key: deets.key,
            type: "track",
            info:"get",
            title:deets.title,
            return: "*"
        })
        .then((res) => {
            // initialise the sockets intitial track progress
            socket.emit("INITIAL", `${res.data.progress}`);
            clients[num-1].data.progress = `${res.data.progress}`;
        })
        .catch((error) => {
            console.log(error)
        })

    })


    socket.on("UPDATE", (deets)=>{
        //var ts = Math.round((new Date()).getTime() / 1000); //unix epoch time
        console.log("updating")
        //adds the progress for the song to the array where the socket is the same
        //instead of making an API key every second
        //traverse through the array every time
        for (let i = 0; i < clients.length; i++) {
            if(clients[i] !== null && clients[i].s === socket) //same client making the request
            {
                // update their data
                clients[i].data = deets;
            }

        }
    })

    // socket.on("KILL", (key)=>{ //disconnect
    //     socket.emit("KILLED","hey ima disconnect u now so dont message back")
    //     for(var i =0; i < clients.length(); i++)
    //     {
    //         if(clients[i] == key)
    //         {
    //             arr.splice(i, 1); //index is start and then remove just one element
    //             socket.emit("KILLED", key);
    //         }
    //     }
    // })
    //
    // socket.on("LIST", ()=>{
    //     socket.emit("theLIST", clients);
    // })
})


// define the port the server will run on
let PORT = process.env.PORT || 3000;
server.listen(PORT, console.log(`Socket server running on port: ${PORT}`))

//setup the console commands for monitoring and controlling the socket connections
var rl = readline.createInterface(process.stdin, process.stdout);
rl.setPrompt('> ');
rl.prompt();
rl.on('line', function(line) {
    clear();
    if (line === "LIST")
        list()
    else if(line === "KILL") {
        rl.prompt();
    }
    else if(line === "QUIT")
        quit()
    else
        kill(line);

    rl.prompt();
}).on('close',function(){
    process.exit(0);
});


function list(){

    console.log("Client Connections: ---------------------")
    for (let i = 0; i < clients.length; i++) {
        if(clients[i]!=null)
            console.log("Client-id: "+(i+1)+" Auth-key: "+clients[i].key)
    }
}

function kill(option){
    if(clients[option-1] != null)
    {
        clients[option-1].s.emit("RECONNECT"); //only called when the user is killed directly. Reloads the page for new socket connection
        clients[option-1].s.disconnect('unauthorized'); //closes this socket, should set off disconnect function
        //make new connection by refreshing the page

    }

    clients[option-1] = null;
}

function quit(){

    for (let i = 0; i < clients.length; i++) {
        if(clients[i]!=null)
        {
            clients[i].s.emit("END");
            clients[i].s.disconnect('unauthorized');
        }

    }
    server.close();
    process.exit(0); //end terminal
}

function clear(){
    process.stdout.write("\u001b[3J\u001b[2J\u001b[1J");
    console.clear();
}


