<style>
    #btnUpdate, #btnCancel, #form{
        display: none;
    }
</style>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Edit Transaksi</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"> Transaksi > Edit</li>
            </ol>
            <div class="row">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> {{ Session::get('success')}}
                        {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button> --}}
                    </div>
                    <br>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> {{ Session::get('error')}}
                        {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button> --}}
                    </div>
                @endif
            </div>
            <div class="row mb-3" id="form">

            </div>
            <div class="row">
                <div class="col-md-4">
                    <h5>Detail Transaksi</h5>
                    <table class="table">
                        <tr>
                            <td>No. Transaksi</td>
                            <td>:</td>
                            <td>{{ $transaksi->nomor_transaksi }}</td>
                        </tr>
                        <tr>
                            <td>Admin</td>
                            <td>:</td>
                            <td>{{ $transaksi->user->username }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Transaksi</td>
                            <td>:</td>
                            <td>{{ date('d/m/Y H:i', strtotime($transaksi->tgl_transaksi)) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-8">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ url('transaksi/list') }}" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
            <form action="{{ url('transaksi/update/'.$transaksi->id_transaksi) }}" method="post">
                @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header card-item">
                            <i class="fas fa-inventory"></i>
                            Item
                        </div>
                        <div class="card-body overflow-auto">
                            @foreach ($item as $a)
                                <ul class="list-group mb-3">
                                    <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="addItem({{ $a->id_item }})">
                                        <div class="flex-column">
                                            {{ $a->nama_item }}
                                            <p><small>{{ $a->kategori->nama_kategori }}</small></p>
                                            <span class="badge badge-info badge-pill text-primary"> {{ $a->stok_item }}</span>
                                            <span class="badge badge-danger badge-pill text-primary">Rp {{ number_format($a->harga_item,0,'','.') }}</span>
                                            <span class="badge badge-warning badge-pill 
                                            @if ($a->expired_item < date('Y-m-d'))
                                                {{ 'text-danger'}}
                                            @else
                                                {{ 'text-success'}}
                                            @endif
                                            "> Exp. {{ date('d-m-Y',strtotime($a->expired_item)) }}</span>
                                        </div>
                                    <div class="image-parent">
                                        <img src="{{ asset('assets/assets/img/toast-bread-icon-design-free-vector.jpg') }}" class="img-fluid" style="width: 100px" alt="quixote">
                                    </div>
                                    </a>
                                </ul>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Detail Transaksi
                        </div>
                        <div class="card-body">
                            <table id="tableData" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Item</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($transaksi_detail) > 0)
                                        @foreach ($transaksi_detail as $td)
                                            <tr>
                                                <td>{{ $td->item->nama_item }}<input type='hidden' name='id_item[]' value='{{ $td->item->id_item }}' class='id'></td>
                                                <td>{{ $td->item->kategori->nama_kategori }}<input type='hidden' name='id_kategori[]' value='{{ $td->item->kategori->id_kategori }}'></td>
                                                <td><input type='hidden' name='harga_item[]' id='harga{{ $td->item->id_item }}' value='{{ $td->item->harga_item }}' class='harga'><span id='harga_sep{{ $td->item->id_item }}' class='harga_sep{{ $td->item->id_item }}'>{{ number_format($td->item->harga_item,0,'','.') }}</span></td>
                                                <td><input type='number' name='qty[]' id='qty{{ $td->item->id_item }}' class='form-control form-control-sm qty' value='{{ $td->qty }}' onkeyup='change_quantity()'></td>
                                                <td><input type='hidden' name='subtotal[]' id='subtotal{{ $td->item->id_item }}' value="{{ $td->subtotal }}" class='form-control form-control-sm subtotal' readonly><span class='subtotal_sep{{ $td->item->id_item }}'>{{ number_format($td->subtotal,0,'','.') }}</span></td>
                                                <td><a href='javascript:void(0)' class='btn btn-danger deleteBtn'><i class='fas fa-trash'></i></a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <h3>TOTAL : Rp <span id="total">{{ number_format($transaksi->total,0,'','.') }}</span> <input type="hidden" id="totalVal" name="total"></h3>
                </div>
                <div class="col-md-4">
                    <div class="d-grid gap-2">
                        <input type="submit" class="btn btn-primary btn-block" value="Submit Transaksi">
                    </div>
                </div>
            </div>
            </form>
        </div>
    </main>
<script>
    function total(){
        var sum = 0;
        $(".subtotal").each(function() {
            var value = $(this).val();

            if(!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
            }
        });
        var tt = numberFormat(sum);
        $("#total").html(tt);
        $("#totalVal").val(sum);
    }

    function addItem(id){
        $.ajax({
            url : "{{ url('item/get') }}/"+id,
            method : "GET",
            dataType : "JSON",
            success:function(res){
                var qtyItem = $("#qty"+res.id_item);
                var subtotal = res.harga_item * 1;
                var harga = $("#harga"+res.id_item).val();
                if ($("#tableData tr td input.id[value='"+res.id_item+"']").length == 0 && qtyItem.length == 0){
                        $("#tableData").append(
                            "<tr>"+
                                "<td>"+res.nama_item+"<input type='hidden' name='id_item[]' value='"+res.id_item+"' class='id'></td>"+
                                "<td>"+res.kategori.nama_kategori+"<input type='hidden' name='id_kategori[]' value='"+res.id_kategori+"'></td>"+
                                "<td><input type='hidden' name='harga_item[]' id='harga"+res.id_item+"' value='"+res.harga_item+"' class='harga'><span class='harga_sep"+res.id_item+"'></span></td>"+
                                "<td><input type='number' name='qty[]' id='qty"+res.id_item+"' class='form-control form-control-sm qty' value='1' onkeyup='change_quantity()'></td>"+
                                "<td><input type='hidden' name='subtotal[]' id='subtotal"+res.id_item+"' value="+subtotal+" class='form-control form-control-sm subtotal' readonly><span class='subtotal_sep"+res.id_item+"'></span></td>"+
                                "<td><a href='javascript:void(0)' class='btn btn-danger deleteBtn'><i class='fas fa-trash'></i></a></td>"+
                            "<tr>"
                        );
                        $(".harga_sep"+res.id_item).html(numberFormat(res.harga_item))
                        $(".subtotal_sep"+res.id_item).html(numberFormat(subtotal))
                        total();
                    }else{
                        var currentVal = parseInt(qtyItem.val());
                        if(!isNaN(currentVal) && qtyItem.length == 1){
                            $("#qty"+res.id_item).val(parseInt(parseInt($("#qty"+res.id_item).val()) + 1));
                        }
                        $("#subtotal"+res.id_item).val(qtyItem.val()*harga);
                        $(".harga_sep"+res.id_item).html(numberFormat(res.harga_item))
                        $(".subtotal_sep"+res.id_item).html(numberFormat(qtyItem.val()*harga))
                        total();
                    }
            }
        })
    }

    $("#tableData").on("click", ".deleteBtn", function() {
        $(this).closest("tr").remove();
        total();
    });

    function change_quantity(){
        var sum = 0;
        $('#tableData > tr').each(function() {
            var id = $(this).find('.id').val();
            var qty = $(this).find('.quantity').val();
            var price = $(this).find('.harga').val();
            var amount = (qty*price)
            sum+=amount;
            $(this).find('.harga_sep'+id).html('Rp '+numberFormat(price));
            $(this).find('.subtotal').val(amount);
            $(this).find('.subtotal_sep'+id).html('Rp '+numberFormat(amount));
            total();
        });
    }

    function numberFormat(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>
