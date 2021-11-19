@extends('layout.base')

@section('content')
    <h1>Kasarnya dulu boss:</h1>

    <br><br>

    <div class="container">

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
                <td>{{$jmlKurang}}</td>
                <td>{{$jmlCukup}}</td>
                <td>{{$jmlLebih}}</td>
                <td>{{$totEntropykel}}</td>
                <td></td>
              </tr>

              {{-- @for ($i = 0; $i < count($macamAtribut); $i++) --}}
              @for ($i = 0; $i < 4; $i++)
                <tr>
                  <th scope="row">{{$macamAtribut[$i]}}</th>
                  <th colspan="6"></th>
                  <th>{{$allGain[$i]}}</th>
                </tr>

                @for ($j = 0; $j < count($arrayNamaBagianAttribut[$i]); $j++)
                <tr>
                  <td colspan="2">{{$arrayNamaBagianAttribut[$i][$j]}}</td>
                  <td>{{$totalValueAttribute[$i][$j]}}</td>
                  <td>{{$sortingTarget[$i][$j][0]}}</td>
                  <td>{{$sortingTarget[$i][$j][1]}}</td>
                  <td>{{$sortingTarget[$i][$j][2]}}</td>
                  <td>{{$allEntropy[$i][$j]}}</td>
                </tr>
                @endfor
              @endfor

              
            </tbody>
          </table>

    </div>
    

    

    
    
@endsection