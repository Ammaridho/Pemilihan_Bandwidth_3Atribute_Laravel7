@extends('layout.base')

@section('content')

    <div class="container mt-5">

      <div class="row">
        <div class="col text-center">
          <h1>Semua Dataset</h1>
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
    

    

    
    
@endsection