<script>
$(document).ready(function() {
    // Initialize the DataTable
    $('#tbl_competidores').DataTable({
        "pageLength": 7,
        "bLengthChange": false,
        "searching": false
    });

    // Add any additional JavaScript functionality here
    const COD_MOLECULA = "18813022"
    getRequest(COD_MOLECULA);

    

});


async function getRequest(COD_MOLECULA){
    try {
        // Fetch data from the server
        const response = await fetch('getImportacion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                COD_MOLECULA: COD_MOLECULA,
            })
        });        

        const result = await response.json();
        

    } catch (error) {
        console.error('Error al obtener los datos:', error);
    }
}


</script>