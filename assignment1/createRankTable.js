/**
 * Created by hayre on 8/21/16.
 */
/*jshint -W117*/
var Table = (function() {
    "use strict";
    var pub = {};

    /*
    *Loads in the the tournament details from the xml file.
    * passes the xml onto the function parseTable.
    * */
    function showTable() {
        var tableSource = './table/tournament.xml';
        $.ajax({
            type: "GET",
            url: tableSource,
            cache: false,
            success: function(data) {
                $("#resultTable").html(parseTable(data));
            },

            error: function(data) {
                alert('There are no reviews');
            }
        });
        console.log("Show Table called");
    }
    /*
     *Creates and orders the xml data into a table.
     *Table is created by using objects that are created from the xml data.
     */
    function parseTable(data) {
        //String is initialised which is used to create the table.
        var targetString = "<table class='pure-table'><thead><tr><th>Rank</th><th>Team</th><th>Played</th><th>Won</th><th>Drawn</th><th>Lost</th><th>For</th><th>Against</th><th>Diff</th><th>Points</th></tr></thead>";
        var checkList = [];
        var sortRank = [];

        if ($(data).find("match").object === 0) {
            alert("There is no matches");
        }
        //for each match in the xml file store the teams and score, and then pass them onto an object.
        $(data).find("match").each(function () {
            var team1 = $(this).find("team")[0].textContent;
            var team2 = $(this).find("team")[1].textContent;
            var score1 = $(this).find('team')[0].getAttribute('score');
            var score2 = $(this).find('team')[1].getAttribute('score');
            //spaces are removed from teams
            var noSpaceTeam1 = team1.replace(/\s/g, '');
            var noSpaceTeam2 = team2.replace(/\s/g, '');

            //checks to see if the game has been played.
            if (score1 !== null) {
                //checks to see if team1 is in the array checkList.
                if ($.inArray(noSpaceTeam1, checkList) === -1) {
                    var won = 0, drawn = 0, lost = 0, intFor = 0, against = 0, diff = 0, points = 0;

                    if (score1 > score2) {
                        won += 1;
                        points += 2;
                    } else if (score1 === score2) {
                        drawn += 1;
                        points += 1;
                    } else {
                        lost += 1;
                        points += 0;
                    }
                    intFor = score1;
                    against = score2;
                    diff = intFor - against;

                    //creates a new object using noSpaceTeam1 as the variable name.
                    window[noSpaceTeam1] = {
                        name: team1,
                        played: 1,
                        won: won,
                        drawn: drawn,
                        lost: lost,
                        for: parseInt(intFor),
                        against: parseInt(against),
                        diff: diff,
                        points: points
                    };
                    //team name is then added to the array in order to be checked later.
                    checkList.push(noSpaceTeam1);
                } else {
                    //because the object already exists the object with the name of the current noSpaceTeam1 has its current values edited 
                    if (score1 > score2) {
                        window[noSpaceTeam1].won += 1;
                        window[noSpaceTeam1].points += 2;
                    } else if (score1 === score2) {
                        window[noSpaceTeam1].drawn += 1;
                        window[noSpaceTeam1].points += 1;
                    } else {
                        window[noSpaceTeam1].lost += 1;
                        window[noSpaceTeam1].points += 0;
                    }
                    window[noSpaceTeam1].played += 1;
                    window[noSpaceTeam1].for += parseInt(score1);
                    window[noSpaceTeam1].against += parseInt(score2);
                    window[noSpaceTeam1].diff = window[noSpaceTeam1].for - window[noSpaceTeam1].against;
                }
                //Same as team1 but with team2
                if ($.inArray(noSpaceTeam2, checkList) === -1) {
                    var won2 = 0, drawn2 = 0, lost2 = 0, intFor2 = 0, against2 = 0, diff2 = 0, points2 = 0;

                    if (score1 < score2) {
                        won2 += 1;
                        points2 += 2;
                    } else if (score1 === score2) {
                        drawn2 += 1;
                        points2 += 1;
                    } else {
                        lost2 += 1;
                        points2 += 0;
                    }
                    intFor2 = score2;
                    against2 = score1;
                    diff2 = intFor2 - against2;
                    window[noSpaceTeam2] = {
                        name: team2,
                        played: 1,
                        won: won2,
                        drawn: drawn2,
                        lost: lost2,
                        for: parseInt(intFor2),
                        against: parseInt(against2),
                        diff: diff2,
                        points: points2
                    };
                    checkList.push(noSpaceTeam2);
                } else {
                    if (score1 < score2) {
                        window[noSpaceTeam2].won += 1;
                        window[noSpaceTeam2].points += 2;
                    } else if (score1 === score2) {
                        window[noSpaceTeam2].drawn += 1;
                        window[noSpaceTeam2].points += 1;
                    } else {
                        window[noSpaceTeam2].lost += 1;
                        window[noSpaceTeam2].points += 0;
                    }
                    window[noSpaceTeam2].played += 1;
                    window[noSpaceTeam2].for += parseInt(score2);
                    window[noSpaceTeam2].against += parseInt(score1);
                    window[noSpaceTeam2].diff = window[noSpaceTeam2].for - window[noSpaceTeam2].against;
                }
            }

        });
        /*
        *loops through all the points and adds them to an array to be sorted and reversed,
        *this places ranks the teams in order.
        */
        for (var j = 0; j < checkList.length; j += 1) {
            sortRank.push(window[checkList[j]].points);
            sortRank.sort().reverse();
        }
        
        /*
         *loops through the objects and checks if the points match the array sortRank at count
         *each value is then added to targetString which is creating the table
         */
        var count = 0;
        while (count < sortRank.length) {
            for (var i = 0; i < checkList.length; i += 1) {
                if (parseInt(sortRank[count]) === parseInt(window[checkList[i]].points)) {
                    count += 1;
                    targetString += "<tr><td>" + count + "</td><td>" + window[checkList[i]].name + "</td><td>" + window[checkList[i]].played + "</td><td>" + window[checkList[i]].won + "</td><td>" + window[checkList[i]].drawn + "</td><td>" + window[checkList[i]].lost + "</td><td>" + window[checkList[i]].for + "</td><td>" + window[checkList[i]].against + "</td><td>" + window[checkList[i]].diff + "</td><td class='points'>" + window[checkList[i]].points + "</td></tr>";
                }
            }
        }

        targetString += "</table>";
        //targetString is has now built the table and is added to the div result table. 
       $('#resultTable').html(targetString);
    }
    
    pub.setup = function() {
        showTable();
    };
    return pub;
}());
$(document).ready(Table.setup);