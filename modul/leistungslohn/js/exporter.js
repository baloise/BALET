$(document).ready(function(){

    $('#getCSV').click(function(){

        var users = [

            ["b000003", 4, 1],
            ["b123000", 4, 2],
            ["b123000", 4, 3]

        ];

        $.redirect('./modul/leistungslohn/call/createCSV.php', {userArray:users});

    });

});