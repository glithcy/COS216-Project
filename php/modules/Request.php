<?php
require_once "Validator.php";
require_once './config.php';

class Request
{
    private static $instance;

    private function __construct()
    {
    }

    public function Resolve()
    {
        $req = json_decode(file_get_contents("php://input")); //input is the raw data on postman

        if(isset($req->key) != true) //key has not been set, used to validate details and send key back with success response
        {
            if(isset($req->type) && $req->type == "login") {

                header("Content-Type: application/json");
                //the details of the person (name and password) must be sent through
                //the type will be login
                //add parameters username and password to test the user login
                if (isset($req->email) && isset($req->password)) {
                    //correct parameters added otherwise send an error.


                    function test_input($data)
                    {
                        $data = trim($data);
                        $data = stripslashes($data);
                        $data = htmlspecialchars($data);
                        return $data;
                    }

                    $email = $req->email;
                    $pass = $req->password;

                    $salt = "-45dfeHK/__yu349@-/klF21-1_\/4JkUP/4";
                    $salt .= chr(ord(substr($pass, 0, 1)) + 3);
                    $salt .= chr(ord(substr($pass, 1, 1)) + 3);
                    $salt .= chr(ord(substr($pass, 2, 1)) + 3);
                    $pass .= $salt; //salt is added to end


                    try {

                        $query = Connection::getInstance()->getConnection()->prepare("SELECT * FROM Users WHERE Email=?");
                        $query->execute([$email]);

                        if ($user = $query->fetch()) { //retrieved something (exists in db)
                            $hash = $user['Password'];

                            if (password_verify($pass, $hash)) {

                                $key = $user['API_key'];
                                $name = $user['Name'];
                                $surname = $user['Surname'];
                                $theme = $user['Theme'];
                                $genre = $user['Genre'];
                                $year = $user['Year'];

                                echo json_encode(array("status" => "success", "timestamp" => time(), "key" => $key, "firstname" => $name, "surname"=> $surname, "theme"=> $theme, "genre"=> $genre, "year"=> $year));

                            } else {
                                self::error("Invalid Password");
                            }
                        } else {
                            self::error("Email not in DB");
                        }

                    } catch (PDOException $e) {
                        self::error("Couldn't connect to DB");
                    }
                }
            }else{ //no type set therefore error
                //there is no API key so if the type isn't called login then there will be an error
                self::error("Access Denied. No API key");
            }

        }else { //api key is set
            if (!Validator::validate($req->key)) {
                http_response_code(401);
                header("Content-Type: application/json");
                self::error("Unauthorised");
            } else { //valid api key
                if (isset($req->return)) {
                    if(isset($req->type))
                    {
                        if($req->type == "info")
                        {
                            if(isset($req->title)) {
                                if ($req->title == "*") //get all titles from the spotify playlist. Do a deezer search request for each
                                {
                                    if ($req->return[0] == "*") {
                                        $response = $this->request($req->key,"https://api.spotify.com/v1/playlists/6nKgvpnVm71pmTb4kjXRWE", true);
                                        if (isset($req->type)) {
                                            if ($req->type == "info") {
                                                self::ResponseObject($response);
                                            } else {

                                            }
                                            //for prac 4 (like update and things)
                                        } else {
                                            self::error("Type must be set");
                                        }

                                    } else //array of specific details for entire array
                                    {
                                        $response = $this->request($req->key, "https://api.spotify.com/v1/playlists/6nKgvpnVm71pmTb4kjXRWE");
                                        if (isset($req->type)) {
                                            if ($req->type == "info") {
                                                $array = array();
                                                foreach ($response as $item) {
                                                    if (isset($req->return)) {
                                                        $array[] = $this->selective($item, $req);
                                                    }
                                                }
                                                self::ResponseObject($array);
                                            } else {

                                            }
                                            //for prac 4 (like update and things)
                                        } else {
                                            self::error("Type must be set");
                                        }
                                    }

                                } else //specific song by title if it is not empty. If empty go to ranking
                                {
                                    $this->extraFields($req, "title", true);
                                }
                            }else if (isset($req->rating)) {
                                $this->extraFields($req, "rating");
                            }//Will be called if title and ranking are empty
                            else if (isset($req->ranking)) {
                                $this->extraFields($req, "ranking");
                            } else if (isset($req->artist)) {
                                $this->extraFields($req, "artist", true);
                            } else if (isset($req->album)) {
                                $this->extraFields($req, "album", true);
                            } else
                            {
                                self::error("No info parameters set.");
                            }

                        }else if($req->type == "update")
                        {
                            echo "update";
                            $update = false;
//                                    http_response_code(200);
                            if(isset($req->theme))
                            {
                                try {
                                    $s = "UPDATE Users SET Theme=? WHERE API_key=?";
                                    $query = Connection::getInstance()->getConnection()->prepare($s);
                                    $query->execute([$req->theme, $req->key]);

                                    $update = true;
                                }catch(PDOException $e)
                                {
                                    self::error("Can't update theme");
                                }

                            }

                            if(isset($req->genre))
                            {
                                try {
                                    $s = "UPDATE Users SET Genre=? WHERE API_key=?";
                                    $query = Connection::getInstance()->getConnection()->prepare($s);
                                    $query->execute([$req->genre, $req->key]);
                                    $update = true;

                                }catch(PDOException $e)
                                {
                                    self::error("Can't update genre");
                                }

                            }

                            if(isset($req->year))
                            {
                                try {

                                    $s = "UPDATE Users SET Year=? WHERE API_key=?";
                                    $query = Connection::getInstance()->getConnection()->prepare($s);
                                    $query->execute([$req->year, $req->key]);
                                    $update = true;
                                }catch(PDOException $e)
                                {
                                    self::error("Can't update year");
                                }

                            }

                            if($update !== true)
                            {
                                self::error("Couldn't update because variables not set");
                            }
                        }else if($req->type == "rate")
                        {

                            if(isset($req->title))
                            {

                                if(isset($req->rating))
                                {

                                    try
                                    {
                                        //select a song from the songs table
                                        //if the song doesnt exist add the title
                                        //check if this user has rated this song before: check if api and song entry exists in table
                                        //if exists update the entry
                                        //if it doesnt exist add a new entry with API and title and rating


                                        $query = Connection::getInstance()->getConnection()->prepare("SELECT * FROM Songs WHERE Song_Name=?");
                                        $query->execute([$req->title]);

                                        if (!$song = $query->fetch()) //dosent retrieve something from db
                                        {
                                            $sql = "INSERT INTO `Songs` (`Song_Name`) VALUES (:song)";
                                            $statement = Connection::getInstance()->getConnection()->prepare($sql);
                                            $statement->execute(array(":song"=>$req->title));
                                        }//song should now be in db

                                        //check if user has rated before
                                        $query = Connection::getInstance()->getConnection()->prepare("SELECT * FROM Ratings WHERE Song_Name=? AND API_key=?");
                                        $query->execute([$req->title, $req->key]);

                                        if (!$rating = $query->fetch()) //doesn't retrieve something from db
                                        {
                                            $sql = "INSERT INTO `Ratings` (`API_key`, `Song_Name`, `Rating`) VALUES (:api, :song, :rating)";
                                            $statement = Connection::getInstance()->getConnection()->prepare($sql);
                                            $statement->execute(array(":api"=>$req->key, ":song"=>$req->title, ":rating"=>$req->rating));
                                        }else
                                        {
                                            $sql = "UPDATE Ratings SET Rating=? WHERE API_key=? AND Song_Name=?";
                                            $statement = Connection::getInstance()->getConnection()->prepare($sql);
                                            $statement->execute([$req->rating, $req->key, $req->title]);
                                        }


                                    }catch(PDOException $e)
                                    {
                                        self::error("Can't update user rating because of: ".$e->getMessage());
                                    }
                                }else
                                {
                                    self::error("Rating not set for rating");
                                }
                            }else
                            {
                                self::error("Title not set for rating");
                            }
                        }else if($req->type == "track")
                        {
                            if(isset($req->title))
                            {
                                if(isset($req->info))
                                {
                                    if($req->info === "set")
                                    {
                                        if(isset($req->timestamp)) //should be datetime format
                                        {
                                            //if timestamp is incorrect then the db will send error
                                            if(isset($req->progress))
                                            {
                                                try
                                                {
                                                    //select a song from the songs table
                                                    //if the song doesnt exist add the title
                                                    //check if this user has rated this song before: check if api and song entry exists in table
                                                    //if exists update the entry
                                                    //if it doesnt exist add a new entry with API and title and rating

                                                    //only if progress is ore than what already there then update it and send back then updated progress
                                                    //for updating the audio element which is there

                                                    $query = Connection::getInstance()->getConnection()->prepare("SELECT * FROM Songs WHERE Song_Name=?");
                                                    $query->execute([$req->title]);

                                                    if (!$song = $query->fetch()) //dosent retrieve something from db
                                                    {
                                                        $sql = "INSERT INTO `Songs` (`Song_Name`) VALUES (:song)";
                                                        $statement = Connection::getInstance()->getConnection()->prepare($sql);
                                                        $statement->execute(array(":song" => $req->title));
                                                    }//song should now be in db

                                                    //check if user has listened to song before
                                                    $query = Connection::getInstance()->getConnection()->prepare("SELECT * FROM Tracks WHERE Song_Name=? AND API_key=?");
                                                    $query->execute([$req->title, $req->key]);

//                                                    $epoch = $req->timestamp;
//                                                    $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
//                                                    $d = $dt->format('Y-m-d H:i:s');

                                                    if (!$track = $query->fetch()) //doesn't retrieve something from db
                                                    {
                                                        $sql = "INSERT INTO `Tracks` (`API_key`, `Song_Name`, `Time_stamp`, `Progress`) VALUES (:api, :song, :time_, :progress)";
                                                        $statement = Connection::getInstance()->getConnection()->prepare($sql);
                                                        $statement->execute(array(":api" => $req->key, ":song" => $req->title, ":time_" => $req->timestamp, ":progress" => $req->progress));
                                                    }//will be in db after this

                                                    //retrieve the progress (extra sql call not good coding but eh, ifs too much)
                                                    $query = Connection::getInstance()->getConnection()->prepare("SELECT * FROM Tracks WHERE Song_Name=? AND API_key=?");
                                                    $query->execute([$req->title, $req->key]);

                                                    $track = $query->fetch();
                                                    $progress = $track['Progress'];



                                                    $sql = "UPDATE Tracks SET Time_stamp=?, Progress=? WHERE API_key=? AND Song_Name=?";
                                                    $statement = Connection::getInstance()->getConnection()->prepare($sql);
                                                    $statement->execute([$req->timestamp, $req->progress, $req->key, $req->title]);
                                                                        //latest timestamp    doesnt matter if you update the progress


                                                    if($progress != $req->progress) //send back a null response
                                                    {
                                                        self::progress($req->progress); //will be latest progress from server
                                                    }else{
                                                        self::progress(-1); //no difference, no need to update the sockets with this
                                                    }
//

                                                } catch (PDOException $e)
                                                {
                                                    self::error("Can't update track progress because of: " . $e->getMessage());
                                                }
                                            }else{
                                                self::error("no progress");
                                            }

                                        }else{
                                            self::error("no timestamp declared");
                                        }
                                    }else if($req->info === "get")
                                    { //no progress or timestamp
                                        try
                                        {
                                            $query = Connection::getInstance()->getConnection()->prepare("SELECT * FROM Songs WHERE Song_Name=?");
                                            $query->execute([$req->title]);

                                            if (!$song = $query->fetch()) //dosent retrieve something from db
                                            {
                                                $sql = "INSERT INTO `Songs` (`Song_Name`) VALUES (:song)";
                                                $statement = Connection::getInstance()->getConnection()->prepare($sql);
                                                $statement->execute(array(":song" => $req->title));
                                            }//song should now be in db

                                            //check if user has listened to song before
                                            $query = Connection::getInstance()->getConnection()->prepare("SELECT * FROM Tracks WHERE Song_Name=? AND API_key=?");
                                            $query->execute([$req->title, $req->key]);

                                            if (!$track = $query->fetch()) //doesn't retrieve something from db
                                                $progress = 0; //user hasn't listened to it before
                                            else
                                                $progress = $track["Progress"];

                                            self::progress($progress);

                                        } catch (PDOException $e)
                                        {
                                            self::error("Can't update track progress because of: " . $e->getMessage());
                                        }
                                    }

                                }else{
                                    self::error("no info type set (set/get)");
                                }
                            }else{
                                self::error("no song title set for track");
                            }
                        }else
                        {
                            self::error("Not a valid type");
                        }
                    }else
                    {
                        self::error("Type not set.");
                    }
                }else
                {
                    self::error("Return not set");
                }

            } //end of valid api key
        } //end of api key is set
    } //end of resolve function


