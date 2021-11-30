@extends('layout.base')

@section('content')
    
    
        <head>
            <link href="css/style.css"rel="stylesheet"type="text/css"/>
        </head>
        <body>
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
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
        </body>

@endsection