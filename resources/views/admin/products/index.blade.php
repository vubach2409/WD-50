@extends('admin.dashboard')

@section('content')
    <div class="container-fluid">
        <h1>Products</h1>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Create Product</a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <form id="filterForm">
                    <div class="row align-items-end">
                        <!-- Search by name -->
                        <div class="col-md-3 mb-3">
                            <label for="productNameSearch">Search by name</label>
                            <div class="input-group">
                                <input type="text" name="productNameSearch" id="productNameSearch" class="form-control">
                                <div class="input-group-append">
                                    <button type="button" id="nameSearchButton" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="startDate">Start Date</label>
                            <input type="date" name="startDate" id="startDate" class="form-control"
                                max="{{ date('Y-m-d') }}">
                            <div id="startDateError" class="text-danger"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="endDate">End Date</label>
                            <input type="date" name="endDate" id="endDate" class="form-control"
                                max="{{ date('Y-m-d') }}">
                            <div id="endDateError" class="text-danger"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="button" id="filterButton" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table" id="productsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
@endsection
@section('scripts-libs')
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true, // Enable responsive
                ajax: {
                    url: "{{ route('admin.products.index') }}",
                    data: function(d) {
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                        d.productNameSearch = $('#productNameSearch').val(); // Pass the search term
                    }
                },
                dom: 'Brt<"bottom d-flex justify-content-between align-items-center"l<"pagination-wrapper"pi>>', // Add B for buttons
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                language: {
                    paginate: {
                        previous: '<span aria-hidden="true">&laquo;</span>',
                        next: '<span aria-hidden="true">&raquo;</span>'
                    },
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries"
                },


                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'category',
                        name: 'category',
                        orderable: false
                    },
                    {
                        data: 'brand',
                        name: 'brand'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format('HH:mm:ss DD/MM/YYYY');
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#startDate').change(function() {
                let startDate = $(this).val();
                if (startDate) {
                    $('#endDate').attr('min', startDate); // Đặt giá trị min cho endDate
                } else {
                    $('#endDate').removeAttr('min'); // Xóa min nếu startDate bị xóa
                }
            });

            // When user select end date
            $('#endDate').change(function() {
                let startDate = $('#startDate').val();
                let endDate = $(this).val();

                if (startDate && endDate && endDate < startDate) {
                    $(this).val(''); // Xóa giá trị endDate nếu không hợp lệ
                }
            });
            // Validation function
            function validateDates() {
                let startDate = $('#startDate').val();
                let endDate = $('#endDate').val();
                let isValid = true;
                $('#startDateError').text('');
                $('#endDateError').text('');
                if (startDate && endDate) {
                    if (startDate > endDate) {
                        isValid = false;
                    }
                }
                return isValid;
            }

            // Handle name search
            $('#nameSearchButton').click(function() {
                table.ajax.reload(); // Reload the table when the name search button is clicked
            });

            // Handle filter dates
            $('#filterButton').click(function() {
                if (validateDates()) {
                    table.ajax.reload();
                }
            });
        });
    </script>
@endsection