    public static function getInstance()
    {
        if(self::$instance==null)
            self::$instance = new Request();
        return self::$instance;
    }
    public function spotifyGET($url)
    {
        $token  = json_decode($this->spotifyPOST());
        //$token  = json_decode($this->spotifyPOST(), true);
        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_RETURNTRANSFER => 1,

            CURLOPT_URL => $url,

            CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$token->access_token]

        ));
        curl_setopt($curl, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
        $result = curl_exec($curl);
        $http = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        http_response_code($http);
        curl_close ($curl);
        return $result;
    }

    public function spotifyPOST()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_RETURNTRANSFER => 1,

            CURLOPT_URL => "https://accounts.spotify.com/api/token",

            CURLOPT_POST => 1,

            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',

            CURLOPT_HTTPHEADER => ['Authorization: Basic OGMzY2FiOWZmNjhjNDhhZmJiY2I1NzhkMWY3MDFmZGY6NTU1NzY5MjRmNzA2NGU5MTlhOTljYzdkNzFiODM3NWM=','Content-Type: application/x-www-form-urlencoded']

        ));
        curl_setopt($curl, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
        $result = curl_exec($curl);
        $http = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        http_response_code($http);
        curl_close ($curl);
        return $result;
    }

    public function deezer($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_RETURNTRANSFER => 1,

            CURLOPT_URL => $url,

            CURLOPT_HTTPHEADER => ['x-rapidapi-host: deezerdevs-deezer.p.rapidapi.com','x-rapidapi-key: 49a713da49msh3bbfda21ad3acb3p1203ebjsn5e4aa2f30bb5']
        ));
        curl_setopt($curl, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
        $result = curl_exec($curl);
        $http = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        http_response_code($http);
        curl_close ($curl);
        return $result;
    }

    public function request($api, $url, $gg = false){ //if $genres is true then the genres are added
        //returns php object which can have the individual data pulled from each object as its traversed
        $data = $this->spotifyGET($url);
        //echo $data;
        header("Content-Type: application/json"); //tells how to display it
        //don't put anything before this header you will get a token error *rolls eyes*
        //get the array of songs to loop through and make new array to add to using Deezer and send back
        $data = json_decode($data);
        $items = $data->tracks->items; //accessing php associative array (simpler than the json way)
        $myItems = array(); //php array to be translated into json

        //foreach($items as $song) { //make Deezer search inside
        for ($x = 0; $x < 20; $x++)
        {
            $song = $items[$x];
            $mySong = new stdClass(); //must create a new object for each of them
            $song = $song->track;
            $mySong->title = $song->name;
            $mySong->artist = $song->album->artists[0]->name;
            $mySong->artwork = $song->album->images[1]->url;
            $mySong->release = $song->album->release_date;
            $mySong->album = $song->album->name;
            $mySong->duration = ceil($song->duration_ms/1000) . "s";
            $mySong->preview = $song->preview_url;
            $mySong->album_type = $song->album->album_type;
            //$mySong->rating = strval(round($song->popularity/20,2));
            $mySong->rating = self::rate($api, $mySong->title); //retrives the users rating using the api key
            $myItems[] = $mySong;
        }
        //sort the array based off of the ratings
        usort($myItems,function($first,$second){
            return $first->rating < $second->rating;
        });
        //loop through and add billboard ratings based off of popularity
        for ($x = 1; $x <= sizeof($myItems); $x++) {

            $s = strval($x);
            $myItems[$x-1]->ranking =  $s ;
        }

        if($gg)
        {
            //get genres through deezer (can be added or removed when necessary)
            //takes too long to load
            for ($x = 0; $x < 20; $x++) {
                //gets a single track element
                $albumID = json_decode($this->deezer("https://deezerdevs-deezer.p.rapidapi.com/search?limit=1&q=". urlencode($myItems[$x]->title)));
                //can return nothing, genre set to nothing
                if(isset($albumID->data[0])){
                    $albumID = $albumID->data[0]->album->id;
                    $genres = json_decode($this->deezer("https://deezerdevs-deezer.p.rapidapi.com/album/". urlencode($albumID)));
                    $genres = $genres->genres->data; //data returns an array
                    $array = array();
                    foreach($genres as $item){
                        $array[] = $item->name;
                    }
                    $item = null;
                    $myItems[$x]->genres = $array; //an array
                }else{
                    //add empty array
                    $arr = array();
                    $myItems[$x]->genres = $arr; //an array
                }

            }
        }
        return $myItems;
    }

    public function selective($item, $req){
        $list = new stdClass();
        foreach ($req->return as $return)
        {
            if ($return == "title")
                $list->title = $item->title;
            else if ($return == "artist")
                $list->artist = $item->artist;
            else if ($return == "artwork")
                $list->artwork = $item->artwork;
            else if ($return == "release")
                $list->release = $item->release;
            else if ($return == "album")
                $list->album = $item->album;
            else if ($return == "duration")
                $list->duration = $item->duration;
            else if ($return == "rating")
                $list->rating = $item->rating;
            else if ($return == "preview")
                $list->preview = $item->preview;
            else if ($return == "album_type")
                $list->album_type = $item->album_type;
            else if ($return == "genre") {
                $albumID = json_decode($this->deezer("https://deezerdevs-deezer.p.rapidapi.com/search?limit=1&q=" . urlencode($item->title)));
                if (isset($albumID->data[0])) {
                    $albumID = $albumID->data[0]->album->id;
                    $genres = json_decode($this->deezer("https://deezerdevs-deezer.p.rapidapi.com/album/" . urlencode($albumID)));
                    $genres = $genres->genres->data; //data returns an array
                    $arr = array();
                    foreach ($genres as $genre) {
                        $arr[] = $genre->name;
                    }
                    $genre = null;
                    $list->genres = $arr; //an array
                }else{
                    //add empty array
                    $arr = array();
                    $list->genres = $arr; //an array
                }
            } else if ($return == "ranking")
                $list->ranking = $item->ranking;
        }
        return $list;
    }

    public static function ResponseObject($array)
    {
        header("Content-Type: application/json");
        if(!(http_response_code()==200))
        {
            http_response_code(400);
            echo json_encode(array("status"=>"failure", "timestamp"=>time(),"data"=> []));
        }
        else
            echo json_encode(array("status"=>"success", "timestamp"=>time(),"data"=>$array));
    }

    public static function error($message)
    {
        header("Content-Type: application/json");
        http_response_code(400);
        echo json_encode(array("status"=>"failure", "timestamp"=>time(), "message"=>$message));
    }

    public static function progress($progress)
    {
        header("Content-Type: application/json");
        if(!(http_response_code()==200))
        {
            http_response_code(400);
            echo json_encode(array("status"=>"failure", "timestamp"=>time(),"progress"=>""));
        }
        else
            echo json_encode(array("status"=>"success", "timestamp"=>time(),"progress"=>$progress));
    }

    public function extraFields($req, $field, $search = false) //search is a boolean saying whether the string must be found within the other string
    {
        if($req->$field != "")
        {
            $response = $this->request($req->key,"https://api.spotify.com/v1/playlists/6nKgvpnVm71pmTb4kjXRWE");
            if ($req->return[0] == "*") //return all fields
            {
                if (isset($req->type) && $req->type == "info") {
                    $songs = array();
                    foreach ($response as $song) {
                        if($search !== true)
                        {
                            $r = $req->$field;
                            if ($r === $song->$field) {
                                //find genre before echoing song
                                $albumID = json_decode($this->deezer("https://deezerdevs-deezer.p.rapidapi.com/search?limit=1&q=" . urlencode($song->title)));
                                if (isset($albumID->data[0])) {
                                    $albumID = $albumID->data[0]->album->id;
                                    $genres = json_decode($this->deezer("https://deezerdevs-deezer.p.rapidapi.com/album/" . urlencode($albumID)));
                                    $genres = $genres->genres->data; //data returns an array
                                    $arr = array();
                                    foreach ($genres as $item) {
                                        $arr[] = $item->name;
                                    }
                                    $item = null;
                                    $song->genres = $arr; //an array
                                }else{
                                    //add empty array
                                    $arr = array();
                                    $song->genres = $arr; //an array
                                }
                                $songs[] = $song;
                            }
                        }else
                        {
                            $s = strtoupper($song->$field);
                            $r = strtoupper($req->$field);
                            if (strpos($s, $r) !== false)  //means the song contains the title requested
                            {
                                //find genre before echoing song
                                $albumID = json_decode($this->deezer("https://deezerdevs-deezer.p.rapidapi.com/search?limit=1&q=" . urlencode($song->title)));
                                if (isset($albumID->data[0])) {
                                    $albumID = $albumID->data[0]->album->id;
                                    $genres = json_decode($this->deezer("https://deezerdevs-deezer.p.rapidapi.com/album/" . urlencode($albumID)));
                                    $genres = $genres->genres->data; //data returns an array
                                    $array = array();
                                    foreach ($genres as $item) {
                                        $array[] = $item->name;
                                    }
                                    $item = null;
                                    $song->genres = $array; //an array
                                }else{
                                    //add empty array
                                    $arr = array();
                                    $song->genres = $arr; //an array
                                }
                                $songs[] = $song;
                            }
                        }

                    }//end for loop
                    self::ResponseObject($songs); //an array of all songs which matched the description
                } else {
                    //for prac 4 (like update and things)
                }
            } else {
                if (isset($req->type) && $req->type == "info") {
                    $array = array();
                    foreach ($response as $item) {
                        if($search !== true)
                        {
                            if ($req->$field === $item->$field)  //means the song contains the title requested
                            {
                                if (isset($req->return)) {
                                    $array[] = $this->selective($item, $req);
                                }
                            }
                        }else
                        {
                            $s = strtoupper($item->$field);
                            $r = strtoupper($req->$field);
                            if (strpos($s, $r) !== false)  //means the song contains the title requested
                            {
                                if (isset($req->return)) {
                                    $array[] = $this->selective($item, $req);
                                }
                            }
                        }

                    } //foreach item in playlist loop
                    self::ResponseObject($array);
                }
            }
        }else
        {
            $arr = array();
            self::ResponseObject($arr);
        }
    }

    public function update($param, $value, $api){
        try {

            $s = "UPDATE Users SET ?=? WHERE API_key=?";
            $query = Connection::getInstance()->getConnection()->prepare($s);
            $query->execute([$param, $value, $api]);

            //echo json_encode(array("status"=>"success", "timestamp"=>time(), "message"=>"updated".$param));
            echo "success";

        } catch (PDOException $e) {
            self::error("Invalid Parameter");
        }
    }


    public function rate($api, $song)
    {
        $query = Connection::getInstance()->getConnection()->prepare("SELECT * FROM Ratings WHERE Song_Name=? AND API_key=?");
        $query->execute([$song, $api]);

        if ($rat = $query->fetch()) //retrieves something
        {
            //found a rating
            $rating = $rat['Rating'];
        }else
        {
            $rating = "";
        }

        return $rating; //can be empty string
    }

}