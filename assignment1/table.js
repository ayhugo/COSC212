/**
 * Created by hayre on 8/23/16.
 */
var Table = (function() {
    "use strict";
    var pub = {};
    /*
     *Opens the xml file and sends it to parseTable.
     */
    function showTable() {
        var tableSource = './table/tournament2.xml';
        $.ajax({
            type: "GET",
            url: tableSource,
            cache: false,
            success: function(data) {
                $("#resultTable").html(parseTable(data));
            },

            error: function(data) {
                alert('Not a valid xml file.');
            }
        });
        console.log("Show Table called");
    }
    /*
     * Creates a result table by using the values passed by the xml file.
     */
    function parseTable(data){
        //initialises the table
        var targetString = "<table class='pure-table'><thead><tr><th>Date</th><th colspan='2'>Team1 Vs Team2</th><th>Score</th><th>Venue</th></tr></thead>";

        if ($(data).find("match").object === 0){
            alert("There is no matches");
        }
        $(data).find("match").each(function () {
            var venue = $(this).find("venue")[0].textContent;
            var team1 = $(this).find("team")[0].textContent;
            var team2 = $(this).find("team")[1].textContent;
            var score1 = $(this).find('team')[0].getAttribute('score');
            var score2 = $(this).find('team')[1].getAttribute('score');
            var day = $(this).find("day")[0].textContent;
            var month = $(this).find("month")[0].textContent;
            var year = $(this).find("year")[0].textContent;
            var finalScore;
            if (score1 === null){
                finalScore = "to be played";
            } else {
                finalScore = score1+"-"+score2;
            }
            targetString += "<tr><td>" + day + "/" + month + "/" + year +"</td><td>" +team1+ "</td><td>" + team2 +"</td><td>" +finalScore+"</td><td>" + venue +"</td></tr>";
        });
        targetString += "</table>";
        return targetString;
    }
    pub.setup = function() {
        showTable();
    };
    return pub;
}());
$(document).ready(Table.setup);