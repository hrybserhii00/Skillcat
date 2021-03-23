function ExecuteScript(strId)
{
  switch (strId)
  {
      case "6nG7mbXlrFg":
        Script1();
        break;
      case "5w3QoSjAAmx":
        Script2();
        break;
      case "6pmGZ3MWWos":
        Script3();
        break;
      case "6DQuP1NXE7I":
        Script4();
        break;
  }
}

function Script1()
{
  //For allowing access to variables from Articulate Player
var player = GetPlayer();

//==============================
//Code for generating Port Feedback
//==============================

//Assigning Articulate variables to Javascript variables
var blackConnectorPort = player.GetVar("BlackConnectorPort");
var redConnectorPort = player.GetVar("RedConnectorPort");

var  blackPortFeedback = "";
var  redPortFeedback = "";
var  blackPortScore = 0;
var  redPortScore = 0;

if (blackConnectorPort.localeCompare("COM") == 0) {
	 blackPortFeedback = "You have correctly connected the Black connector to the COM Port.";
	 blackPortScore = 2;
} else if (blackConnectorPort.localeCompare("mVAO") == 0) {
	 blackPortFeedback = "You have connected the Black connector to the VΩmA port. It will not affect anything but it is generally not correct, because the Black connector is generally connected to the COM port.";
	 blackPortScore = 1;
} else {
	blackPortFeedback = "You have not connected the Black connector to any of the ports.";
}

if (redConnectorPort.localeCompare("COM") == 0) {
	 redConnectorPort = "You have connected the Red connector to the COM port. It will not affect anything but it is generally not correct, because the Red connector is generally connected to the VΩmA port.";
	 redPortScore = 1;
} else if (redConnectorPort.localeCompare("mVAO") == 0) {
	 redConnectorPort = "You have correctly connected the Red connector to the VΩmA port.";
	 redPortScore = 2;
} else {
	redConnectorPort = "You have not connected the Red connector to any of the ports.";
}

//Assigning Javascript variable to Articulate variable
player.SetVar("BlackPortFeedback", blackPortFeedback);
player.SetVar("BlackPortScore", blackPortScore);

player.SetVar("RedPortFeedback", redConnectorPort);
player.SetVar("RedPortScore", redPortScore);

}

