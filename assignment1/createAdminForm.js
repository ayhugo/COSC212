/**
 * Created by hayre on 8/25/16.
 */
/*jshint -W117*/
var AdminVenues = (function () {
    "use strict";

    var pub;
    pub = {};

    /*
     *loads in the venues xml file and passes it onto parsetable
     */
    function getVenues() {
        var tableSource = './table/venues2.xml';
        $.ajax({
            type: "GET",
            url: tableSource,
            cache: false,
            success: function(data) {
                $("#matchVenue").html(parseTable(data));
            },

            error: function(data) {
                alert('Not a valid xml file.');
            }
        });
        console.log("Show Table called");
    }
   /*
   * creates a drop down box the only contains the venues from the xml.
   */
    function parseTable(data){
        var targetString = "";

        if ($(data).find("venues").object === 0){
            targetString += "There is no matches";
        }
        var count = $(data).find("venue").length;
        $(data).find("venues").each(function () {
            for (var i = 0; i < count; i += 1) {
                var venue = $(this).find("venue")[i].textContent;
                var noSpaceVenue = venue.replace(/\s/g, '');
                targetString += "<option value=" + noSpaceVenue + ">" + venue + "</option>";
            }

        });
        $('#matchVenue').html(targetString);
    }
    /*
     * when a match is selected from the drop down box all the values of the form is filled out with the associated
     */
    function getMatches() {
        var tableSource = './table/tournament2.xml';
        $.ajax({
            type: "GET",
            url: tableSource,
            cache: false,
            success: function(data) {
                $("#getMatch").html(parseTable2(data));
                $("#getMatch").on('change', function () {
                    $("#matchDate").val($("#getMatch").val().split('|')[0]);
                    if ($("#getMatch").val().split('|')[2].split('-')[0].replace(/\s/g, '') === "0") {
                        $("#matchScoreFor").val($("#getMatch").val().split('|')[2].split('-')[0].trim());
                    } else if (!parseInt($("#getMatch").val().split('|')[2].split('-')[0])) {
                        $("#matchScoreFor").val("Enter result");
                        $("#matchScoreAgainst").val("Enter result");
                    } else {
                        $("#matchScoreFor").val($("#getMatch").val().split('|')[2].split('-')[0].trim());
                        $("#matchScoreAgainst").val($("#getMatch").val().split('|')[2].split('-')[1].trim());
                    }
                    
                    if (!parseInt($("#getMatch").val().split('|')[2].split('-')[0])) {
                        $("#matchScoreFor").val("0");
                        $("#matchScoreAgainst").val("0");
                    } else {
                        $("#matchScoreFor").val($("#getMatch").val().split('|')[2].split('-')[0].trim());
                        $("#matchScoreAgainst").val($("#getMatch").val().split('|')[2].split('-')[1].trim());
                    }

                    $("#matchFor").val($("#getMatch").val().split('|')[1].split('vs')[0].trim());
                    $("#matchAgainst").val($("#getMatch").val().split('|')[1].split('vs')[1].trim());
                    $("#matchVenue").val($("#getMatch").val().split('|')[3].replace(/\s/g, ''));
                });
            },

            error: function(data) {
                alert('Not a valid xml file.');
            }
        });
        console.log("Show Table called");
    }
    /*
     * creates a drop down box the only contains all the set matches from the xml.
     */
    function parseTable2(data){
        var targetString = "<option value = 'new'>New Match</option>";

        if ($(data).find("match").object === 0){
             alert("There is no matches");
        }
        $(data).find("match").each(function () {
            var venue = $(this).find("venue")[0] .textContent;
            var team1 = $(this).find("team")[0].textContent;
            var team2 = $(this).find("team")[1].textContent;
            var score1 = $(this).find('team')[0].getAttribute('score');
            var score2 = $(this).find('team')[1].getAttribute('score');
            var day = $(this).find("day")[0].textContent;
            var month = $(this).find("month")[0].textContent;
            var year = $(this).find("year")[0].textContent;
            var venueNoSpaces = venue.replace(/\s/g, '');
            var finalScore;
            if (score1 === null){
                finalScore = "to be played";
            } else {
                finalScore = score1+"-"+score2;
            }
            targetString += "<option value='" +day +"/"+month+"/"+year+" | "+team1+ " vs " +team2+" | "+ finalScore+" | "+ venueNoSpaces + "'  >" +day +"/"+month+"/"+year+" | "+team1+ " vs " +team2+" | "+ finalScore+" | "+ venueNoSpaces + "</option>";
        });

        $('#getMatch').html(targetString);

 

    }

    
    

    pub.setup = function () {
        getVenues();
        getMatches();
        

    };

    return pub;
}());
$(document).ready(AdminVenues.setup);