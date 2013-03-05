/*
 * New formate for bleep player and recorder
 * Create on Thusday 09-02-2012
 * 
 * @Author	Tapeshwar das
 */
 
// Call on page Load (use for default playing the bleep)

function getDefaultBleep()
{
	var bleepIdArrayD	= [];
	var strArrayD		= [];
	
	var defaultSelectedBleep	= $('#defaultSelectedBleep').val();
	var profileUserIdD			= $('#profileUserId').val();
	
	strArrayD = defaultSelectedBleep.split('|');
	
	
	bleepIdArrayD.push(strArrayD[0]);
	
	
	
	//This function update for bleep num play and Listening user;
	updateBleepNumPlayAndListening(bleepIdArrayD, profileUserIdD);
	
	// Return bleep mp3 file name;
	return strArrayD[1];
}



// This function is called if play button is clicked
function getSelectedBleep()
{
	
	alert("here i m");
	
}


// this function is called if whole list played
function completePlay()
{
	// Enable all check box after stop paying the bleeps;
	$('#bleepList :input').removeAttr('disabled');
	
}

// This function is called if stopPlay button is clicked
function playStop()
{
	// Enable all check box after stop paying the bleeps by user;
	$('#bleepList :input').removeAttr('disabled');

}


// This fuction use for update bleep numPlay and listening by users.
// This function called if bleep play configured.

function updateBleepNumPlayAndListening(bleepIdArray, profileUserId)
{
	var strUrl			= Base_Url + '/bleeps/updatebleepnumplayandlistening';
	var bleepIdArrayStr	= bleepIdArray;

	$.ajax({
		type: "POST",
		url: strUrl,
		data: {bleepId:bleepIdArrayStr,profileUserId:profileUserId},
		async: false,
		success: function() {
		
		}
	});
	
}


// This function use for update bleepbox notification
function updateBleepBoxNotification(bleepIdArray, loginUserId)
{
	var strUrl			= Base_Url + '/bleeps/updatebleepboxnotify';
	var bleepIdArrayStr	= bleepIdArray;
	
	// hide for Admin
	if(loginUserId != -1)
	{
		$.ajax({
			type: "POST",
			url: strUrl,
			data: {bleepIdArr:bleepIdArrayStr, key:'bleepBox'},
			async: false,
			success: function() {
				// Remove listen bleep class
				/*$.each(bleepIdArrayStr, function(key, value)
				{ 
					//alert(key + ': ' + value);
					$('#'+value).removeClass('newBleep');
				});*/	
			}
		});
	}
}


// This function use for update GroupBleeps notification
function updateGroupBleepNotification(bleepIdArray, loginUserId, groupId)
{
	var strUrl			= Base_Url + '/bleeps/updategroupbleepsnotify';
	var bleepIdArrayStr	= bleepIdArray;
	
	// hide for Admin
	if(loginUserId != -1)
	{
		$.ajax({
			type: "POST",
			url: strUrl,
			data: {bleepIdArr:bleepIdArrayStr, loginUserId:loginUserId, groupId:groupId},
			async: false,
			success: function() {
			
			}
		});
	}
}



function getCheckedBleeps()
{
	var bleepInfoArray	= [];
	var bleepIdArray	= [];
	var bleepMp3Array	= [];
	var strArray		= [];
	
	var bleepIdStr		= '';
	var bleepMp3Str		= '';
	var profileUserId	= $('#profileUserId').val();
	
	$(':checkbox:checked').each(function(i){
		
		bleepInfoArray[i] = $(this).val();
		
	});
	
	$.each(bleepInfoArray, function(index, value)
	{
		strArray = value.split('|');
		
		bleepIdArray.push(strArray[0]);
		bleepMp3Array.push(strArray[1]);
		
	});
	
	// Create array to string;
	bleepIdStr = bleepIdArray.toString();
	bleepMp3Str= bleepMp3Array.toString();
	
	//This function update for bleep num play and Listening user;
	updateBleepNumPlayAndListening(bleepIdArray, profileUserId);
	
	return bleepMp3Str;
}