function Script2()
{
  //For allowing access to variables from Articulate Player
var player = GetPlayer();

//==============================
//Code for generating Dial Feedback
//==============================

//Assigning Articulate variables to Javascript variables
var probeConnect = player.GetVar("ProbeConnect");
var display = player.GetVar("Display");
var displayForCorrectProbes = player.GetVar("DisplayForCorrectProbes");
var displayForReverseProbes = player.GetVar("DisplayForReverseProbes");
var knobValue = player.GetVar("KnobValue");

var tempDisp = "";
if (probeConnect.localeCompare("Correct") == 0) {
	// If probe is connected
	tempDisp = displayForCorrectProbes;
} else if (probeConnect.localeCompare("InCorrect") == 0) {
	// If probe is connected, but incorrect way
	tempDisp = displayForReverseProbes;
} else {
	// If probe is not connected, but incorrect way
	tempDisp = display;
}

var  knobFeeback = "";
var  knobScore = 0;

if (knobValue.localeCompare("") == 0) {
	 knobFeeback = "It seems you forgot to switch ON the multimeter before getting the reading. It is important to note that changing the dial to the desired setting is essential to get a measurement value on the multimeter.";
}

if (knobValue.localeCompare("200 V (DC)") == 0 || knobValue.localeCompare("500 V (DC)") == 0) {
	 knobFeeback = "You chose a setting of KNOB_VALUE on the dial. This is an incorrect setting that leads to an underload and a reading of DISPLAY_VALUE.";
}

if (knobValue.localeCompare("20 V (DC)") == 0) {
	 knobFeeback = "You chose a setting of KNOB_VALUE on the dial. This is the most accurate setting for measuring voltage of a AAA battery. You have got the reading of DISPLAY_VALUE.";
	 knobScore = 3;
}

if (knobValue.localeCompare("200 mV (DC)") == 0 || knobValue.localeCompare("2000 mV (DC)") == 0) {
	 knobFeeback = "You chose a setting of KNOB_VALUE on the dial. This is an incorrect setting that leads to an overload and a reading of DISPLAY_VALUE.";
}

if (knobValue.localeCompare("20 MΩ") == 0 || knobValue.localeCompare("200 MΩ") == 0 || knobValue.localeCompare("20 KΩ") == 0 || knobValue.localeCompare("200 KΩ") == 0 || knobValue.localeCompare("200 Ω") == 0 || knobValue.localeCompare("2000 Ω") == 0) {
	 knobFeeback = "You chose an incorrect setting of KNOB_VALUE on the dial. This setting is used to measure resistance while you are actually trying to measure voltage.";
}

if (knobValue.localeCompare("Continuity") == 0) {
	 knobFeeback = "You chose an incorrect setting. This setting is used to check continuity while you are actually trying to measure voltage.";
}

if (knobValue.localeCompare("Square Wave") == 0) {
	 knobFeeback = "You chose an incorrect setting. This setting is used to measure square wave while you are actually trying to measure voltage.";
}

if (knobValue.localeCompare("10 A") == 0) {
	 knobFeeback = "You chose an incorrect setting of KNOB_VALUE on the dial. This setting is used to measure large current while you are actually trying to measure voltage.";
}

if (knobValue.localeCompare("20 mA") == 0 || knobValue.localeCompare("200 mA") == 0 || knobValue.localeCompare("2000 µA") == 0) {
	 knobFeeback = "You chose an incorrect setting of KNOB_VALUE of the dial. This setting is used to measure DC current while you are actually trying to measure voltage.";
}

if (knobValue.localeCompare("200 V (AC)") == 0 || knobValue.localeCompare("500 V (AC)") == 0) {
	 knobFeeback = "You chose an incorrect setting of KNOB_VALUE on the dial.This setting is used to measure AC voltage while you are actually trying to measure DC voltage.";
}

 knobFeeback = knobFeeback.replace("KNOB_VALUE", knobValue);
 knobFeeback = knobFeeback.replace("DISPLAY_VALUE", tempDisp);

//Assigning Javascript variable to Articulate variable
player.SetVar("DialFeeback", knobFeeback);
player.SetVar("DialScore", knobScore);

}

