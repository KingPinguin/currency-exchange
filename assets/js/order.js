import $ from 'jquery';  

$(function(e){
    
    $("#get_quote").submit(function(e){
        e.preventDefault();
        $( "#purchase" ).remove();
        $( ".purchased" ).remove();
        $( ".amount" ).remove();
        $("#quote").prepend( "<div class='alert alert-info calculating' role='alert'>Calculating...</div>" );
        $.post($(this).attr("action"), $(this).serialize(), function(jsonData){
            $( ".calculating" ).remove();
            $( ".error" ).remove();
            $('#quote').append(
                "<ul class='amount' style='list-style-type:none;'><li>Amount that you need to pay in USD = "+jsonData['currencyPaidAmount']+"</li></ul>"
            );
            $('#quote').append("<button type='submit' class='btn btn-success' id='purchase'>Purchase</button>");
            $('#purchase').click(function(e){
                $.ajax({
                    type: 'POST',
                    url: 'http://localhost:8001/api/quotes',
                    data: JSON.stringify(jsonData),
                    success: function(data) {
                        $( "#purchase" ).remove();
                        $('#quote').append("<div class='alert alert-success purchased' role='alert'>Your purchase is succesfull!</div>");
                    },
                    contentType: "application/json",
                    dataType: 'json'
                });
            })
            console.log(jsonData);
        }, "json")
        .fail(function() {
            $( "#purchase" ).remove();
            $( ".calculating" ).remove();
            $("#form").prepend( "<div class='alert alert-danger error' role='alert'>Form values are not valid</div>" );
        });
    });

  });