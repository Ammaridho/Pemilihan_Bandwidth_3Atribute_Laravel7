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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Skripsi C4.5</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" type="button" href="{{route('home', ['idData' => $idData])}}">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="btn nav-link" data-toggle="modal" data-target="#importExcel">Import Data Excel</a>
            </li>
            {{-- <li>
              <a class="nav-link" href="{{route('hasilDecisiontree')}}">Buat Ulang Tree</a>
            </li> --}}
            <li class="nav-item">
              <a class="btn nav-link" data-toggle="modal" data-target="#modalHasilPrediksi">Hasil Prediksi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="">About</a>
            </li>
        </div>
    </nav>

    <!-- Import Excel -->
		<div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">

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
              {{-- <button class="buttonSubmitSearchHasilDecisionTree" type="submit">Cari</button> --}}
            </div>

            <div class="list-group listHasilDecisionTree" id="listHasilDecisionTree">
              {{-- <a href="#" class="list-group-item list-group-item-action active">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">List group item heading</h5>
                  <small>3 days ago</small>
                </div>
                <p class="mb-1">Some placeholder content in a paragraph.</p>
                <small>And some small print.</small>
              </a> --}}
              @foreach ($semuaData as $item)
                <a href="{{ route('home', ['idData' => $item['id']]) }}" class="list-group-item list-group-item-action">
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{$item['namaHasilDecisionTree']}}</h5>
                    <small class="text-muted">{{$item['id']}}</small>
                  </div>
                  <p class="mb-1">Jumlah Data : {{$item['jmlKel']}}</p>
                  <small class="text-muted">{{$item['deskripsi']}}</small>
                </a>
              @endforeach
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
			</div>
		</div>
    
    @yield('content')

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
    -->
  </body>

  <script>
    $(document).ready(function(){
      $("#textSearchHasilDecisionTree").on("keyup", function() {
        var value = $(this).val().toLowerCase();

        $("#listHasilDecisionTree a").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });

      });
    });
  </script>
</html>