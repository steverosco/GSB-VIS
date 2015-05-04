$( document ).ready(function() {
    $('.btnChoixVis').click(function(){
        var id;
        id = $(this).val();
//        console.log(id);
        var param = "id=" + id;
        $(document).load('{{ path("gsb_comptable_vuFraisVis") }}',param);
    });
});