document.addEventListener("DOMContentLoaded", function () {
	$('.nav-menu .nav-link').click(function(e) {
        const isActive = $(this).hasClass('active');
        $(".nav-menu .nav-link").removeClass('active');
        if (!isActive) {
            $(this).addClass('active');
        }
    });

    // Handle clicks outside nav-link
    $(document).mouseup(function(e) {
        var container = $(".nav-menu .nav-link");
        // If the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.removeClass('active');
        }
    });
	
	$(".cultureSlider_outer").slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: false,
		arrows: false,
	});

	$(".culture-prev-button").click(function () {
		$(".cultureSlider_outer").slick("slickPrev");
	});
	$(".culture-next-button").click(function () {
		$(".cultureSlider_outer").slick("slickNext");
	});
	
	$(".company-slider-container").slick({
		slidesToShow: 1,
		infinite: true,
		slidesToScroll: 1,
		arrows: false,
		fade: true,
		asNavFor: ".company-image-slider",
		dots: false,
	});
	$(".company-image-slider").slick({
		slidesToShow: 5,
		infinite: true,
		slidesToScroll: 1,
		asNavFor: ".company-slider-container",
		dots: false,
		arrows: false,
		centerMode: true,
		focusOnSelect: true,
		autoplay: true,
		autoplaySpeed: 3000,
		responsive: [
			{
				breakpoint: 998,
				settings: {
					slidesToShow: 3,
				},
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
				},
			},
			{
				breakpoint: 576,
				settings: {
					slidesToShow: 1,
				},
			},
		],
	});
	// Custom Previous Button
	$(".prev-company-button").click(function () {
		$(".company-image-slider").slick("slickPrev");
	});

	// Custom Next Button
	$(".next-company-button").click(function () {
		$(".company-image-slider").slick("slickNext");
	});
	
	$(".exclusiveSpeakers-slider").slick({
        // centerMode: true,
        // centerPadding: '0',
        // initialSlide: 1,

        dots: false,
        infinite: false,
        slidesToShow:7,
        slidesToScroll: 1,
        arrows: false,
        responsive: [
          {
            breakpoint: 1500,
            settings: {
              slidesToShow: 5,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 4,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
            },
          },

          {
            breakpoint: 768,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 450,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
            },
          },
        ],
      });

      // Custom Previous Button
      $(".prev-button-exclusivespeaker").click(function () {
        $(".exclusiveSpeakers-slider").slick("slickPrev");
      });

      // Custom Next Button
      $(".next-button-exclusivespeaker").click(function () {
        $(".exclusiveSpeakers-slider").slick("slickNext");
      });
	

	// Newroom slider
	// Initialize variables
	var $slider = $(".industrynewsslider");
	var isPaused = true; // Start with paused state

	// Define SVG icons
	const pauseSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15 18q-.402 0-.701-.299T14 17V7q0-.402.299-.701T15 6h1.5q.402 0 .701.299T17.5 7v10q0 .402-.299.701T16.5 18zm-7.5 0q-.402 0-.701-.299T6.5 17V7q0-.402.299-.701T7.5 6H9q.402 0 .701.299T10 7v10q0 .402-.299.701T9 18z"/></svg>`;

	const playSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M8.6 5.2A1 1 0 0 0 7 6v12a1 1 0 0 0 1.6.8l8-6a1 1 0 0 0 0-1.6z" clip-rule="evenodd"/></svg>`;

	// Wait for DOM to be ready
	$(document).ready(function() {
		// Initialize Slick slider
		$slider.slick({
			dots: true,
			infinite: true,
			autoplay: false,
			autoplaySpeed: 2500,
			arrows: false,
			swipeToSlide: true,
		});

		// Add navigation buttons
		$(".industrynewsslider .slick-dots")
		  .before(
			  '<button type="button" class="prev-button-slider p-0 btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m14 7l-5 5l5 5"/></svg></button>'
		  )
		  .after(
			  '<button type="button" class="next-button-slider p-0 btn"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m10 17l5-5l-5-5"/></svg></button>'
		  );

		  // Wrap controls
		  $(".slick-dots, .prev-button-slider, .next-button-slider").wrapAll(
			  '<div class="d-flex align-items-center industrynewsslider-btnarea"></div>'
		  );

		  // Add play/pause button
		  $(".next-button-slider").after(
			  `<button id="autoplay-toggle" class="p-0 btn">${playSvg}</button>`
		  );

		  // Bind click events immediately after adding buttons
		  $('.prev-button-slider').on('click', function() {
			  $slider.slick('slickPrev');
		  });

		  $('.next-button-slider').on('click', function() {
			  $slider.slick('slickNext');
		  });

		  // Direct event binding for play/pause
		  $('#autoplay-toggle').on('click', function() {
			  if (isPaused) {
				  $slider.slick('slickPlay');
				  $(this).html(pauseSvg);
				  isPaused = false;
			  } else {
				  $slider.slick('slickPause');
				  $(this).html(playSvg);
				  isPaused = true;
			  }
		  });
	  });


	//About us brand slider
	$('.brandSlider_inner').slick({
		dots: false,
		autoplay:true,
		autoplaySpeed: 0,
		infinite: true,
		speed: 5000,
		slidesToShow: 12,
		slidesToScroll: 1,
		arrows:false,
		pauseOnHover: false,
		swipe: false,
		draggable: false,
		cssEase: 'linear',
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1
				}
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		]
	});
	$('.brand-slider-conference').slick({
		dots: false,
		autoplay:true,
		autoplaySpeed: 0,
		infinite: true,
		speed: 5000,
		slidesToShow: 13,
		slidesToScroll: 1,
		arrows:false,
		pauseOnHover: false,
		swipe: false,
		draggable: false,
		cssEase: 'linear',
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1
				}
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		]
	});
		
	// Case study Slider
	$(".casestudy-card").slick({
		slidesToShow: 4,
		slidesToScroll: 1,
		dots: false,
		arrows: false,
		inifite:true,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1,
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});

	// Custom Previous Button
	$(".casestudy_prev").click(function () {
		$(".casestudy-card").slick("slickPrev");
	});

	// Custom Next Button
	$(".casestudy_next").click(function () {
		$(".casestudy-card").slick("slickNext");
	});


	// Home Banner Slider
	$(document).ready(function(){
  $('.homebannerSlider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 5000,
    speed: 1000,
    arrows: false,
    dots: true,
    cssEase: 'ease-in-out',
    swipe: true,
    rtl: false // Important: Default is left to right
  });
});
	
	// Home Banner Slider
	/* $(".home-banner-conference").slick({
		slidesToShow: 0,
		slidesToScroll: 0,
		dots: false,
		arrows: false,
		autoplay:false,
	}); */
	
	document.querySelectorAll('.nav-item').forEach(item => {
		const link = item.querySelector('.nav-link');
		const submenu = item.querySelector('.submenu');

		if (submenu) {
			link.addEventListener('click', (e) => {
				if (window.innerWidth <= 768) {
					e.preventDefault();
					e.stopPropagation(); // Prevent event bubbling
					
					// Close all other open submenus
					/* document.querySelectorAll('.nav-item').forEach(otherItem => {
						if (otherItem !== item) {
							otherItem.classList.remove('active');
						}
					}); */

					// Toggle current submenu
					item.classList.toggle('active');
					if(item.getElementsByClassName('nav-link')[0].getElementsByTagName("span")[0].classList.contains('arrow')){
						item.getElementsByClassName('nav-link')[0].getElementsByTagName("span")[0].classList.add('arrow-reverse');
						item.getElementsByClassName('nav-link')[0].getElementsByTagName("span")[0].classList.remove('arrow');
					}else{
						item.getElementsByClassName('nav-link')[0].getElementsByTagName("span")[0].classList.add('arrow');
						item.getElementsByClassName('nav-link')[0].getElementsByTagName("span")[0].classList.remove('arrow-reverse');
					}
				}
			});
		}
	});
});

