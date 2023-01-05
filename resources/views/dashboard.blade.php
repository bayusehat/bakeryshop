<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">Transaksi</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 pl-3">
                                    <h3 class="text-left">{{ $transaksi }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">Item Card</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 pl-3">
                                    <h3 class="text-left">{{ $item }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body"> Become Expired Item</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 pl-3">
                                    <h3 class="text-left">{{ $become_expired }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">Expired Item</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 pl-3">
                                    <h3 class="text-left">{{ $expired }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           <div class="row">
                <div class="col-md-12 col-sm-12 col-xl-12">
                    <canvas id="myChart"></canvas>
                </div>
           </div>
        </div>
    </main>
<script>
    var labels =  {!! json_encode($labels) !!};
    var users =  {{ json_encode($data) }};
  
    const data = {
        labels: labels,
        datasets: [{
            label: 'Transaksi Per-Bulan',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: users,
        }]
    };
  
    const config = {
        type: 'line',
        data: data,
        options: {}
    };
  
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
</script>