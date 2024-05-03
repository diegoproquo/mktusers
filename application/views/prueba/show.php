<div id="container">

    <div id="body" style="text-align: center; margin-top:50px;">
        <button onclick="PruebaConexion()">Prueba de conexión</button>

        <div class="row" style="margin-top:50px;">
            <label id="contador"><span>Nº de sites: </span></label>
        </div>
        <div class="row" style="margin-top:50px;">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <textarea id="textarea" style="min-width:800px;" rows="10"></textarea>
            </div>
            <div class="col-md-3"></div>

        </div>
    </div>


</div>


<script>
    function PruebaConexion() {
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>/Prueba/PruebaConexionUnifi',
            dataType: 'json',
            success: function(response) {
                for (i = 0; i < response.length; i++) {
                    $('#textarea').append(response[i]['desc']);
                    $('#textarea').append("&#13;&#10");
                }
                $('#contador').append(response.length);


            },
            error: function(error) {
                console.log("error");
                console.log(error);

            }
        });
    }
</script>