// const slides = document.querySelectorAll(".become-sponsor-slider-container .slide");
// const slideNumber = document.querySelectorAll(".become-sponsor-slider-container .slide-number");

// function showSlide(index) {
// 	if (index >= slides.length) {
// 		currentSlide = 0;
// 	} else if (index < 0) {
// 		currentSlide = slides.length - 1;
// 	} else {
// 		currentSlide = index;
// 	}

// 	slides.forEach(slide => slide.classList.remove("active"));
// 	slides[currentSlide].classList.add("active");

// 	slideNumber.forEach(num => num.textContent = `${currentSlide + 1} / ${slides.length}`);
// }

// function changeSlide(step) {
// 	showSlide(currentSlide + step);
// }
// ageda tab content
document.addEventListener("DOMContentLoaded", function () {
	const tabs = document.querySelectorAll(".agenda-tab");
	const contents = document.querySelectorAll(".agenda-tab-content");

	tabs.forEach(tab => {
		tab.addEventListener("click", function () {
			// Remove active class from all tabs
			tabs.forEach(t => t.classList.remove("active"));
			this.classList.add("active");

			// Hide all content
			contents.forEach(content => content.classList.remove("active"));

			// Show selected content
			const selectedTab = this.getAttribute("data-tab");
			document.getElementById(selectedTab).classList.add("active");
		});
	});
// Speaker drawer click handler
$(document).on("click", ".speaker-bio", function() {
  let id = $(this).data("id");
  let name = $(this).data("nm");
  let designation = $(this).data("dsgn");
  let bio = $(this).data("bio");
  let image = $(this).data("img");
  let linkedin = $(this).data("linkedin");

  // Update drawer content
  $(".drawer-speaker-image").attr("src", image);
  $(".drawer-speaker-name").text(name);
  $(".drawer-speaker-position").text(designation);
  $(".drawer-speaker-bio").text(bio);

  // Update LinkedIn link
  if (linkedin) {
    $(".drawer-speaker-linkedin").attr("href", linkedin).parent().show();
  } else {
    $(".drawer-speaker-linkedin").attr("href", "#").parent().hide();
  }

  // Show the drawer
  toggleSpeakerDrawer();
});

// Sponsor drawer click handler
$(document).on("click", ".sponsor-bio", function() {
  let id = $(this).data("id");
  let name = $(this).data("nm");
  let designation = $(this).data("dsgn");
  let bio = $(this).data("bio");
  let image = $(this).data("img");
  let linkedin = $(this).data("linkedin");

  // Update drawer content
  $(".drawer-sponsor-image").attr("src", image);
  $(".drawer-sponsor-name").text(name);
  $(".drawer-sponsor-position").text(designation);
  $(".drawer-sponsor-bio").text(bio);

  // Update LinkedIn link
  if (linkedin) {
    $(".drawer-sponsor-linkedin").attr("href", linkedin).parent().show();
  } else {
    $(".drawer-sponsor-linkedin").attr("href", "#").parent().hide();
  }

  // Show the drawer
  toggleSponsorDrawer();
});
});
// Speaker drawer functions
function toggleSpeakerDrawer() {
  const speakerDrawer = document.getElementById('speakerdrawer');
  const overlay = document.getElementById('overlay');
  const body = document.body;
  
  // Close sponsor drawer if open
  document.getElementById('sponsordrawer').classList.remove('open');
  
  const isOpen = speakerDrawer.classList.toggle('open');
  overlay.classList.toggle('active', isOpen);
  body.classList.toggle('no-scroll', isOpen);
}

