@extends('layout.base')

@section('content')
    
    
        <head>
            <link href="css/style.css"rel="stylesheet"type="text/css"/>
        </head>
        <body>
            <div class="container-fluid">
                <div class="row">
                    <div class="tree">
                        <ul>
                            <li> <a href="#"><img src="images/1.jpg"><span>Child</span></a>
                                <ul>
                                    <li><a href="#"><img src="images/2.jpg"><span>Grand Child</span></a>
                                        <ul>
                                            <li> <a href="#"><img src="images/3.jpg"><span>Great Grand Child</span></a> </li>
                                            <li> <a href="#"><img src="images/4.jpg"><span>Great Grand Child</span></a> </li>
                                        </ul>
                                    </li>
                                    <li> <a href="#"><img src="images/5.jpg"><span>Grand Child</span></a>
                                        <ul>
                                            <li> <a href="#"><img src="images/6.jpg"><span>Great Grand Child</span></a> </li>
                                            <li> <a href="#"><img src="images/7.jpg"><span>Great Grand Child</span></a> </li>
                                            <li> <a href="#"><img src="images/8.jpg"><span>Great Grand Child</span></a> </li>
                                        </ul>
                                    </li>
                                    <li> <a href="#"><img src="images/5.jpg"><span>Grand Child</span></a>
                                        <ul>
                                            <li> <a href="#"><img src="images/6.jpg"><span>Great Grand Child</span></a> </li>
                                            <li> <a href="#"><img src="images/7.jpg"><span>Great Grand Child</span></a> </li>
                                            <li> <a href="#"><img src="images/8.jpg"><span>Great Grand Child</span></a> </li>
                                        </ul>
                                    </li>
                                    <li><a href="#"><img src="images/9.jpg"><span>Grand Child</span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-center">
                        <h1>Masukkan Data Prediksi</h1>
                        
                        <div class="container mt-3">
                            <form action="{{route('prosesCek')}}" method="POST">
    
                                @csrf
                      
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
                      
                                <hr class="shadow-lg bg-white mt-5 mb-5">
                      
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
                      
                                <hr class="shadow-lg bg-white mb-5">
                      
                                
                                <div class="form-group text-center">
                                  <div class="row">
                                    <div class="col">
                                      <label for="kesimpulan">Kesimpulan pemakaian untuk kebutuhan:</label>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col">
                                      <div id="kesimpulan" class="btn-group btn-group-toggle" data-toggle="buttons" required>
                                        <label class="btn border border-dark btn-warning" style="background-color: #e0606d">
                                          <input type="radio" name="kesimpulan" id="kesimpulan" value="kurang" required> Kurang
                                        </label>
                                        <label class="btn border border-dark btn-warning" style="background-color: #64af75">
                                          <input type="radio" name="kesimpulan" id="kesimpulan" value="cukup" required> Cukup
                                        </label>
                                        <label class="btn border border-dark btn-warning" style="background-color: #62acb8">
                                          <input type="radio" name="kesimpulan" id="kesimpulan" value="Lebih" required> Lebih
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                
                                <hr class="shadow-lg bg-white mt-5 mb-5">
                      
                                <div class="form-row mt-5">
                                  <div class="col">
                                    {{-- <center>
                                      {!! NoCaptcha::renderJs('fr', false, 'recaptchaCallback') !!}
                                    {!! NoCaptcha::display() !!}
                                    </center> --}}
                                    
                                     </div>
                                </div>
                                
                                <div class="text-center mt-3">
                                  <button type="submit" class="btn serahkan" >Serahkan</button>
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
        
            
          </script>

@endsection