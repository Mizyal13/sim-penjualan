(function (window, document) {
	"use strict";

	var DEFAULT_OPTIONS = {
		type: "info",
		title: null,
		message: "",
		duration: 4000,
		dismissible: true
	};

	var ICON_MAP = {
		success: "✔",
		error: "✖",
		warning: "⚠",
		info: "ℹ"
	};

	var TITLE_MAP = {
		success: "Berhasil",
		error: "Terjadi Kesalahan",
		warning: "Perhatian",
		info: "Informasi"
	};

	var VALID_TYPES = ["success", "error", "warning", "info"];
	var CONTAINER_ID = "app-toast-container";

	function ensureContainer() {
		var existing = document.getElementById(CONTAINER_ID);
		if (existing) {
			return existing;
		}
		var container = document.createElement("div");
		container.id = CONTAINER_ID;
		container.className = "app-toast-container";
		document.body.appendChild(container);
		return container;
	}

	function detectType(message) {
		if (!message) {
			return "info";
		}
		var lower = String(message).toLowerCase();
		if (lower.search(/gagal|error|invalid|tidak/i) !== -1) {
			return "error";
		}
		if (lower.search(/berhasil|sukses|tersimpan|diubah|dihapus/i) !== -1) {
			return "success";
		}
		if (lower.search(/warning|peringatan|hati-hati|periksa/i) !== -1) {
			return "warning";
		}
		return "info";
	}

	function normaliseOptions(messageOrOptions, type, overrides) {
		var options;
		if (typeof messageOrOptions === "string") {
			options = { message: messageOrOptions };
			if (typeof type === "string") {
				options.type = type;
			} else if (type && typeof type === "object") {
				options = Object.assign(options, type);
			}
			if (overrides && typeof overrides === "object") {
				options = Object.assign(options, overrides);
			}
		} else if (messageOrOptions && typeof messageOrOptions === "object") {
			options = Object.assign({}, messageOrOptions);
		} else {
			options = { message: "" };
		}

		options = Object.assign({}, DEFAULT_OPTIONS, options);

		if (!options.type || VALID_TYPES.indexOf(options.type) === -1) {
			options.type = detectType(options.message);
		}

		if (!options.title) {
			options.title = TITLE_MAP[options.type] || TITLE_MAP.info;
		}

		if (typeof options.duration !== "number" || options.duration < 0) {
			options.duration = DEFAULT_OPTIONS.duration;
		}

		return options;
	}

	function createToastElement(options) {
		var toast = document.createElement("div");
		toast.className = "app-toast app-toast--" + options.type;

		var icon = document.createElement("div");
		icon.className = "app-toast__icon";
		icon.setAttribute("aria-hidden", "true");
		icon.textContent = ICON_MAP[options.type] || ICON_MAP.info;

		var content = document.createElement("div");
		content.className = "app-toast__content";

		if (options.title) {
			var title = document.createElement("p");
			title.className = "app-toast__title";
			title.textContent = options.title;
			content.appendChild(title);
		}

		if (options.message) {
			var message = document.createElement("p");
			message.className = "app-toast__message";
			message.textContent = options.message;
			content.appendChild(message);
		}

		toast.appendChild(icon);
		toast.appendChild(content);

		var closeBtn = null;
		if (options.dismissible !== false) {
			closeBtn = document.createElement("button");
			closeBtn.type = "button";
			closeBtn.className = "app-toast__close";
			closeBtn.setAttribute("aria-label", "Close notification");
			closeBtn.innerHTML = "&times;";
			toast.appendChild(closeBtn);
		}

		var progress = null;
		var progressBar = null;

		if (options.duration > 0) {
			progress = document.createElement("div");
			progress.className = "app-toast__progress";

			progressBar = document.createElement("div");
			progressBar.className = "app-toast__progress-bar";
			progressBar.style.transform = "scaleX(1)";

			progress.appendChild(progressBar);
			content.appendChild(progress);
		}

		return {
			element: toast,
			closeButton: closeBtn,
			progressBar: progressBar
		};
	}

	function scheduleRemoval(toastEl, options) {
		var hideDelay = 350;
		toastEl.classList.remove("app-toast--visible");
		setTimeout(function () {
			if (toastEl && toastEl.parentNode) {
				toastEl.parentNode.removeChild(toastEl);
			}
		}, hideDelay);
	}

	function show(messageOrOptions, type, overrides) {
		if (typeof window === "undefined" || typeof document === "undefined") {
			return;
		}

		var options = normaliseOptions(messageOrOptions, type, overrides);
		var container = ensureContainer();

		// Keep container tidy: limit to 4 notifications
		while (container.children.length >= 4) {
			container.removeChild(container.firstChild);
		}

		var fragments = createToastElement(options);
		var toastEl = fragments.element;

		container.appendChild(toastEl);

		// Force reflow before adding visible class
		requestAnimationFrame(function () {
			toastEl.classList.add("app-toast--visible");
			if (fragments.progressBar && options.duration > 0) {
				fragments.progressBar.style.transition = "transform " + options.duration + "ms linear";
				requestAnimationFrame(function () {
					fragments.progressBar.style.transform = "scaleX(0)";
				});
			}
		});

		var removeTimeout = null;
		if (options.duration > 0) {
			removeTimeout = setTimeout(function () {
				scheduleRemoval(toastEl, options);
			}, options.duration + 350);
		}

		function manualClose() {
			if (removeTimeout) {
				clearTimeout(removeTimeout);
			}
			scheduleRemoval(toastEl, options);
		}

		if (fragments.closeButton) {
			fragments.closeButton.addEventListener("click", manualClose);
		}

		return {
			close: manualClose
		};
	}

	function closeAll() {
		var container = document.getElementById(CONTAINER_ID);
		if (!container) {
			return;
		}
		Array.prototype.slice.call(container.children).forEach(function (toast) {
			toast.classList.remove("app-toast--visible");
			setTimeout(function () {
				if (toast && toast.parentNode) {
					toast.parentNode.removeChild(toast);
				}
			}, 350);
		});
	}

	window.AppToast = {
		show: show,
		closeAll: closeAll,
		detectType: detectType
	};

	if (window.__pendingAppToast) {
		var pending = window.__pendingAppToast;
		delete window.__pendingAppToast;
		setTimeout(function(){
			try{
				show(pending);
			}catch(err){
				console.error('Gagal menampilkan toast tertunda:', err);
			}
		}, 10);
	}

	// Backward compatibility helpers
	window.showToast = show;
	window.showNotification = function (message, type, overrides) {
		return show(message, type, overrides);
	};

})(window, document);
