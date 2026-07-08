    <script src="{{ asset('website/assets/js/custom.js') }}"></script>
    <footer class="footer">
      <div class="container">
        <div class="footer-upper px-0">
          <div class="mailing-list">
            <span class="footer-top-left-span"
              >Join the mailing list for the latest PTN updates.</span
            >
            <button id="show-popup" class="btn-gray-thin-border">
              Sign up
            </button>
          </div>
          <div class="social-section">
            <span class="footer-top-left-span"
              >Stay connected with PTN on social.</span
            >
            <div class="social-icons">
              <a
                href="https://www.facebook.com/ptnevents/"
                class="social-icon"
                target="blank"
                ><i class="fab fa-facebook"></i
              ></a>
              <a
                href="https://x.com/ptn_events/"
                class="social-icon"
                target="blank"
                ><i class="fab fa-twitter"></i
              ></a>
              <a
                href="https://www.instagram.com/ptnevents/"
                class="social-icon"
                target="blank"
                ><i class="fab fa-instagram"></i
              ></a>
              <a
                href="https://www.linkedin.com/company/ptnevents"
                class="social-icon"
                target="blank"
                ><i class="fab fa-linkedin"></i
              ></a>
              <a
                href="https://www.youtube.com/@ptnevents"
                class="social-icon"
                target="blank"
                ><i class="fab fa-youtube"></i
              ></a>
            </div>
          </div>
        </div>
        <div class="footer-divider"></div>
        <div class="footer-lower px-0">
          <div class="legal-links">
            <span class="copyright"
              >Copyright © 2025 PTN Events Pvt. Ltd. or its affiliates. All
              rights reserved.</span
            >
            <span class="separator">/</span>
            <a href="ptn-privacy-policy" class="legal-link">Privacy Policy</a>
            <span class="separator">/</span>
            <a href="terms" class="legal-link">Terms of Use</a>
            <span class="separator">/</span>
            <a href="cookie-policy" class="legal-link">Cookie preferences</a>
            <span class="separator">/</span>
            <a href="code-of-conduct" class="legal-link">Code Of Conduct</a>
          </div>
        </div>
      </div>
    </footer>

    <div
      id="responseModal"
      class="modal"
      role="dialog"
      aria-modal="true"
      aria-labelledby="commonModalTitle"
      aria-describedby="commonModalMessage"
    >
      <div class="modal-content">
        <!--<span class="close-modal" aria-label="Close modal">×</span>-->
        <h3
          id="modalTitle"
          class="card-header-title a-fs-24 a-ff-ss a-fw-600 a-fc-00"
        ></h3>
        <span id="modalMessage"></span>
        <div>
          <button id="modal-ok-btn" class="btn-blue-thin-fill mb-4 mt-4">
            OK
          </button>
        </div>
      </div>
    </div>
    <script src="{{ asset('website/assets/js/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('website/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('website/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('website/assets/js/script.js') }}"></script>
 
    <!-- Add these scripts at the end of your body -->
    
    <script src="{{ asset('website/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('website/assets/js/slick.min.js') }}"></script>




    
@include('web.layout.sections.all_modal')