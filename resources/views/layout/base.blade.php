<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <script src="js/jquery-3.6.0.min.js"></script>

    <title>Skripsi Decision Tree C4.5</title>
  </head>
  <body>
    
    {{-- navigasi --}}
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <a class="skripsic45 navbar-brand font-weight-bold">Skripsi C4.5</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav">
            @if (session('session_login'))
              <li class="nav-item">
                <a class="btn nav-link" id="buttonBuatPolaPrediksi" data-toggle="modal" data-target="#modalHasilPrediksi" id="testtt">List Hasil Pola Prediksi</a>
              </li>
              <li class="nav-item">
                <a class="btn nav-link"  data-toggle="modal" data-target="#importExcel">Buat Pola Prediksi</a>
              </li>
              @if (session('data')['username'] == 'adminUtama')
                <li class="nav-item">
                  <a class="btn nav-link" data-toggle="modal" data-target="#modalListAkun">List Akun</a>
                </li>
              @endif
            @endif
            <li class="nav-item">
              <a class="btn nav-link" data-toggle="modal" data-target="#tentangWebsite">Tentang Website</a>
            </li>
          </ul>
            <div class="navbar-nav ml-auto">
              <li class="nav-item">
                @if (!session('session_login'))
                  <a class="btn nav-link" id="buttonLogin" data-toggle="modal" data-target="#formlogin">Login</a>              
                @else
                <div class="dropdown nav-item">
                  <a id="buttonKeranjang" class="btn dropdown-toggle" href="#" role="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{(session('data')['username'])}}
                  </a>
                
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="btn tombol" onclick="signout()">Keluar</a>
                  </div>
                </div>
                @endif
              </li>
            </div>
        </div>
    </nav>

    {{-- formlogin --}}
    <div class="modal fade" id="formlogin" aria-labelledby="formlogin" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-sm" role="document">

				<form method="post" action="/signin" enctype="multipart/form-data">
					<div class="modal-content text-center">
						<div class="modal-header">
							<h5 class="modal-title text-center" id="formlogin">Masuk</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}
              <label for="username">Username</label>
              <div class="form-group">
                <input type="text" name="username" id="username" required>
              </div>

              <label for="password">Password</label>
              <div class="form-group">
                <input type="password" name="password" id="password" required>
              </div>

              <p>Ingin memprediksi dengan data set sendiri? <a class="btn" id="closeFormLogin" data-toggle="modal" data-target="#signup">Buat Akun</a></p>

						</div>
            <div class="bawah text-center">
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Masuk</button>
              </div>
						</div>
					</div>
				</form>
        
			</div>
		</div>

    {{-- signUp --}}
    <div class="modal fade" id="signup" aria-labelledby="signup" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-sm" role="document">

				<form method="post" action="{{route('signup')}}" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-center" id="signup">Daftar</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}
              <label for="username">Username</label>
              <div class="form-group">
                <input type="text" name="username" id="username" required>
              </div>

              <label for="password">Password</label>
              <div class="form-group">
                <input type="password" name="password" id="password" required>
              </div>

              <label for="password_confirmation">confirm Password</label>
              <div class="form-group">
                <input type="password" name="password_confirmation" id="password_confirmation" required>
              </div>

              <span id="message"></span>
              
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
							<button type="submit" class="btn" id="buttonsubmitsignup" style="background-color: grey" disabled>Submit</button>
						</div>
					</div>
				</form>
        
			</div>
		</div>

    @if (session('session_login'))
      <!-- Import Excel -->
      <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">

          <form method="post" action="/importExcel" enctype="multipart/form-data">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Membuat Pola Prediksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">

                <div class="container-fluid">

                  <div class="row">
                    <div class="col">
                      {{ csrf_field() }}

                      <label for="">Penjelasan :</label>
                      <p style="text-align:justify">Proses pembuatan pola dilakukan berdasarkan data data pengguna internet terdahulu yang nantinya akan dimasukkan ke dalam machine learning algoritma C4.5.</p>


                      <label for="namaData">Nama data :</label>
                      <div class="form-group">
                        <input type="text" name="namaData" id="namaData" required>
                      </div>

                      <label for="deskripsiData">Deskripsi :</label>
                      <div class="form-group">
                        <textarea name="deskripsiData" id="deskripsiData" cols="65%" rows="10"></textarea>
                      </div>
                    </div>
                    <div class="col">
                      <label for="">Ketentuan Excel :</label>
                      <div class="ketentuan" style="text-align:justify">
                        <p>1. Satu data mewakili satu keluarga, yang didalamnya : <br>
                          <span class="tab">- Banyak Penghuni.<br>
                          - Banyak Gadget setiap penghuni.<br>
                          - Range Penggunaan setiap gadget.<br>
                          - Bandwidth Yang digunakan.<br>
                          - Simpulan Pemakaian.<br>
                          - dll sesuai contoh<br>
                          <span class="mintab">2. Excel terdiri dari 3 sheet sesuai contoh.<br>
                        3. Masing masing sheet harus berelasi id sesuai contoh.<br>
                        4. Title setiap kolom harus ada dan penulisan sesuai contoh.<br>
                        5. Maksimal jumlah data keluarga adalah 5000 data (dapat <span class="tab">lebih namun waktu proses akan lebih lama, 5000 data memakan waktu 2 jam dalam proses).</p>
                      </div>

                      <a href="{{route('downloadcontoh')}}">download contoh excel</a>

                      <br><br>
                      
                      <label for="fileExcel">Pilih file excel</label>
                      <div class="form-group">
                        <input type="file" name="file" id="fileExcel" required="required">
                      </div>
                    </div>
                  </div>
                </div>

                

                

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Import</button>
              </div>
            </div>
          </form>
          
        </div>
      </div>

      <!-- modalHasilPrediksi -->
      <div class="modal fade" id="modalHasilPrediksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">List Hasil Prediksi Decision Tree</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">

              {{-- Form Search --}}
              <div class="searchHasilDecisionTree mb-3">
                <label for="textSearchHasilDecisionTree">Cari :</label>
                <input id="textSearchHasilDecisionTree" type="text" name="searchHasilDecisionTree">
              </div>

              
              @if (count($semuaData) > 0)
                <div class="list-group listHasilDecisionTree" id="listHasilDecisionTree">

                  @foreach ($semuaData as $item)

                    <a href="{{ route('home', ['idData' => $item['id']]) }}" class="list-group-item list-group-item-action" id="buttonIsiListHasilDecisionTree">

                        <div class="row d-flex w-100 justify-content-between">

                            <div class="col-9 text-center">
                              <h5 class="mb-1">{{$item['namaHasilDecisionTree']}}</h5>
                              <small class="mb-1">Jumlah Data : {{$item['jmlKel']}}</small><br>
                              <small class="mb-1">{{$item['created_at']}}</small><br>
                              <small class="text-muted" >{{$item['deskripsi']}}</small>
                            </div>

                            <div class="col-3 text-center">
                              @if ($item['status'] != 'utama')
                                <form action="/hapusHasilDecisionTree/{{$item['id']}}" method="post" >
                                  @csrf
                                  @method('DELETE')
                                    <button class="btn btn-sm text-center" onclick="return confirm('Yakin mau hapus?')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash text-center" viewBox="0 0 16 16">
                                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                  </svg></button>
                                </form>
                              @endif
                              @if (session('data')['username'] == 'adminUtama')
                                {{-- Button jadikan utama --}}
                                @if ($item['status'] == 'utama')
                                    <button type="button" class="btn btn-success prima btn-sm text-center mt-5" style="font-size: 10px" disabled>
                                      {{$item['status']}}
                                    </button>
                                @else
                                  <form action="/jadikanpolautama/{{$item['id']}}">
                                    <button class="btn btn-primary prima btn-sm text-center" style="font-size: 10px">
                                      Jadikan Utama
                                    </button>
                                  </form>
                                @endif  
                              @endif
                            </div>
                            
                        </div>

                    </a>

                  @endforeach

                </div>
              @else
                <div class="butatpolaa text-center">
                  <a class="btn btn-primary" data-toggle="modal" data-target="#importExcel" id="closeHasilPrediksi">Buat Pola Prediksi</a>
                </div>
              @endif

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
            
          </div>

        </div>
      </div>

      @if (session('data')['username'] == 'adminUtama')
        <!-- modalListAkun -->
        <div class="modal fade" id="modalListAkun" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title">List Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">

                {{-- Form Search --}}
                <div class="searchListAkun mb-3">
                  <label for="textSearchListAkun">Cari :</label>
                  <input id="textSearchListAkun" type="text" name="searchListAkun">
                </div>
                
                  <div class="list-group listAkun" id="listAkunisi">

                    @foreach ($listAkun as $item)

                      @if ($item['username'] != 'adminUtama')
                        <div class="list-group-item list-group-item-action" id="buttonIsiListAkun">

                            <div class="row d-flex w-100 justify-content-between">

                                <div class="col-9 text-center">
                                  <h5 class="mb-1">{{$item['username']}}</h5>
                                  
                                </div>

                                <div class="col-3 text-center">
                                  <form action="/hapusAdmin/{{$item['id']}}" method="post" >
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm text-center" onclick="return confirm('Yakin mau hapus?')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash text-center" viewBox="0 0 16 16">
                                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg></button>
                                  </form>
                                </div>
                                
                            </div>

                          </div>
                      @endif

                    @endforeach

                  </div>

              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
              </div>
              
            </div>

          </div>
        </div>
      @endif

    @endif
      <!-- tentangWebsite -->
      <div class="modal fade" id="tentangWebsite" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">

          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">Tentang Website</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <div class="row">
                <div class="col-sm-3 p-4">
                    <img src="img/fotocv.png" class="img-thumbnail">
                </div>
                <div class="col-sm-9 p-5">
                    <p class="text-justify">
                      Assalamualaikum, Wr. Wb. 
                      <br><br>
                      Perkenalkan nama saya Ammaridho, 
                      <br><br>
                      saya adalah mahasiswa prodi Teknik Informatika angkatan 2018, Fakultas Sains dan Teknologi, UIN Syarif Hidayatullah Jakarta.
                      <br><br>
                      Website ini merupakan hasil penelitian saya dalam rangka penyelesaiian Tugas Akhir Skripsi yang berjudul "Pemilihan Bandwidth Internet Rumahan Dengan Metode Algoritma Decision Tree C4.5".
                    </p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

    <script src="js/script.js"></script>

  </body>

  <script>
    
    $(document).ready(function(){
      
      //Hover klik listhasildeskripsi
      $('#buttonIsiListHasilDecisionTree').on('click',function(){
        $(this).prop('active',true);
      })

      //Searching hasil decisiontree =================================
      $("#textSearchHasilDecisionTree").on("keyup", function() {
        var value = $(this).val().toLowerCase();

        $("#listHasilDecisionTree a").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });

      });

      //Searching Akun =================================
      $("#textSearchListAkun").on("keyup", function() {
        var value = $(this).val().toLowerCase();

        $("#listAkunisi a").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });

      });
    });
  </script>
</html>