window.onload = function() {
	// Mobile nav menu
	var menu = document.getElementById('menu');
	var close = document.getElementById('close');
	var nav = document.getElementById('nav');

	// Events with IE support
	if (window.addEventListener) {
		menu.addEventListener("click", toggleMenu, false);
		close.addEventListener("click", toggleMenu, false);
	} else { // <= IE8
		menu.attachEvent("onclick", toggleMenu);
		close.addEventListener("click", toggleMenu, false);
	}

	function toggleMenu() {
		toggleClass(menu, 'active');
		toggleClass(close, 'active');
		toggleClass(nav, 'open');
	}

	// Scroll to top
	var top = document.getElementById('top');

	// Events with IE support
	if (window.addEventListener) {
		top.addEventListener("click", scrollToTop, false);
	} else { // <= IE8
		top.attachEvent("onclick", scrollToTop);
	}

	function scrollToTop() {
		var scrollStep = -window.scrollY / (150 / 15),
		scrollInterval = setInterval(function() {
			if ( window.scrollY !== 0 ) {
				window.scrollBy( 0, scrollStep );
			} else {
				clearInterval(scrollInterval);
			}
		}, 15);
	}

	// Dropdowns
	var dropdown = document.getElementById('dropdown');
	if (dropdown) {
		if (window.addEventListener) {
			dropdown.addEventListener("click", toggleDropdown, false);
		} else {
			dropdown.attachEvent("onclick", toggleDropdown);
		}
	}

	function toggleDropdown() {
		toggleClass(this.parentNode, 'open');
		var children = this.childNodes;
		for (var i = 0; i < children.length; i++) {
			if (children[i].tagName === "SPAN") {
				toggleClass(children[i], "icon-angle-up");
				toggleClass(children[i], "icon-angle-down");
			}
		}
	}

	// FAQs
	var faqQuestions = document.getElementsByClassName('question-title');
	for (var i = 0; i < faqQuestions.length; i++) {
		if (window.addEventListener) {
			faqQuestions[i].addEventListener("click", toggleQuestion, false);
		} else {
			faqQuestions[i].attachEvent("onclick", toggleQuestion);
		}
	}

	function toggleQuestion() {
		toggleClass(this.parentNode, 'open');
		var children = this.childNodes;
		for (var i = 0; i < children.length; i++) {
			if (children[i].tagName === "SPAN") {
				toggleClass(children[i], "icon-plus");
				toggleClass(children[i], "icon-times");
			}
		}
	}

	// Videos
	var reordered = false;
	var videoContainer = document.getElementById('videos');
	if (videoContainer) {
		var videos = videoContainer.children.length;
		var videoElements = videoContainer.children;
		var prev = document.getElementById('prev');
		var next = document.getElementById('next');
		if (window.addEventListener) {
			next.addEventListener("click", nextVideo, false);
			prev.addEventListener("click", prevVideo, false);
		} else {
			next.attachEvent("onclick", nextVideo);
			prev.attachEvent("onclick", prevVideo);
		}
	}

	function nextVideo() {
		var currentVideo = getCurrentVideo();
		toggleClass(currentVideo, "hidden");
		toggleClass((currentVideo && currentVideo.nextElementSibling) ? currentVideo.nextElementSibling : videoElements[0], "hidden");
		slideVideos();
	}

	function prevVideo() {
		var currentVideo = getCurrentVideo();
		toggleClass(currentVideo, "hidden");
		toggleClass((currentVideo && currentVideo.previousElementSibling) ? currentVideo.previousElementSibling : videoElements[videos-1], "hidden");
		slideVideos();
	}

	function getCurrentVideo() {
		for (var i = 0; i < videos; i++) {
			if (!hasClass(videoElements[i], "hidden")) {
				return videoElements[i];
			}
		}
		return false;
	}

	function slideVideos() {
		var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

		if (windowWidth >= 768) {
			var currentVideo = 0;
			for (var i = 0; i < videos; i++) {
				if (!hasClass(videoElements[i], "hidden")) {
					currentVideo = i;
				}
			}

			var left = 0;
			if (currentVideo >= 0) {
				left = currentVideo * ((windowWidth >= 1200) ? 27 : 42);
			}
			left = -left + "%";

			for (var i2 = 0; i2 < videos; i2++) {
				videoElements[i2].style.left = left;
			}
		}
	}

	// hasClass with IE support
	function hasClass(element, className) {
		if (element.classList) {
			return element.classList.contains(className);
		}

		return !!element.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'));
	}

	// Toggle class with IE support
	function toggleClass(element, className) {
		if (element.classList) {
			element.classList.toggle(className);
		} else {
			// For <= IE9
			var classes = element.className.split(" ");
			var i = classes.indexOf(className);

			if (i >= 0) {
				classes.splice(i, 1);
			} else {
				classes.push(className);
				element.className = classes.join(" ");
			}
		}
	}

	// Add class with IE support
	function addClass(element, className) {
		if (element.classList) {
			element.classList.add(className);
		} else if (!hasClass(element, className)) {
			element.className += " " + className;
		}
	}

	// Add class with IE support
	function removeClass(element, className) {
		if (element.classList) {
			element.classList.remove(className);
		} else if (hasClass(element, className)) {
			var reg = new RegExp('(\\s|^)' + className + '(\\s|$)');
			element.className = element.className.replace(reg, ' ');
		}
	}

	// Resize window
	if (window.addEventListener) {
		window.addEventListener("resize", resizeWindow, false);
	} else {
		window.attachEvent("onresize", resizeWindow);
	}

	function resizeWindow() {
		// Reorder videos
		if (videoContainer) {
			var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

			// Reorder videos based on width
			if (!reordered && windowWidth >= 768) {
				var videoOrder = [];
				for (var i = 0; i < videos; i++) {
					if (i !== 0 && (i - 1) % 2 === 0) {
						videoOrder.push(i);
					}
				}
	
				videoOrder = videoOrder.reverse();
				for (var i2 = videoOrder.length - 1; i2 >= 0; i2--) {
					videoContainer.insertBefore(videoElements[videoOrder[i2]], videoElements[0]);
				}
	
				reordered = true;
			}
	
			if (reordered && windowWidth < 768) {
				var videoReorder = [];
				for (var i3 = 0; i3 < videos; i3++) {
					for (var i4 = 0; i4 < videos; i4++) {
						if ((i3 + 1) == videoElements[i4].getAttribute('data-order')) {
							videoElements[i4].style.left = 0;
							videoReorder.push(videoElements[i4]);
						}
					}
				}
				
				// Remove everything
				videoContainer.innerHTML = "";
				
				// Make new order
				for (var i5 = 0; i5 < videoReorder.length; i5++) {
					videoContainer.append(videoReorder[i5]);
				}
	
				reordered = false;
			}
	
			slideVideos();
		}
	}

	resizeWindow();

	// Add next/previous element sibling in IE8
	if (!("nextElementSibling" in document.documentElement)){
		Object.defineProperty(Element.prototype, "nextElementSibling", {
			get: function(){
				var e = this.nextSibling;
				while(e && 1 !== e.nodeType)
						e = e.nextSibling;
				return e;
			}
		});
	}

	if (!("previousElementSibling" in document.documentElement)){
		Object.defineProperty(Element.prototype, "previousElementSibling", {
			get: function(){
				var e = this.previousSibling;
				while(e && 1 !== e.nodeType)
					e = e.previousSibling;
				return e;
			}
		});
	}

	// Scroll to elements, anchor links
	var anchorLinks = document.getElementsByClassName('anchor');
	for (var i2 = 0; i2 < anchorLinks.length; i2++) {
		if (window.addEventListener) {
			anchorLinks[i2].addEventListener("click", scrollLink, false);
		} else {
			anchorLinks[i2].attachEvent("onclick", scrollLink);
		}
	}

	function scrollLink(event) {
		var anchor = this.getAttribute("href").replace('#','');
		scrollToElementID(anchor);
		event.preventDefault();
		return false;
	}

	function currentYPosition() {
		if (self.pageYOffset) {
			return self.pageYOffset;
		}

		// Internet Explorer <= 8
		if (document.body.scrollTop) {
			return document.body.scrollTop;
		}

		return 0;
	}

	function elmYPosition(eID) {
		var elm = document.getElementById(eID);
		var y = elm.offsetTop;
		var node = elm;
		while (node.offsetParent && node.offsetParent != document.body) {
			node = node.offsetParent;
			y += node.offsetTop;
		}
		return y;
	}

	function scrollToElementID(eID) {
		var startY = currentYPosition();
		var stopY = elmYPosition(eID);
		var distance = stopY > startY ? stopY - startY : startY - stopY;
		if (distance < 100) {
			scrollTo(0, stopY); return;
		}
		var speed = Math.round(distance / 100);
		if (speed >= 20) speed = 20;
		var step = Math.round(distance / 25);
		var leapY = stopY > startY ? startY + step : startY - step;
		var timer = 0;
		if (stopY > startY) {
			for ( var i = startY; i < stopY; i += step ) {
				setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
				leapY += step; if (leapY > stopY) leapY = stopY; timer++;
			} return;
		}
		for ( var i2 = startY; i2 > stopY; i2 -= step ) {
			setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
			leapY -= step; if (leapY < stopY) leapY = stopY; timer++;
		}
		return false;
	}
};


