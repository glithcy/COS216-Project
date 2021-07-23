<?php include 'config.php'; ?>
<?php
    //session_start();
    $token = "key";
    $name = "firstname";
    $surname = "surname";
    $theme = "theme";
    if(isset($_COOKIE[$token]) && isset($_COOKIE[$name]) && isset($_COOKIE[$surname]))
    {
        echo	'<nav>
                <ul id="ul">
                    <li><a id = "0" href="index.php">Home</a></li>
                    <li><a id = "login" href="index.php" onclick="logout()">Logout</a></li>
                    <li><a id = "register">' . $_COOKIE[$name] . ' ' .  $_COOKIE[$surname] . '</a></li>
                    <li><a id = "1" href="calendar.php">Calendar</a></li>
                    <li><a id = "2" href="track.php">Track</a></li>
                    <li><a id = "3" href="featured.php">Featured</a></li>
                    <li><a id = "4" href="topRated.php">Top Rated</a></li>
                    <li><a id = "5" href="newReleases.php">New Releases</a></li>
                    <li><a id = "6" href="trending.php">Trending</a></li>
                </ul>
		    </nav>
		    
		    <script>
		        let login = document.getElementById(\'login\') ;
                let register = document.getElementById(\'register\') ;
                login.style.cursor = "pointer";
                register.style.color = "#F868A3";
                login.style.color = "#F868A3";
                login.style.backgroundColor = "#280428";
                register.style.backgroundColor = "#280428";
		    </script>
		    
		    <script src="./js/header.js"></script>
		    
		    ';
    }else{
        echo	'<nav>
                <ul id="ul">
                    <li><a id = "0" href="index.php">Home</a></li>
                    <li><a id = "login" href="login.php">Login</a></li>
                    <li><a id = "register" href="signup.php">Register</a></li>
                    <li><a id = "1" href="calendar.php">Calendar</a></li>
                    <li><a id = "2" href="track.php">Track</a></li>
                    <li><a id = "3" href="featured.php">Featured</a></li>
                    <li><a id = "4" href="topRated.php">Top Rated</a></li>
                    <li><a id = "5" href="newReleases.php">New Releases</a></li>
                    <li><a id = "6" href="trending.php">Trending</a></li>
                </ul>
		    </nav>';
    }

//echo	'
//		    <script>
//		        if(getCookie("theme") === "dark"){
//                alert("chnage theme light login");
//                makeLight();
//                document.cookie = "theme=light"
//                }else if(getCookie("theme") === "light"){
//                    alert("chnage theme dark login");
//                    makeDark();
//                    document.cookie = "theme=dark"
//                }else
//                {
//                    //the theme is null
//                    alert("default make dark");
//                    makeDark();
//                }
//		        
//		        function makeDark(){
//                    var nav = document.getElementById("ul").style.backgroundColor = "#BF2762";    
//                    var footer = document.getElementById("footer").style.backgroundColor = "#280428";  
//                   // var main = document.getElementsByName("body")[0].style.backgroundColor = black;
//                }
//                
//                function makeLight(){
//                    var nav = document.getElementById("ul").style.backgroundColor = "#F868A3";   
//                    var footer = document.getElementById("footer").style.backgroundColor = "#F868A3";  
//                }
//		    </script>
		    
	//	    ';

//if(isset($_COOKIE[$theme]))
//{
//    echo	'
//		    <script>
//		        if(getCookie("theme") === "dark"){
//                alert("chnage theme light login");
//                makeLight();
//                document.cookie = "theme=light"
//                }else if(getCookie("theme") === "light"){
//                    alert("chnage theme dark login");
//                    makeDark();
//                    document.cookie = "theme=dark"
//                }else
//                {
//                    //the theme is null
//                }
//		    </script>
//
//		    ';
//}else{
//    echo	'<nav>
//                <ul id="ul">
//                    <li><a id = "0" href="index.php">Home</a></li>
//                    <li><a id = "login" href="login.php">Login</a></li>
//                    <li><a id = "register" href="signup.php">Register</a></li>
//                    <li><a id = "1" href="calendar.php">Calendar</a></li>
//                    <li><a id = "2" href="track.php">Tour</a></li>
//                    <li><a id = "3" href="featured.php">Featured</a></li>
//                    <li><a id = "4" href="topRated.php">Top Rated</a></li>
//                    <li><a id = "5" href="newReleases.php">New Releases</a></li>
//                    <li><a id = "6" href="trending.php">Trending</a></li>
//                </ul>
//		    </nav>';
//}


echo '
        <div id="myModal" class="modal">
        <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close">&times;</span>
                    <h2 id="heading"></h2>
                </div>
                <div class="modal-body" id="body">
                    <p id="question" style="color: black"></p>

                    <form action="#" id="confirm" style="bottom: 1vh" onsubmit="return false"> 
                        <input id="input" type="submit" value="Accept">
                        <input id="input2" type="submit" value="Rate">
                    </form>
                </div>
                <div class="modal-footer">
                    <h5>Flow Music.</h5>
                </div>
            </div>
        </div>
        
        <div id="rateModal" class="modal">
        <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close">&times;</span>
                    <h1 id="heading1"></h1>
                </div>
                <div class="modal-body" id="body">
                    <p id="question1" style="color: black">Rate this song</p>

                    <form action="#" id="confirm1" style="bottom: 1vh" onsubmit="formRate(); return false"> 
                        <input style="background-color: black" type="radio" id="1star" name="star" value="1">
                        <input style="background-color: black" type="radio" id="2star" name="star" value="2">
                        <input style="background-color: black" type="radio" id="3star" name="star" value="3" checked="checked">
                        <input style="background-color: black" type="radio" id="4star" name="star" value="4">
                        <input style="background-color: black" type="radio" id="5star" name="star" value="5">
                        <br/>
                        <input id="input1" type="submit" value="Rate">
                    </form>
                </div>
                <div class="modal-footer">
                    <h5>Flow Music.</h5>
                </div>
            </div>
        </div>
    ';


echo '
        <style>
            #heading{
                font-size: 5vh;
                color: white;
            }
            
            .modal {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                padding-top: 100px; /* Location of the box */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }
            
            /* Modal Content */
            .modal-content {
                position: relative;
                background-color: #fefefe;
                margin: auto;
                padding: 0;
                border: 1px solid #888;
                width: 30%;
                box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
                -webkit-animation-name: animatetop;
                -webkit-animation-duration: 0.4s;
                animation-name: animatetop;
                animation-duration: 0.4s
            }
            
            /* Add Animation */
            @-webkit-keyframes animatetop {
                from {top:-300px; opacity:0}
                to {top:0; opacity:1}
            }
            
            @keyframes animatetop {
                from {top:-300px; opacity:0}
                to {top:0; opacity:1}
            }
            
            /* The Close Button */
            .close {
                color: white;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }
            
            .close:hover,
            .close:focus {
                color: #000;
                text-decoration: none;
                cursor: pointer;
            }
            
            .modal-header {
                padding: 5px 16px;
                background-color: #bf2762;
                color: white;
            }
            
            .modal-body {padding: 2px 16px;}
            
            .modal-footer {
                padding: 2px 16px;
                background-color: #bf2762;
                color: white;
            }
            
            #input1, input[type=submit]{
                background-color: black;
                height = 20px;
                width: 10vw;
                border-radius: 5px;
                color: white;
            }
            
            .slider{
                left-margin: auto;
                right-margin: auto;
                display: block;
                bottom: 2vh;
                background: #bf2762;
            }
            
            #range{
                right:0px;
            }
            
            input{
                -moz-box-shadow: none; 
                box-shadow: none;
            }

        </style>
    ';

?>