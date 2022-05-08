@extends('layout.base')

@section('content')

  <head>
    <link href="/css/style.css"rel="stylesheet"type="text/css"/>
  </head>

  <body>
    
    {{-- notif --}}
    <div class="container notif">
      <div class="row">
        <div class="col text-center">
          @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>    
                <strong>{{ $message }}</strong>
            </div>
          @endif

          @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>    
              <strong>{{ $message }}</strong>
            </div>
          @endif

          @if ($message = Session::get('warning'))
            <div class="alert alert-warning alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>    
              <strong>{{ $message }}</strong>
          </div>
          @endif

          @if ($message = Session::get('info'))
            <div class="alert alert-info alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>    
              <strong>{{ $message }}</strong>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Jumbotron --}}
    <div class="jumbotron">
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="isijumbotron m-auto">
              <h1 class="display-4">Pemilihan Bandwidth Internet Rumahan</h1>
              <p class="lead">Proses pemilihan menggunakan matode Algoritma Decision Tree C4.5</p>
              <hr class="my-4">
              @if (session('session_login'))
                @if ($idData == null)
                  <h4>Silahkan Pilih Hasil Pola Decision Tree</h4>
                  <a class="btn btn-primary" data-toggle="modal" data-target="#modalHasilPrediksi" id="testtt">Pilih hasil pola</a>
                @else
                  <h4>Anda Menggunakan Data {{$namaData}}</h4>
                @endif
              @else
                <h4>Scroll Kebawah untuk melakukan prediksi</h4>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    
    {{-- isi --}}

    {{-- JIKA LOGIN --}}
        @if (session('session_login') && $idData != null)
          {{-- TabelData --}}
          <div class="container-fluid tabelData pt-5 pb-5">
            <div class="container">

              <div class="row">
                <div class="col">
                  <h1 class="text-center">Data : {{$namaData}}</h1>
                  <h3 class="text-center">Akurasi : {{$akurasi}}</h3>
                </div>
              </div>
        
              <div class="row">
                <div class="col text-center">
                  {{-- <h1>Semua Dataset</h1> --}}
        
                  {{-- notifikasi form validasi --}}
                  @if ($errors->has('file'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('file') }}</strong>
                  </span>
                  @endif
        
                  {{-- notifikasi sukses --}}
                  @if ($sukses = Session::get('sukses'))
                  <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{{ $sukses }}</strong>
                  </div>
                  @endif
                  
                </div>
              </div>
        
              <div class="row">
                <div class="col">
                  {{-- Table --}}
                  <table class="table table-hover text-center">
                    <thead>
                      <tr>
                        <th colspan="2">Atribut</th>
                        <th scope="col">Jml Kasus</th>
                        <th scope="col">Kurang</th>
                        <th scope="col">Cukup</th>
                        <th scope="col">Lebih</th>
                        {{-- <th scope="col">Entropy</th>
                        <th scope="col">Gain</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      {{-- Total --}}
                      <tr>
                        <th scope="row">Total</th>
                        <td></td>
                        <td>{{$jmlKel}}</td>
                        <td>{{$jml[0]}}</td>
                        <td>{{$jml[1]}}</td>
                        <td>{{$jml[2]}}</td>
                        {{-- <td>{{$totEntropykel}}</td> --}}
                        <td></td>
                      </tr>      
        
                      @for ($i = 0; $i < count($hasil); $i++)
                        <tr>
                          <th scope="row">{{$hasil[$i]['macamAtribut']}}</th>
                          <th colspan="6"></th>
                          {{-- <th>{{$hasil[$i]['gain']}}</th> --}}
                        </tr>                
        
                        @for ($j = 0; $j < count($hasil[$i]['arrayNamaBagianAttribut']); $j++)
                        <tr>
                          <td colspan="2">{{$hasil[$i]['arrayNamaBagianAttribut'][$j]}}</td>
                          <td>{{$hasil[$i]['totalValueAttribute'][$j]}}</td>
                          <td>{{$hasil[$i]['sortingTarget'][$j][0]}}</td>
                          <td>{{$hasil[$i]['sortingTarget'][$j][1]}}</td>
                          <td>{{$hasil[$i]['sortingTarget'][$j][2]}}</td>
                          {{-- <td>{{$hasil[$i]['entropy'][$j]}}</td> --}}
                        </tr>
                        @endfor
                      @endfor
        
                      
                    </tbody>
                  </table>
                </div>
              </div>

            </div>      
          </div>
          
          {{-- treediagram --}}
          <div class="container-fluid treeDiagram pt-5 pb-5 ">
            <div class="row">
              <div class="col-12 text-center judultreediagram">
                <h1 class="text-warning">Hasil Pola Prediksi</h1>
                <p class="font-weight-bold text-white">Berikut adalah pola hasil decision Tree C4.5</p>
              </div>
              <div class="col-12">
                <div class="frametree">
                  <div class="tree">
                    <ul>
                          <li> <a href="#"><span>{{$akar[1]}}</span></a>
                              @if (isset($akar[2]))
                                  <ul>
                                      @for ($i = 0; $i < count($akar[2]); $i++)
                                          <li><a href="#"><span>{{$akar[2][$i]}}</span></a>
                                              @if (isset($akar[3][$i]))
                                                  <ul>
                                                      @for ($j = 0; $j < count($akar[3][$i]); $j++)
                                                          <li><a href="#"><span>{{$akar[3][$i][$j]}}</span></a> 
                                                              @if (isset($akar[4][$i][$j]))
                                                                  <ul>
                                                                      @for ($k = 0; $k < count($akar[4][$i][$j]); $k++)
                                                                          <li><a href="#"><span>{{$akar[4][$i][$j][$k]}}</span></a>
                                                                              @if (isset($akar[5][$i][$j][$k]))
                                                                                  <ul>
                                                                                      @for ($l = 0; $l < count($akar[5][$i][$j][$k]); $l++)
                                                                                          <li><a href="#"><span>{{$akar[5][$i][$j][$k][$l]}}</span></a></li> 
                                                                                      @endfor
                                                                                  </ul>
                                                                              @endif
                                                                          </li> 
                                                                      @endfor
                                                                  </ul>
                                                              @endif
                                                          </li>
                                                      @endfor
                                                  </ul>
                                              @endif
                                          </li>
                                      @endfor
                                  </ul>
                              @endif
                          </li>
                      </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="container mt-5">
              <div class="row">
                <div class="col text-center">
                  <h3 class="text-white mb-3" >Ketentuan membaca alur diagram diatas :</h3>
                </div>
              </div>
              <div class="row">
                <div class="col-3">
                  <p class="text-white" style="font-size: 20px">
                    1.	Bandwidth: <br>
                    <span class="tab text-white">a.	<= 20 	(Kiri)<br>
                    b.	<= 40	(Tengah)<br>
                    c.	> 40	(Kanan)
                  </p>
                </div>
                <div class="col-3">
                  <p class="text-white" style="font-size: 20px">2.	Jumlah Penghuni:<br>
                    <span class="tab text-white">a.	<= 3	(Kiri)<br>
                    b.	<= 5	(Tengah)<br>
                    c.	> 5		(Kanan)
                  </p>
                </div>
                <div class="col-3"> 
                  <p class="text-white" style="font-size: 20px">
                    3.	Total Gadget:<br>
                    <span class="tab text-white">a.	<= 5 	(Kiri)<br>
                    b.	<= 7	(Tengah)<br>
                    c.	7 >		(Kanan)
                  </p>
                </div>
                <div class="col-3">
                  <p class="text-white" style="font-size: 20px">
                    4.	Range Penggunaan:	<br>
                    <span class="tab text-white">Proses:<br>
                      <span class="tab text-white">a.	Pengakumulasian Point:<br>
                        <span class="tab text-white">Ringan = 1<br>
                          Sedang = 2<br>
                          Berat = 3<br>
                            
                           <span class="mintab text-white">b.	Membandingkan hasil akumulasi point<br>
                            <span class="tab text-white">< 10	(Kiri)<br>
                          <= 15 	(Tengah)<br>
                          15 > 	(Kanan)
                  </p>
                </div>
              </div>
            </div>
          </div>

          {{-- top provider --}}
          <div class="container pt-5 pb-5">
            <div class="row">
              <div class="col">
                <h1 class="text-center font-weight-bold" style="text-shadow: 3px 2px white;">Top Provider</h1>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <p class="text-center font-weight-bold" style="text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white;">Ini adalah provider terbaik berdasarkan dataset yang ada, penentuan didapat dari harga satuan bandwidth termurah :</p>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <p class="text-center font-weight-bold" style="text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white;">Bandwidth kurang dari 21 Mbps</p>
              </div>
            </div>
            <div class="row">
              <?php $i = 0; ?>
              @foreach ($best_provider as $item)
                {{-- card provider --}}
                <div class="col-sm-4 p-2 justify-content-center text-center">
                    <a href="http://www.google.com/search?q={{$item['namaprovider']}}+internet" target="_blank" >
                    <div class="card" style="height:15rem;">
                      <div class="row" style="height: 70%">
                        <img src="img/logoprovider/{{$item['namaprovider']}}.png" class="card-img-top m-auto" style=" width: 10rem;" alt="...">
                      </div>
                      <div class="row">
                        <div class="card-body">
                          <h5 class="card-title">{{$item['namaprovider']}}</h5>
                          <div class="row">
                            <div class="col">
                              <p class="card-text">{{$item['bandwidth']}} Mbps</p>
                            </div>
                            <div class="col">
                              <p class="card-text">Rp. {{$item['harga']}},-</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
                <?php $i++; ?>
                @if ($i == 3)
            </div>
            <div class="row mt-3">
              <div class="col">
                <p class="text-center font-weight-bold" style="text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white;">Bandwidth 21 - 40 Mbps</p>
              </div>
            </div>
            <div class="row">
                @endif
                @if ($i == 6)
            </div>
            <div class="row">
              <div class="col mt-3">
                <p class="text-center font-weight-bold" style="text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white;">Bandwidth lebih dari 40 Mbps</p>
              </div>
            </div>
            <div class="row">
                @endif
            @endforeach
            </div>
          </div>

          {{-- prediksi --}}
          <div class="container-fluid hasilPrediksi">
            {{-- hasil prediksi --}}
            @if (isset($hasilPrediksi)) 
              <div class="row pt-5 pb-5">
                <div class="col">
                  <h1 class="text-center font-weight-bold">Rincian hasil Prediksi</h1>
                  <p class="text-center font-weight-bold">Berikut adalah hasil prediksi berdasarkan 3 range bandwidth</p>
                </div>
              </div>
              <div class="row pb-2 pb-3 simpulanRincian">
                <div class="col-4">
                  <h3 class="text-center">20 Mbps Kebawah</h3>
                  @if (substr($hasilPrediksi[0],0,6) == 'Kurang')
                    <h2 class="text-center text-danger font-weight-bold">{{substr($hasilPrediksi[0],0,6)}}</h2>
                  @elseif(substr($hasilPrediksi[0],0,6) == 'Cukup ')
                    <h2 class="text-center text-success font-weight-bold">{{substr($hasilPrediksi[0],0,6)}}</h2>
                  @elseif(substr($hasilPrediksi[0],0,6) == 'Lebih ')
                    <h2 class="text-center text-primary font-weight-bold">{{substr($hasilPrediksi[0],0,6)}}</h2>
                  @endif
                  
                  <h5 class="text-center">Ketepatan : {{substr($hasilPrediksi[0],6)}}</h5>
                </div>
                <div class="col-4">
                  <h3 class="text-center">20 - 40 Mbps</h3>
                  @if (substr($hasilPrediksi[1],0,6) == 'Kurang')
                    <h2 class="text-center text-danger font-weight-bold">{{substr($hasilPrediksi[1],0,6)}}</h2>
                  @elseif(substr($hasilPrediksi[1],0,6) == 'Cukup ')
                    <h2 class="text-center text-success font-weight-bold">{{substr($hasilPrediksi[1],0,6)}}</h2>
                  @elseif(substr($hasilPrediksi[1],0,6) == 'Lebih ')
                    <h2 class="text-center text-primary font-weight-bold">{{substr($hasilPrediksi[1],0,6)}}</h2>
                  @endif
                  <h5 class="text-center">Ketepatan : {{substr($hasilPrediksi[1],6)}}</h5>
                </div>
                <div class="col-4">
                  <h3 class="text-center">40 Mbps Keatas</h3>
                  @if (substr($hasilPrediksi[2],0,6) == 'Kurang')
                    <h2 class="text-center text-danger font-weight-bold">{{substr($hasilPrediksi[2],0,6)}}</h2>
                  @elseif(substr($hasilPrediksi[2],0,6) == 'Cukup ')
                    <h2 class="text-center text-success font-weight-bold">{{substr($hasilPrediksi[2],0,6)}}</h2>
                  @elseif(substr($hasilPrediksi[2],0,6) == 'Lebih ')
                    <h2 class="text-center text-primary font-weight-bold">{{substr($hasilPrediksi[2],0,6)}}</h2>
                  @endif
                  <h5 class="text-center">Ketepatan : {{substr($hasilPrediksi[2],6)}}</h5>
                </div>
              </div>
              
              <hr class="bg-dark">
 
              <div class="row pt-2 pb-3">
                <div class="col">
                  <h1 class="text-center font-weight-bold">Kesimpulan</h1>
                </div>
              </div>

              <div class="row pb-4">
                <div class="col">
                  <h2 class="text-center text-success bg-dark p-2">{{$simpulanprediksi}}</h2>
                </div>
              </div>  

              <div class="container">
                <div class="row">
                  <div class="col">
                    <p class="text-center font-weight-bold">Ini adalah provider terbaik dengan harga satuan bandwidth termurah :</p>
                  </div>
                </div>
                <div class="row text-center pb-5">
                  @foreach ($best_providerrekomendasi as $item)
                    {{-- card provider --}}
                    <div class="col-sm-4 p-2 justify-content-center text-center">
                        <a href="http://www.google.com/search?q={{$item['namaprovider']}}+internet" target="_blank" >
                          <div class="card" style="height:15rem;">
                            <div class="row" style="height: 70%">
                              <img src="img/logoprovider/{{$item['namaprovider']}}.png" class="card-img-top m-auto" style=" width: 10rem;" alt="...">
                            </div>
                            <div class="row">
                              <div class="card-body">
                                <h5 class="card-title">{{$item['namaprovider']}}</h5>
                                <div class="row">
                                  <div class="col">
                                    <p class="card-text">{{$item['bandwidth']}} Mbps</p>
                                  </div>
                                  <div class="col">
                                    <p class="card-text">Rp. {{$item['harga']}},-</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </a>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            {{-- form prediksi --}}
            <div class="row pt-5 pb-5 formPrediksi">
                <div class="col text-center">
                  @if (isset($hasilPrediksi))
                    <h1 class="text-warning">Masukkan Data untuk melakukan prediksi ulang</h1>
                  @else
                    <h1 class="text-warning">Masukkan Data untuk diprediksi</h1>
                  @endif
                    
                    
                    <div class="container mt-3">
                        <form action="{{route('prosesCek')}}" method="GET" name="prediksi" id="form">

                            @csrf
                            
                            <input type="text" name="idData" value="{{$idData}}" hidden>
                  
                            <div class="row">
                              <div class="col text-center">
                                {{-- <h4>Penjelasan singkat</h4> --}}
                                <p>Penggunaan dibagi atas 3 macam yaitu:</p>
                                <p>1. Ringan = Chatting, Browsing, streaming resolusi rendah 360p.</p>
                                <p>2. Sedang = Social Media Streaming , video conference, download dan upload < 10Gb (sedang).</p>
                                <p>3. Berat  = Streaming (Full HD / 4k), download dan upload > 10Gb (berat), Gaming Intens.</p>
                              </div>
                            </div>
                  
                            {{-- Penggunaan --}}
                            <div class="form-row mt-5 " id="formpenghuni">
                              <div class="form-col col-md-2 mb-4">
                                <div class="form-group">
                                  <label for="jumlahPenghuni">Jumlah penghuni yang memiliki Gadget</label>
                                  <input id="jumlahpenghuni" type="number" maxlength="2" name="jumlahPenghuni" class="form-control" placeholder="Jumlah penghuni.." min="0" max="30" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  required>
                                </div>
                              </div>
                  
                              <div class="form-col col-md-4 offset-md-1 mb-4">
                                <div id="penghuni"></div>
                              </div>
                  
                              <div class="form-col col-md-4 offset-md-1 mb-4">
                                <div id="formgadget"></div>
                              </div>

                            </div>
                  
                            <hr class=" bg-white mt-5 mb-5">
                            
                            <div class="text-center mt-3">
                              <button type="submit" class="btn serahkan" style="background-color: #C37A61; border: solid 1px black" >Cek Prediksi</button>
                            </div>
                  
                        </form>
                    </div>
                </div>
            </div>

          </div>
        @endif
      
    {{-- JIKA TIDAK LOGIN --}}
        @if (!session('session_login'))

          <div class="container-fluid penjelasanData">
            <div class="container pt-5 pb-5">
              <div class="row">
                <div class="col text-center isipenjelasanData">
                  <h1>{{$namaData}}</h1>
                  <h3>Update : {{$tanggalData}}</h3>
                  <p>{{$deskripsiData}}</p>
                </div>
            </div>
            </div>
          </div>

          {{-- top provider --}}
          <div class="container pt-5 pb-5">
            <div class="row">
              <div class="col">
                <h1 class="text-center font-weight-bold" style="text-shadow: 3px 2px white;">Top Provider</h1>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <p class="text-center font-weight-bold" style="text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white;">Ini adalah provider terbaik berdasarkan dataset yang ada, penentuan didapat dari harga satuan bandwidth termurah :</p>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <p class="text-center font-weight-bold" style="text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white;">Bandwidth kurang dari 21 Mbps</p>
              </div>
            </div>
            <div class="row">
              <?php $i = 0; ?>
              @foreach ($best_provider as $item)
                {{-- card provider --}}
                <div class="col-sm-4 p-2 justify-content-center text-center">
                    <a href="http://www.google.com/search?q={{$item['namaprovider']}}+internet" target="_blank" >
                    <div class="card" style="height:15rem;">
                      <div class="row" style="height: 70%">
                        <img src="img/logoprovider/{{$item['namaprovider']}}.png" class="card-img-top m-auto" style=" width: 10rem;" alt="...">
                      </div>
                      <div class="row">
                        <div class="card-body">
                          <h5 class="card-title">{{$item['namaprovider']}}</h5>
                          <div class="row">
                            <div class="col">
                              <p class="card-text">{{$item['bandwidth']}} Mbps</p>
                            </div>
                            <div class="col">
                              <p class="card-text">Rp. {{$item['harga']}},-</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
                <?php $i++; ?>
                @if ($i == 3)
            </div>
            <div class="row mt-3">
              <div class="col">
                <p class="text-center font-weight-bold" style="text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white;">Bandwidth 21 - 40 Mbps</p>
              </div>
            </div>
            <div class="row">
                @endif
                @if ($i == 6)
            </div>
            <div class="row">
              <div class="col mt-3">
                <p class="text-center font-weight-bold" style="text-shadow: -1px -1px 0 white, 1px -1px 0 white, -1px 1px 0 white, 1px 1px 0 white;">Bandwidth lebih dari 40 Mbps</p>
              </div>
            </div>
            <div class="row">
                @endif
            @endforeach
            </div>
          </div>

          {{-- prediksi --}}
          <div class="container-fluid hasilPrediksi">
            {{-- hasil prediksi --}}
            @if (isset($hasilPrediksi)) 
              <div class="row pt-5 pb-4">
                <div class="col">
                  <h1 class="text-center font-weight-bold">Hasil Prediksi</h1>
                </div>
              </div>

              <div class="row pb-4">
                <div class="col">
                  <h2 class="text-center text-success bg-dark p-2">{{$simpulanprediksi}}</h2>
                </div>
              </div>  

              <div class="container">
                <div class="row">
                  <div class="col">
                    <p class="text-center font-weight-bold">Ini adalah provider terbaik dengan harga satuan bandwidth termurah :</p>
                  </div>
                </div>
                <div class="row text-center pb-5">
                  @foreach ($best_providerrekomendasi as $item)
                    {{-- card provider --}}
                    <div class="col-sm-4 p-2 justify-content-center text-center">
                        <a href="http://www.google.com/search?q={{$item['namaprovider']}}+internet" target="_blank" >
                          <div class="card" style="height:15rem;">
                            <div class="row" style="height: 70%">
                              <img src="img/logoprovider/{{$item['namaprovider']}}.png" class="card-img-top m-auto" style=" width: 10rem;" alt="...">
                            </div>
                            <div class="row">
                              <div class="card-body">
                                <h5 class="card-title">{{$item['namaprovider']}}</h5>
                                <div class="row">
                                  <div class="col">
                                    <p class="card-text">{{$item['bandwidth']}} Mbps</p>
                                  </div>
                                  <div class="col">
                                    <p class="card-text">Rp. {{$item['harga']}},-</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </a>
                    </div>
                  @endforeach
                </div>
              </div>

            @endif

            {{-- form prediksi --}}
            <div class="row pt-5 pb-5 formPrediksi">
                <div class="col text-center">
                  @if (isset($hasilPrediksi))
                  <h1 class="text-warning">Masukkan Data untuk melakukan prediksi ulang</h1>
                  @else
                    <h1 class="text-warning">Masukkan Data untuk diprediksi</h1>
                  @endif
                    
                    
                    <div class="container mt-3">
                        <form action="{{route('prosesCek')}}" method="GET" name="prediksi" id="form">

                            @csrf
                            
                            <input type="text" name="idData" value="{{$idData}}" hidden>
                  
                            <div class="row">
                              <div class="col text-center">
                                {{-- <h4>Penjelasan singkat</h4> --}}
                                <p>Penggunaan dibagi atas 3 macam yaitu:</p>
                                <p>1. Ringan = Chatting, Browsing, streaming resolusi rendah 360p.</p>
                                <p>2. Sedang = Social Media Streaming , video conference, download dan upload < 10Gb (sedang).</p>
                                <p>3. Berat  = Streaming (Full HD / 4k), download dan upload > 10Gb (berat), Gaming Intens.</p>
                              </div>
                            </div>
                  
                            {{-- Penggunaan --}}
                            <div class="form-row mt-5 " id="formpenghuni">
                              <div class="form-col col-md-2 mb-4">
                                <div class="form-group">
                                  <label for="jumlahPenghuni">Jumlah penghuni yang memiliki Gadget</label>
                                  <input id="jumlahpenghuni" type="number" maxlength="2" name="jumlahPenghuni" class="form-control" placeholder="Jumlah penghuni.." min="0" max="30" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  required>
                                </div>
                              </div>
                  
                              <div class="form-col col-md-3 offset-md-1 mb-4">
                                <div id="penghuni"></div>
                              </div>
                  
                              <div class="form-col col-md-5 offset-md-1 mb-4">
                                <div id="formgadget"></div>
                              </div>

                            </div>
                  
                            <hr class=" bg-white mt-5 mb-5">
                            
                            <div class="text-center mt-3">
                              <button type="submit" class="btn serahkan" style="background-color: #C37A61; border: solid 1px black" >Cek Prediksi</button>
                            </div>
                  
                        </form>
                    </div>
                </div>
            </div>

          </div>
        @endif

    {{-- footer --}}
    <div class="container-fluid">
      <div class="row footer">
          <div class="pt-2 col-12 text-center">
            <p>Copyright &copy; 2020 - 2022 Ammaridho</p>
        </div>
      </div>
    </div>

  </body>
    
  <script>
    $('#jumlahpenghuni').on("keyup change",function() {
      var jumlahpenghuni = $('#jumlahpenghuni').val();
    
      $.get("{{route('penghuni')}}",{jumlahpenghuni:jumlahpenghuni}, function(data) {
        $("#penghuni").html(data);
      });
    });

    var recaptchaCallback = function () {
      alert('bisaa');
    }

    // $('.serahkan').on('click',function(){
    //   alert('bisa');

    //   var dataString = $('#form').serialize();

    //   $.ajax({
    //     type: "POST",
    //     url: "{{route('prosesCek')}}",
    //     data: dataString,
    //     success: function() {
    //       console.log('bisa');
    //       $('.hasilPrediksi').html();
    //     },
    //   });
    //     // console.error('error bos');

    //   return false;

    // });

  </script>


@endsection