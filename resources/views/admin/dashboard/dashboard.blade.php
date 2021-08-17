@extends('layouts.admin')

@section('styles')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUx-lN2Wy6w2C0f2o14A3GgY--AqGiXPc"></script>
@endsection

@section('content')

<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card-box widget-chart-two">
                    <div class="float-right">
                        <input data-plugin="knob" data-width="80" data-height="80" data-linecap=round
                               data-fgColor="#0acf97" value="37" data-skin="tron" data-angleOffset="180"
                               data-readOnly=true data-thickness=".1"/>
                    </div>
                    <div class="widget-chart-two-content">
                        <p class="text-muted mb-0 mt-2">Teams</p>
                        <h2 class="" data-plugin="counterup">{{ $teams }} </h2>
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card-box widget-chart-two">
                    <div class="float-right">
                        <input data-plugin="knob" data-width="80" data-height="80" data-linecap=round
                               data-fgColor="#f9bc0b" value="92" data-skin="tron" data-angleOffset="180"
                               data-readOnly=true data-thickness=".1"/>
                    </div>
                    <div class="widget-chart-two-content">
                        <p class="text-muted mb-0 mt-2">Venues</p>
                        <h2 class="" data-plugin="counterup">{{ $venues }} </h2>
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card-box widget-chart-two">
                    <div class="float-right">
                        <input data-plugin="knob" data-width="80" data-height="80" data-linecap=round
                               data-fgColor="#f1556c" value="14" data-skin="tron" data-angleOffset="180"
                               data-readOnly=true data-thickness=".1"/>
                    </div>
                    <div class="widget-chart-two-content">
                        <p class="text-muted mb-0 mt-2">Challenges</p>
                        <h2 class="" data-plugin="counterup">{{ $challanges }} </h2>
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card-box widget-chart-two">
                    <div class="float-right">
                        <input data-plugin="knob" data-width="80" data-height="80" data-linecap=round
                               data-fgColor="#2d7bf4" value="60" data-skin="tron" data-angleOffset="180"
                               data-readOnly=true data-thickness=".1"/>
                    </div>
                    <div class="widget-chart-two-content">
                        <p class="text-muted mb-0 mt-2">Matches</p>
                        <h2 class="" data-plugin="counterup">{{ $matches }}</h2>
                    </div>

                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-lg-6">
                <div class="card-box carousel-card">
                    <h4 class="mb-4 text-uppercase text-orange">Challenges</h4>
                    @if(count($latestChallanges) > 0)
                    <div id="carouselExampleControls1" class="carousel slide pl-3 pr-3" data-ride="carousel">
                        <div class="carousel-inner">
                            @php
                                $counter = 1;
                            @endphp
                            @foreach ($latestChallanges as $latestChallange)
                            <div @if($counter == 1) class="carousel-item active" @else class="carousel-item" @endif>
                                <div class="row d-flex align-items-center">
                                    <div class="col-4 text-center">
                                        <img class="rounded-circle" @if($latestChallange->challenger->logo != null) src="{{ asset('files/'.$latestChallange->challenger->logo) }}" @else src="{{ asset('files/default/team-a.jpg') }}" @endif alt="" height="100" width="100">
                                        <div class="font-weight-bold">{{ $latestChallange->challenger->name }}</div>
                                        <div class="font-weight-bold text-muted">{{ count($latestChallange->challenger->members) }} Players</div>
                                    </div>
                                <div class="col-4 text-center">
                                        <div class="badge badge-pill badge-danger font-16 pl-3 pr-3">VS</div>
                                        <div><small class="text-muted">{{ $latestChallange->proposed_time }} <br/>{{ $latestChallange->venue->name }}</small></div>
                                    </div>
                                    <div class="col-4 text-center ">
                                        <img class="rounded-circle" @if($latestChallange->opponent->logo != null) src="{{ asset('files/'.$latestChallange->opponent->logo) }}" @else src="{{ asset('files/default/team-a.jpg') }}" @endif alt="" height="100" width="100">
                                        <div class="font-weight-bold">{{ $latestChallange->opponent->name }}</div>
                                        <div class="font-weight-bold text-muted">{{ count($latestChallange->opponent->members) }} Players</div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $counter = 0;
                            @endphp
                        @endforeach
                        </div>
                          <a class="carousel-control-prev" href="#carouselExampleControls1" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                          </a>
                          <a class="carousel-control-next" href="#carouselExampleControls1" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                          </a>
                    </div>
                    @else
                    <div align="center">
                        <img class="" src="{{ asset('files/default/no-data.png') }}" height="100" width="100" alt="">
                        <div class="text-muted">No challenge found.</div>
                    </div>
                    @endif
                </div>
            </div>
        
            <div class="col-lg-6">
                <div class="card-box carousel-card">
                    <h4 class="mb-4 text-uppercase text-orange">Schedule Matches</h4>
                    @if(count($latestMatches) > 0)
                    <div id="carouselExampleControls" class="carousel slide pl-3 pr-3" data-ride="carousel">
                        <div class="carousel-inner">
                            @php
                            $count = 1;
                        @endphp
                        @foreach ($latestMatches as $latestMatche)
                            <div  @if($count == 1) class="carousel-item active" @else class="carousel-item" @endif>
                                <div class="row d-flex align-items-center">
                                    <div class="col-4 text-center">
                                        <img class="rounded-circle" @if($latestMatche->challenge->challenger->logo != null) src="{{ asset('files/'.$latestMatche->challenge->challenger->logo) }}" @else src="{{ asset('files/default/team-a.jpg') }}" @endif alt="" height="100" width="100">
                                        <div class="font-weight-bold">{{ $latestMatche->challenge->challenger->name }}</div>
                                         <div class="font-weight-bold text-muted">{{ count($latestMatche->challenge->challenger->members) }} Players</div>
                                    </div>
                                   <div class="col-4 text-center">
                                        <div class="badge badge-pill badge-danger font-16 pl-3 pr-3">VS</div>
                                        <div><small class="text-muted">{{ $latestMatche->challenge->proposed_time }} <br/>{{ $latestMatche->challenge->venue->name }}</small></div>
                                    </div>
                                    <div class="col-4 text-center ">
                                        <img class="rounded-circle" @if($latestMatche->challenge->opponent->logo != null) src="{{ asset('files/'.$latestMatche->challenge->opponent->logo) }}" @else src="{{ asset('files/default/team-a.jpg') }}" @endif alt="" height="100" width="100">
                                        <div class="font-weight-bold">{{ $latestMatche->challenge->opponent->name}}</div>
                                         <div class="font-weight-bold text-muted">{{ count($latestMatche->challenge->opponent->members) }} Players</div>
                                    </div>
                                </div>
                            </div>
                            @php
                            $count = 0;
                            @endphp
                        @endforeach
                        </div>
                          <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                          </a>
                          <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                          </a>
                    </div>
                    @else
                    <div align="center">
                        <img class="" src="{{ asset('files/default/no-data.png') }}" height="100" width="100" alt="">
                        <div class="text-muted">No match found.</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-lg-6 ">
                <div class="card-box">
                    <div class="text-uppercase mb-4">
                        <div class="pull-left"><h4 class="text-orange">Recent Teams</h4></div>
                        @if(count($recentTeams) > 0)
                            <div class="pull-right"><a href="{{ route('allTeams') }}" class="btn btn-rounded btn-sm btn-success">View All</a></div>
                        @endif
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="inbox-widget slimscroll" style="max-height: 370px;">
                        @if(count($recentTeams) > 0)
                        @foreach ($recentTeams as $recentTeam)
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img @if($recentTeam->logo != null) src="{{ asset('files/'.$recentTeam->logo) }}" @else src="{{ asset('files/default/team-a.jpg') }}" @endif class="rounded-circle" height="40" width="40" alt=""></div>
                                    <p class="inbox-item-author">{{ $recentTeam->name }}</p>
                                    <p class="inbox-item-author text-muted ">{{ $recentTeam->num_of_players }} Players</p>
                                    <div class=" badge badge-pill badge-primary inbox-item-date text-white text-uppercase mt-2">{{ $recentTeam->game->name }}</div>
                                </div>
                            </a> 
                        @endforeach
                        @else
                        <div align="center" class="mt-5">
                            <img class="" src="{{ asset('files/default/no-data.png') }}" height="100" width="100" alt="">
                            <div class="text-muted">No recent teams.</div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        

            <div class="col-lg-6">
                <div class="card-box">
                    <div class="header-title mb-4">
                        <div class="pull-left text-orange"><h4>VENUES</h4></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="map" id="map" data-property="map" style="height: 373px;"></div>
                </div>
            </div>
        </div>
        <!-- end row -->



    </div> <!-- container -->

