var selectedItems = [];
var passtype_v = "";
$(document).ready(function(){
    passtype_v = $(".ticket_type").html();

    // AJAX - Closed classes
    $.ajax({
        type: "GET",
        url: "closed.php",
        success: function(data) {
            console.log("Closed response: " + data);
            var parsedData = JSON.parse(data);
            $("td").each(function() {
                var thisIndex = $("td").index(this);

                // mark class as closed, and nonselectable
                if ($.inArray(thisIndex, parsedData) != -1) {
                    if(typeof $(this).attr("role") != 'undefined'){
                        $(this).css("color", "##3D3D3D");
                        if ($(this).attr("role") == 'alice') {
                            $(this).css("background", "#407C87");
                        } else if ($(this).attr("role") == 'aqua'){
                            $(this).css("background", "#178E89");
                        } else {
                            //yoga
                            $(this).css("background", "#AF5D92");
                        }
                    } else {
                        //milonga squares
                        $(this).css("background", "#999999");
                        $(this).find("h2").css("color", "#AF5D92");
                    }
                    $(this).toggleClass("nonselectable");
                }
            });
        }
    });

    // Selections
    $("td").click(function() {
        var c = $("td").index(this);

        if (typeof $(this).attr("class") == "undefined" ||
            $(this).attr("class").indexOf("nonselectable") == -1) {

            // Deselect all other items in row
            $(this).closest('tr').find('td').each(function() {
            if (typeof $(this).attr('class') != "undefined" &&
                $(this).attr('class').indexOf('selected') != -1 &&
                $('td').index(this) != c) {
                $(this).toggleClass("selected");
                selectedItems.splice(selectedItems.indexOf($('td').index(this)), 1);
                console.log("Automatically deselected " + c);
            }
        });

            if ($.inArray(c, selectedItems) == -1) {
                // Select new item
                selectedItems.push(c);
                console.log("Pushed " + c);
                $(this).toggleClass("selected");
            } else {
                if (typeof $(this).attr("class") !== "undefined") {
                    selectedItems.splice(selectedItems.indexOf(c), 1);
                    $(this).toggleClass("selected");
                    console.log("Removed " + c);
                }
            }

            // AJAX - Best price updating
            $.ajax({
                type: "GET",
                url: "request.php",
                data: {selection: JSON.stringify(selectedItems),
                        passtype: passtype_v},
                success: function(data) {
                    console.log(data);
                    var response = JSON.parse(data);
                    $("#status").html(response[0]);
                    $("#yourprice").html("Your price is: $<span id='numeric_price'>"
                                        + response[1] + ".00</span>");
                }
            });
        }
    });

    // Submitting
    $("#submit").click(function() {
        $("#class_string").val(JSON.stringify(selectedItems));
        $("#price").val($("#numeric_price").html());
    });
}); //document.ready

window.onsubmit = function() {
    var alertString = "";

    if (selectedItems.length == 0) {
        alertString += "Please select at least one class.";
    }

    if (alertString.length > 0) {
        alert(alertString);

        return false;
    } else {
        return true;
    }
}
