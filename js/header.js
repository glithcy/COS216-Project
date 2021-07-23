$(document).ready(function(){

    var modal = document.getElementById("myModal");
    var modal1 = document.getElementById("rateModal");
    var span = document.getElementsByClassName("close")[0];
    var span1 = document.getElementsByClassName("close")[1];
    var q = document.getElementById("question");
    var h = document.getElementById("heading");

    q.innerHTML = "you want to change the Theme for the entire website?";
    h.innerHTML = "Are You Sure...";

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    span1.onclick = function() {
        modal1.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            //alert("oooh");
            modal.style.display = "none";
        }
    }

});


function rate(card){

    var b = document.getElementById("confirm1");
    //b.setAttribute("onsubmit", "formRate()");

    var title = document.getElementsByClassName("titl");
    var col = document.getElementsByClassName("column");
    var choice;
    for(let i=0;i<title.length;i++)
    {
        if(card === col[i])
        {
            choice = title[i].innerHTML;
        }
    }

    sessionStorage.setItem("choice",choice);

    var modal = document.getElementById("rateModal");
    var q = document.getElementById("question1");

    var h = document.getElementById("heading1");
    var l = document.getElementById("input1");

    h.innerHTML = choice;


    //its already selected in the header as modal
    modal.style.display = "block";
    l.innerHTML = "Accept";


}

function getCookie(name)
{
    var re = new RegExp(name + "=([^;]+)");
    var value = re.exec(document.cookie);
    return (value != null) ? unescape(value[1]) : null;
}

function formRate(choice){

    var star = document.getElementsByName("star");


    var txt = "";

    for (var i = 0; i < star.length; i++) {
        if (star[i].checked) {
            txt = star[i].value;
        }
    }

    var c = sessionStorage.getItem("choice");

    //alert("star: " +txt);
    //alert("choice: " +c);

    var key = getCookie("key");
    //alert(key);
    let formData = {
        "key": key,
        "type": "rate",
        "title": c,
        "rating": txt,
        "return": "*"
    };

    $.ajax({
        url: './php/api.php',
        type: 'post',
        dataType: 'json',
        contentType: 'application/json',
        success: function (res) {
           // alert("real success");
        },
        error: function (xhr) {
            if(xhr.status === 200){
               // alert("success");
                var modal = document.getElementById("rateModal");
                modal.style.display = "none";
                location.reload();
            }else{
                // fail
            }
        },
        data: JSON.stringify(formData)
    });

    //return true;
}