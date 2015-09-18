@extends('layouts.framework')

@section('css')
@parent
		<link href="/assets/css/header/header-login.css" rel="stylesheet">
	    <link href="/assets/css/login/login.css" rel="stylesheet">
@stop

@section('header')
<div id= "headerGeneric" class="header-fixed">
		    <div class="header-first-bar">
		        <div class="header-limiter">
		            <div id="headerleft" class="headcomp">
		                <a href="#" class="logo">
		                    <div id="myLogo"></div>
		                </a>
		            </div>
		            <div id="headermid" class="headcomp">
		                <span id="htitle"></span>
		                <span id="hsubtitle"></span>
		            </div>
		            <div id="headerright" class="headcomp">
		                <button id="loginButton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Login</button>
		            </div>
		        </div>
		    </div>
		</div>
@stop

@section('body')
<div id="loginContainer" class="container-fluid">
			<div class="row">
				<div class="section initial-section section-shadow wow pulse animated" data-wow-duration="3s" data-wow-iteration="infinite" data-wow-delay="300ms">
		            <div class="container">
		                <div class="row">
		                    <div class="center-block p4 initial-section-content">
		                        <h2 class="h1 page-title">Smart Developer Hub</h2>
		                        <p class="h4 page-title-content up">
		                            Smart Developer Hub provides insights about the performance of
		                            software development teams by generating quantitative and qualitative
		                            metrics based on metadata gathered from ALM tools that are used in the
		                            organization's development process.
		                            </p>
		                        <p class="h4 page-title-content down">
		                            The Smart Developer Hub platform has been designed with extensibility 
		                            and interoperability in mind in order to facilitate the integration of heterogeneous 
		                            ALM tools and the provision of tool-independent metrics.
		                        </p>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <div id="arqPanel" class="section feature-section gradient-1">
		            <div class="container">
		                <div class="row">
		                    <div class="row">
		                        <div class="wow pulse animated">
		                            <h2 class="h1 arqTitle">Software Developer Team Performance Analysis using Linked Data</h2>
		                            <p class="h4 parr first">
		                                To facilitate the consumption of the data provided by heterogeneous ALM tools, the 
		                                Smart Developer Hub platform (a.k.a. SDH platform) standardizes the data access 
		                                mechanism as well as the data model (a.k.a. SDH vocabulary) and format used for 
		                                the exchange of the data within the platform, using the web as a platform and leveraging 
		                                standards such as the <a href="http://www.w3.org/TR/ldp/">LDP</a>, <a href="http://www.w3.org/TR/2014/REC-rdf11-mt-20140225/">RDF</a>, and <a href="http://www.w3.org/TR/owl-features/">OWL</a> W3C recommendations and the 
		                                <a href="http://www.w3.org/Submission/shapes/">OSLC</a> initiative from OASIS.
		                            </p>
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col col-sm-12 wow fadeInLeft animated" data-wow-duration="1.1s" data-wow-delay="0.2s">
		                            <div class="screenshot">
		                                <img class="image" height="100%" width="80%" src="/assets/images/sdh-architecture.png">
		                            </div>
		                        </div>
		                    </div>
		                    <div class="row">
		                        <div class="col col-md-12 col-sm-12 wow fadeInUp animated" data-wow-duration="1.1s">
		                            <p class="h4 parr">
		                                In particular, the SDH platform promotes the integration of ALM tools using LDP-aware 
		                                adapters that enable exposing the tools' data as Linked Data defined using a common 
		                                vocabulary (i.e., the SDH vocabulary) that is exchanged using a common format (i.e., 
		                                RDF serialiations).
		                            </p>
		                            <p class="h4 parr">
		                                To facilitate the consumption of this distributed information graph the SDH platform 
		                                provides the Agora, which exploits the SDH vocabulary for creating query plans that 
		                                define how to traverse this Linked Data graph in order to retrieve the required data.
		                            </p>
		                            <p class="h4 parr">
		                                The Smart Developer Hub metric services leverage the query plans provided by the 
		                                Agora to retrieve and process the information required to calculate the different 
		                                measurements. These measurements are then stored in the form of service-specific 
		                                internal data marts.
		                            </p>
		                            <p class="h4 parr">
		                                Finally, the SDH platform offers a set of customizable dashboards to visualize the 
		                                metrics via different widgets. These dashboards,  which are adapted to the profile 
		                                of user within the organization, allow selecting the time range of interest, adjusting 
		                                automatically the metrics values shown according to the selected time range.
		                            </p>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
			</div>
		</div>
		<!-- Modal -->
		<div id="myModal" class="modal fade" role="dialog">
		  	<div class="modal-dialog">
		        <div id="loginForm" class="col-md-8 col-md-offset-2">
		            <div class="panel panel-default">
		                <div class="panel-heading" data-toggle="tooltip" title="Please, fill the form and login SDH">SDH Login</div>
		                <div class="panel-body">
		                    @if (count($errors) > 0)
		                        <div class="alert alert-danger">
		                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
		                            <ul>
		                                @foreach ($errors->all() as $error)
		                                    <li>{{ $error }}</li>
		                                @endforeach
		                            </ul>
		                        </div>
		                    @endif

		                    <form class="form-horizontal" role="form" method="POST" action="/auth/login">
		                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

		                        <div class="form-group">
		                            <label class="col-md-4 control-label">Username</label>
		                            <div class="col-md-6">
		                                <input type="text" class="form-control" name="username" value="{{ old('username') }}">
		                            </div>
		                        </div>

		                        <div class="form-group">
		                            <label class="col-md-4 control-label">Password</label>
		                            <div class="col-md-6">
		                                <input type="password" class="form-control" name="password">
		                            </div> 
		                        </div>

		                        <div class="form-group">
		                            <div class="col-md-6 col-md-offset-4">
		                                <div class="checkbox">
		                                    <label>
		                                        <input type="checkbox" name="remember"> Remember Me
		                                    </label>
		                                </div>
		                            </div>
		                        </div>

		                        <div class="form-group">
		                            <div class="col-md-6 col-md-offset-4">
		                                <button type="submit" class="btn btn-primary">Login</button>
		                            </div>
		                        </div>
		                    </form>

		                </div>
		            </div>
		        </div>
		  	</div>
		</div>
@stop

@section('scripts')
	@parent
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	    <script type="application/javascript">
			$(document).ready(function(){
				$("#loginButton").click(function() {
					//$("#myModal").modal({"backdrop": "static"});
			    });
				console.log("SDH Welcome Ready!");
			});
	    </script>
@stop