function closeSpeakerDrawer() {
  const speakerDrawer = document.getElementById('speakerdrawer');
  const overlay = document.getElementById('overlay');
  const body = document.body;
  
  speakerDrawer.classList.remove('open');
  overlay.classList.remove('active');
  body.classList.remove('no-scroll');
}

// Sponsor drawer functions
function toggleSponsorDrawer() {
  const sponsorDrawer = document.getElementById('sponsordrawer');
  const overlay = document.getElementById('overlay');
  const body = document.body;
  
  // Close speaker drawer if open
  document.getElementById('speakerdrawer').classList.remove('open');
  
  const isOpen = sponsorDrawer.classList.toggle('open');
  overlay.classList.toggle('active', isOpen);
  body.classList.toggle('no-scroll', isOpen);
}

function closeSponsorDrawer() {
  const sponsorDrawer = document.getElementById('sponsordrawer');
  const overlay = document.getElementById('overlay');
  const body = document.body;
  
  sponsorDrawer.classList.remove('open');
  overlay.classList.remove('active');
  body.classList.remove('no-scroll');
}

// Close all drawers
function closeAllDrawers() {
  closeSpeakerDrawer();
  closeSponsorDrawer();
}

/* document.addEventListener("DOMContentLoaded", function () {
	const navLinks = document.querySelectorAll("#company-tab .nav-item .nav-link");
	let currentIndex = 0;

	setInterval(() => {
		// Remove 'active' class from all nav-links
		navLinks.forEach(link => link.classList.remove("active"));

		// Add 'active' to current link
		const currentLink = navLinks[currentIndex];
		currentLink.classList.add("active");

		// Activate the corresponding tab content
		const targetTabPaneId = currentLink.getAttribute("data-target");
		document.querySelectorAll(".tab-pane").forEach(pane => {
			pane.classList.remove("active", "show");
		});
		const activePane = document.querySelector(targetTabPaneId);
		if (activePane) {
			activePane.classList.add("active", "show");
		}

		// Move to next tab
		currentIndex = (currentIndex + 1) % navLinks.length;
	}, 3000);
}); */

