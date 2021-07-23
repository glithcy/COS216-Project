<?php
echo	'<script>
		        

		        function logout() //change logout and login buttons
		        { 
		             let logout = document.getElementById(\'login\') ;
                     let name = document.getElementById(\'register\') ;
                     //let api = document.getElementById(\'out\') ;
                     
                     //api.remove();
                    
                     name.setAttribute("href", "signup.php");
                     logout.setAttribute("href", "login.php");
                     
                     
                     name.innerHTML = "Register";
                     logout.innerHTML = "Login";
                     
                     logout.removeAttribute("onclick");
                     
                     sessionStorage.removeItem("key");
                     sessionStorage.removeItem("firstname");
                     sessionStorage.removeItem("surname");
                     sessionStorage.removeItem("theme");
                     
                     name.style.color = "#8969e0";
                     logout.style.color = "#8969e0";
                     
                     logout.style.backgroundColor = "#280428";
                     name.style.backgroundColor = "#280428";
                     
                     document.cookie = "key= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
                     document.cookie = "theme= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
                     document.cookie = "firstname= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
                     document.cookie = "surname= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
                     document.cookie = "genre= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
                     document.cookie = "year= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
                     
                 }
		   </script>
		    ';

//$past = time() - 3600;
//setCookie($_COOKIE['firstname'], "", $past, '/' );
//setCookie($_COOKIE['key'], "", $past, '/' );
//                        foreach ( $_COOKIE as $key => $value )
//                        {
//                            if()
//                            {
//                                setcookie( $key, $value, $past, '/' );
//                            }
//
//                        }