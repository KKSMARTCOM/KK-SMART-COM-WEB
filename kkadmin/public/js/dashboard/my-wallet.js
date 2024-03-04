(function($) {
    /* "use strict" */


 var dzChartlist = function(){
	
	var screenWidth = $(window).width();
		
	var activityBar = function(){
		var activity = document.getElementById("activity");
		if (activity !== null) {
			var activityData = [{
				first: [350, 180, 150, 350, 400, 200, 300, 250, 220, 200, 250, 350, 350, 180, 150, 350, 400, 200, 300, 250, 220, 200, 250, 350, 300, 250, 220, 200, 250, 350]
			}];
			activity.height = 320;
			
			var config = {
				type: "bar",
				data: {
					labels: [
						"06",
						"07",
						"08",
						"09",
						"10",
						"11",
						"12",
						"13",
						"14",
						"15",
						"16",
						"17",
						"18",
					],
					datasets: [
						{
							label: "My First dataset",
							data:  [350, 180, 150, 350, 400, 200, 300, 250, 220, 200, 250, 350, 350, 180, 150, 350, 400, 200, 300, 250, 220, 200, 250, 350, 300, 250, 220, 200, 250, 350],
							borderColor: 'rgba(47, 76, 221, 0)',
							borderWidth: "0",
							barThickness:'flex',
							backgroundColor: '#6418C3',
							minBarLength:10
						}
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins : {
					
						legend: {
							display: false
						},
						tooltip: {
							mode: "index",
							intersect: false,
						}
					},
					barPercentage : .3,
					scales: {
						
						y: {
							gridLines: {
								color: "rgba(233,236,255,1)",
								drawBorder: true
							},
							max: 600,
							min: 0,
							ticks: {
								fontColor: "#3e4954",
								stepSize: 200
							},
						},
						x: {
							gridLines: {
								display: false,
								zeroLineColor: "transparent"
							},
							ticks: {
								stepSize: 200,
								fontColor: "#3e4954",
								fontFamily: "Nunito, sans-serif"
							}
						},
					},
					
				}
			};

			var ctx = document.getElementById("activity").getContext("2d");
			var myLine = new Chart(ctx, config);

			var items = document.querySelectorAll("#user-activity .nav-tabs .nav-item");
			items.forEach(function(item, index) {
				item.addEventListener("click", function() {
					config.data.datasets[0].data = activityData[index].first;
					myLine.update();
				});
			});
		}
	}
	/* Function ============ */
		return {
			init:function(){
			},
			
			
			load:function(){	
				activityBar();		
			},
			
			resize:function(){
				
			}
		}
	
	}();
	jQuery(document).ready(function(){
	});
		
	jQuery(window).on('load',function(){
		setTimeout(function(){
			dzChartlist.load();
		}, 1000); 
		
	});

	jQuery(window).on('resize',function(){
		
		
	});     

})(jQuery);