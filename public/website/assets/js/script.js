//    <!-- Home page Script -->
$(document).ready(function () {
  // Experience slider
  $(".upcomingConferences-slider").slick({
    dots: false,
    infinite: false,
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: false,
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 500,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  // Custom Previous Button
  $(".prev-button").click(function () {
    $(".upcomingConferences-slider").slick("slickPrev");
  });

  // Custom Next Button
  $(".next-button").click(function () {
    $(".upcomingConferences-slider").slick("slickNext");
  });

  //Recente Event
  $(".upcomingConferences-slider-recentevent").slick({
    dots: false,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    centerMode: true,
    centerPadding: "0",
    initialSlide: 0,
    arrows: false,
    edgeFriction: 0.15,
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  // Custom Previous Button
  $(".prev-button-recentevent").click(function () {
    $(".upcomingConferences-slider-recentevent").slick("slickPrev");
  });

  // Custom Next Button
  $(".next-button-recentevent").click(function () {
    $(".upcomingConferences-slider-recentevent").slick("slickNext");
  });
});

const body = document.body;
const mobileToggle = document.querySelector(".mobile-toggle");
const navMenu = document.querySelector(".nav-menu");

mobileToggle.addEventListener("click", () => {
  mobileToggle.classList.toggle("active");
  navMenu.classList.toggle("active");
  body.classList.toggle("menu-open");
});

document.querySelectorAll(".submenu-item").forEach((item) => {
  const link = item.querySelector(".submenu-link");
  const superSubmenu = item.querySelector(".super-submenu");

  if (superSubmenu) {
    link.addEventListener("click", (e) => {
      if (window.innerWidth <= 768) {
        e.preventDefault();
        e.stopPropagation();
        item.classList.toggle("active");
        if (
          item
            .getElementsByClassName("submenu-link")[0]
            .getElementsByTagName("span")[0]
            .classList.contains("arrow")
        ) {
          item
            .getElementsByClassName("submenu-link")[0]
            .getElementsByTagName("span")[0]
            .classList.add("arrow-reverse");
          item
            .getElementsByClassName("submenu-link")[0]
            .getElementsByTagName("span")[0]
            .classList.remove("arrow");
        } else {
          item
            .getElementsByClassName("submenu-link")[0]
            .getElementsByTagName("span")[0]
            .classList.add("arrow");
          item
            .getElementsByClassName("submenu-link")[0]
            .getElementsByTagName("span")[0]
            .classList.remove("arrow-reverse");
        }
      }
    });
  }
});

// Close menu and reset when screen size changes
window.addEventListener("resize", () => {
  if (window.innerWidth > 768) {
    navMenu.classList.remove("active");
    body.classList.remove("menu-open");
    document.querySelectorAll(".nav-item, .submenu-item").forEach((item) => {
      item.classList.remove("active");
    });
  }
});
const carouselItems = document.querySelectorAll(".carousel-item");
const logoItems = document.querySelectorAll(".partners-logo-item");

function showCarouselItem(index) {
  carouselItems.forEach((item, i) => {
    item.classList.toggle("active", i === index);
  });
  logoItems.forEach((logo, i) => {
    logo.classList.toggle("active", i === index);
  });
}

logoItems.forEach((logo, index) => {
  logo.addEventListener("click", () => showCarouselItem(index));
});

// Auto Rotate Logos
let currentIndex = 0;
setInterval(() => {
  currentIndex = (currentIndex + 1) % carouselItems.length;
  showCarouselItem(currentIndex);
}, 10000); // Change interval time as needed

function showCarouselItem(index) {
  carouselItems.forEach((item, i) => {
    item.classList.toggle("active", i === index);

    // Reset animation for text-content
    const textContent = item.querySelector(".text-content");
    if (textContent) {
      textContent.style.opacity = "0"; // Reset opacity
      textContent.style.animation = "none"; // Remove any existing animation
      setTimeout(() => {
        textContent.style.animation = "fadeInLeft 0.6s ease forwards";
      }, 50); // Small delay to reapply animation
    }

    // Reset animation for team-image
    const teamImage = item.querySelector(".team-image");
    if (teamImage) {
      teamImage.style.opacity = "0"; // Reset opacity
      teamImage.style.animation = "none"; // Remove any existing animation
      setTimeout(() => {
        teamImage.style.animation = "fadeInRight 0.6s ease forwards";
      }, 50); // Small delay to reapply animation
    }
  });

  logoItems.forEach((logo, i) => {
    logo.classList.toggle("active", i === index);
  });
}
"undefined" === typeof _trfq || (window._trfq = []);
"undefined" === typeof _trfd && (window._trfd = []),
  _trfd.push(
    { "tccl.baseHost": "secureserver.net" },
    { ap: "cpsh-oh" },
    { server: "sg2plzcpnl506721" },
    { dcenter: "sg2" },
    { cp_id: "10091084" },
    { cp_cl: "8" }
  ); // Monitoring performance to make your website faster. If you want to opt-out, please contact web hosting support.

document.addEventListener("DOMContentLoaded", function () {
  // Define all searchable items
  const searchItems = [
    {
      title: "Account & billing issues",
      description: "Questions about invoices, payments, or account access",
      action:
        "mailto:accounts@ptnevents.com?cc=info@ptnevents.com,heema.solanki@ptnevents.com&subject=Questions%20on%20recent%20purchase&body=Please-mention-your-billing-related-issues-here",
      category: "Support",
    },
    {
      title: "Conference questions",
      description:
        "Information about upcoming events, schedules, or registration",
      action:
        "mailto:info@ptnevents.com?cc=henry.stewart@ptnevents.com,alkesh.parmar@ptnevents.com&subject=We%20want%20more%20info%20about%20upcoming%20events&body=We-are-looking-for",
      category: "Events",
    },
    {
      title: "General corporate questions",
      description: "Inquiries about our company, services, or partnerships",
      action:
        "mailto:info@ptnevents.com?cc=chris.lee@ptnevents.com,sanket@ptnevents.com&subject=More%20information%20need%20on&body=We-are-looking-for",
      category: "Corporate",
    },
    {
      title: "Explore upcoming conferences",
      description: "Browse our calendar of events and conferences",
      action: "/conferences",
      category: "Events",
    },
    {
      title: "Help Center",
      description: "Connect for business related matters",
      action:
        "mailto:henry.stewart@ptnevents.com?cc=info@ptnevents.com, ryan.murphy@ptnevents.com&subject=Connecting%20for%20business%20related%20matters&body=We-would-like-to-explore-following-options-for",
      category: "Support",
    },
    {
      title: "Partnership Inquiry",
      description: "Connect with Global Partnership team",
      action:
        "mailto:chris.lee@ptnevents.com?cc=info@ptnevents.com, sanket@ptnevents.com&subject=Inquiring%20about%20potential%20partnership%20at%20PTNEvents&body=We-would-like-to-explore-partnership-opportunities-for",
      category: "Corporate",
    },
    {
      title: "Marketing and Advertisement Support",
      description: "Talk to our Marketing Team",
      action:
        "mailto:info@ptnevents.com?cc=chris.lee@ptnevents.com, sarah.jones@ptnevents.com&subject=Marketing%20and%20Advertisement%20Support&body=We-are-looking-for",
      category: "Marketing",
    },
  ];

  const searchInput = document.getElementById("search-input");
  const suggestions = document.getElementById("search-suggestions");
  let timer;

  // Highlight matching text
  function highlightText(text, query) {
    if (!query) return text;
    const regex = new RegExp(query, "gi");
    return text.replace(
      regex,
      (match) => `<span class="highlight">${match}</span>`
    );
  }

  // Handle search input
  searchInput.addEventListener("input", function () {
    clearTimeout(timer);
    const query = this.value.trim().toLowerCase();

    if (query.length >= 2) {
      timer = setTimeout(() => {
        const matches = searchItems.filter((item) => {
          return (
            item.title.toLowerCase().includes(query) ||
            item.description.toLowerCase().includes(query) ||
            item.category.toLowerCase().includes(query)
          );
        });

        if (matches.length > 0) {
          suggestions.innerHTML = "";
          matches.forEach((item) => {
            const suggestion = document.createElement("div");
            suggestion.className = "search-suggestion-item";
            suggestion.innerHTML = `
                            <div><strong class="a-ff-rh a-fs-15 a-fw-800">${highlightText(
                              item.title,
                              query
                            )}</strong></div>
                            <div class="a-ff-ss a-fs-15 a-fw-400">${highlightText(
                              item.description,
                              query
                            )}</div>
                        `;
            suggestion.addEventListener("click", () => {
              if (item.action.startsWith("mailto:")) {
                window.location.href = item.action;
              } else {
                window.location.href = item.action;
              }
            });
            suggestions.appendChild(suggestion);
          });
          suggestions.style.display = "block";
        } else {
          suggestions.style.display = "none";
        }
      }, 300);
    } else {
      suggestions.style.display = "none";
    }
  });

  // Hide suggestions when clicking outside
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".search-container")) {
      suggestions.style.display = "none";
    }
  });

  // Handle Enter key to go to first suggestion
  searchInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      const firstSuggestion = suggestions.querySelector(
        ".search-suggestion-item"
      );
      if (firstSuggestion) {
        firstSuggestion.click();
      }
    }
  });
});