</div> <!-- content -->

@endsection

@section('scripts')
<script>
    $('.carousel').carousel({
  interval: 5000
});
</script>
<script>
    function initMap() {
        var map;
       var bounds = new google.maps.LatLngBounds();
           
        // Display a map on the web page
        map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 31.52039909362793, lng: 74.29034423828125},
      });
          
            
        // Multiple markers location, latitude, and longitude
        var markers = [
            <?php if(count($allVanues) > 0){
                foreach($allVanues as $row){
                    echo '["'.$row['name'].'", '.$row['lat'].', '.$row['lng'].'],';
                }
            }
            ?>
        ];
                            
        // Info window content
        var infoWindowContent = [
            <?php if(count($allVanues) > 0){
                foreach($allVanues as $row1){ ?>
                    ['<div class="info_content">' +
                    '<h3><?php echo $row1['name']; ?></h3>' +
                    '<p><?php echo $row1['address']; ?></p>' + '</div>'],
            <?php }
            }
            ?>
        ];
            
        // Add multiple markers to map
        var infoWindow = new google.maps.InfoWindow(), marker, i;
        
        // Place each marker on the map  
        for( i = 0; i < markers.length; i++ ) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: markers[i][0]
            });
            
            // Add info window to marker    
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infoWindow.setContent(infoWindowContent[i][0]);
                    infoWindow.open(map, marker);
                }
            })(marker, i));
    
            // Center the map to fit all markers on the screen
           
        }
    
        // Set zoom level
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom(9);
            google.maps.event.removeListener(boundsListener);
        });
        
    }
    
    // Load initialize function
    google.maps.event.addDomListener(window, 'load', initMap);
    </script>
@endsection
