var map = L.map('map');
map.setView([-7.569139167352268, 110.82969055581445], 13);
map.setMaxBounds([
    [7.534094278914132, 142.44039477106142],
    [-10.435766018932203, 94.65812673930749]
]);

//icons
var baseIcon = L.Icon.extend({
    options: {
        iconSize: [32, 32],
        // iconAnchor: [-16, -16],
        // popupAnchor: [-3, -76],
        shadowUrl: '/images/icon_map/marker-shadow.png',
        shadowSize: [32, 32],
        shadowAnchor: [8, 16]
    }
});

let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; OpenStreetMap contributors'
});

let googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});

let googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});

let googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});

let googleTerrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});

let baseMaps = {
    "Open Street Map": osm,
    "Google Streets": googleStreets,
    "Google Hybrid": googleHybrid,
    "Google Satelite": googleSat,
    "Google Terrain": googleTerrain,
}

// let layerControl = L.control.layers(baseMaps).addTo(map);
map.addLayer(osm);
// map.addLayer(googleHybrid);

let geoJsonData = L.geoJSON().addTo(map);

// var marker = L.marker([-7.559209,110.8188121]).addTo(map);

var sidebar = L.control.sidebar('sidebar', { position: 'right' }).addTo(map);

let getShowData = () => {
    let form = document.getElementById("filter-form");
    let formdata = new FormData(form);
    // console.log('Serialized data:',formdata);
    // geoJsonData.clearLayers();
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Typical action to be performed when the document is ready:
            let data = xhttp.responseText;
            let jsondata = JSON.parse(data);
            // console.log(jsondata);
            geoJsonData.clearLayers();

            let newdata = L.geoJSON(jsondata, {
                style: function (feature) {
                    return { color: feature.properties.color };
                },
                pointToLayer: function (feature, latlng) {
                    if (feature.properties.icon != 'marker-icon.png') {
                        let newicon = new baseIcon({
                            iconUrl: feature.properties.iconimage,
                        });
                        let marker = L.marker(latlng, {
                            icon: newicon,
                        })
                        return marker;
                    }
                    let marker = L.marker(latlng);
                    return marker;
                },
                onEachFeature: function (feature, layer) {
                    // console.log(layer);
                    layer.bindPopup('<a href="#" onclick=showTask("' + feature.properties.taskid + '") class="show-task-popup" data-bs-toggle="modal" data-bs-target="#taskModal" data-taskid="' + feature.properties.taskid + '">' + feature.properties.taskname + '</a>');
                    return layer;
                },
                smoothFactor: 0.0001,
            });

            geoJsonData.addLayer(newdata);
        }
    };
    xhttp.open("POST", "/front-map/default/show", true);
    xhttp.send(formdata);
}

document.addEventListener("DOMContentLoaded", function (event) {
    getShowData();
});

const send = document.querySelector("#submit-filter-data");
const sender = document.querySelectorAll(".map-filter-data");
for (let j = 0; j < sender.length; j++) {
    let element = sender[j];
    element.addEventListener('click', async => getShowData());
}
send.addEventListener('click', async => getShowData());

function showTask(taskid) {
    let xhttp = new XMLHttpRequest();
    let url = '/front-map/default/task-data';
    let form = document.getElementById("task-form");
    let formdata = new FormData(form);
    formdata.append("taskid", taskid);
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Typical action to be performed when the document is ready:
            // let data = xhttp.response;
            // console.log(data);
            document.getElementById('target-modal-body').innerHTML = xhttp.responseText;
            let renderid = document.getElementById('scurveid').innerHTML;
            let weeksdata = JSON.parse(document.getElementById('weeksdata').innerHTML);
            let plansdata = JSON.parse(document.getElementById('plansdata').innerHTML);
            let realisations = JSON.parse(document.getElementById('realisationsdata').innerHTML);
            showSCurve(renderid, weeksdata, plansdata, realisations);
        }
    };
    xhttp.open("POST", url, true);
    xhttp.send(formdata);
}

function showSCurve(renderid, weeks, plans, realisations) {
    Highcharts.setOptions([]); new Highcharts.chart(renderid,
        {
            "chart": {
                "renderTo": renderid
            },
            "credits": {
                "enabled": false
            },
            "title": {
                "text": "Kurva S",
                "align": "center"
            },
            "subtitle": {
                "text": "Total Data : ",
                "align": "center"
            },
            "xAxis": {
                "categories": weeks,
                "title": {
                    "text": "Minggu ke-"
                }
            },
            "yAxis": {
                "title": {
                    "text": "Progress (%)"
                }
            },
            "tooltip": {
                "pointFormat": "{series.name}: <b>{point.y}</b>%"
            },
            "accessibility": {
                "point": {
                    "valueSuffix": "%"
                }
            },
            "plotOptions": {
                "pie": {
                    "allowPointSelect": true,
                    "cursor": "pointer",
                    "dataLabels": {
                        "enabled": true,
                        "format": "<b>{point.name}</b>: {point.y}%"
                    }
                }
            },
            "series": [
                {
                    "name": "Target",
                    "data": plans
                },
                {
                    "name": "Realisasi",
                    "data": realisations
                }
            ]
        });
}

const osmmap = document.querySelector("#osm-map");

osmmap.addEventListener('click', function () {
    map.removeLayer(osm);
    map.removeLayer(googleStreets);
    map.removeLayer(googleSat);
    map.removeLayer(googleTerrain);
    map.addLayer(osm);
});