// <!-- Home page Script -->
$(document).ready(function () {
  // Experience slider
  $(".upcomingConferences-slider").slick({
    dots: false,
    infinite: false,
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: false,
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
        },
      },

      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 500,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  // Custom Previous Button
  $(".prev-button").click(function () {
    $(".upcomingConferences-slider").slick("slickPrev");
  });

  // Custom Next Button
  $(".next-button").click(function () {
    $(".upcomingConferences-slider").slick("slickNext");
  });

  //Recente Event
  $(".upcomingConferences-slider-recentevent").slick({
    dots: false,
    infinite: true,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 1,
    centerMode: true,
    centerPadding: "0",
    initialSlide: 0,
    arrows: false,
    edgeFriction: 0.15,
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  });

  // Custom Previous Button
  $(".prev-button-recentevent").click(function () {
    $(".upcomingConferences-slider-recentevent").slick("slickPrev");
  });

  // Custom Next Button
  $(".next-button-recentevent").click(function () {
    $(".upcomingConferences-slider-recentevent").slick("slickNext");
  });
});

/**
 * Handles the event filter dropdown changes
 *
 * When the filter is changed between "Upcoming" and "Past" events,
 * this script makes an AJAX call to reload the conference lists
 * without refreshing the entire page.
 */
document.getElementById("eventFilter").addEventListener("change", function () {
  const filter = this.value; // Get selected value ('upcoming' or 'past')

  // Make AJAX POST request to current page
  fetch(window.location.href, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      "X-CSRF-Token":
        "CeuZOpDLG98PCW9Hp8FTPi2B8GE2eC2wvRU0cBFmNxRqhO9z6q96uEZBJjfVlQBubraAIg8gR8jLY0RDdQlDZQ==", // CSRF protection
    },
    body: `filter=${encodeURIComponent(filter)}`, // Send filter parameter
  })
    .then((res) => res.text()) // Get response as HTML
    .then((html) => {
      // Create temporary DOM element to parse response
      const tempDOM = document.createElement("div");
      tempDOM.innerHTML = html;

      // Update all conference lists (all tabs, not just active one)
      ["oilandgasevent", "supplychainevent", "renewableenergy"].forEach(
        (id) => {
          const newEvents = tempDOM.querySelector(`#${id} .event-content`);
          if (newEvents) {
            document.querySelector(`#${id} .event-content`).innerHTML =
              newEvents.innerHTML;
          }
        }
      );
    })
    .catch((err) => console.error("Filter load error:", err)); // Error handling
});




      function showModal(title, message, callback = null) {
        const modal = document.getElementById("responseModal");
        const modalTitle = document.getElementById("modalTitle");
        const modalMessage = document.getElementById("modalMessage");
        const modalOkBtn = document.getElementById("modal-ok-btn");

        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modal.style.display = "block";

        const newBtn = modalOkBtn.cloneNode(true);
        modalOkBtn.parentNode.replaceChild(newBtn, modalOkBtn);

        newBtn.onclick = () => {
          modal.style.display = "none";
          if (typeof callback === "function") {
            callback();
          }
        };
      }

      document
        .getElementById("jobApplicationForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const form = e.target;
          const formData = new FormData(form);
          const file = document.getElementById("cv-upload").files[0];

          if (file && file.size > 2 * 1024 * 1024) {
            showModal("Error", "File size must be less than 2MB.");
            return;
          }

          document.getElementById("loader").style.display = "block";

          fetch(form.action, {
            method: "POST",
            body: formData,
          })
            .then((response) => response.json())
            .then((result) => {
              document.getElementById("loader").style.display = "none";
              if (result.success) {
                showModal(
                  "Thank you!",
                  result.message || "Application submitted successfully.",
                  () => {
                    window.location.href = "/careers";
                  }
                );
              } else {
                showModal("Error", result.message || "Something went wrong.");
              }
            })
            .catch((error) => {
              document.getElementById("loader").style.display = "none";
              showModal("Error", "An error occurred. Please try again.");
              console.error(error);
            });
        });

      document.addEventListener("DOMContentLoaded", function () {
        const fileInput = document.getElementById("cv-upload");
        const fileNameSpan = document.getElementById("file-name");
        const uploadIcon = document.getElementById("upload-icon");
        const deleteBtn = document.getElementById("delete-btn");

        // Handle file selection
        fileInput.addEventListener("change", function () {
          if (this.files && this.files.length > 0) {
            fileNameSpan.textContent = this.files[0].name;
            deleteBtn.classList.remove("inactive");
            deleteBtn.classList.add("active");
          }
        });

        // Handle delete button click
        deleteBtn.addEventListener("click", function () {
          if (!this.classList.contains("inactive")) {
            fileInput.value = "";
            fileNameSpan.textContent = "Attach your CV";
            uploadIcon.textContent = "+";
            deleteBtn.classList.remove("active");
            deleteBtn.classList.add("inactive");
          }
        });
      });
      document
        .getElementById("cv-upload")
        .addEventListener("change", function () {
          const file = this.files[0];
          const maxSize = 2 * 1024 * 1024; // 2MB

          if (file && file.size > maxSize) {
            this.value = "";
            showModal("File Too Large", "File size must be less than 2MB.");
          }
        });
      function copyToClipboard() {
        const dummy = document.createElement("input");
        const text = window.location.href;
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);

        // Show custom modal instead of alert
        const modal = document.getElementById("custom-modal");
        modal.style.display = "flex";

        // Close modal when OK button is clicked
        document
          .querySelector(".modal-close")
          .addEventListener("click", function () {
            modal.style.display = "none";
          });

        // Close modal when clicking outside the content
        modal.addEventListener("click", function (e) {
          if (e.target === modal) {
            modal.style.display = "none";
          }
        });
      }