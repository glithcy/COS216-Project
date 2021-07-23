var cal = {
  /* [PROPERTIES] */
  mName : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], // Month Names
  data : null, // Events for the selected period
  sDay : 0, // Current selected day
  sMth : 0, // Current selected month
  sYear : 0, // Current selected year
  sMon : false, // Week start on Monday?

  /* [FUNCTIONS] */
  list : function () {
  // cal.list() : draw the calendar for the given month

    // BASIC CALCULATIONS
    // Note - Jan is 0 & Dec is 11 in JS.
    // Note - Sun is 0 & Sat is 6
    cal.sMth = parseInt(document.getElementById("cal-mth").value); // selected month
    cal.sYear = parseInt(document.getElementById("cal-yr").value); // selected year
    var daysInMth = new Date(cal.sYear, cal.sMth+1, 0).getDate(), // number of days in selected month
        startDay = new Date(cal.sYear, cal.sMth, 1).getDay(), // first day of the month
        endDay = new Date(cal.sYear, cal.sMth, daysInMth).getDay(); // last day of the month

    // LOAD DATA FROM LOCALSTORAGE
    cal.data = localStorage.getItem("cal-" + cal.sMth + "-" + cal.sYear);
    if (cal.data==null) {
      localStorage.setItem("cal-" + cal.sMth + "-" + cal.sYear, "{}");
      cal.data = {};
    } else {
      cal.data = JSON.parse(cal.data);
    }

    // DRAWING CALCULATIONS
    // Determine the number of blank squares before start of month
    var squares = [];
    if (cal.sMon && startDay != 1) {
      var blanks = startDay==0 ? 7 : startDay ;
      for ( i=1; i<blanks; i++) { squares.push("b"); }
    }
    if (!cal.sMon && startDay != 0) {
      for ( i=0; i<startDay; i++) { squares.push("b"); }
    }

    // Populate the days of the month
    for ( i=1; i<=daysInMth; i++) { squares.push(i); }

    // Determine the number of blank squares after end of month
    if (cal.sMon && endDay != 0) {
      var blanks = endDay==6 ? 1 : 7-endDay;
      for ( i=0; i<blanks; i++) { squares.push("b"); }
    }
    if (!cal.sMon && endDay != 6) {
      var blanks = endDay==0 ? 6 : 6-endDay;
      for ( i=0; i<blanks; i++) { squares.push("b"); }
    }

    // DRAW HTML
    // Container & Table
    var container = document.getElementById("cal-container"),
        cTable = document.createElement("table");
    cTable.id = "calendar";
    container.innerHTML = "";
    container.appendChild(cTable);

    // First row - Days
    var cRow = document.createElement("tr"),
        cCell = null,
        days = ["Sun", "Mon", "Tue", "Wed", "Thur", "Fri", "Sat"];
    if (cal.sMon) { days.push(days.shift()); }
    for (var d of days) {
      cCell = document.createElement("td");
      cCell.innerHTML = d;
      cRow.appendChild(cCell);
    }
    cRow.classList.add("head");
    cTable.appendChild(cRow);

    // Days in Month
    var total = squares.length;
    cRow = document.createElement("tr");
    cRow.classList.add("day");
    for (i=0; i<total; i++) {
      cCell = document.createElement("td");
      if (squares[i]=="b") { cCell.classList.add("blank"); }
      else {
        cCell.innerHTML = "<div class='dd'>"+squares[i]+"</div>";
        if (cal.data[squares[i]]) {
          cCell.innerHTML += "<div class='evt'>" + cal.data[squares[i]] + "</div>";
        }
        cCell.addEventListener("click", function(){
          cal.show(this);
        });
      }
      cRow.appendChild(cCell);
      if (i!=0 && (i+1)%7==0) {
        cTable.appendChild(cRow);
        cRow = document.createElement("tr");
        cRow.classList.add("day");
      }
    }

    // REMOVE ANY ADD/EDIT EVENT DOCKET
    cal.close();
  },

  //me: shows the info for that specific day
  //me: can add the image and stuff here
  show : function (el) {
  // cal.show() : show edit event docket for selected day
  // PARAM el : Reference back to cell clicked

    // FETCH EXISTING DATA
    cal.sDay = el.getElementsByClassName("dd")[0].innerHTML; //the number of the day is stored in cal.sDay as a string number for accessing the array
    //JSON object cal.data is stored in local storage as an array.
    //The JSON object can be adjusted to stored an array of objects instead of just a string.
    //cal.data[cal.sDay].title
    //cal.data[cal.sDay].imageURL
    //cal.data[cal.sDay].

    // DRAW FORM
    var tForm = "<h1>" + (cal.data[cal.sDay] ? cal.data[cal.sDay]  : "") + "</h1>";
    tForm += "<div id='evt-date'>" + cal.sDay + " " + cal.mName[cal.sMth] + " " + cal.sYear + "</div>";
    // tForm += "<textarea id='evt-details' required>" + (cal.data[cal.sDay] ? cal.data[cal.sDay] : "") + "</textarea>";
    tForm += "<input type='button' value='Close' onclick='cal.close()'/>";
    tForm += "<input type='button' value='Delete' onclick='cal.del()'/>";
    // tForm += "<input type='submit' value='Save'/>";

    // ATTACH
    var eForm = document.createElement("form");
    eForm.addEventListener("submit", cal.save);
    eForm.innerHTML = tForm;
    var container = document.getElementById("cal-event");
    container.innerHTML = "";
    container.appendChild(eForm);
  },

  showToday : function (dd) {
    // cal.show() : show edit event docket for selected day
    // PARAM el : Reference back to cell clicked

    cal.sDay = dd;

    // DRAW FORM
    var tForm = "<h1>" + (cal.data[cal.sDay] ? cal.data[cal.sDay]  : "") + "</h1>";
    tForm += "<div id='evt-date'>" + cal.sDay + " " + cal.mName[cal.sMth] + " " + cal.sYear + "</div>";
    // tForm += "<textarea id='evt-details' required>" + (cal.data[cal.sDay] ? cal.data[cal.sDay] : "") + "</textarea>";
    tForm += "<input type='button' value='Close' onclick='cal.close()'/>";
    tForm += "<input type='button' value='Delete' onclick='cal.del()'/>";
    // tForm += "<input type='submit' value='Save'/>";

    // ATTACH
    var eForm = document.createElement("form");
    eForm.addEventListener("submit", cal.save);
    eForm.innerHTML = tForm;
    var container = document.getElementById("cal-event");
    container.innerHTML = "";
    container.appendChild(eForm);
  },

  close : function () {
  // cal.close() : close event docket

    document.getElementById("cal-event").innerHTML = "";
  },

  save : function (evt) {
  // cal.save() : save event

    evt.stopPropagation();
    evt.preventDefault();
    cal.data[cal.sDay] = document.getElementById("evt-details").value;
    localStorage.setItem("cal-" + cal.sMth + "-" + cal.sYear, JSON.stringify(cal.data));
    cal.list();
  },

  del : function () {
  // cal.del() : Delete event for selected date

    if (confirm("Remove event?")) {
      delete cal.data[cal.sDay];
      localStorage.setItem("cal-" + cal.sMth + "-" + cal.sYear, JSON.stringify(cal.data));
      cal.list();
    }
  },

  nextMonth : function () {
    var myMonth = document.getElementById("cal-mth").value; //integer
    var myYear = document.getElementById("cal-yr").value; // selected year

    if (myMonth === "11") //december, go to following year
    {

      document.getElementById("cal-yr").value = parseInt(myYear) + 1;
      document.getElementById("cal-mth").value = "0";
    } else {
      document.getElementById("cal-mth").value = parseInt(myMonth) +1;
    }

    cal.list();
  },

  prevMonth : function () {
    var myMonth = document.getElementById("cal-mth").value; //integer
    var myYear = document.getElementById("cal-yr").value; // selected year

    if (myMonth == 0) //december, go to following year. THese are of string and int type. Cant be ===
    {
      document.getElementById("cal-yr").value = parseInt(myYear) - 1;
      document.getElementById("cal-mth").value = "11";
    } else {
      document.getElementById("cal-mth").value = parseInt(myMonth) -1;
    }

    cal.list();
  },

  today : function () {
    //must go to the specific month, year and day and then show that specifically
    var today = new Date();
    var dd = String(today.getDate());
    var mm = String(today.getMonth()); //January is 0!
    var yyyy = today.getFullYear();

    document.getElementById("cal-yr").value = yyyy;
    document.getElementById("cal-mth").value = mm;

    cal.list();
    cal.showToday(dd);
  }
};

