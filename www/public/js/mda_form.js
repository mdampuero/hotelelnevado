$("form").submit(function() {
    var error = null;
    $("form input").removeClass("inputError");
    $("#messageErrorForm").removeClass();
    $("form").find(':input').each(function() {
        if ($(this).attr("mandatory") != undefined) {
            if (this.type == "checkbox") {
                if (this.checked == false) {
                    $(this).addClass("inputError");
                    error = $(this).attr("mandatory");
                    $(this).focus();
                    return false;
                }
            }
            if (this.value == "") {

                $(this).addClass("inputError");
                error = $(this).attr("mandatory");
                $(this).focus();
                return false;

            }
            if ($(this).attr("default") != undefined) {
                if (this.value == $(this).attr("default")) {
                    $(this).addClass("inputError");
                    error = $(this).attr("mandatory");
                    $(this).focus();
                    return false;
                }
            }
            if ($(this).attr("email") != undefined) {
                if (!is_valid_email(this)) {
                    $(this).addClass("inputError");
                    error = $(this).attr("email");
                    $(this).focus();
                    return false;
                }
            }
            if ($(this).attr("repeat") != undefined) {
                var repeat = $("input#" + $(this).attr("repeat")).val();
                if (this.value != repeat) {
                    $(this).addClass("inputError");
                    error = $(this).attr("repeat_label");
                    $(this).focus();
                    return false;
                }
            }
        }
        if ($(this).attr("minimum") != undefined) {
            var min = parseInt($(this).attr("minimum"));
            if (this.value.length < min && this.value.length != 0) {
                $(this).addClass("inputError");
                error = $(this).attr("minimum_label");
                $(this).focus();
                return false;
            }
        }
    })
    if (error != null) {
        $("#messageErrorForm").addClass("messageError");
        $("#messageErrorForm").html(error);
        $("#messageErrorForm").fadeIn();
        return false;
    } else {
        $("#messageErrorForm").addClass("messageSend");
        $("#messageErrorForm").html($("form").attr("sending"));
        $("#messageErrorForm").fadeIn();
        $('form input[type="submit"]').attr("disabled", "disabled")
        return true;
    }

}); //submit