function getSelectedBleepHtml5()
{ 
	var selector_checked = $("input[@id=selectedBleep]:checked").length;
	if(selector_checked == 0)
	{
		alert('Please select atleast one bleep.');
		return false;
	}
	else
	{ 
		// Call the function which is checking all the selected bleeps
		//getCheckedBleeps();
		
		// Disable all check box during paying the bleeps;
		//$('#bleepList :input').attr('disabled', true);
		
		
		var bleepInfoArray	= [];
		var bleepIdArray	= [];
		var bleepMp3Array	= [];
		var strArray		= [];
		
		var bleepIdStr		= '';
		var bleepMp3Str		= '';
		var profileUserId	= $('#profileUserId').val();
		
		$(':checkbox:checked').each(function(i){
			bleepInfoArray[i] = $(this).val();
		});
		

		$.each(bleepInfoArray, function(index, value)
		{
			strArray = value.split('|');
			
			bleepIdArray.push(strArray[0]);
			bleepMp3Array.push(strArray[1]);
		});
		
		// Create array to string;
		bleepIdStr = bleepIdArray.toString();
		bleepMp3Str= bleepMp3Array.toString();
				
		// Disable all check box during paying the bleeps;
		//$('#bleepList :input').attr('disabled', true);
		
		//This function update for bleep num play and Listening user;
		updateBleepNumPlayAndListening(bleepIdArray, profileUserId);
		
		return bleepMp3Str;
	}
	
}


// Call on checked for html5 player; Commented by Prawez
/*function checkedBleep()
{	
	var bleepInfoArray	= [];
	var bleepIdArray	= [];
	var strArray		= [];
	
	var bleepIdStr		= '';
	var bleepMp3Str		= '';
	//var profileUserId	= $('#profileUserId').val();
	
	$(':checkbox:checked').each(function(i){
		
		bleepInfoArray[i] = $(this).val();
		
	});
	
	
	var beepString	= '';
	
	$.each(bleepInfoArray, function(index, value)
	{
		strArray = value.split('|');
		
		bleepIdArray.push(strArray[0]);
		
		var player = new MediaElementPlayer('#player','');
		
		player.pause();
		
		var nextAudioFile = Base_Url+'/upload/bleep/'+strArray[1]
		
		player.setSrc(nextAudioFile);
		
		//player.play();
	
	});
}*/

/**
 * Create js array of all displayed bleep on page
 */
var bleepArr = new Array();
var tempBleepArr = new Array();
$(document).ready(function(){
	$("input[@name=selectedBleep]:checkbox").each(function(){
		if($(this).val() != 'on') bleepArr.push($(this).val());
    });
});

/**
 * This method is used to play html5 player
 */
function checkedBleep()
{ 
	var selector_checked = $("input[@name=selectedBleep]:checked").length;
	
	if(!selector_checked && ($('.list > li.active').find(':checkbox').attr('value'))) selector_checked = 1;
	
	if(selector_checked == 0)
	{
		alert('Please select atleast one bleep.');
		return false;
	}
	else
	{
		var bleepInfoArray	= [];
		var bleepIdArray	= [];
		var strArray		= [];
		var bleepMp3Arr		= [];
		var profileUserId	= $('#profileUserId').val();
		var	bBox_bleepType	= $('#bBox_bleepType').val();
		
		var bleepChkArr 	= new Array();
		var currIndex;
		var falgPlaySelected = 1;
		
		$('input[@id=selectedBleep]:checked').each(function(i){
			bleepInfoArray[i] 	= $(this).val();
		});
		
		if(bleepInfoArray.length == 0)
		{
			bleepInfoArray[0] = $('.list > li.active').find(':checkbox').attr('value');
			falgPlaySelected = 0;
		}
		
		if(bleepInfoArray.length)
		{
			$.ajax({
				type : 'POST',
				beforeSend: function(){
					
				},
				complete: function(){
					
				},
				url: Base_Url + '/home/playselectedbleep',
				//data: {bleepMp3:bleepMp3Arr, bleepId:bleepIdArray, bleepArr:bleepArr},
				data: {bleepInfoArray:bleepInfoArray, bleepArr:bleepArr, falgPlaySelected:falgPlaySelected},
				success: function(response){
					$('#recorderButton').removeAttr('onclick');
					$('#dumyHtmlPlayer').hide();
					$('#jplyaerload').show();
					$('#jplyaerload').html(response);
					
					//Get bleep Id List
					var bleepIdArray	= ($('#bleepIdList').val()).split();
					
					//update listening records
					updateBleepNumPlayAndListening(bleepIdArray, profileUserId);
					
					// for update bleepbox notification
					if(bBox_bleepType == 'DB')
					{
						var	loginUserId	= $('#loginUserId').val();
						
						updateBleepBoxNotification(bleepIdArray, loginUserId);
					}
					else if(bBox_bleepType == 'G')
					{
						var	groupId		= $('#groupId').val();
						var	loginUserId	= $('#loginUserId').val();
						
						updateGroupBleepNotification(bleepIdArray, loginUserId, groupId);
					}
				}
			});
		}		
	}
}


function clearhtmlplayer()
{
	alert("jhsshdshd");
	//destroy the instance of html player
	$("#jquery_jplayer_2").jPlayer("destroy");
	$("#jplyaerload").hide();
	$('#dumyHtmlPlayer').show();
	$('#recorderButton').attr('onclick', 'openRecorder();');
	$(".list > li.current_play").removeClass("current_play");
}