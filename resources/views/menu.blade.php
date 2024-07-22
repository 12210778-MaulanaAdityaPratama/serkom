<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPMM Restoran ABC</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/css/dropify.min.css" integrity="sha512-LF9ghZey1+ev/UcJ+bQLMkZDoz5v56wLFd9M+k7Es+HInTHFNYEfp6cf41JXr/q1qFxPj9cqvghJnlZI20EqXQ==" crossorigin="anonymous" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            padding: 20px;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .btn-primary {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">SIPMM Restoran ABC</h1>
        <a class="btn btn-success mb-2" href="javascript:void(0)" id="createNewMenu">Create New Menu</a>
        <table id="menu-table" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Menu</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <th>Gambar</th>
                    <th width="100px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="menuForm" name="menuForm" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="menu_id">
                        <div class="form-group">
                            <label for="nama_makanan" class="col-sm-12 control-label">Nama Menu</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="nama_makanan" name="nama_makanan" placeholder="Enter Nama Menu" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label">Deskripsi</label>
                            <div class="col-sm-12">
                                <textarea id="deskripsi" name="deskripsi" required="" placeholder="Enter Deskripsi" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label">Harga</label>
                            <div class="col-sm-12">
                                <input type="number" id="harga" name="harga" required="" placeholder="Enter Harga" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label">Kategori</label>
                            <div class="col-sm-12">
                                <input type="text" id="kategori" name="kategori" required="" placeholder="Enter Kategori" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label">Gambar</label>
                            <div class="col-sm-12">
                                <input type="file" id="gambar" name="gambar" class="dropify" required="" data-default-file="">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/js/dropify.min.js" integrity="sha512-YnTdmg8sQKGAiPA90jQQPyL0l8TxRr8GP62WpsOWo2Vn+jaSYCDudMYRBEWIB+XtEsYXgL8ZsZYbrsRfgHo55A==" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
            var table = $('#menu-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('menus.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'nama_makanan', name: 'nama_makanan'},
                    {data: 'deskripsi', name: 'deskripsi'},
                    {data: 'harga', name: 'harga'},
                    {data: 'kategori', name: 'kategori'},
                    {
                        data: 'gambar',
                        name: 'gambar',
                        render: function(data, type, full, meta) {
                            return data ? '<img src="' + data + '" height="50"/>' : '';
                        },
                        orderable: false
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
    
            $('#createNewMenu').click(function () {
                $('#saveBtn').val("create-menu");
                $('#menu_id').val('');
                $('#menuForm').trigger("reset");
                $('#modelHeading').html("Create New Menu");
                $('#ajaxModel').modal('show');
                $('.dropify').dropify(); // Initialize Dropify
            });
    
            $('body').on('click', '.editMenu', function () {
                var menu_id = $(this).data('id');
                $.get("{{ route('menus.index') }}" +'/' + menu_id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Menu");
                    $('#saveBtn').val("edit-menu");
                    $('#ajaxModel').modal('show');
                    $('#menu_id').val(data.id);
                    $('#nama_makanan').val(data.nama_makanan);
                    $('#deskripsi').val(data.deskripsi);
                    $('#harga').val(data.harga);
                    $('#kategori').val(data.kategori);
                    if(data.gambar) {
                        $('.dropify').attr("data-default-file", '/storage/' + data.gambar);
                    }
                    $('.dropify').dropify();
                })
            });
    
            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
    
                var formData = new FormData($('#menuForm')[0]);
    
                $.ajax({
                    data: formData,
                    url: "{{ route('menus.store') }}",
                    type: "POST",
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#menuForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        $('#saveBtn').html('Save Changes');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });
    
            $('body').on('click', '.deleteMenu', function () {
                var menu_id = $(this).data("id");
                confirm("Are You sure want to delete !");
    
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('menus.store') }}"+'/'+menu_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
    
        });
    </script>
    
</body>
</html>
