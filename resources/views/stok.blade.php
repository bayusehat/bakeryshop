<style>
    #btnUpdate, #btnCancel, #form{
        display: none;
    }
</style>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">History Stok</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Stok</li>
            </ol>
            {{-- <div class="row mb-3">
                <div class="col-md-12">
                    <a href="javascript:void(0)" onclick="create();" class="btn btn-success"><i class="fa fa-plus"></i> Create new User</a>
                </div>
            </div>
            <div class="row mb-3" id="form">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" id="header-form">
                            <i class="fas fa-plus"></i>
                            Insert new User
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Username</label>
                                    <input type="text" name="username" class="form-control" id="username">
                                    <small class="text-danger notif" id="err_username"></small>
                                </div>
                                <div class="col-md-3">
                                    <label for="">No. HP</label>
                                    <input type="text" name="no_hp" class="form-control" id="no_hp">
                                    <small class="text-danger notif" id="err_no_hp"></small>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Role</label>
                                     <select name="role" id="role" class="form-control">
                                        <option value="">-- Choose Role --</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Karyawan</option>
                                    </select>
                                    <small class="text-danger notif" id="err_role"></small>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Status</label>
                                    <input type="text" name="status" class="form-control" id="status">
                                    <small class="text-danger notif" id="err_status"></small>
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <button type="button" class="btn btn-block btn-primary" id="btnSave" onclick="insertUser()"><i class="fas fa-save"></i> Save</button>
                                    <button type="button" class="btn btn-block btn-warning" id="btnUpdate" onclick="updateUser()"><i class="fas fa-save"></i> Update</button>
                                    <button type="button" class="btn btn-block btn-danger" id="btnCancel" onclick="cancel()"><i class="fas fa-times"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    History Stok
                </div>
                <div class="card-body">
                    <table id="tableData">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Item</th>
                                <th>Kategori</th>
                                <th>Mod Stok</th>
                                <th>Action</th>
                                <th>ID Action</th>
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
                    title: 'Data History - '+strDate,
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                }
            ],
            destroy: true,
            ajax: {
                url: '{{ url("history/load") }}'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { name: 'nama_item', data : 'nama_item'},
                { name: 'nama_kategori', data : 'nama_kategori'},
                { name: 'mod_stok', data : 'mod_stok'},
                { name: 'action', data : 'action'},
                { name: 'id_action', data : 'id_action'}
            ],
            lengthMenu: [10,50,-1],
            order: [[0, 'desc']],
        });
    }
</script>
