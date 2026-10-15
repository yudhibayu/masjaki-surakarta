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
