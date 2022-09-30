<style>
    #btnUpdate, #btnCancel, #form{
        display: none;
    }
</style>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">User</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">User</li>
            </ol>
            <div class="row mb-3">
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
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Item List
                </div>
                <div class="card-body">
                    <table id="tableData">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>No. HP</th>
                                <th>Status</th>
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
                    title: 'Data User - '+strDate,
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                }
            ],
            destroy: true,
            ajax: {
                url: '{{ url("user/load") }}'
            },
            columns: [
                // { name: 'no', searchable: false, className: 'text-center' },
                { name: 'nama' },
                { name: 'no_hp'},
                { name: 'status'},
                { name: 'action' }
            ],
            lengthMenu: [10,50,-1],
            order: [[2, 'desc']],
        });
    }

    function cancel(){
        $("#btnSave").show();
        $("#btnUpdate").hide();
        $("#btnCancel").hide();
        $("#username").val("");
        $("#status").hide();
        $("#role").val("").trigger('change');
        $("#no_hp").val("");
        $("#header-form").html('<i class="fas fa-plus"></i> Insert new User');
    }

    function create(){
        $("#form").show();
        $("#status").hide();
        $("#header-form").html('<i class="fas fa-plus"></i> Insert new User');
    }

    function editUser(id){
        $("#form").show();
        $("#btnSave").hide();
        $("#btnUpdate").show();
        $("#btnCancel").show();
        $("#status").show();
        $("#header-form").html('<i class="fas fa-edit"></i> Update User');
        $.ajax({
            url : "{{ url('user/edit') }}/"+id,
            method : 'GET',
            dataType : 'JSON',
            success:function(res){
                $("#username").val(res.username);
                $("#role").val(res.role).trigger('change');
                $("#no_hp").val(res.no_hp);
                $("#status").val(res.status);
                $("#btnUpdate").attr('onclick','updateUser('+res.id_user+')');
            }
        })
    }

    function updateUser(id){
        $.ajax({
            url : "{{ url('user/update') }}/"+id,
            headers : {
                'X-CSRF-TOKEN' : $('meta[name=csrf-token]').attr('content')
            },
            data : {
                'username' : $("#username").val(),
                'role' : $("#role").val(),
                'no_hp' : $("#no_hp").val(),
                'status' : $("#status").val()
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

    function insertUser(){
        $.ajax({
            url : "{{ url('user/insert') }}",
            headers : {
                'X-CSRF-TOKEN' : $('meta[name=csrf-token]').attr('content')
            },
            data : {
                'username' : $("#username").val(),
                'role' : $("#role").val(),
                'no_hp' : $("#no_hp").val()
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

    function deleteUser(id){
        $.ajax({
            url : "{{ url('user/delete') }}/"+id,
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

    function changeStatus(status, id){
        $.ajax({
            url : "{{ url('user/status') }}/"+status+"/"+id,
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
