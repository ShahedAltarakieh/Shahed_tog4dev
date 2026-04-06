$(document).ready(function(){
    $(document).on('change', '#type_id', function (){
       var type = $(this).val();
        $("#category_id option").removeClass("d-none");
        console.log(type)
        if(type == 1){
            $("#category_id").parents(".form-group").remove("d-none");
            $("#target").parents(".form-group").addClass("d-none");
            $("#target_usd").parents(".form-group").addClass("d-none");
            $("#category_id option[data-type=1]").addClass("d-none");
            $("#category_id option[data-type=2]").addClass("d-none");
            $("#category_id option[data-type=3]").addClass("d-none");
            $("#category_id option[data-type=4]").removeClass("d-none");
        } else if(type == 2){
            $("#category_id").parents(".form-group").removeClass("d-none");
            $("#target").parents(".form-group").addClass("d-none");
            $("#target_usd").parents(".form-group").addClass("d-none");
            $("#category_id option[data-type=1]").addClass("d-none");
            $("#category_id option[data-type=2]").removeClass("d-none");
            $("#category_id option[data-type=3]").addClass("d-none");
            $("#category_id option[data-type=4]").addClass("d-none");
        } else if(type == 3){
            $("#category_id").parents(".form-group").removeClass("d-none");
            $("#target").parents(".form-group").removeClass("d-none");
            $("#target_usd").parents(".form-group").removeClass("d-none");
            $("#category_id option[data-type=1]").addClass("d-none");
            $("#category_id option[data-type=2]").addClass("d-none");
            $("#category_id option[data-type=3]").removeClass("d-none");
            $("#category_id option[data-type=4]").addClass("d-none");
        }
    });

    $(document).on('click', '.btn-generate', function(){
        $("#generated_prices").removeClass("d-none");
        var d_1_option_1 = $("#dropdown_1_option_1").val();
        var d_1_option_2 = $("#dropdown_1_option_2").val();
        var d_2_option_1 = $("#dropdown_2_option_1").val();
        var d_2_option_2 = $("#dropdown_2_option_2").val();

        var label_price_1 = d_1_option_1;
        if(d_2_option_1 !== ''){
            label_price_1 = label_price_1 + " - " + d_2_option_1;
        }

        var label_price_2 = d_1_option_1;
        if(d_2_option_2 !== ''){
            label_price_2 = label_price_2 + " - " + d_2_option_2;
            $("#prices_option_2").val(label_price_2);
            $(".tr-2").removeClass("d-none");
        } else{
            $("#prices_option_2").val("");
            $(".tr-2").addClass("d-none");
        }

        var label_price_3 = d_1_option_2;
        if(d_2_option_1 !== ''){
            label_price_3 = label_price_3 + " - " + d_2_option_1;
        }

        var label_price_4 = d_1_option_2;
        if(d_2_option_2 !== ''){
            label_price_4 = label_price_4 + " - " + d_2_option_2;
            $("#prices_option_4").val(label_price_4);
            $(".tr-4").removeClass("d-none");
        } else {
            $("#prices_option_4").val("");
            $(".tr-4").addClass("d-none");
        }

        $("#prices_option_1").val(label_price_1);
        $("#prices_option_3").val(label_price_3);
    });
});
