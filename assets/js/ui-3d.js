document.addEventListener("DOMContentLoaded", function () {
	var tiltable = document.querySelectorAll(".tilt-card, .stat-card, .welcome-card, .content .isi-content, .sidebar");
	if (window.VanillaTilt && tiltable.length) {
		tiltable.forEach(function (el) {
			if (el && !el.vanillaTilt) {
				new window.VanillaTilt(el, {
					max: 12,
					speed: 400,
					scale: 1.02,
					glare: true,
					"max-glare": 0.25,
					gyroscope: true,
					perspective: 900
				});
			}
		});
	}

	var floating = document.querySelectorAll("[data-float]");
	if (floating.length) {
		floating.forEach(function (el, index) {
			var amplitude = el.getAttribute("data-float") || 12;
			var delay = index * 0.35;
			el.style.setProperty("--float-distance", amplitude + "px");
			el.style.animation = "appFloat 6s ease-in-out " + delay + "s infinite alternate";
		});
	}
});

// keyframes injected once
(function injectFloatKeyframes() {
	var style = document.createElement("style");
	style.innerHTML = "@keyframes appFloat{0%{transform:translateY(0);}100%{transform:translateY(calc(var(--float-distance, 12px)) );}}";
	document.head.appendChild(style);
})();
