$(document).ready(function(){

    $('.corrSubAvg').each(function(){

        $(this).click(function(){

            $(this).prop("disabled",true);
            var button = $(this);

            var subid = $(this).attr('subjid');

            $('.corrSubAvgNum').each(function(){

                if($(this).attr('subjid') == subid){

                    var corrGrade = $(this).val();

                    $.ajax({
                        method: "POST",
                        url: "./modul/noten/modify.php",
                        data: {todo:"correction", subid:subid, corrGrade:corrGrade},
                        success: function(data){

                            if(data){

                                $('#errorText').html(data);
                                $('#errorAlert').slideDown("fast");
                                button.prop("disabled",false);

                            } else {

                                button.prop("disabled",false);

                                $('#successText').html(translate[101]);
                                $("#successAlert").slideDown("fast").delay(1300).slideUp("slow");

                                $('.subAvg').each(function(){

                                    if($(this).attr('subjid') == subid){
                                        var text = $(this).html();

                                        var newText = "<s>" + text + "</s> <b style='color:red'>" + corrGrade + "</b>";

                                        $(this).html(newText);

                                    }

                                });

                            }

                        }
                    });

                }

            });

        });

    });

    $('.userGradesHead').each(function(){
        $(this).click(function(){

            var selection = $(this).attr("containerID");

            $('.detailedGrades').each(function(){
                if($(this).attr("containerID") == selection){
                    $(this).slideToggle("slow");
                }
            });
        });
    });

    $(".btnSelect").each(function(){

        $(this).click(function(){

            var object = $(this);

            $(".btnSelect").each(function(){
                $(this).removeClass("btn-success", 1000);
                $(this).removeClass("selectedType");
            });

            $(object).addClass("btn-success", 1000);
            $(object).addClass("selectedType");

        });

    });

    $('.addGrade').each(function(){

        $(this).click(function(){

            $("#errorAlert").slideUp("fast");

            $('.addGrade').each(function(){
                $(this).prop("disabled",true);
            });

            var subjectId = $(this).attr('fSubject');
            var weight, title, grade, reason;
            var error = "";

            $('.fgradeTitle').each(function(){
                if($(this).attr('fSubject') == subjectId){
                    title = $(this).val();
                }
            });

            $('.fgradeNote').each(function(){
                if($(this).attr('fSubject') == subjectId){
                    grade = $(this).val();
                }
            });

            $('.fgradeWeight').each(function(){
                if($(this).attr('fSubject') == subjectId){
                    weight = $(this).val();
                }
            });

            $('.fgradeReason').each(function(){
                if($(this).attr('fSubject') == subjectId){
                    reason = $(this).val();
                }
            });

            if(!weight){
                error = error + "<li>" + translate[173]+"</li>";
            }

            if(!title){
                error = error + "<li>" + translate[172]+"</li>";
            }

            if(!grade){
                error = error + "<li>" + translate[171]+"</li>";
            }

            if(grade < 4 && !reason){
                error = error + "<li>" + translate[174]+"</li>";
            }

            if(error){

                $('#errorText').html(error);
                $('#errorAlert').slideDown("fast");

                $('.addGrade').each(function(){
                    $(this).prop("disabled",false);
                });

            } else {

                $("#errorAlert").slideUp("fast");

                $.ajax({
                    method: "POST",
                    url: "./modul/noten/modify.php",
                    data: {todo:"addGrade", grade:grade, title:title, weight:weight, subjectId:subjectId, reason:reason},
                    success: function(data){

                        if(data){

                            $('#errorText').html(data);
                            $('#errorAlert').slideDown("fast");

                            $('.addGrade').each(function(){
                                $(this).prop("disabled",false);
                            });

                        } else {

                            $('#successText').html(translate[103]);
                            $("#successAlert").slideDown("fast").delay(1300).slideUp("slow",function(){
                                $("#pageContent").load("modul/noten/noten.php", function(){
                                    $('.loadScreen').fadeTo("fast", 0, function(){
                                        $('#pageContents').fadeTo("fast", 1);
                                    });
                                });
                            });

                        }

                    }
                });

            }


        });

    });

    $('.divtoggler').each(function(){

        $(this).click(function(){

            var subSemId = $(this).attr("subSemid");

            $(".divtogglercontent").each(function(){

                if($(this).attr("subSemid") == subSemId){
                    $(this).slideToggle("fast");
                }

            });

        });

    });

    $('.delGrade').each(function(){

        $(this).click(function(){

            var gradeId = $(this).attr('gradeId');

            if(!gradeId){
                $('#errorText').html(translate[95]);
                $('#errorAlert').slideDown("fast");
            } else {

                $.ajax({
                    method: "POST",
                    url: "./modul/noten/modify.php",
                    data: {todo:"deleteGrade", gradeId:gradeId},
                    success: function(data){

                        if(data){

                            $('#errorText').html(data);
                            $('#errorAlert').slideDown("fast");

                        } else {

                            $('.gradeEntry').each(function(){
                                if($(this).attr('gradeId') == gradeId){
                                    $(this).slideUp("slow", function(){
                                       $("#pageContent").load("modul/noten/noten.php", function(){
                                            $('.loadScreen').fadeTo("fast", 0, function(){
                                                $('#pageContents').fadeTo("fast", 1);
                                            });
                                        });
                                    });
                                }
                            });

                        }

                    }
                });

            }

        });

    });

    $('#addSubject').click(function(){

        $('#errorForm').html(error).slideUp("fast");
        $('#addSubject').prop("disabled",true);

        var error = "";

        var subName = $('#newSubNam').val();
        var subSem = $('#newSubSem').val();
        var subType = 1;

        if($("#LIT").length){

            var i = 0;
            $('.selectedType').each(function(){
                i = i + 1;
            });

            if(i == 1){
                subType = $('.selectedType').attr("value");
            } else {
                error = error + "<li>" + translate[195]+"</li>";
            }

        }

        if(!subName){
            error = error + "<li>" + translate[196]+"</li>";
        }

        if(!subSem || subSem == "Semester:"){
            error = error + "<li>" + translate[197]+"</li>";
        }

        if(error){

            $('#errorText').html(error);
            $('#errorAlert').slideDown("fast");

            $('#addSubject').prop("disabled",false);

        } else {

            $('#errorAlert').slideUp("fast");

            $.ajax({
                method: "POST",
                url: "./modul/noten/modify.php",
                data: {todo:"addSubject", subName:subName, subSem:subSem, subType:subType},
                success: function(data){

                    if(data){

                        $('#errorText').html(data);
                        $('#errorAlert').slideDown("fast");

                        $('#addSubject').prop("disabled",false);

                    } else {

                        $('#successText').html(translate[175]);
                        $("#successAlert").slideDown("fast").delay(1300).slideUp("slow",function(){
                            $("#pageContent").load("modul/noten/noten.php", function(){
                                $('.loadScreen').fadeTo("fast", 0, function(){
                                    $('#pageContents').fadeTo("fast", 1);
                                });
                            });
                        });


                    }

                }
            });
        }

    });

    $('.deleteSubject').each(function(){

        $(this).click(function(event){

            object = $(this);
            event.preventDefault();

            $('#warningText').html(translate[162]);
            $('#warningAlert').slideDown("fast");
            $("#warningButton").slideDown("fast");
            $("#warningButton").click(function(event){

                event.preventDefault();

                var subId = object.attr('subjectId');

                if(!subId){
                    $('#errorText').html(translate[163]);
                    $('#errorAlert').slideDown("fast");
                } else {
                    $.ajax({
                        method: "POST",
                        url: "./modul/noten/modify.php",
                        data: {todo:"deleteSubject", subId:subId},
                        success: function(data){

                            if(data){

                                $('#errorText').html(data);
                                $('#errorAlert').slideDown("fast");

                            } else {

                                $('#warningAlert').slideUp("fast");

                                $('.delSubTag').each(function(){

                                    if($(this).attr('fSubject') == subId){
                                        $(this).slideUp('slow');
                                    }

                                });

                            }

                        }
                    });
                }

            });

        });

    });

    $('.fgradeNote').each(function(){
        $(this).keyup(function(){

            var fid = $(this).attr("fSubject");

            if($(this).val() <= 4 && $(this).val()){
                $('.badDay').each(function(){
                    if($(this).attr("fSubject") == fid){
                        $(this).slideDown("slow");
                    }
                });
            } else {
                $('.badDay').each(function(){
                    if($(this).attr("fSubject") == fid){
                        $(this).slideUp("slow");
                    }
                });
            }
        });
    });

    $('.contentToggler').each(function(){

        $(this).click(function(){

            var semID = $(this).attr("semID");

            $(".toggleContent").each(function(){

                if($(this).attr("semID") == semID){
                    $(this).slideToggle("fast");
                }

            });

        });

    });

});