// INIT - DRAW MONTH & YEAR SELECTOR
window.addEventListener("load", function () {
  //CLEAR LOCAL STORAGE BEFORE ADDING NEW STUFF
  localStorage.clear(); //makes it session storage basically

  // DATE NOW
  var now = new Date(),
      nowMth = now.getMonth(),
      nowYear = parseInt(now.getFullYear());

  // APPEND MONTHS SELECTOR
  var month = document.getElementById("cal-mth");
  for (var i = 0; i < 12; i++) {
    var opt = document.createElement("option");
    opt.value = i;
    opt.innerHTML = cal.mName[i];
    if (i==nowMth) { opt.selected = true; }
    month.appendChild(opt);
  }

  // APPEND YEARS SELECTOR
  // Set to 10 years range. Change this as you like.
  var year = document.getElementById("cal-yr");
  for (var i = nowYear-10; i<=nowYear+10; i++) {
    var opt = document.createElement("option");
    opt.value = i;
    opt.innerHTML = i;
    if (i==nowYear) { opt.selected = true; }
    year.appendChild(opt);
  }

  // START - DRAW CALENDAR
  document.getElementById("cal-set").addEventListener("click", cal.list);
  cal.list();

  // PREV TODAY AND NEXT BUTTONS
  document.getElementById("cal-next").addEventListener("click", cal.nextMonth); //call a function which will find the day its currently on and moves to the next.
  document.getElementById("cal-prev").addEventListener("click", cal.prevMonth);
  document.getElementById("cal-today").addEventListener("click", cal.today);

  //POPULATE
  callback();
});


//global variable
var count = 0;

function swap() {
  var main = document.getElementsByTagName("main")[0];
  var cover = document.getElementById("cover");
  main.style.display = "block";
  cover.style.display = "none";
  cal.today();
}



//new callback
function callback()
{
  var rows = document.getElementsByClassName("row");
  var key = getCookie("key");

  let formData = {
    "key": key,
    "type": "info",
    "title": "*",
    "ranking": "",
    "return": ["title", "release"]
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

      var data = res.data;

      for(let i=0; i<res.data.length; i++)
      {
        var release = data[i].release;
        var title = data[i].title;

        var year = parseInt(release.substring(0, 4));
        var month = parseInt(release.substring(5, 7)) -1;
        var day = parseInt(release.substring(8, 10));

        document.getElementById("cal-yr").value = year;
        document.getElementById("cal-mth").value = month;
        cal.list();

        cal.sDay = day;
        cal.data[cal.sDay] = title;
        localStorage.setItem("cal-" + cal.sMth + "-" + cal.sYear, JSON.stringify(cal.data));
        cal.list();
      }
      swap();
    },
    error: function(data) {
      //alert("Failure to make call");
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