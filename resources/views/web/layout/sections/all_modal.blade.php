  <!-- modals -->
    <div id="popup-overlay" class="overlay common-modal" data-type="1">
      <div class="popup">
        <div class="p-3">
          <button
            class="close-modal-btn close-popup-btn px-3 py-2"
          >
            ×
          </button>
        </div>
        <div class="re-logo pb-3">
          <img
            class="logo-modal"
            src="{{ asset('website/assets/images/logo.png') }}"
          />
        </div>

        <!-- Step 1 -->
        <div class="step step1">
          <h2>Stay in the loop for all things event.</h2>
          <p>
            Join the conference email list for all the latest event updates.
          </p>
          <form class="emailForm">
            <div class="input-container">
              <input type="email" name="email" placeholder=" " required="" />
              <label>Email address *</label>
            </div>
            <p class="small-text">
              By proceeding, you're agreeing to receive communications from PTN
              Events Private Limited and/or its affiliates, in accordance with
              our <a href="terms">Terms of Use</a> and
              <a href="ptn-privacy-policy">Privacy Policy</a>.
            </p>
            <button type="submit" class="btn-gray-thin-fill">Sign up</button>
          </form>
        </div>

        <!-- Step 2 -->
        <div class="step step2 d-none">
          <h2>Confirm Contact Information</h2>
          <p>All fields are required.</p>
          <form class="detailsForm">
            <div class="input-container">
              <input type="text" name="firstName" placeholder=" " required="" />
              <label for="firstName">First Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="lastName" placeholder=" " required="" />
              <label for="lastName">Last Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="companyName" placeholder=" " />
              <label for="companyName">Company Name (optional)</label>
            </div>
            <div class="row">
              <div class="col-lg-3 pr-0">
                <div class="input-container">
                  <select class="country-code" name="country_code">
                    <option value="+91">+91 (IN)</option>
                    <option value="+1">+1 (US)</option>
                    <option value="+44">+44 (UK)</option>
                    <!-- Add more as needed -->
                  </select>
                </div>
              </div>
              <div class="col-lg-9 pl-2 contactnumber">
                <div class="input-container">
                  <input
                    type="tel"
                    name="contactNumber"
                    placeholder=" "
                    required=""
                  />
                  <label for="contactNumber">Contact Number *</label>
                </div>
              </div>
            </div>
            <div class="mt-3">
              <button type="button" class="btn-gray-thin-border backBtn">
                Back
              </button>
              <button type="submit" class="btn-gray-thin-fill">Submit</button>
            </div>
          </form>
        </div>

        <!-- Step 3 -->
        <div class="step step3 d-none">
          <h2 class="finalTitle">Thank you!</h2>
          <p class="finalMessage">
            Please check your email inbox to confirm your subscription...
          </p>
        </div>
      </div>
    </div>
    <div
      id="register-interest-overlay"
      class="overlay common-modal"
      data-type="5"
    >
      <div class="popup">
        <div class="p-3">
          <button
            class="close-modal-btn close-register-interest-btn px-3 py-2"
          >
            ×
          </button>
        </div>
        <div class="re-logo pb-3">
          <img
            class="logo-modal"
            src="images/ptn-logo.png"
          />
        </div>

        <!-- Step 1 -->
        <div class="step step1">
          <h2>Register your interest!</h2>
          <p>
            Join the conference email list for all the latest event updates and
            offers.
          </p>
          <form class="emailForm">
            <div class="input-container">
              <input type="email" name="email" placeholder=" " required="" />
              <label>Email address *</label>
            </div>
            <p class="small-text">
              By proceeding, you're agreeing to receive communications from PTN
              Events Private Limited and/or its affiliates, in accordance with
              our <a href="terms">Terms of Use</a> and
              <a href="ptn-privacy-policy">Privacy Policy</a>.
            </p>
            <button type="submit" class="btn-gray-thin-fill">Sign up</button>
          </form>
        </div>

        <!-- Step 2 -->
        <div class="step step2 d-none">
          <h2>Confirm Contact Information</h2>
          <p>All fields are required.</p>
          <form class="detailsForm">
            <div class="input-container">
              <input type="text" name="firstName" placeholder=" " required="" />
              <label for="firstName">First Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="lastName" placeholder=" " required="" />
              <label for="lastName">Last Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="companyName" placeholder=" " />
              <label for="companyName">Company Name (optional)</label>
            </div>

            <div class="input-container">
              <input
                type="tel"
                name="contactNumber"
                placeholder=" "
                required=""
              />
              <label for="contactNumber">Contact Number *</label>
            </div>

            <div class="mt-3">
              <button type="button" class="btn-gray-thin-border backBtn">
                Back
              </button>
              <button type="submit" class="btn-gray-thin-fill">Submit</button>
            </div>
          </form>
        </div>

        <!-- Step 3 -->
        <div class="step step3 d-none">
          <h2 class="finalTitle">Thank you!</h2>
          <p class="finalMessage">
            Please check your email inbox to confirm your subscription and add
            the domain @ptnevents.com to your safe sender list to ensure receipt
            of future email.
          </p>
        </div>
      </div>
    </div>
    <div
      id="download-brochure-overlay"
      class="overlay common-modal"
      data-type="2"
    >
      <div class="popup">
        <div class="p-3">
          <button
            class="close-modal-btn close-download-brochure-btn px-3 py-2"
          >
            ×
          </button>
        </div>
        <div class="re-logo pb-3">
          <img
            class="logo-modal"
            src="images/ptn-logo.png"
          />
        </div>

        <!-- Step 1 -->
        <div class="step step1">
          <h2>Download Brochure</h2>
          <p>
            Join the conference email list for all the latest event updates.
          </p>
          <form class="emailForm">
            <div class="input-container">
              <input type="email" name="email" placeholder=" " required="" />
              <label>Email address *</label>
            </div>
            <p class="small-text">
              By proceeding, you're agreeing to receive communications from PTN
              Events Private Limited and/or its affiliates, in accordance with
              our <a href="terms">Terms of Use</a> and
              <a href="ptn-privacy-policy">Privacy Policy</a>.
            </p>
            <button type="submit" class="btn-gray-thin-fill">Sign up</button>
          </form>
        </div>

        <!-- Step 2 -->
        <div class="step step2 d-none">
          <h2>Confirm Contact Information</h2>
          <p>All fields are required.</p>
          <form class="detailsForm">
            <div class="input-container">
              <input type="text" name="firstName" placeholder=" " required="" />
              <label for="firstName">First Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="lastName" placeholder=" " required="" />
              <label for="lastName">Last Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="companyName" placeholder=" " />
              <label for="companyName">Company Name (optional)</label>
            </div>

            <div class="input-container">
              <input
                type="tel"
                name="contactNumber"
                placeholder=" "
                required=""
              />
              <label for="contactNumber">Contact Number *</label>
            </div>

            <div class="mt-3">
              <button type="button" class="btn-gray-thin-border backBtn">
                Back
              </button>
              <button type="submit" class="btn-gray-thin-fill">Submit</button>
            </div>
          </form>
        </div>

        <!-- Step 3 -->
        <div class="step step3 d-none">
          <h2 class="finalTitle">Thank you!</h2>
          <p class="finalMessage">
            Please check your email inbox to confirm your subscription...
          </p>
        </div>
      </div>
    </div>

    <div id="attendee-list-overlay" class="overlay common-modal" data-type="9">
      <div class="popup">
        <div class="p-3">
          <button
            class="close-modal-btn close-attendee-list-btn px-3 py-2"
          >
            ×
          </button>
        </div>
        <div class="re-logo pb-3">
          <img
            class="logo-modal"
            src="images/ptn-logo.png"
          />
        </div>

        <!-- Step 1 -->
        <div class="step step1">
          <h2>Attendee List</h2>
          <p>
            Join the conference email list for all the latest event updates.
          </p>
          <form class="emailForm">
            <div class="input-container">
              <input type="email" name="email" placeholder=" " required="" />
              <label>Email address *</label>
            </div>
            <p class="small-text">
              By proceeding, you're agreeing to receive communications from PTN
              Events Private Limited and/or its affiliates, in accordance with
              our <a href="terms">Terms of Use</a> and
              <a href="ptn-privacy-policy">Privacy Policy</a>.
            </p>
            <button type="submit" class="btn-gray-thin-fill">Sign up</button>
          </form>
        </div>

        <!-- Step 2 -->
        <div class="step step2 d-none">
          <h2>Confirm Contact Information</h2>
          <p>All fields are required.</p>
          <form class="detailsForm">
            <div class="input-container">
              <input type="text" name="firstName" placeholder=" " required="" />
              <label for="firstName">First Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="lastName" placeholder=" " required="" />
              <label for="lastName">Last Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="companyName" placeholder=" " />
              <label for="companyName">Company Name (optional)</label>
            </div>

            <div class="input-container">
              <input
                type="tel"
                name="contactNumber"
                placeholder=" "
                required=""
              />
              <label for="contactNumber">Contact Number *</label>
            </div>

            <div class="mt-3">
              <button type="button" class="btn-gray-thin-border backBtn">
                Back
              </button>
              <button type="submit" class="btn-gray-thin-fill">Submit</button>
            </div>
          </form>
        </div>

        <!-- Step 3 -->
        <div class="step step3 d-none">
          <h2 class="finalTitle">Thank you!</h2>
          <p class="finalMessage">
            Please check your email inbox to confirm your subscription...
          </p>
        </div>
      </div>
    </div>

    <div
      id="media-partnership-overlay"
      class="overlay common-modal"
      data-type="4"
    >
      <div class="popup">
        <div class="p-3">
          <button
            class="close-modal-btn close-media-partnership-btn px-3 py-2"
          >
            ×
          </button>
        </div>
        <div class="re-logo pb-3">
          <img
            class="logo-modal"
            src="images/ptn-logo.png"
          />
        </div>

        <!-- Step 1 -->
        <div class="step step1">
          <h2>Media Partnership</h2>
          <p>
            Join the conference email list for all the latest event updates.
          </p>
          <form class="emailForm">
            <div class="input-container">
              <input type="email" name="email" placeholder=" " required="" />
              <label>Email address *</label>
            </div>
            <p class="small-text">
              By proceeding, you're agreeing to receive communications from PTN
              Events Private Limited and/or its affiliates, in accordance with
              our <a href="terms">Terms of Use</a> and
              <a href="ptn-privacy-policy">Privacy Policy</a>.
            </p>
            <button type="submit" class="btn-gray-thin-fill">Sign up</button>
          </form>
        </div>

        <!-- Step 2 -->
        <div class="step step2 d-none">
          <h2>Confirm Contact Information</h2>
          <p>All fields are required.</p>
          <form class="detailsForm">
            <div class="input-container">
              <input type="text" name="firstName" placeholder=" " required="" />
              <label for="firstName">First Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="lastName" placeholder=" " required="" />
              <label for="lastName">Last Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="companyName" placeholder=" " />
              <label for="companyName">Company Name (optional)</label>
            </div>

            <div class="input-container">
              <input
                type="tel"
                name="contactNumber"
                placeholder=" "
                required=""
              />
              <label for="contactNumber">Contact Number *</label>
            </div>

            <div class="mt-3">
              <button type="button" class="btn-gray-thin-border backBtn">
                Back
              </button>
              <button type="submit" class="btn-gray-thin-fill">Submit</button>
            </div>
          </form>
        </div>

        <!-- Step 3 -->
        <div class="step step3 d-none">
          <h2 class="finalTitle">Thank you!</h2>
          <p class="finalMessage">
            Please check your email inbox to confirm your subscription...
          </p>
        </div>
      </div>
    </div>
    <div id="contact-us-overlay" class="overlay common-modal" data-type="5">
      <div class="popup">
        <div class="p-3">
          <button
            class="close-modal-btn close-contact-us-btn px-3 py-2"
          >
            ×
          </button>
        </div>
        <div class="re-logo pb-3">
          <img
            class="logo-modal"
            src="images/ptn-logo.png"
          />
        </div>

        <!-- Step 1 -->
        <div class="step step1">
          <h2>Contact Us</h2>
          <p>
            Join the conference email list for all the latest event updates.
          </p>
          <form class="emailForm">
            <div class="input-container">
              <input type="email" name="email" placeholder=" " required="" />
              <label>Email address *</label>
            </div>
            <p class="small-text">
              By proceeding, you're agreeing to receive communications from PTN
              Events Private Limited and/or its affiliates, in accordance with
              our <a href="terms">Terms of Use</a> and
              <a href="ptn-privacy-policy">Privacy Policy</a>.
            </p>
            <button type="submit" class="btn-gray-thin-fill">Sign up</button>
          </form>
        </div>

        <!-- Step 2 -->
        <div class="step step2 d-none">
          <h2>Confirm Contact Information</h2>
          <p>All fields are required.</p>
          <form class="detailsForm">
            <div class="input-container">
              <input type="text" name="firstName" placeholder=" " required="" />
              <label for="firstName">First Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="lastName" placeholder=" " required="" />
              <label for="lastName">Last Name *</label>
            </div>

            <div class="input-container">
              <input type="text" name="companyName" placeholder=" " />
              <label for="companyName">Company Name (optional)</label>
            </div>

            <div class="input-container">
              <input
                type="tel"
                name="contactNumber"
                placeholder=" "
                required=""
              />
              <label for="contactNumber">Contact Number *</label>
            </div>

            <div class="mt-3">
              <button type="button" class="btn-gray-thin-border backBtn">
                Back
              </button>
              <button type="submit" class="btn-gray-thin-fill">Submit</button>
            </div>
          </form>
        </div>

        <!-- Step 3 -->
        <div class="step step3 d-none">
          <h2 class="finalTitle">Thank you!</h2>
          <p class="finalMessage">
            Please check your email inbox to confirm your subscription...
          </p>
        </div>
      </div>
    </div>
    <!-- Overlay (only need one) -->
    <div id="overlay" onclick="closeAllDrawers()"></div>

    <!-- Speaker Drawer -->
    <div class="drawer" id="speakerdrawer">
      <div class="drawer-header">
        <button type="button" class="close-btn" onclick="closeSpeakerDrawer()">
          <span class="offcanvas-close-icon" alt="Close Modal">×</span>
        </button>
      </div>
      <div class="drawer-content">
        <img class="drawer-speaker-image" src="#" alt="Speaker Photo" />
        <h2 class="drawer-speaker-name"></h2>
        <span class="drawer-speaker-position"></span>
        <div class="drawer-speaker-bio"><p class="drawer-speaker-bio"></p></div>
        <div class="speaker-social">
          <a
            href="#"
            class="drawer-speaker-linkedin"
            target="_blank"
            rel="noopener noreferrer"
          >
            <i
              class="fab fa-linkedin auto-style-52"
            ></i>
          </a>
          <div class="speaker-sessions d-none">
            <a
              href="#"
              class="text-link blue-on-light-bg"
              target="_blank"
              rel="noopener noreferrer"
              >S851 - Radical Respect: How to Work Together Better<span
                class="sr-only"
                >(opens in a new window)</span
              ><br
            /></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Sponsor Drawer -->
    <div class="drawer" id="sponsordrawer">
      <div class="drawer-header">
        <button type="button" class="close-btn" onclick="closeSponsorDrawer()">
          <span class="offcanvas-close-icon" alt="Close Modal">×</span>
        </button>
      </div>
      <div class="drawer-content">
        <img class="drawer-sponsor-image" src="#" alt="Sponsor Photo" />
        <h2 class="drawer-sponsor-name"></h2>
        <span class="drawer-sponsor-position"></span>
        <div class="drawer-sponsor-bio"><p class="drawer-sponsor-bio"></p></div>
        <div class="sponsor-social">
          <a
            href="#"
            class="drawer-sponsor-linkedin"
            target="_blank"
            rel="noopener noreferrer"
          >
            <i
              class="fab fa-linkedin auto-style-52"
            ></i>
          </a>
          <div class="sponsor-sessions d-none">
            <a
              href="#"
              class="text-link blue-on-light-bg"
              target="_blank"
              rel="noopener noreferrer"
              >S851 - Radical Respect: How to Work Together Better<span
                class="sr-only"
                >(opens in a new window)</span
              ><br
            /></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Cancel Modal Popup -->
    <div
      class="modal fade"
      id="mi-modal"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-card">
            <div class="">
              <div class="row align-items-left justify-content-between">
                <div class="col">
                  <!-- Title -->
                  <h4
                    class="card-header-title"
                    id="myModalLabel"
                    class="auto-style-53"
                  >
                    Cancel Order
                  </h4>
                </div>
                <div class="col-auto">
                  <!-- Close -->
                  <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                  >
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
              </div>
              <!-- / .row -->
            </div>
            <div class="card-body">
              <span>Are you sure you want to cancel this registration?</span
              ><br /><br />
              <button
                type="button"
                class="btn-blue-thin-fill"
                id="modal-btn-si"
              >
                Yes, Cancel
              </button>
              <button
                type="button"
                class="btn-gray-thin-border"
                id="modal-btn-no"
              >
                Do Not Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>