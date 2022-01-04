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
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background-color: #cb997e">
        <a class="navbar-brand font-weight-bold">Skripsi C4.5</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav">
            @if (session('session_login'))
            <li class="nav-item">
              <a class="btn nav-link" data-toggle="modal" data-target="#modalHasilPrediksi" id="testtt">List Hasil Pola Prediksi</a>
            </li>
            <li class="nav-item">
              <a class="btn nav-link" data-toggle="modal" data-target="#importExcel">Buat Pola Prediksi</a>
            </li>
            @endif
            <li class="nav-item">
              <a class="btn nav-link" data-toggle="modal" data-target="#tentangWebsite">Tentang Website</a>
            </li>
          </ul>
            <div class="navbar-nav ml-auto">
              <li class="nav-item">
                @if (!session('session_login'))
                  <a class="btn nav-link" data-toggle="modal" data-target="#formlogin">Login</a>              
                @else
                <div class="dropdown nav-item">
                  <a id="buttonKeranjang" class="btn dropdown-toggle" href="#" role="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{(session('data')['username'])}}
                  </a>
                
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="btn tombol" onclick="signout()">Sign Out</a>
                  </div>
                </div>
                @endif
              </li>
            </div>
        </div>
    </nav>

    {{-- formlogin --}}
    <div class="modal fade" id="formlogin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm" role="document">

				<form method="post" action="/signin" enctype="multipart/form-data">
					<div class="modal-content text-center">
						<div class="modal-header">
							<h5 class="modal-title text-center" id="exampleModalLabel">Login</h5>
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

              <span>Belum memiliki akun? <a class="btn" data-toggle="modal" data-target="#signup">Sign Up</a></span>

						</div>
            <div class="bawah text-center">
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
						</div>
					</div>
				</form>
        
			</div>
		</div>

    {{-- signUp --}}
    <div class="modal fade" id="signup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm" role="document">

				<form method="post" action="{{route('signup')}}" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-center" id="exampleModalLabel">Sign Up</h5>
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
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn" id="buttonsubmitsignup" style="background-color: grey" disabled>Submit</button>
						</div>
					</div>
				</form>
        
			</div>
		</div>

    @if (session('session_login'))
      <!-- Import Excel -->
      <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">

          <form method="post" action="/importExcel" enctype="multipart/form-data">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Import Data Excel</h5>
              </div>
              <div class="modal-body">

                {{ csrf_field() }}
                <label for="namaData">Nama data</label>
                <div class="form-group">
                  <input type="text" name="namaData" id="namaData" required>
                </div>

                <label for="deskripsiData">Deskripsi</label>
                <div class="form-group">
                  <textarea name="deskripsiData" id="deskripsiData" cols="30" rows="10"></textarea>
                </div>

                <label for="fileExcel">Pilih file excel</label>
                <div class="form-group">
                  <input type="file" name="file" id="fileExcel" required="required">
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Import</button>
              </div>
            </div>
          </form>
          
        </div>
      </div>

      <!-- modalHasilPrediksi -->
      <div class="modal fade" id="modalHasilPrediksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">

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
              
              @if (isset($semuaData))
                <div class="list-group listHasilDecisionTree" id="listHasilDecisionTree">
                  @foreach ($semuaData as $item)
                    <a href="{{ route('home', ['idData' => $item['id']]) }}" class="list-group-item list-group-item-action" id="buttonIsiListHasilDecisionTree">
                      <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{$item['namaHasilDecisionTree']}}</h5>
                        {{-- button hapus --}}
                        <form action="/hapusHasilDecisionTree/{{$item['id']}}"  method="post" style="float: right">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm" onclick="return confirm('Yakin mau hapus?')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                              <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg></button>
                        </form>
                      </div>
                      <p class="mb-1">Jumlah Data : {{$item['jmlKel']}}</p>
                      <small class="text-muted">{{$item['deskripsi']}}</small>
                    </a>
                  @endforeach
                </div>
              @else
                  <p class="text-center">Kosong, silahkan buat pola prediksi</p>
              @endif

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            
          </div>

        </div>
      </div>
      
      @endif
      <!-- tentangWebsite -->
      <div class="modal fade" id="tentangWebsite" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">

          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title">List Hasil Prediksi Decision Tree</h5>
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
                    <p class="text-justify">Assalamualaikum, Wr. Wb. 
                      <br><br>
                      Perkenalkan nama saya Ammaridho, 
                      <br><br>
                      saya adalah mahasiswa prodi Teknik Informatika angkatan 2018, Fakultas Sains dan Teknologi, UIN Syarif Hidayatullah Jakarta.
                      <br><br>
                      Saya sedang melakukan penelitian dalam rangka penyelesaiian Tugas Akhir Skripsi yang berjudul "Pemilihan Bandwidth Internet Rumahan Dengan Metode Algoritma Decision Tree C4.5".
                      <br><br>
                      Saya ucapkan terimakasih kepada saudara/i atas ketersediaannya mengisi survey ini, data yang diambil hanya akan digunakan untuk penelitian saya dan tidak akan disebarluaskan.</p>
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

      //Searching =================================
      $("#textSearchHasilDecisionTree").on("keyup", function() {
        var value = $(this).val().toLowerCase();

        $("#listHasilDecisionTree a").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });

      });
    });
  </script>
</html>