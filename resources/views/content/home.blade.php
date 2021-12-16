@extends('layout.base')

@section('content')

  <head>
    <link href="css/style.css"rel="stylesheet"type="text/css"/>
  </head>

  <body>
    <div class="container mt-5">

      <div class="row">
        <div class="col">
          <h1>Data : {{$namaData}}</h1>
        </div>
      </div>

      <div class="row">
        <div class="col text-center">
          <h1>Semua Dataset</h1>

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

      <div class="row mt-5">
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
                <th scope="col">Entropy</th>
                <th scope="col">Gain</th>
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
                <td>{{$totEntropykel}}</td>
                <td></td>
              </tr>      

              @for ($i = 0; $i < count($hasil); $i++)
                <tr>
                  <th scope="row">{{$hasil[$i]['macamAtribut']}}</th>
                  <th colspan="6"></th>
                  <th>{{$hasil[$i]['gain']}}</th>
                </tr>                

                @for ($j = 0; $j < count($hasil[$i]['arrayNamaBagianAttribut']); $j++)
                <tr>
                  <td colspan="2">{{$hasil[$i]['arrayNamaBagianAttribut'][$j]}}</td>
                  <td>{{$hasil[$i]['totalValueAttribute'][$j]}}</td>
                  <td>{{$hasil[$i]['sortingTarget'][$j][0]}}</td>
                  <td>{{$hasil[$i]['sortingTarget'][$j][1]}}</td>
                  <td>{{$hasil[$i]['sortingTarget'][$j][2]}}</td>
                  <td>{{$hasil[$i]['entropy'][$j]}}</td>
                </tr>
                @endfor
              @endfor

              
            </tbody>
          </table>
        </div>
      </div>
        

    </div>
    
    <div class="container-fluid">
      <div class="row pt-5" style="background-color: #b7b7a4">
        <div class="col-12 text-center">
          <h1>Tree Hasil C4.5</h1>
        </div>
        <div class="col-12">
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
      <div class="row">
          <div class="col">
              <div class="hasilPrediksi"></div>
          </div>
      </div>
      <div class="row mt-3 pt-5 pb-5 mb-3" style="background-color: #ffe8d6">
          <div class="col text-center">
              <h1>Masukkan Data untuk diprediksi</h1>
              
              <div class="container mt-3">
                  <form action="{{route('prosesCek')}}" method="POST" name="prediksi" id="form">

                      @csrf
                      
                      <input type="text" name="idData" value="{{$idData}}" hidden>
            
                      <div class="row">
                        <div class="col text-center">
                          <h4>Penjelasan singkat</h4>
                          <p>Provider = Nama penyedia layanan internet.</p>
                          <p>Bandwidth = Kecepatan internet (mbps).</p>
                        </div>
                      </div>
            
                      {{-- Provider --}}
                      <div class="form-row">
                        <div class="form-group col-4">
                          <label for="inputState">Provider</label>
                          <select id="inputState" class="form-control" name="provider" required>
                            <option selected>Provider anda saat ini..</option>
                            <option value="My Republic">My Republic</option>
                            <option value="Indihome">Indihome</option>
                            <option value="Groovy">Groovy</option>
                            <option value="biznet">Biznet</option>
                            <option value="CBN Fiber">CBN Fiber</option>
                            <option value="First Media">First Media</option>
                            <option value="Oxygen.id">Oxygen.id</option>
                            <option value="XL Home">XL Home</option>
                            <option value="indosat GIG">Indosat GIG</option>
                            <option value="Orbit Telkomsel">Orbit Telkomsel</option>
                            <option value="MNC PLay">MNC PLay</option>
                            <option value="Transvision">Transvision</option>
                            <option value="Megavision">Megavision</option>
                          </select>
                        </div>
                        <div class="form-group col-4">
                          <label for="bandwidth">Bandwidth</label>
                          <input type="number" class="form-control" id="bandwidth" name="bandwidth" placeholder="cth. 50" min="0" max="999" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                        </div>
                        <div class="form-group col-4">
                          <label for="biayaBulanan">Biaya Bulanan</label>
                          <input type="number" class="form-control" id="biayaBulanan" name="biayaBulanan" placeholder="cth. 400000" min="0" max="9999999" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                        </div>
                      </div>
            
                      <hr class=" bg-dark mt-5 mb-5">
            
                      <div class="row">
                        <div class="col text-center">
                          <h4>Penjelasan singkat</h4>
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
                            <label for="jumlahPenghuni">Jumlah penghuni</label>
                            <input id="jumlahpenghuni" type="number" name="jumlahPenghuni" class="form-control" placeholder="Jumlah penghuni.." min="0" max="50" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                          </div>
                        </div>
            
                        <div class="form-col col-md-4 offset-md-1 mb-4">
                          <div id="penghuni"></div>
                        </div>
            
                        <div class="form-col col-md-4 offset-md-1 mb-4">
                          <div id="formgadget"></div>
                        </div>
                      </div>
            
                      
                      <hr class=" bg-dark mt-5 mb-5">
            
                      <div class="form-row mt-5">
                        <div class="col">
                          {{-- <center>
                            {!! NoCaptcha::renderJs('fr', false, 'recaptchaCallback') !!}
                          {!! NoCaptcha::display() !!}
                          </center> --}}
                          
                            </div>
                      </div>
                      
                      <div class="text-center mt-3">
                        <button type="submit" class="btn serahkan" style="background-color: #cb997e; border: solid 1px black" >Cek Prediksi</button>
                      </div>
            
                  </form>
              </div>
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