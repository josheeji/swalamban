// onlineseva-carousel

$('.onlineseva-carousel').owlCarousel({
	autoplay: true,
	autoplayTimeout: 3000,
	smartSpeed: 1000,
	autoplayHoverPause: true,
	loop: true,
	margin: 15,
	responsiveClass: true,
	responsive: {
		0: {
			items: 2,
			nav: true
		},
		480: {
			items: 3,
			nav: false
		},
		770: {
			items: 5,
			nav: false
		},
		1000: {
			items: 7,
			nav: true,
			
		}
	}
})
// Onlineseva Carousel End

// News Carousel
$('.news-carousel').owlCarousel({
	autoplay: true,
	autoplayTimeout: 3000,
	smartSpeed: 1000,
	autoplayHoverPause: true,
	loop: true,
	margin: 5,
	responsiveClass: true,
	responsive: {
		0: {
			items: 1,
			nav: true
		},
		480: {
			items: 1,
			nav: false
		},
		770: {
			items: 2,
			nav: false
		},
		1000: {
			items: 3,
			nav: true,
			loop: false
		}
	}
})
// News Carousel End  

// Pie Chart
var dom = document.getElementById('data-chart');
var myChart = echarts.init(dom, null, {
renderer: 'canvas',
useDirtyRect: false
});
var app = {};

var option;

option = {
title: {
	text: '',
	subtext: '',
	left: 'center'
},
tooltip: {
	trigger: 'item'
},
legend: {
	orient: 'vertical',
	left: 'left'
},
series: [{
	name: 'Access From',
	type: 'pie',
	radius: '50%',
	data: [{
		value: 1048,
		name: 'Market Share'
	}, {
		value: 735,
		name: 'Solvency Ratio'
	}, {
		value: 580,
		name: 'Status'
	}, {
		value: 484,
		name: 'Earnings'
	}, {
		value: 300,
		name: 'Footprints'
	}],
	emphasis: {
		itemStyle: {
			shadowBlur: 10,
			shadowOffsetX: 0,
			shadowColor: 'rgba(0, 0, 0, 0.5)'
		}
	}
}]
};

if (option && typeof option === 'object') {
myChart.setOption(option);
}

window.addEventListener('resize', myChart.resize);
// Pie Chart End

// Search 
$(".search-btn").click(function() {
$(".searchwrapper").addClass("active");
$(this).css("display", "none");
$(".search-data").fadeIn(500);
$(".close-btn").fadeIn(500);
$(".search-data .line").addClass("active");
setTimeout(function() {
	$("input").focus();
	$(".search-data label").fadeIn(500);
	$(".search-data span").fadeIn(500);
}, 800);
});
$(".close-btn").click(function() {
$(".searchwrapper").removeClass("active");
$(".search-btn").fadeIn(800);
$(".search-data").fadeOut(500);
$(".close-btn").fadeOut(500);
$(".search-data .line").removeClass("active");
$("input").val("");
$(".search-data label").fadeOut(500);
$(".search-data span").fadeOut(500);
});



var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-36251023-1']);
_gaq.push(['_setDomainName', 'jqueryscript.net']);
_gaq.push(['_trackPageview']);

(function() {
var ga = document.createElement('script');
ga.type = 'text/javascript';
ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(ga, s);
})();
// Search End

// Gallery
$(document).ready(function() {
	$('.view').magnificPopup({
		type:'image'});
  });

  

// $(document).ready(function() {

// $('.view').magnificPopup({
// type: 'image',
// closeOnContentClick: true,
// mainClass: 'mfp-img-mobile',
// image: {
// verticalFit: true
// }

// })
// });






// Gallery End










