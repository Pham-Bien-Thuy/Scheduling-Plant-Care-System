<!DOCTYPE HTML>
<html>
  <head>
    <title>ESP32 WITH MYSQL DATABASE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="icon" href="data:,">
    <style>
      html {font-family: Arial; display: inline-block; text-align: center;}
      p {font-size: 1.2rem;}
      h4 {font-size: 0.8rem;}
      body {margin: 0;}
      .topnav {overflow: hidden; background-color: #0c6980; color: white; font-size: 1.2rem;}
      .content {padding: 5px; }
      .card {background-color: white; box-shadow: 0px 0px 10px 1px rgba(140,140,140,.5); border: 1px solid #0c6980; border-radius: 15px;}
      .card.header {background-color: #0c6980; color: white; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; border-top-right-radius: 12px; border-top-left-radius: 12px;}
      .cards {max-width: 700px; margin: 0 auto; display: grid; grid-gap: 2rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));}
      .reading {font-size: 1.3rem;}
      .packet {color: #bebebe;}
      .temperatureColor {color: #fd7e14;}
      .humidityColor {color: #1b78e2;}
      .statusreadColor {color: #702963; font-size:12px;}
      .LEDColor {color: #183153;}
      
      /* ----------------------------------- Toggle Switch */
      .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
      }

      .switch input {display:none;}

      .sliderTS {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #D3D3D3;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 34px;
      }

      .sliderTS:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: #f7f7f7;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
      }

      input:checked + .sliderTS {
        background-color: #00878F;
      }

      input:focus + .sliderTS {
        box-shadow: 0 0 1px #2196F3;
      }

      input:checked + .sliderTS:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
      }

      .sliderTS:after {
        content:'OFF';
        color: white;
        display: block;
        position: absolute;
        transform: translate(-50%,-50%);
        top: 50%;
        left: 70%;
        font-size: 10px;
        font-family: Verdana, sans-serif;
      }

      input:checked + .sliderTS:after {  
        left: 25%;
        content:'ON';
      }

      input:disabled + .sliderTS {  
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
      }

	.control-container {
  	display: flex;
  	align-items: center;
 	gap: 5px; /* Khoảng cách giữa các phần tử */
	}

	.switch {
  	display: flex;
  	align-items: center;
	}

	.control-input {
  	padding: 5px;
  	font-size: 0.5rem;
  	border: 1px solid #ccc;
  	border-radius: 4px;
	}

	.control-button {
  	padding: 5px 10px;
  	font-size: 0.5rem;
  	background-color: #007BFF;
  	color: white;
  	border: none;
  	border-radius: 4px;
  	cursor: pointer;
	transition: opacity 0.3s ease; /* Hiệu ứng mờ */
	}

	.control-button.hidden {
	  opacity: 0;
	  pointer-events: none; /* Không cho phép nhấn khi ẩn */
	}
      /* ----------------------------------- */
    </style>
  </head>
  
  <body>
    <div class="topnav">
      <h3>ESP32 WITH MYSQL DATABASE</h3>
    </div>
    
    <br>
    
    <!-- __ DISPLAYS MONITORING AND CONTROLLING ____________________________________________________________________________________________ -->
    <div class="content">
      <div class="cards">
        
        <!-- == MONITORING ======================================================================================== -->
        <div class="card">
          <div class="card header">
            <h3 style="font-size: 1rem;">MONITORING</h3>
          </div>
          
          <!-- Displays the humidity and temperature values received from ESP32. *** -->
          <h4 class="temperatureColor"><i class="fas fa-thermometer-half"></i> TEMPERATURE</h4>
          <p class="temperatureColor"><span class="reading"><span id="ESP32_01_Temp"></span> &deg;C</span></p>
          <h4 class="humidityColor"><i class="fas fa-tint"></i> HUMIDITY</h4>
          <p class="humidityColor"><span class="reading"><span id="ESP32_01_Humd"></span> &percnt;</span></p>
          <!-- *********************************************************************** -->
          
          <p class="statusreadColor"><span>Status Read Sensor DHT11 : </span><span id="ESP32_01_Status_Read_DHT11"></span></p>
        </div>
        <!-- ======================================================================================================= -->
        
        <!-- == CONTROLLING ======================================================================================== -->
        <div class="card">
          <div class="card header">
            <h3 style="font-size: 1rem;">CONTROLLING</h3>
          </div>
          	<h4 class="LEDColor"><i class="fas fa-lightbulb"></i> LED</h4>

          	<div class="control-container">
  	 	 <!-- Checkbox -->
  		 <label class="switch">
    	 	   <input type="checkbox" id="ESP32_01_TogLED" onclick="GetTogBtnLEDState('ESP32_01_TogLED')">
    		   <div class="sliderTS"></div>
  		 </label>
  
  		 <!-- LED Time ON -->
  		 <input type="text" id="led-time-on" class="control-input" placeholder="Time On">
  
  		 <!-- LED Time OFF -->
  		 <input type="text" id="led-time-off" class="control-input" placeholder="Time Off">
  
  		 <!-- Button -->
  		 <input type="button" id="submit-led-time" class="control-button" onclick="handleSubmitLedTime()" value="Submit">
		</div>

		 <h4 class="LEDColor"><i class="fas fa-lightbulb"></i> PUMP</h4>

		 <div class="control-container">
  	 	  <!-- Checkbox -->
  		  <label class="switch">
    	 	   <input type="checkbox" id="ESP32_01_TogPUMP" onclick="GetTogBtnLEDState('ESP32_01_TogPUMP')">
    		   <div class="sliderTS"></div>
  		  </label>
  
  		  <!-- PUMP Time ON -->
  		  <input type="text" id="pump-time-on" class="control-input" placeholder="Time On">
  
  		  <!-- PUMP Time OFF -->
  		  <input type="text" id="pump-time-off" class="control-input" placeholder="Time Off">
  
  		  <!-- Button -->
  		  <input type="button" id="submit-pump-time" class="control-button" onclick="handleSubmitPumpTime()" value="Submit">
		 </div>
          <!-- *********************************************************************** -->
        </div>  
        <!-- ======================================================================================================= -->
        
      </div>
    </div>
    
    <br>
    
    <div class="content">
      <div class="cards">
        <div class="card header" style="border-radius: 15px;">
            <h3 style="font-size: 0.7rem;">LAST TIME RECEIVED DATA FROM ESP32 [ <span id="ESP32_01_LTRD"></span> ]</h3>
            <button onclick="window.open('recordtable.php', '_blank');">Open Record Table</button>
            <h3 style="font-size: 0.7rem;"></h3>
        </div>
      </div>
    </div>
    <!-- ___________________________________________________________________________________________________________________________________ -->
    
    <script>
      //------------------------------------------------------------
      document.getElementById("ESP32_01_Temp").innerHTML = "NN"; 
      document.getElementById("ESP32_01_Humd").innerHTML = "NN";
      document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = "NN";
      document.getElementById("ESP32_01_LTRD").innerHTML = "NN";
      //------------------------------------------------------------
      
      Get_Data("esp32_01");
      
      setInterval(myTimer, 5000);
      
      //------------------------------------------------------------
      function myTimer() {
        Get_Data("esp32_01");
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function Get_Data(id) {
	if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            const myObj = JSON.parse(this.responseText);
            if (myObj.id == "esp32_01") {
              document.getElementById("ESP32_01_Temp").innerHTML = myObj.temperature;
              document.getElementById("ESP32_01_Humd").innerHTML = myObj.humidity;
              document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = myObj.status_read_sensor_dht11;
              document.getElementById("ESP32_01_LTRD").innerHTML = "Time : " + myObj.ls_time + " | Date : " + myObj.ls_date + " (dd-mm-yyyy)";
              if (myObj.LED == "ON") {
                document.getElementById("ESP32_01_TogLED").checked = true;
              } else if (myObj.LED == "OFF") {
                document.getElementById("ESP32_01_TogLED").checked = false;
              }
              if (myObj.PUMP == "ON") {
                document.getElementById("ESP32_01_TogPUMP").checked = true;
              } else if (myObj.PUMP == "OFF") {
                document.getElementById("ESP32_01_TogPUMP").checked = false;
              }
            }
          }
        };
        xmlhttp.open("POST","getdata.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id="+id);
			}
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function GetTogBtnLEDState(togbtnid) {
        if (togbtnid == "ESP32_01_TogLED") {
          var togbtnchecked = document.getElementById(togbtnid).checked;
          var togbtncheckedsend = "";
          if (togbtnchecked == true) togbtncheckedsend = "ON";
          if (togbtnchecked == false) togbtncheckedsend = "OFF";
          Update_LEDs("esp32_01","LED",togbtncheckedsend);
        }
        if (togbtnid == "ESP32_01_TogPUMP") {
          var togbtnchecked = document.getElementById(togbtnid).checked;
          var togbtncheckedsend = "";
          if (togbtnchecked == true) togbtncheckedsend = "ON";
          if (togbtnchecked == false) togbtncheckedsend = "OFF";
          Update_LEDs("esp32_01","PUMP",togbtncheckedsend);
        }
      }
      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function Update_LEDs(id,lednum,ledstate) {
	if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            //document.getElementById("demo").innerHTML = this.responseText;
          }
        }
        xmlhttp.open("POST","updateLEDs.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id="+id+"&lednum="+lednum+"&ledstate="+ledstate);
	}
	
	function getCurrentTime() {
    	  const now = new Date();
    	  const hours = String(now.getHours()).padStart(2, '0');
    	  const minutes = String(now.getMinutes()).padStart(2, '0');
    	  const seconds = String(now.getSeconds()).padStart(2, '0');
    	  return `${hours}:${minutes}:${seconds}`;
  	}
	
	function handleSubmitLedTime() {
	  const button = document.getElementById('submit-led-time');
	  const ledOnTime = document.getElementById('led-time-on').value;
	  const ledOffTime = document.getElementById('led-time-off').value;
  	  // Ẩn button
  	  button.classList.add('hidden');
	  		
          if(window.XMLHttpRequest) {
	    xmlhttp = new XMLHttpRequest();
	  } else {
	    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  
	  xmlhttp.onreadystatechange = function() {
	    
	    if(this.readyState == 4 && this.status == 200) {
      	       const myObj = JSON.parse(this.responseText);
	       // Hàm kiểm tra thời gian
  	  	const checkTimeLedOn = () => {
    	    	  if (ledOnTime === getCurrentTime()) {
       	      	    //document.getElementById("ESP32_01_TogLED").checked = true;
	      	    Update_LEDs("esp32_01","LED","ON");
		    clearInterval(invervalLedOn); // Dung kiem tra  
    	    	  }
  	  	};
	  	const intervalLedOn = setInterval(checkTimeLedOn, 1000);

	  	const checkTimeLedOff = () => {
    	    	  if (ledOffTime === getCurrentTime()) {
       	      	    //document.getElementById("ESP32_01_TogLED").checked = false;
	      	    Update_LEDs("esp32_01","LED","OFF");
		    clearInterval(invervalLedOff); // Dung kiem tra
    	    	  }
  	 	};
	  	const intervalLedOff = setInterval(checkTimeLedOff, 1000);
	    }
	  }
	  // Hiển thị lại button sau 2 giây
	  setTimeout(() => {
	    button.classList.remove('hidden');
	  }, 2000);
	  xmlhttp.open("POST","getdata.php",true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("id="+"esp32_01");	
	  
	}

	function handleSubmitPumpTime() {
	  const button = document.getElementById('submit-pump-time');
	  const pumpOnTime = document.getElementById('pump-time-on').value;
	  const pumpOffTime = document.getElementById('pump-time-off').value;
  	  // Ẩn button
  	  button.classList.add('hidden');
	  		
          if(window.XMLHttpRequest) {
	    xmlhttp = new XMLHttpRequest();
	  } else {
	    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  
	  xmlhttp.onreadystatechange = function() {
	    
	    if(this.readyState == 4 && this.status == 200) {
      	       const myObj = JSON.parse(this.responseText);
	       // Hàm kiểm tra thời gian
		const checkTimePumpOn = () => {
    	    	  if (pumpOnTime === getCurrentTime()) {
       	      	    //document.getElementById("ESP32_01_TogPUMP").checked = true;
	      	    Update_LEDs("esp32_01","PUMP","ON");
		    clearInterval(invervalPumpOn); // Dung kiem tra  
    	    	  }
  	  	};
	  	const intervalPumpOn = setInterval(checkTimePumpOn, 1000);

	  	const checkTimePumpOff = () => {
    	    	  if (pumpOffTime === getCurrentTime()) {
       	      	    //document.getElementById("ESP32_01_TogPUMP").checked = false;
	      	    Update_LEDs("esp32_01","PUMP","OFF");
		    clearInterval(invervalPumpOff); // Dung kiem tra
    	    	  }
  	 	};
	  	const intervalPumpOff = setInterval(checkTimePumpOff, 1000);
	    }
	  }
	  // Hiển thị lại button sau 2 giây
	  setTimeout(() => {
	    button.classList.remove('hidden');
	  }, 2000);
	  xmlhttp.open("POST","getdata.php",true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("id="+"esp32_01");	
	  
	}
	
      //------------------------------------------------------------
    </script>
  </body>
</html>