function Script3()
{
  //For allowing access to variables from Articulate Player
var player = GetPlayer();

//==============================
//Code for generating Probe Feedback
//==============================

//Assigning Articulate variables to Javascript variables
var redConnectorPort = player.GetVar("RedConnectorPort");
var blackConnectorPort = player.GetVar("BlackConnectorPort");

var blackProbeTerminal = player.GetVar("BlackProbeTerminal");
var redProbeTerminal = player.GetVar("RedProbeTerminal");

var probeConnect = player.GetVar("ProbeConnect");

var probeFeedback = "";
var probeScore = 0;

if (probeConnect.localeCompare("Correct") == 0) {
	// If probe is connected
	if (blackProbeTerminal.localeCompare("Negative") == 0 && blackConnectorPort.localeCompare("COM") == 0) {
		// User has done correct connection with correct settings
		// Probe Setting: Correct
		// Connector Setting: Correct
		// Wire Setting: Correct
		probeFeedback = "You used the black probes on the negative terminal and red probes on the positive terminal of the battery, while your black connector was connected to the COM port and red connector was connected to the VΩmA port. This was a perfect setting.";
		probeScore = 3;
	} else {
		// User has done correct connection with incorrect settings
		// Probe Setting: Correct
		// Connector Setting: Correct
		// Wire Setting: InCorrect
		probeFeedback = "You used the black probes on the positive terminal and red probes on the negative terminal of the battery, while your black connector was connected to the VΩmA port and red connector was connected to the COM port. Hence, you got the measured values in positive. That is good work, overall.";
		probeScore = 2;
	}
} else if (probeConnect.localeCompare("InCorrect") == 0) {
	// If probe is connected, but incorrect way

	if (blackProbeTerminal.localeCompare("Positive") == 0) {
		// Probe Setting: InCorrect
		// Connector Setting: Correct
		probeFeedback = "You used the red probes on the negative terminal and black probes on the positive terminal of the battery, while your red connector was connected to the VΩmA port and black connector was connected to the COM port. Hence, you got your measured values in negative.";
		probeScore = 0;
	} else {
		// Probe Setting: Correct
		// Connector Setting: InCorrect
		probeFeedback = "You used the black probes on the negative terminal and red probes on the positive terminal of the battery, while your black connector was connected to the VΩmA port and red connector was connected to the COM port. Hence, you got your measured values in negative. To ensure that such issues never occur, you must always connect the black connector to the COM port, red connector to the VΩmA port while the black probe to the negative terminal and the red probe to the positive terminal.";
		probeScore = 0;
	}
} else {
	// If probe is not connected

	if (blackProbeTerminal.localeCompare("None") == 0 && redProbeTerminal.localeCompare("None") == 0) {
		probeFeedback = "You have not connected the black and red probe to any terminals of the battery.";
		probeScore = 0;
	} else if (blackProbeTerminal.localeCompare("None") == 0) {
		probeFeedback = "You have not connected the black probe to any terminals of the battery.";
		probeScore = 0;
	} else if (redProbeTerminal.localeCompare("None") == 0) {
		probeFeedback = "You have not connected the red probe to any terminals of the battery.";
		probeScore = 0;
	} else {

		if (blackProbeTerminal.localeCompare("Negative") == 0) {
			probeFeedback = "You used the black probes on the negative terminal and red probes on the positive terminal of the battery. This was a perfect setting.";
			probeScore = 1;
		} else {
			probeFeedback = "You used the red probes on the negative terminal and black probes on the positive terminal of the battery.";
			probeScore = 0;
		}
	}
}

//Assigning Javascript variable to Articulate variable
player.SetVar("ProbeFeedback", probeFeedback);
player.SetVar("ProbeScore", probeScore);

}

function Script4()
{
  //For allowing access to variables from Articulate Player
var player = GetPlayer();

//==============================
//Code for summing up the Score of all Actions
//==============================

//Assigning Articulate variables to Javascript variables
var BlackPortScoreJS = player.GetVar("BlackPortScore");  
var RedPortScoreJS = player.GetVar("RedPortScore");
var DialScoreJS = player.GetVar("DialScore");          
var ProbeScoreJS = player.GetVar("ProbeScore");

var FinalScoreJS = BlackPortScoreJS + RedPortScoreJS + DialScoreJS + ProbeScoreJS;

//Assigning Javascript variable to Articulate variable
player.SetVar("FinalScore", FinalScoreJS);

//==============================
//Code for summing up the Score of all Actions
//==============================

//Assigning Articulate variables to Javascript variables
var BlackPortFeedbackJS = player.GetVar("BlackPortFeedback");
var RedPortFeedbackJS = player.GetVar("RedPortFeedback");
var DialFeedbackJS = player.GetVar("DialFeedback");          
var ProbeFeedbackJS = player.GetVar("ProbeFeedback");

var FinalFeedbackJS = BlackPortFeedbackJS + RedPortFeedbackJS + DialFeedbackJS + ProbeFeedbackJS;

//Assigning Javascript variable to Articulate variable
player.SetVar("FinalFeedback", FinalFeedbackJS);

//==============================
//Send Final Score to LMS
//==============================
var lmsAPI = parent;
lmsAPI.SetPointBasedScore(FinalScoreJS, 10, 0);
}

