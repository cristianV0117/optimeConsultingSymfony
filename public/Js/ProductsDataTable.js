class ProductsDataTable
{
    dataTable()
    {
        $('#productosTabla').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No hay usuarios",
                "info": "mostrar pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "search": "Buscar:",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
            }

        });
    }
}
(new ProductsDataTable()).dataTable();