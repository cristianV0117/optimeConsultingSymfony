class CategoriesDataTable
{
    dataTable()
    {
        $('#categoriasTabla').DataTable({
            "pageLength": 25,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No hay categorias",
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
(new CategoriesDataTable()).dataTable();