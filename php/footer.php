<?php

if(isset($_COOKIE['key']) ) //only display the toggle if logged in
{
//switch html and css
echo ' <div id = "toggle" >
            <span id="s">Theme </span>
            <label class="switch" style="bottom:0px">
                <input type="checkbox" id="switch">
                <span class="slider round" onclick="changeTheme()"></span>
            </label>
       </div>';

echo '<footer id="footer">Flow Music.</footer>';


    echo '
        <script>
        
            function getCookie(name)
            {
                var re = new RegExp(name + "=([^;]+)");
                var value = re.exec(document.cookie);
                return (value != null) ? unescape(value[1]) : null;
            }
            
            //toggle button
            //allow them to confirm the change
            //document.getElementById("toggle").addEventListener.onclick = changeTheme();

            
            function changeTheme(){
                //connect to db via ajax call
                //Confirm
            

                //make preference change
                if(getCookie("theme") === "dark"){
                    //alert("change theme light----");
                    makeLight();    
                }else if(getCookie("theme") === "light"){
                    //alert("change theme dark----");
                    makeDark();
                }else
                {
                    //the theme is null
                    //cookie should be set to dark by default
                }
                
                //change preferences in db

            }
            
            function makeDark(){
                document.cookie = "theme=dark"
                var nav = document.getElementById("ul").style.backgroundColor = "#BF2762";    
                var footer = document.getElementById("footer").style.backgroundColor = "#280428"; 
                var footer = document.getElementById("s").style.color = "#BF2762";  
                var footer = document.getElementById("footer").style.color = "#F868A3";  
               // var main = document.getElementsByName("body")[0].style.backgroundColor = black;
               makecall("dark");
            }
            
            function makeLight(){
                document.cookie = "theme=light" 
                var nav = document.getElementById("ul").style.backgroundColor = "#F868A3";   
                var footer = document.getElementById("footer").style.backgroundColor = "#F868A3";  
                var footer = document.getElementById("footer").style.color = "#BF2762";  
                var footer = document.getElementById("s").style.color = "black";  
                makecall("light");
            }
            
            function makecall(theme)
            {
                var key = getCookie("key");
                let formData = {
                    "key": key,
                    "type": "update",
                    "theme": theme,
                    "return": "*"
                };
            
                $.ajax({
                    url: \'./php/api.php\',
                    type: \'post\',
                    dataType: \'json\',
                    contentType: \'application/json\',
                    success: function (res) {
                        //alert("real success");
                    },
                    error: function (res) {
                        if(res.status === 200){
                            //alert("success");
                        }else{
                            //alert("fail");
                        }
                    },
                    data: JSON.stringify(formData)
                });
            }
            
            
        ';


    //setting the page onload;
    if(isset($_COOKIE['theme']))
    {
        if( $_COOKIE['theme']  == "dark") {

            echo 'makeDark();';
            $_COOKIE['theme']  = "light";
        }elseif($_COOKIE['theme']  == "light")
        {
            echo 'makeLight();';
            $_COOKIE['theme']  = "dark";
        }
    }

    if(isset($_COOKIE['genre']))
    {
        echo '
                function getCookie(name)
                {
                    var re = new RegExp(name + "=([^;]+)");
                    var value = re.exec(document.cookie);
                    return (value != null) ? unescape(value[1]) : null;
                }
            
                //var genre = document.getElementById("genre").value = getCookie("genre");
                //var year = document.getElementById("year").value = getCookie("year");
             
        ';

    }

echo
        '
        </script>

        ';

        
 echo '       <style>
            #toggle{
                background-color: rgba(0,0,0,0.5); 
                padding: 10px; 
                position: absolute; 
                width: 15vw; 
                height: 4vh; 
                bottom: 0px; 
                right: 1%;
            }
            
            #s{
                position: fixed; 
                right: 8vw; 
                z-index:1; 
                color: black; 
                bottom: 0px
            }
        
            .switch {
              position: absolute;
              display: inline-block;
              right: 30px;
              width: 60px;
              height: 3vh;
              z-index:1;
            }
            
            /* Hide default HTML checkbox */
            .switch input {
              opacity: 0;
              width: 0;
              height: 0;
            }
            
            /* The slider */
            .slider {
              position: absolute;
              cursor: pointer;
              top: 0;
              left: 0;
              right: 0;
              bottom: 0;
              background-color: #ccc;
              -webkit-transition: .4s;
              transition: .4s;
            }
            
            .slider:before {
              position: absolute;
              content: "";
              height: 2vh;
              width: 26px;
              left: 4px;
              bottom: 4px;
              background-color: white;
              -webkit-transition: .4s;
              transition: .4s;
            }
            
            input:checked + .slider {
              background-color: #c010c6;
            }
            
            input:focus + .slider {
              box-shadow: 0 0 1px #c010c6;
            }
            
            input:checked + .slider:before {
              -webkit-transform: translateX(26px);
              -ms-transform: translateX(26px);
              transform: translateX(26px);
            }
            
            /* Rounded sliders */
            .slider.round {
              border-radius: 34px;
            }
            
            .slider.round:before {
              border-radius: 50%;
            }
        </style>
    ';

}else{
    echo '<footer id="footer">Flow Music.</footer>';
}

