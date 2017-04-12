/**
 * Created by hayre on 8/25/16.
 */
/*jshint -W117*/
var AdminValidation = (function () {
    "use strict";

    var pub;

    pub = {};
/*
 *checks if key being pressed is a number
 */
    function checkKeyIsDigit(event) {
        var characterPressed, charStr;
        characterPressed = event.keyCode || event.which || event.charCode;
        charStr = "0";
        if (characterPressed < charStr.charCodeAt(0)) {
            return false;
        }
        charStr = "9";
        return characterPressed <= charStr.charCodeAt(0);
    }
    /*
     * checks to see if the date enter is valid according to the boolean in the function.
     */
    function isValidDate(dateString, messages) {

        var parts = dateString.split("/");
        var day = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        var year = parseInt(parts[2], 10);
        console.log(parts);

        if( parts === null || parts === "" || year < 2000 || year > 9999 || month === 0 || month > 12 || day > 31 || day.length > 2 || month.length > 2 || year.length > 4) {
            messages.push("please enter a valid date");
        }
    }
    /*
     * checks if a team is playing two games in one day and if the form has been filled out
     */
    function checkTeam(dateValue, team1, team2, arrayCheckList, messages){
        if (dateValue === "" || team1 === "" || team2 === "") {
            messages.push("please fill out the form and don't leave any blanks.");
        } else {
            for (var i = 0; i < arrayCheckList.length; i += 1) {

                if (arrayCheckList[i].match.indexOf(dateValue) > -1) {

                    if ((arrayCheckList[i].match.indexOf(team1)) > -1) {
                        messages.push(team1 + " cannot play two games in one day.");
                    }
                    if ((arrayCheckList[i].match.indexOf(team2)) > -1) {
                        messages.push(team2 + " cannot play two games in one day.");
                    }
                }
            }
        }

    }
    /* Confirm to update the result table. */
    function comfirmChange(){
        alert("Thank you, your table has been updated");
        $("#errors").html("<p>Your table has been updated.</p>");
        return false;
    }

    /* Cancel the update from going ahead */
    function cancelChange() {
        $("#errors").html("");
        $('#comfirmChange').toggle();
        $('#cancelChange').toggle();
        return false;
    }

    /*
     * checks to see if two venues aren't being used during the same day.
     */
    function checkVenue(dateValue, venue, arrayCheckList, messages){
        for (var i = 0; i < arrayCheckList.length; i += 1){
            if (arrayCheckList[i].match.indexOf(dateValue) > -1){
                if ((arrayCheckList[i].match.indexOf(venue)) > -1 ){
                    messages.push("A game must be played in different venues on the same day");

                }
            }
        }

    }
    //clears the date
    function clearDate() {
        $("#matchDate").val("");
    }
    //clears the score
    function clearScores() {
        $("#matchScoreAgainst").val("");
        $("#matchScoreFor").val("");
    }
    /*
     *Validates the form by calling the check functions and passing the correct variables.
     */
    function validateForm() {
        var messages, matchVenue, matchDate, matchFor, matchAgainst, matchForScore, matchAgainstScore, errorHTML;
        messages = [];
        //creates an array of all current games to be checked against the values being submitted. 
        var arrayCheckList = [];
        $('#getMatch > option').each(function (index) {
            var matchText = {match: this.text};
            arrayCheckList.push(matchText);
        });

        matchFor = $("#matchFor").val();
        matchAgainst =  $("#matchAgainst").val();
        matchDate = $("#matchDate").val();
        matchDate.replace(matchDate[matchDate.length], '');
        checkTeam(matchDate, matchFor, matchAgainst, arrayCheckList, messages);
        isValidDate(matchDate, messages);
        matchVenue = $("#matchVenue").val();
        checkVenue(matchDate, matchVenue, arrayCheckList, messages);

        matchAgainstScore = $('#matchScoreAgainst').val();
        matchForScore = $('#matchScoreFor').val();

        if (messages.length === 0) {
            $("#errors").html("<p>The following Changes will be made:</p><ul><li>Date: "+matchDate+"<li>Team For: "+matchFor+"<li>Team For Score: "+matchForScore+"<li>Team Against: "+matchAgainst+"<li>Team Against Score: "+matchAgainstScore+"<li>Venue: "+matchVenue+"</ul>");
            $('#comfirmChange').toggle();
            $('#cancelChange').toggle();
            $('#comfirmChange').click(comfirmChange);
            $('#cancelChange').click(cancelChange);

        } else {
            // Report the error messages
            errorHTML = "<p><strong>There were errors processing your form</strong></p>";
            errorHTML += "<ul>";
            messages.forEach(function (msg) {
                    errorHTML += "<li>" + msg;
            });
            errorHTML += "</ul>";
            $("#errors").html(errorHTML);
        }

        return false;




    }
    /**
     * Setup function for admin validation.
     */
    pub.setup = function () {

        $('#submitButton').click(validateForm);
        $("#matchScoreAgainst").keypress(checkKeyIsDigit);
        $("#matchScoreFor").keypress(checkKeyIsDigit);
        $('#comfirmChange').toggle();
        $('#cancelChange').toggle();

        $("#matchDate").keyup(function(){
            if ($(this).val().length === 2){
                $(this).val($(this).val() + "/");
            }else if ($(this).val().length === 5){
                $(this).val($(this).val() + "/");
            }
        });

        $("#matchDate").keypress(checkKeyIsDigit);
        $("#clearDate").click(clearDate);
        $("#clearScores").click(clearScores);

    };
    return pub;
}());
$(document).ready(AdminValidation.setup);