document.addEventListener("DOMContentLoaded", function () {
	const navLinks = document.querySelectorAll("#company-tab .nav-item .nav-link");
	let currentIndex = 0;

	if (navLinks.length === 0) return; // Exit if no nav links found

	setInterval(() => {
		// Defensive check
		if (!navLinks[currentIndex]) return;

		// Remove 'active' from all links
		navLinks.forEach(link => link.classList.remove("active"));

		// Add 'active' to current link
		const currentLink = navLinks[currentIndex];
		currentLink.classList.add("active");

		// Activate the corresponding tab content
		const targetTabPaneId = currentLink.getAttribute("data-target");
		if (targetTabPaneId) {
			document.querySelectorAll(".tab-pane").forEach(pane => {
				pane.classList.remove("active", "show");
			});
			const activePane = document.querySelector(targetTabPaneId);
			if (activePane) {
				activePane.classList.add("active", "show");
			}
		}

		// Move to next tab
		currentIndex = (currentIndex + 1) % navLinks.length;
	}, 3000);
});

document.addEventListener("DOMContentLoaded", () => {
	// Signup Popup
	const popupOverlay = document.getElementById("popup-overlay");
	//const closeBtn = document.querySelector(".close-popup-btn");
	const showPopupBtn = document.getElementById("show-popup");
	showPopupBtn.addEventListener("click", () => {
		popupOverlay.classList.add("visible");
	});
	/* closeBtn.addEventListener("click", () => {
		event.stopPropagation();
		popupOverlay.classList.remove("visible");
		resetModal();
	}); */
	popupOverlay.addEventListener("click", (event) => {
		if (event.target === popupOverlay) {
			popupOverlay.classList.remove("visible");
		}
	});
	
	

  // Register Interest Popup
  const registerInterestOverlay = document.getElementById("register-interest-overlay");
  const showRegisterInterestBtn = document.getElementById("show-register-interest");

  if (showRegisterInterestBtn && registerInterestOverlay) {
    showRegisterInterestBtn.addEventListener("click", () => {
      registerInterestOverlay.classList.add("visible");
    });

    registerInterestOverlay.addEventListener("click", (event) => {
      if (event.target === registerInterestOverlay) {
        registerInterestOverlay.classList.remove("visible");
      }
    });
  }
	
	//Download Brochure
	const downloadBrochureOverlay = document.getElementById("download-brochure-overlay");
	//const downloadBrochureCloseBtn = document.querySelector(".close-download-brochure-btn");
	const showDownloadBrochureBtns = document.querySelectorAll(".show-download-brochure");
	showDownloadBrochureBtns.forEach((btn) => {
		btn.addEventListener("click", (event) => {
			event.stopPropagation();
			downloadBrochureOverlay.classList.add("visible");
		});
	});
	/* downloadBrochureCloseBtn.addEventListener("click", (event) => {
		event.stopPropagation();
		downloadBrochureOverlay.classList.remove("visible");
		resetModal();
	}); */
	downloadBrochureOverlay.addEventListener("click", (event) => {
		if (event.target === downloadBrochureOverlay) {
			downloadBrochureOverlay.classList.remove("visible");
		}
	});
	
	//Attendee List
	const attendeeListOverlay = document.getElementById("attendee-list-overlay");
	//const attendeeListCloseBtn = document.querySelector(".close-attendee-list-btn");
	const showAttendeeListBtns = document.querySelectorAll(".show-attendee-list");
	showAttendeeListBtns.forEach((btn) => {
		btn.addEventListener("click", (event) => {
			event.stopPropagation();
			attendeeListOverlay.classList.add("visible");
		});
	});
	/* attendeeListCloseBtn.addEventListener("click", (event) => {
		event.stopPropagation();
		attendeeListOverlay.classList.remove("visible");
		resetModal();
	}); */
	attendeeListOverlay.addEventListener("click", (event) => {
		if (event.target === attendeeListOverlay) {
			attendeeListOverlay.classList.remove("visible");
		}
	});
	
	//Media Partnership
	const mediaPartnershipOverlay = document.getElementById("media-partnership-overlay");
	//const mediaPartnershipCloseBtn = document.querySelector(".close-media-partnership-btn");
	const showMediaPartnershipBtns = document.querySelectorAll(".show-media-partnership");
	showMediaPartnershipBtns.forEach((btn) => {
		btn.addEventListener("click", (event) => {
			event.stopPropagation();
			mediaPartnershipOverlay.classList.add("visible");
		});
	});
	/* mediaPartnershipCloseBtn.addEventListener("click", (event) => {
		event.stopPropagation();
		mediaPartnershipOverlay.classList.remove("visible");
		resetModal();
	}); */
	mediaPartnershipOverlay.addEventListener("click", (event) => {
		if (event.target === mediaPartnershipOverlay) {
			mediaPartnershipOverlay.classList.remove("visible");
		}
	});
	
	//Contact Us
	const contactUsOverlay = document.getElementById("contact-us-overlay");
	//const contactUsCloseBtn = document.querySelector(".close-contact-us-btn");
	const showContactUsBtns = document.querySelectorAll(".show-contact-us");
	showContactUsBtns.forEach((btn) => {
		btn.addEventListener("click", (event) => {
			event.stopPropagation();
			contactUsOverlay.classList.add("visible");
		});
	});
	/* contactUsCloseBtn.addEventListener("click", (event) => {
		event.stopPropagation();
		contactUsOverlay.classList.remove("visible");
		resetModal();
	}); */
	contactUsOverlay.addEventListener("click", (event) => {
		if (event.target === contactUsOverlay) {
			contactUsOverlay.classList.remove("visible");
		}
	});
	
	document.querySelectorAll(".common-modal").forEach(modal => {
		const type = modal.dataset.type;
		const conferenceId = document.getElementById("conference-id").value.trim();
		const emailForm = modal.querySelector(".emailForm");
		const detailsForm = modal.querySelector(".detailsForm");
		const step1 = modal.querySelector(".step1");
		const step2 = modal.querySelector(".step2");
		const step3 = modal.querySelector(".step3");
		const finalTitle = modal.querySelector(".finalTitle");
		const finalMessage = modal.querySelector(".finalMessage");
		let attendeeData = {};

		// Step 1: Email
		emailForm.addEventListener("submit", function(e) {
			e.preventDefault();
			const email = emailForm.querySelector("input[name='email']").value.trim();
			if (!validateEmail(email)) {
				alert("Please enter a valid email.");
				return;
			}
			attendeeData = { email, type, conferenceId };
			step1.style.display = "none";
			step2.style.display = "block";
		});

		// Step 2: Details
		detailsForm.addEventListener("submit", function(e) {
			e.preventDefault();
			const data = {
				...attendeeData,
				firstName: detailsForm.querySelector("[name='firstName']").value.trim(),
				lastName: detailsForm.querySelector("[name='lastName']").value.trim(),
				companyName: detailsForm.querySelector("[name='companyName']").value.trim(),
				contactNumber: detailsForm.querySelector("[name='contactNumber']").value.trim()
			};

			// Basic validation
			if (!data.firstName || !data.lastName || !data.contactNumber) {
				showFinal(false);
				return;
			}

			// AJAX submission
			fetch("/submit-data", {
				method: "POST",
				headers: { "Content-Type": "application/json" },
				body: JSON.stringify(data)
			})
			.then(res => res.json())
			.then(result => {
				showFinal(result.success);
			})
			.catch(err => {
				console.error(err);
				showFinal(false);
			});
		});

		// Back Button
		modal.querySelector(".backBtn").addEventListener("click", () => {
			step2.style.display = "none";
			step1.style.display = "block";
		});
		
		// Close & Reset
		modal.querySelector(".close-modal-btn").addEventListener("click", () => {
			modal.classList.remove("visible");
			setTimeout(() => {
			  resetModal();
			}, 2000);
		});
	
		function showFinal(success) {
			step1.style.display = "none";
			step2.style.display = "none";
			step3.style.display = "block";
			if (success) {
				finalTitle.textContent = "Thank you!";
				finalMessage.innerHTML = "Please check your inbox and add <b>@ptnevents.com</b> to your safe sender list.";
			} else {
				finalTitle.textContent = "Oops!";
				finalMessage.textContent = "Something went wrong. Please check your inputs and try again.";
			}
		}
		
		function resetModal() {
			step1.style.display = "block";
			step2.style.display = "none";
			step3.style.display = "none";
			emailForm.reset();
			detailsForm.reset();
			attendeeData = {};
		}
	});
	document.querySelectorAll(".speaking-modal").forEach(modal => {
    const type = modal.dataset.type;
    const conferenceId = document.getElementById("conference-id")?.value.trim() || '';
    const emailForm = modal.querySelector(".emailForm");
    const detailsForm = modal.querySelector(".detailsForm");
    const step1 = modal.querySelector(".step1");
    const step2 = modal.querySelector(".step2");
    const step3 = modal.querySelector(".step3");
    const finalTitle = modal.querySelector(".finalTitle");
    const finalMessage = modal.querySelector(".finalMessage");
    let attendeeData = {};

    // Email validation function
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Step 1: Email
    emailForm.addEventListener("submit", function(e) {
        e.preventDefault();
        const firstName = emailForm.querySelector("input[name='firstName']").value.trim();
        const lastName = emailForm.querySelector("input[name='lastName']").value.trim();
        const email = emailForm.querySelector("input[name='email']").value.trim();
        const contactNumber = emailForm.querySelector("input[name='contactNumber']").value.trim();
        
        if (!validateEmail(email)) {
            showFinal(false, "Please enter a valid email address.");
            return;
        }
        
        attendeeData = { 
            firstName, 
            lastName, 
            email, 
            contactNumber, 
            type: type || '8', 
            conferenceId 
        };
        step1.style.display = "none";
        step2.style.display = "block";
    });

    // Step 2: Details
    detailsForm.addEventListener("submit", function(e) {
        e.preventDefault();
        const data = {
            ...attendeeData,
            companyName: detailsForm.querySelector("[name='companyName']").value.trim(),
            position: detailsForm.querySelector("[name='position']").value.trim(),
            description: detailsForm.querySelector("[name='description']").value.trim()
        };

        console.log("Submitting data:", data); // Debug log

        fetch("/submit-proposal", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest" // Helps identify AJAX requests
            },
            body: JSON.stringify(data)
        })
        .then(async response => {
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `Server error: ${response.status}`);
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                showFinal(true);
            } else {
                showFinal(false, result.message || "Submission failed. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showFinal(false, error.message || "Network error. Please check your connection and try again.");
        });
    });

    // Back Button
    modal.querySelector(".backBtn").addEventListener("click", () => {
        step2.style.display = "none";
        step1.style.display = "block";
    });

    function showFinal(success, message = '') {
        step1.style.display = "none";
        step2.style.display = "none";
        step3.style.display = "block";
        if (success) {
            finalTitle.textContent = "Thank you!";
            finalMessage.innerHTML = "Your proposal has been submitted successfully!";
        } else {
            finalTitle.textContent = "Oops!";
            finalMessage.textContent = message || "Something went wrong. Please check your inputs and try again.";
        }
    }
});

	function validateEmail(email) {
		const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
		return re.test(email);
	}
});