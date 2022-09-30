<style>
    #btnUpdate, #btnCancel, #form{
        display: none;
    }
</style>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Transaksi</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Transaksi > list</li>
            </ol>
            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ url('transaksi') }}" class="btn btn-success"><i class="fa fa-plus"></i> Create new Transaksi</a>
                </div>
            </div>
            {{--  <div class="row mb-3" id="form">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" id="header-form">
                            <i class="fas fa-plus"></i>
                            Insert new Item
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Nama Item</label>
                                    <input type="text" name="nama_item" class="form-control" id="nama_item">
                                    <small class="text-danger notif" id="err_nama_item"></small>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Kategori Item</label>
                                    <select name="id_kategori" id="id_kategori" class="form-control">
                                        <option value="">-- Choose Kategori --</option>
                                        @foreach ($kategori as $v)
                                            <option value="{{ $v->id_kategori }}">{{ $v->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger notif" id="err_id_kategori"></small>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Harga</label>
                                    <input type="text" name="harga_item" class="form-control" id="harga_item">
                                    <small class="text-danger notif" id="err_harga_item"></small>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Stok</label>
                                    <input type="text" name="stok_item" class="form-control" id="stok_item">
                                    <small class="text-danger notif" id="err_stok_item"></small>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Expired Item</label>
                                    <input type="date" name="expired_item" class="form-control" id="expired_item">
                                    <small class="text-danger notif" id="err_expired_item"></small>
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <button type="button" class="btn btn-block btn-primary" id="btnSave" onclick="insertItem()"><i class="fas fa-save"></i> Save</button>
                                    <button type="button" class="btn btn-block btn-warning" id="btnUpdate" onclick="updateItem()"><i class="fas fa-save"></i> Update</button>
                                    <button type="button" class="btn btn-block btn-danger" id="btnCancel" onclick="cancel()"><i class="fas fa-times"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Item List
                </div>
                <div class="card-body">
                    <table id="tableData">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Transaksi</th>
                                <th>Tgl Transaksi</th>
                                <th>Admin</th>
                                <th>Total Transaksi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
<script>
    $(document).ready(function(){
        loadData();
    })
        
    var d = new Date();
    var strDate = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
    function loadData(){
        $('#tableData').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Data Transaksi - '+strDate,
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                    }
                }
            ],
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: '{{ url("transaksi/load") }}'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { name: 'nomor_transaksi', data: 'nomor_transaksi'},
                { name: 'tgl_transaksi', data: 'tgl_transaksi'},
                { name: 'username', data : 'username'},
                { name: 'total' ,data: 'total'},
                { name: 'action' , data: 'action'}
            ],
            lengthMenu: [10,50,-1],
            order: [[0, 'desc']],
        });
    }

    {{--  function cancel(){
        $("#btnSave").show();
        $("#btnUpdate").hide();
        $("#btnCancel").hide();
        $("#nama_item").val("");
        $("#id_kategori").val("").trigger('change');
        $("#stok_item").val("");
        $("#expired_item").val("");
        $("#header-form").html('<i class="fas fa-plus"></i> Insert new Item');
    }

    function create(){
        $("#form").show();
        $("#header-form").html('<i class="fas fa-plus"></i> Insert new Item');
    }

    function editItem(id){
        $("#form").show();
        $("#btnSave").hide();
        $("#btnUpdate").show();
        $("#btnCancel").show();
        $("#header-form").html('<i class="fas fa-edit"></i> Update Item');
        $.ajax({
            url : "{{ url('item/edit') }}/"+id,
            method : 'GET',
            dataType : 'JSON',
            success:function(res){
                $("#nama_item").val(res.nama_item);
                $("#id_kategori").val(res.id_kategori).trigger('change');
                $("#stok_item").val(res.stok_item);
                $("#harga_item").val(res.harga_item);
                $("#expired_item").val(res.expired_item);
                $("#btnUpdate").attr('onclick','updateItem('+res.id_item+')');
            }
        })
    }

    function updateItem(id){
        $.ajax({
            url : "{{ url('item/update') }}/"+id,
            headers : {
                'X-CSRF-TOKEN' : $('meta[name=csrf-token]').attr('content')
            },
            data : {
                'nama_item' : $("#nama_item").val(),
                'id_kategori' : $("#id_kategori").val(),
                'stok_item' : $("#stok_item").val(),
                'harga_item' : $("#harga_item").val(),
                'expired_item' :  $("#expired_item").val()
            },
            method : 'POST',
            dataType : 'JSON',
            success:function(res){
                if(res.status == 200){
                    cancel();
                    $('#tableData').DataTable().ajax.reload(null, false);
                }else if(res.status == 400){
                    $.each(res.errors, function (i, val) {
                        $('#err_'+i).text(val);
                    });
                }else{
                    alert(res.message)
                }
            }
        })
    }

    function insertItem(){
        $.ajax({
            url : "{{ url('item/insert') }}",
            headers : {
                'X-CSRF-TOKEN' : $('meta[name=csrf-token]').attr('content')
            },
            data : {
                'nama_item' : $("#nama_item").val(),
                'id_kategori' : $("#id_kategori").val(),
                'stok_item' : $("#stok_item").val(),
                'harga_item' : $("#harga_item").val(),
                'expired_item' : $("#expired_item").val()
            },
            method : 'POST',
            dataType : 'JSON',
            success:function(res){
                if(res.status == 200){
                    $('#tableData').DataTable().ajax.reload(null, false);
                }else if(res.status == 400){
                    $.each(res.errors, function (i, val) {
                        $('#err_'+i).text(val);
                    });
                }else{
                    alert(res.message)
                }
            }
        })
    }

    function deleteItem(id){
        $.ajax({
            url : "{{ url('item/delete') }}/"+id,
            method : 'GET',
            dataType : 'JSON',
            success:function(res){
                if(res.status == 200){
                    $('#tableData').DataTable().ajax.reload(null, false);
                }else{
                    alert(res.alert);
                }
            }
        })
    }  --}}
    function deleteTransaksi(id){
        $.ajax({
            url : "{{ url('transaksi/delete') }}/"+id,
            method : 'GET',
            dataType : 'JSON',
            success:function(res){
                if(res.status == 200){
                    $('#tableData').DataTable().ajax.reload(null, false);
                }else{
                    alert(res.alert);
                }
            }
        })
    }
</script>
