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
            </div>
        </body>

@endsection