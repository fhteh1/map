<!DOCTYPE html>
<?php

// Inialize session
  session_start();

  // Check, if username session is NOT set then this page will jump to login page
  if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
  }

?>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <title>간단한 지도 표시하기</title>
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?clientId=1niqyBEa3DtYouWdgkxK&submodules=geocoder"></script>

    <script src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
    <style>
    .container{
      width:1900px;
      border: 1px solid gray;
      margin:auto;
    }
    nav{
      float : left;
      width: 15%;
      /* height:860px; */
      border-right:1px solid gray;
    }
    .content{
      float : left;
      width: 83%;
      margin-left:-1px;
      border-left:1px solid gray;
      padding:10px;
    }

    </style>
</head>
<body>
  <div class="container">
  <nav>
    <ol id ="olist">
      <!-- <li>테스트0</li>
      <li>테스트1</li>
      <li>테스트2</li>
      <li>테스트3</li>
      <li>테스트4</li>
      <li>테스트5</li>
      <li>테스트6</li>
      <li>테스트7</li>
      <li>테스트8</li>
      <li>테스트9</li> -->
    </ol>
  </nav>
  <section class="content">
  <div id="map" style="width:100%; height:800px;"></div>
  <hr>
    <button type="button" onclick="showvideo()">전체 카메라 열기</button>
    <button type="button" onclick="closevideo()">전체 카메라 닫기</button>
    <button id="Rbutton" type="button" onclick="searchvideo()">카메라 검색</button>

    <h3><a href="logout.php">logout</a></h3>
  </section>
</div>
</body>

<script>
var HOME_PATH = window.HOME_PATH || '.';

var map = new naver.maps.Map('map', {
    center: new naver.maps.LatLng(35.3354443,129.037246),
    zoom: 10,
    mapTypeControl: true
});

var bounds = map.getBounds(),
    southWest = bounds.getSW(),
    northEast = bounds.getNE(),
    lngSpan = northEast.lng() - southWest.lng(),      //y
    latSpan = northEast.lat() - southWest.lat();      //x
//alert(northEast.lat());
//삭제 동작시 삭제를 위해 생성한 마커, infoWindow, listener, listname. position 배열에 저장해둠
var markers = [],
    infoWindows = [];
var listeners=[];
var listname=[];
//for (var key in MARKER_SPRITE_POSITION) {
var position_b=[];    /////////
var pnum=0;
var markernum=30;        //total marker(임의)
var selectmarker=999;    //선택된 마커, 999로 초기화
var OnOff=false;          //카메라 삭제시 선택되있는 카메라가 있는지 확인
for (var i=0; i<markernum; i++) {

    var position = new naver.maps.LatLng(
        southWest.lat() + latSpan * Math.random(),
        southWest.lng() + lngSpan * Math.random());


      position_b[pnum]=position;  //alert(position_b[pnum]);
      pnum++;

    var marker = new naver.maps.Marker({
        map: map,
        position: position,
        zIndex: 100
    });

    listname[i] = "camera "+i;
    addlist(listname[i]);

    var infoWindow = new naver.maps.InfoWindow({
        content: '<h1>'+listname[i]+'</h1><video src="./img/test.mp4" style="height: 200px;" autoplay controls>',
        maxWidth: 355,
        backgroundColor: "#eee",
        borderColor: "#2db400",
        borderWidth: 1,
        anchorSize: new naver.maps.Size(10, 20),
        anchorSkew: true,
        anchorColor: "#eee",
        pixelOffset: new naver.maps.Point(20, -20)
    });

    markers.push(marker);
    infoWindows.push(infoWindow);

};

naver.maps.Event.addListener(map, 'idle', function() {
    updateMarkers(map, markers);
});

function updateMarkers(map, markers) {
    var mapBounds = map.getBounds();
    var marker, position;

    for (var i = 0; i < markers.length; i++) {
        marker = markers[i];
        position = marker.getPosition();

        if (mapBounds.hasLatLng(position)) {
            showMarker(map, marker);
        }
        else {
            hideMarker(map, marker);
        }
    }
}

function showMarker(map, marker) {
    if (marker.setMap()) return;
    marker.setMap(map);
}

function hideMarker(map, marker) {
    if (!marker.setMap()) return;
    marker.setMap(null);
}

// 해당 마커의 인덱스를 seq라는 클로저 변수로 저장하는 이벤트 핸들러를 반환합니다.
function getClickHandler(seq) {
    return function(e) {
      //  alert(seq);
        selectmarker=seq;
        alert(selectmarker);
        var marker = markers[seq],
            infoWindow = infoWindows[seq];
        //alert(seq);
        if (infoWindow.getMap()) {
            OnOff=false;
            infoWindow.close();
        }
        else {
          OnOff=true;
          for (var i=0, ii=markers.length; i<ii; i++){
              overlay_b[i].setMap(null);
          }
          infoWindow.open(map, marker);
        }
    }
}

for (var i=0, ii=markers.length; i<ii; i++) {
    naver.maps.Event.addListener(markers[i], 'click', getClickHandler(i));
  //    listeners[i]=naver.maps.Event.addListener(markers[i], 'click', getClickHandler(i));
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

var CustomOverlay = function(options) {
    this._element = $('<div id="video_overlay" style="position:absolute;left:0;top:0;width:124px;text-align:center;" >' +
                      '<video src="./img/test.mp4" style="height: 200px;" controls autoplay></div>')

    this.setPosition(options.position);
    this.setMap(options.map || null);
};

CustomOverlay.prototype = new naver.maps.OverlayView();          // CustomOverlay는 OverlayView를 상속 받습니다.
CustomOverlay.prototype.constructor = CustomOverlay;
CustomOverlay.prototype.setPosition = function(position) {
    this._position = position;
    this.draw();
};

CustomOverlay.prototype.getPosition = function() {
    return this._position;
};

CustomOverlay.prototype.onAdd = function() {      //오버레이를 추가할때 호출
    var overlayLayer = this.getPanes().overlayLayer;

    this._element.appendTo(overlayLayer);
};

CustomOverlay.prototype.draw = function() {        //지도에 오버레이를 그려야 할떄 호출
    if (!this.getMap()) {                          // 지도 객체가 설정되지 않았으면 draw 기능을 하지 않습니다.
        return;
    }

    var projection = this.getProjection(),         // projection 객체를 통해 LatLng좌표를 화면좌표로 변경합니다.
        position = this.getPosition(),
        pixelPosition = projection.fromCoordToOffset(position);

    this._element.css('left', pixelPosition.x);
    this._element.css('top', pixelPosition.y);
};

CustomOverlay.prototype.onRemove = function() {      //지도에 오버레이가 삭제될떼 호출

    this._element.remove();
    this._element.off();

};

////////////////////////////////////////////////////////////////////
function addlist(name)
{
    $("#olist").append("<li class='listchild' onClick='WhereIsMarker(this)'>"+name+"</li>");
}
///////////////////////////////////////////////////////////////////////////////////////
//html list add remove
//http://www.mredkj.com/tutorials/tutorial005.html


/*
$(".listchild").on("click", function(){
alert($(this));
$(this).closest("li").remove();
//   $(this).closest("li").remove();
});
*/
//olist
function removelist()
{
  var list = document.getElementById("olist");
  while (list.firstChild) {
      list.removeChild(list.firstChild);
  }
  //$(this).parent().remove();

    // var parent = document.getElementById("olist");
    // var relist="camera "+num;
    // var rli = document.getElementById(relist);
    //
    // parent.removeChild(rli);


  //  $("#olist").append("<li onClick='WhereIsMarker(pos)'>"+name+"</li>");
}

function WhereIsMarker(element){
  // alert(element.getElementsByName('name'));
  // getClickHandler(seq);
}

////////////////////////////////////////////////////////////////////


var overlay_b=[];

for (var i=0, ii=markers.length; i<ii; i++) {
  position= position_b[i];
  //alert(position);
  var overlay = new CustomOverlay({
      map: map,
      position: position
  });
  overlay_b[i]=overlay;
  overlay_b[i].setMap(null);
}

function searchvideo(){
  var openWin;
  // window.name = "부모창 이름";
            window.name = "search";
            // window.open("open할 window", "자식창 이름", "팝업창 옵션");
            openWin = window.open("search.html",
                    "AddMap", "width=200, height=150, resizable = no, scrollbars = no");
}


function findmarker(Cname){
  for (var i=0, ii=markers.length; i<ii; i++){
    infoWindows[i].setMap(null);
    overlay_b[i].setMap(null);
  }
  for (var i=0, ii=markers.length; i<ii; i++) {
    if(Cname==listname[i])
    {
    //  var marker = markers[i]
    //  infoWindows[i].setMap(map);

    overlay_b[i].setMap(map);
  //  map.setCursor('pointer');
      break;
    }
  }
  //alert(Cname);
}


function showvideo(){
  for (var i=0, ii=markers.length; i<ii; i++){
      overlay_b[i].setMap(null);
      overlay_b[i].setMap(map);
  }
}

function closevideo(){
  for (var i=0, ii=markers.length; i<ii; i++){
      overlay_b[i].setMap(null);
  }
}

var CameraLocation, CameraX, CameraY;

function setcamera(name,x,y){
  listname[pnum]=name;
  CameraLocation=name;
  CameraX=x;
  CameraY=y;

    var pos = new naver.maps.LatLng(
        CameraX,CameraY);

    position_b[pnum]=pos;  //alert(position_b[pnum]);
    pnum++;

  var marker = new naver.maps.Marker({
      map: map,
      position: pos,
      zIndex: 100
  });
        alert(pos);

        var infoWindow = new naver.maps.InfoWindow({
            content: '<h1>'+CameraLocation+'</h1><video src="./img/test.mp4" style="height: 200px;" autoplay controls>',
            maxWidth: 355,
            backgroundColor: "#eee",
            borderColor: "#2db400",
            borderWidth: 1,
            anchorSize: new naver.maps.Size(10, 20),
            anchorSkew: true,
            anchorColor: "#eee",
            pixelOffset: new naver.maps.Point(20, -20)
        });

    markers.push(marker);
    infoWindows.push(infoWindow);
    naver.maps.Event.addListener(markers[markernum], 'click', getClickHandler(markernum));      //엥
    var overlay2 = new CustomOverlay({
        map: map,
        position: pos
    });
    overlay_b[markernum]=overlay2;
    overlay_b[markernum].setMap(null);
    markernum++;
    updateMarkers(map, markers);
    alert(markernum);

    addlist(CameraLocation);        ///test
}

function addvideo(){
  var openWin;
  // window.name = "부모창 이름";
            window.name = "navermapver2";
            // window.open("open할 window", "자식창 이름", "팝업창 옵션");
            openWin = window.open("mapadd2.html",
                    "AddMap", "width=200, height=250, resizable = no, scrollbars = no");

}

function removevideo(){
  if(OnOff==false)
  {
    alert('Camera is not selected');
  }
  else{
    var retVal = confirm("Do you want to remove the camera?");

    if( retVal == true ){
      for (var i=0, ii=markers.length; i<ii; i++) {
              naver.maps.Event.clearInstanceListeners(markers[i],'click');
        //    listeners[i]=naver.maps.Event.addListener(markers[i], 'click', getClickHandler(i));
      }
      //removelist(selectmarker+1);

      markers[selectmarker].setMap(null);
      infoWindows[selectmarker].setMap(null);
      //overlay_b[selectmarker].setMap(null);
      alert(selectmarker);
      //  listeners.splice(selectmarker,1);
      listname.splice(selectmarker,1);
      markers.splice(selectmarker,1);
      infoWindows.splice(selectmarker,1);
      position_b.splice(selectmarker,1);
      overlay_b.splice(selectmarker,1);
      //naver.maps.Event.removeListener(listeners[selectmarker]);
      pnum--;
      markernum--;
      removelist();

      for (var i=0, ii=markers.length; i<ii; i++) {
          addlist(listname[i]);
          naver.maps.Event.addListener(markers[i], 'click', getClickHandler(i));
        //    listeners[i]=naver.maps.Event.addListener(markers[i], 'click', getClickHandler(i));
      }
      updateMarkers(map, markers);
      //alert(overlay_b.length);
      OnOff=false;
    }
  }
}

    //alert(selectmarker);
    // var marker=markers[selectmarker];
    // var infoWindow=infoWindows[selectmarker];
    // var position=markers[position_b];
    // var overlay=markers[overlay_b];
    //var listener=
    //naver.maps.Event.removeListener(listeners[selectmarker]);
    //naver.maps.Event.clearInstanceListeners(markers[selectmarker]);


function removeALLvideo()
{
  var retVal = confirm("Do you want to remove all cameras?");

  if( retVal == true ){

    closevideo()
    if(selectmarker!=999)
      infoWindows[selectmarker].setMap(null);
    for (var i=0, ii=markers.length; i<ii; i++) {
            naver.maps.Event.clearInstanceListeners(markers[i],'click');
            markers[i].setMap(null);

      //    listeners[i]=naver.maps.Event.addListener(markers[i], 'click', getClickHandler(i));
    }
    listname=[];
    markers=[];
    infoWindows=[];
    position_b=[];
    overlay_b=[];
    removelist();
    pnum=0;
    markernum=0;
    updateMarkers(map, markers);

    //alert(overlay_b.length);
    OnOff=false;

  }
}

////new naver.maps.LatLng
/////////////////////////////////////////////////////////////////////
var infoWindow2 = new naver.maps.InfoWindow({
    anchorSkew: true
});

map.setCursor('pointer');

// search by tm128 coordinate
function searchCoordinateToAddress(latlng) {
  OnOff=false;
    var tm128 = naver.maps.TransCoord.fromLatLngToTM128(latlng);

    infoWindow2.close();

    naver.maps.Service.reverseGeocode({
        location: tm128,
        coordType: naver.maps.Service.CoordType.TM128
    }, function(status, response) {
        if (status === naver.maps.Service.Status.ERROR) {
            return alert('Something Wrong!');
        }

        var items = response.result.items,
            htmlAddresses = [];

        for (var i=0, ii=items.length, item, addrType; i<ii; i++) {
            item = items[i];
            addrType = item.isRoadAddress ? '[도로명 주소]' : '[지번 주소]';

            htmlAddresses.push((i+1) +'. '+ addrType +' '+ item.address);
            htmlAddresses.push('&nbsp&nbsp&nbsp -> '+ item.point.x +','+ item.point.y);
        }

        infoWindow2.setContent([
                '<div style="padding:10px;min-width:200px;line-height:150%;">',
                '<h4 style="margin-top:5px;">검색 좌표 : '+ response.result.userquery +'</h4><br />',
                htmlAddresses.join('<br />'),
                '<br/><br/><strong>LatLng</strong> : '+ latlng +'<br/>',
                '</div>'
            ].join('\n'));

        infoWindow2.open(map, latlng);
    });
}

// result by latlng coordinate
/*
function searchAddressToCoordinate(address) {
  OnOff=false;
    naver.maps.Service.geocode({        //////////////////////////
        address: address
    }, function(status, response) {
        if (status === naver.maps.Service.Status.ERROR) {
            return alert('Something Wrong!');
        }

        var item = response.result.items[0],
            addrType = item.isRoadAddress ? '[도로명 주소]' : '[지번 주소]',
            point = new naver.maps.Point(item.point.x, item.point.y);

        infoWindow2.setContent([
                '<div style="padding:10px;min-width:200px;line-height:150%;">',
                '<h4 style="margin-top:5px;">검색 주소 : '+ response.result.userquery +'</h4><br />',
                addrType +' '+ item.address +'<br />',
                '&nbsp&nbsp&nbsp -> '+ point.x +','+ point.y,
                '</div>'
            ].join('\n'));


        map.setCenter(point);
        infoWindow2.open(map, point);
    });
}
*/
function initGeocoder() {
    map.addListener('click', function(e) {
        searchCoordinateToAddress(e.coord);
    });
/*
    $('#address').on('keydown', function(e) {
        var keyCode = e.which;

        if (keyCode === 13) { // Enter Key
            searchAddressToCoordinate($('#address').val());
        }
    });

    $('#submit').on('click', function(e) {
        e.preventDefault();

        searchAddressToCoordinate($('#address').val());
    });
*/
  //  searchAddressToCoordinate('정자동 178-1');
}

naver.maps.onJSContentLoaded = initGeocoder;


</script>

</html>
