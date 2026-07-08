@extends('web.layout.app_layout')

@section('title', 'Supply Chain Exhibition in Ahmedabad 2025 | ISCS')

@section('meta_keyword', 'QFS')

@section('meta_description', 'QFS')

@section('structured_data')
    @json([
    "@context" => "https://schema.org",
    "url" => url()->current()
])
@endsection

@section('webLayoutContent')
   
   <main class="main-content">
      <div class="px-0 a-mb-150">
        <div class="">
          <div class="homebannerSlider">
            <div class="homebannerInner bannerSection active">
              <div class="container mt-5">
                <div class="row">
                  <div
                    class="col-md-6 d-flex align-items-center justify-content-center"
                  >
                    <div>
                      <h2 class="top-header-home">
                        Creating Business Opportunities by Connecting People
                      </h2>
                      <p class="mt-4 mb-4 a-fs-18">
                        Explore the most transformative industry events,
                        webinars, conferences as well as our archive of recent
                        key digital events
                      </p>
                      <button
                        class="btn-white-thick-fill mt-4"
                        type="button"
                        onclick="location.href='conferences';"
                      >
                        Explore Conferences
                      </button>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <img
                      src="{{ asset('website/assets/images/glimps.png') }}"
                      class="img-fluid banner-img mt-4 mt-md-0 flt-right"
                      loading="lazy"
                      alt="Business connections"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="homebannerInner bannerSection">
              <div class="container mt-5">
                <div class="row">
                  <div
                    class="col-md-6 d-flex align-items-center justify-content-center"
                  >
                    <div>
                      <h2 class="top-header-home">
                        Connect with brands leading the way in customer
                        experience
                      </h2>
                      <p class="mt-4 mb-4 a-fs-18">
                        We are taking a personal approach to business
                        interaction. Build your own lead gen strategy with us to
                        improve conversion rates and grow your revenue
                      </p>
                      <button
                        class="btn-white-thick-fill mt-4"
                        type="button"
                        onclick="location.href='leadgeneration';"
                      >
                        Explore Partnership
                      </button>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <img
                      src="{{ asset('website/assets/images/glimps.png') }}"
                      class="img-fluid banner-img mt-4 mt-md-0 flt-right"
                      loading="lazy"
                      alt="Customer experience"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="homebannerInner bannerSection">
              <div class="container mt-5">
                <div class="row">
                  <div
                    class="col-md-6 d-flex align-items-center justify-content-center"
                  >
                    <div>
                      <h2 class="top-header-home">
                        Oil & Gas Digital Transformation Conference 2025
                      </h2>
                      <p class="mt-4 mb-4 a-fs-18">
                        Our conference is dedicated to unlocking actionable
                        solutions that drive heightened cost-effectiveness and
                        operational excellence across the entire oil and gas
                        value chain – from upstream exploration to midstream
                        transportation and downstream refining.
                      </p>
                      <button
                        class="btn-white-thick-fill mt-4"
                        type="button"
                        onclick="location.href='classicmembership';"
                      >
                        Explore Classic Membership
                      </button>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <img
                      src="{{ asset('website/assets/images/dtc-hero.png') }}"
                      class="img-fluid banner-img mt-4 mt-md-0 flt-right"
                      loading="lazy"
                      alt="Oil & Gas Conference"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="upcomingConferences a-mb-150">
        <div class="container">
          <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 sub-headers">View upcoming conferences</h2>
            <div class="d-flex">
              <button class="btn left-arrow p-0 mr-1 prev-button" type="button">
                <img src="{{ asset('website/assets/images/Left_Arrow.webp') }}" />
              </button>
              <button
                class="btn right-arrow p-0 ml-1 next-button"
                aria-disabled="false"
                type="button"
              >
                <img src="{{ asset('website/assets/images/Right_Arrow.webp') }}" />
              </button>
            </div>
          </div>
        </div>
        <!-- style="max-width:1685px" -->
        <div class="container-fluid">
          <div class="upcomingConferences-slider">
            <div
              class="card upcomingConferences-card"
              onclick="window.open('https://supplychain-conference.com/', '_blank')"
              style="cursor: pointer"
            >
              <div class="card-body conference-list-slider">
                <p class="badge datetime-badge">29 - 30 September, 2025</p>
                <h5 class="card-title mt-2 a-ff-rh a-fw-600 a-fs-20">
                  Supply Chain Digitalization Conference & Exhibition 2025
                </h5>
              </div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <p class="a-fs-13">Houston, TX, USA</p>
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <path
                      fill="currentColor"
                      fill-rule="evenodd"
                      d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10M9.75 9a.75.75 0 0 1 .75-.75H15a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-2.69l-4.72 4.72a.75.75 0 0 1-1.06-1.06l4.72-4.72H10.5A.75.75 0 0 1 9.75 9"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </span>
              </div>
            </div>
             <div
              class="card upcomingConferences-card"
              onclick="window.open('https://supplychain-conference.com/', '_blank')"
              style="cursor: pointer"
            >
              <div class="card-body conference-list-slider">
                <p class="badge datetime-badge">29 - 30 September, 2025</p>
                <h5 class="card-title mt-2 a-ff-rh a-fw-600 a-fs-20">
                  Supply Chain Digitalization Conference & Exhibition 2025
                </h5>
              </div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <p class="a-fs-13">Houston, TX, USA</p>
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <path
                      fill="currentColor"
                      fill-rule="evenodd"
                      d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10M9.75 9a.75.75 0 0 1 .75-.75H15a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-2.69l-4.72 4.72a.75.75 0 0 1-1.06-1.06l4.72-4.72H10.5A.75.75 0 0 1 9.75 9"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </span>
              </div>
            </div>
            <div
              class="card upcomingConferences-card"
              onclick="window.open('/conferences/digital-transformation', '_blank')"
              style="cursor: pointer"
            >
              <div class="card-body conference-list-slider">
                <p class="badge datetime-badge">01 - 02 October, 2025</p>
                <h5 class="card-title mt-2 a-ff-rh a-fw-600 a-fs-20">
                  O&G Digital Transformation Conference & Exhibition 2025
                </h5>
              </div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <p class="a-fs-13">Houston, TX, USA</p>
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <path
                      fill="currentColor"
                      fill-rule="evenodd"
                      d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10M9.75 9a.75.75 0 0 1 .75-.75H15a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-2.69l-4.72 4.72a.75.75 0 0 1-1.06-1.06l4.72-4.72H10.5A.75.75 0 0 1 9.75 9"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </span>
              </div>
            </div>
            <div
              class="card upcomingConferences-card"
              onclick="window.open('https://ogad-conference.com/', '_blank')"
              style="cursor: pointer"
            >
              <div class="card-body conference-list-slider">
                <p class="badge datetime-badge">06 - 07 October, 2025</p>
                <h5 class="card-title mt-2 a-ff-rh a-fw-600 a-fs-20">
                  Oil and Gas Automation and Digitalization Conference 2025
                </h5>
              </div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <p class="a-fs-13">Houston, TX, USA</p>
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <path
                      fill="currentColor"
                      fill-rule="evenodd"
                      d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10M9.75 9a.75.75 0 0 1 .75-.75H15a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-2.69l-4.72 4.72a.75.75 0 0 1-1.06-1.06l4.72-4.72H10.5A.75.75 0 0 1 9.75 9"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </span>
              </div>
            </div>
            <div
              class="card upcomingConferences-card"
              onclick="window.open('https://evinfra-conference.com/', '_blank')"
              style="cursor: pointer"
            >
              <div class="card-body conference-list-slider">
                <p class="badge datetime-badge">12 - 13 November, 2025</p>
                <h5 class="card-title mt-2 a-ff-rh a-fw-600 a-fs-20">
                  EV Charging Infrastructure Conference and Exhibition 2025
                </h5>
              </div>
              <div
                class="card-footer d-flex align-items-center justify-content-between"
              >
                <p class="a-fs-13">Houston, TX, USA</p>
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <path
                      fill="currentColor"
                      fill-rule="evenodd"
                      d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10M9.75 9a.75.75 0 0 1 .75-.75H15a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-2.69l-4.72 4.72a.75.75 0 0 1-1.06-1.06l4.72-4.72H10.5A.75.75 0 0 1 9.75 9"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="experrienceSection a-mb-150">
        <div class="container">
          <h2 class="sub-headers">Experience our events</h2>
          <div class="row mt-4">
            <div class="col-xl-7">
              <div class="card openingnote-card h-100 mb-0">
                <img
                  class="card-img-top w-100"
                  style="object-fit: cover"
                  src="{{ asset('website/assets/images/a%201.jpg') }}"
                  alt="Opening keynote"
                />
                <div class="card-body">
                  <h3 class="mt-2 a-fs-22 a-fw-900 a-ff-rh">
                    Supply Chain Visibility and <br />Container Shipping
                    Conference 2025
                  </h3>
                  <p class="card-text a-fs-16">
                    Join us for a premier event bringing together global
                    logistics leaders, innovators, and experts. Explore
                    cutting-edge solutions, emerging technologies, and best
                    practices to enhance supply chain transparency, efficiency,
                    and resilience in today's dynamic shipping environment.
                    Don't miss this transformative experience!
                  </p>

                  <button
                    class="btn-white-thick-border px-3 py-1 mt-4"
                    type="button"
                    onclick="window.open('https://container-shipping-conference.com/', '_blank')"
                  >
                    Explore Details
                  </button>
                </div>
              </div>
            </div>
            <div class="col-xl-5">
              <div class="row">
                <div class="col-xl-12 col-md-6">
                  <div class="card mt-xl-0 mt-4 mb-0">
                    <div class="card-body p-0">
                      <div class="row m-0">
                        <div class="col-xl-4 p-0">
                          <img
                            class="img-fluid w-100"
                            src="{{ asset('website/assets/images/digital-conference.png') }}"
                            alt="Inspiration keynote"
                          />
                        </div>
                        <div class="col-xl-8 Inspirationkeynote-card py-3 p-4">
                          <h3 class="a-fs-22 a-fw-900 a-ff-rh">
                            Oil & Gas Digital <br />Twin Conference 2025
                          </h3>
                          <p class="card-text a-fs-16">
                            Explores the Digital Twin ecosystem, focusing on
                            sustainability, Generative AI, upstream integration,
                            cybersecurity, asset performance efficiency, and its
                            role in a Net Zero future.
                          </p>

                          <button
                            class="btn-white-thick-border px-3 py-1 mt-4"
                            type="button"
                            onclick="window.open('https://digital-twin-conference.com/', '_blank')"
                          >
                            Explore Details
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-12 col-md-6">
                  <div class="card mt-xl-6 mt-4 mb-0">
                    <div class="card-body p-0">
                      <div class="row m-0">
                        <div class="col-xl-4 p-0">
                          <img
                            class="img-fluid w-100"
                            src="{{ asset('website/assets/images/supply-chain.png') }}"
                            alt="Inspiration keynote"
                          />
                        </div>
                        <div class="col-xl-8 openingnote-card py-3 p-4">
                          <h3 class="a-fs-22 a-fw-900 a-ff-rh">
                            Oil & Gas Digital Transformation <br />Conference &
                            Exhibition 2025
                          </h3>
                          <p class="card-text a-fs-16">
                            Delve into the latest insights on Innovative
                            Technologies, unlocking actionable solutions for
                            heightened cost-effectiveness and operational
                            excellence.
                          </p>
                          <button
                            class="btn-white-thick-border px-3 py-1 mt-4"
                            type="button"
                            onclick="window.open('https://digital-transformation-conference.com/', '_blank')"
                          >
                            Explore Details
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Company tab -->
      <div class="companytab a-mb-150">
        <div class="container">
          <div
            class="row d-flex align-items-center justify-content-center mt-2"
          >
            <div
              class="col-xl-8 col-lg-10 d-flex align-items-center justify-content-center flex-column"
            >
              <div class="tab-content" id="pills-tabContent">
                <div
                  class="tab-pane fade show active"
                  id="testimonial_1"
                  role="tabpanel"
                  aria-labelledby="testimonial-1-tab"
                >
                  <div class="row">
                    <div class="col-md-8 col-12">
                      <p class="a-fs-22 mb-5">
                        "Thanks very much for this opportunity. I was impressed
                        by the quality of your event. It was as well run and
                        organized as I've ever attended."
                      </p>
                      <small>
                        Rafiq Khurshid<br />
                        IT Specialist & Consultant
                      </small>
                    </div>
                    <div
                      class="col-md-4 col-0 d-md-flex justify-content-end d-none"
                    >
                      <img
                        src="{{ asset('website/assets/images/rafiq-khurshid.jpg') }}"
                        class="img-fluid w-100"
                        style="max-width: 200px"
                      />
                    </div>
                  </div>
                </div>
                <div
                  class="tab-pane fade show"
                  id="testimonial_2"
                  role="tabpanel"
                  aria-labelledby="testimonial-2-tab"
                >
                  <div class="row">
                    <div class="col-md-8 col-12">
                      <p class="a-fs-22 mb-5">
                        "Thank you, team for the Certificate. As mentioned
                        before, I truly appreciate the prompt communication,
                        prep call, and accommodating my change of date. I look
                        forward to partnering with PTN events in the future."
                      </p>
                      <small>
                        Arthi Vasudevan<br />
                        Senior Product Manager
                      </small>
                    </div>
                    <div
                      class="col-md-4 col-0 d-md-flex justify-content-end d-none"
                    >
                      <img
                        src="{{ asset('website/assets/images/arthi-vasudevan.jpg') }}"
                        class="img-fluid w-100"
                        style="max-width: 200px"
                      />
                    </div>
                  </div>
                </div>
                <div
                  class="tab-pane fade show"
                  id="testimonial_3"
                  role="tabpanel"
                  aria-labelledby="testimonial-3-tab"
                >
                  <div class="row">
                    <div class="col-md-8 col-12">
                      <p class="a-fs-22 mb-5">
                        "Thank you for sharing. I should have mentioned this in
                        my feedback; some of the best I have seen for any
                        conference, live or virtual. Awesome event!"
                      </p>
                      <small>
                        Lennart Heip<br />
                        Global Modal and Technology
                      </small>
                    </div>
                    <div
                      class="col-md-4 col-0 d-md-flex justify-content-end d-none"
                    >
                      <img
                        src="{{ asset('website/assets/images/lennart-heip.jpg') }}"
                        class="img-fluid w-100"
                        style="max-width: 200px"
                      />
                    </div>
                  </div>
                </div>
                <div
                  class="tab-pane fade show"
                  id="testimonial_4"
                  role="tabpanel"
                  aria-labelledby="testimonial-4-tab"
                >
                  <div class="row">
                    <div class="col-md-8 col-12">
                      <p class="a-fs-22 mb-5">
                        "We are excited to join this great event. Sign up to
                        hear a speech from Michal Paulski on Cybersecurity
                        during the Oil and Gas Automation & Digitalization
                        Conference."
                      </p>
                      <small>
                        Michal Paulski<br />
                        Senior Manager
                      </small>
                    </div>
                    <div
                      class="col-md-4 col-0 d-md-flex justify-content-end d-none"
                    >
                      <img
                        src="{{ asset('website/assets/images/michal-paulski.jpg') }}"
                        class="img-fluid w-100"
                        style="max-width: 200px"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <ul class="nav nav-pills mt-4" id="company-tab" role="tablist">
                <li class="nav-item mt-3" role="presentation">
                  <button
                    class="nav-link active"
                    id="testimonial-1-tab"
                    data-toggle="pill"
                    data-target="#testimonial_1"
                    type="button"
                    role="tab"
                    aria-controls="testimonial_1"
                    aria-selected="true"
                  >
                    <img src="{{ asset('website/assets/images/saudi-aramco.jpg') }}" alt="" />
                  </button>
                </li>
                <li class="nav-item mt-3" role="presentation">
                  <button
                    class="nav-link"
                    id="testimonial-2-tab"
                    data-toggle="pill"
                    data-target="#testimonial_2"
                    type="button"
                    role="tab"
                    aria-controls="testimonial_2"
                    aria-selected="true"
                  >
                    <img src="{{ asset('website/assets/images/baker-hughes.jpg') }}" alt="" />
                  </button>
                </li>
                <li class="nav-item mt-3" role="presentation">
                  <button
                    class="nav-link"
                    id="testimonial-3-tab"
                    data-toggle="pill"
                    data-target="#testimonial_3"
                    type="button"
                    role="tab"
                    aria-controls="testimonial_3"
                    aria-selected="true"
                  >
                    <img src="{{ asset('website/assets/images/dow.jpg') }}" alt="" />
                  </button>
                </li>
                <li class="nav-item mt-3" role="presentation">
                  <button
                    class="nav-link"
                    id="testimonial-4-tab"
                    data-toggle="pill"
                    data-target="#testimonial_4"
                    type="button"
                    role="tab"
                    aria-controls="testimonial_4"
                    aria-selected="true"
                  >
                    <img src="{{ asset('website/assets/images/accenture.jpg') }}" alt="" />
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="exclusiveSpeakers a-mb-150">
        <div class="container">
          <div
            class="d-flex justify-content-between align-items-center btn-content"
          >
            <h2 class="mb-0 sub-headers">Our exclusive speakers</h2>

            <div class="d-flex align-items-center">
              <button
                class="px-3 btn-blue-thin-fill mr-4"
                type="button"
                onclick="location.href='/speakers-directory';"
              >
                View all speakers
              </button>
              <button
                class="btn left-arrow p-0 mr-1 prev-button-exclusivespeaker"
                type="button"
              >
                <img src="{{ asset('website/assets/images/Left_Arrow.webp') }}" />
              </button>
              <button
                class="btn right-arrow p-0 ml-1 next-button-exclusivespeaker"
                aria-disabled="false"
                type="button"
              >
                <img src="{{ asset('website/assets/images/Right_Arrow.webp') }}" />
              </button>
            </div>
          </div>
        </div>
        <div class="container-fluid">
          <div class="exclusiveSpeakers-slider mt-4 mt-md-5">
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="20"
              data-nm="Ismail Oyewole"
              data-dsgn="Application Team Lead, Prudent Energy"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_20.jfif') }}"
              data-linkedin="https://www.linkedin.com/in/ismail-oyewole-oyedapo/?originalSubdomain=ng"
            >
              <img
                src="{{ asset('website/assets/images/profile_20.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Ismail Oyewole
                </h5>
                <p class="mt-2 md-none">
                  Application Team Lead, Prudent Energy
                </p>
                <p class="mt-2 dd-none">Application Team Lead Prudent Energy</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="23"
              data-nm="Pragyaditya Das"
              data-dsgn="Assistant Manager (Retail Transformation), Indian Oil Corporation Limited"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_23.jfif') }}"
              data-linkedin="https://www.linkedin.com/in/pragyaditya-das"
            >
              <img
                src="{{ asset('website/assets/images/profile_23.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Pragyaditya Das
                </h5>
                <p class="mt-2 md-none">
                  Assistant Manager (Retail Transformation), Indian Oil
                  Corporation Limited
                </p>
                <p class="mt-2 dd-none">
                  Assistant Manager (Retail Transformation) Indian
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="24"
              data-nm="Soumit Roy"
              data-dsgn="Associate Director, Data Analytics and AI, Jade Global Inc"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_24.jfif') }}"
              data-linkedin="linkedin.com/in/soumit-roy-420a5044"
            >
              <img
                src="{{ asset('website/assets/images/profile_24.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Soumit Roy</h5>
                <p class="mt-2 md-none">
                  Associate Director, Data Analytics and AI, Jade Global Inc
                </p>
                <p class="mt-2 dd-none">
                  Associate Director, Data Analytics and
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="26"
              data-nm="Sue Tomic"
              data-dsgn="Board Chair, SCLAA"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_26.jfif') }}"
              data-linkedin="https://www.linkedin.com/in/suetomic/"
            >
              <img
                src="{{ asset('website/assets/images/profile_26.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Sue Tomic</h5>
                <p class="mt-2 md-none">Board Chair, SCLAA</p>
                <p class="mt-2 dd-none">Board Chair SCLAA</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="33"
              data-nm="Shaun Walling"
              data-dsgn="Brand Manager, Arrive"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_33.jfif') }}"
              data-linkedin="https://www.linkedin.com/in/shaun-walling-b63a56153"
            >
              <img
                src="{{ asset('website/assets/images/profile_33.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Shaun Walling
                </h5>
                <p class="mt-2 md-none">Brand Manager, Arrive</p>
                <p class="mt-2 dd-none">Brand Manager Arrive</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="37"
              data-nm="Ueli Rothen"
              data-dsgn="Business Development Manager, Distran AG"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_37.jfif') }}"
              data-linkedin="https://www.linkedin.com/in/ueli-rothen-3b96b0220/"
            >
              <img
                src="{{ asset('website/assets/images/profile_37.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Ueli Rothen</h5>
                <p class="mt-2 md-none">
                  Business Development Manager, Distran AG
                </p>
                <p class="mt-2 dd-none">
                  Business Development Manager Distran AG
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="41"
              data-nm="Raj Kannan"
              data-dsgn="Business Development Manager, SLB"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_41.jfif') }}"
              data-linkedin="linkedin.com/in/rkannanslb"
            >
              <img
                src="{{ asset('website/assets/images/profile_41.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Raj Kannan</h5>
                <p class="mt-2 md-none">Business Development Manager, SLB</p>
                <p class="mt-2 dd-none">Business Development Manager SLB</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="43"
              data-nm="Noman Rasool"
              data-dsgn="Business Process Automation Lead , Digital and Business Transformation, Shell"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/default.png') }}"
              data-linkedin="https://www.linkedin.com/in/noman-rasool-11420327a/"
            >
              <img src="{{ asset('website/assets/images/default.png') }}" class="img-fluid w-100" alt="" />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Noman Rasool
                </h5>
                <p class="mt-2 md-none">
                  Business Process Automation Lead , Digital and Business
                  Transformation, Shell
                </p>
                <p class="mt-2 dd-none">Business Process Automation Lead ,</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="45"
              data-nm="Enrico Schlick"
              data-dsgn="Business Product Owner Transportation, Schaeffler"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_45.jfif') }}"
              data-linkedin="linkedin.com/in/enrico-schlick-b84888128"
            >
              <img
                src="{{ asset('website/assets/images/profile_45.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Enrico Schlick
                </h5>
                <p class="mt-2 md-none">
                  Business Product Owner Transportation, Schaeffler
                </p>
                <p class="mt-2 dd-none">
                  Business Product Owner Transportation Schaeffler
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="50"
              data-nm="Fawaz Alsahan"
              data-dsgn="Chairman of Instrumentation Standards, Saudi Aramco"
              data-bio="Fawaz AlSahan is the Chairman of the Saudi Aramco Instrumentation Standards Committee, IIoT Leader, Member of ISA Publications Committee and Executive Board Member of ISA Saudi Arabia section. Fawaz is a voting member of multiple technical committees in ISO, UL, IOGP and Saudi Standards, Metrology and Quality Organization (SASO). Fawaz has more than 24 years of experience in automation and he is a certified Engineering Consultant (SCE) and a Certified Automation Professional (ISA). Fawaz received more than eight excellence national and international excellence awards including ?The King Award for Inventors and Gifted. Fawaz has five granted patents, author and co-author of many papers and handbooks and he teaches multiple technical courses."
              data-img="{{ asset('website/assets/images/profile_50.jfif') }}"
              data-linkedin="https://www.linkedin.com/in/fawaz-alsahan-77080583/"
            >
              <img
                src="{{ asset('website/assets/images/profile_50.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Fawaz Alsahan
                </h5>
                <p class="mt-2 md-none">
                  Chairman of Instrumentation Standards, Saudi Aramco
                </p>
                <p class="mt-2 dd-none">
                  Chairman of Instrumentation Standards Saudi
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="53"
              data-nm="Silvio Cesar Godinho Teixeira"
              data-dsgn="Chemical Engineer | Project Manager | Maintenance Engineer | Scientist, Petrobras"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_53.jfif') }}"
              data-linkedin="https://www.linkedin.com/in/scgteixeira"
            >
              <img
                src="{{ asset('website/assets/images/profile_53.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Silvio Cesar Godinho Teixeira
                </h5>
                <p class="mt-2 md-none">
                  Chemical Engineer | Project Manager | Maintenance Engineer |
                  Scientist, Petrobras
                </p>
                <p class="mt-2 dd-none">Chemical Engineer | Project Manager</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="59"
              data-nm="Ketut Wiryadi"
              data-dsgn="Chief Commercial Officer, Marubeni-Itochu Tubulars Oceania Pty Ltd"
              data-bio="With over 24 years of expertise in special steel applications for the oil and gas, mining, and associated industries, Ketut brings a wealth of knowledge to the digital transformation landscape.
  Holding a Master of Business Administration from Monash University and a Bachelor of Engineering from Swinburne University, he has successfully led cross-functional teams in new business projects and spearheaded the global digitalisation initiatives for Marubeni-Itochu Tubulars.
  Ketut has been instrumental in the creation and organisational adoption of the Tubestream and Pipesales applications, driving innovation and efficiency within the company."
              data-img="{{ asset('website/assets/images/profile_59.png') }}"
              data-linkedin="https://www.linkedin.com/in/ketut-wiryadi-378b3135/"
            >
              <img
                src="{{ asset('website/assets/images/profile_59.png') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Ketut Wiryadi
                </h5>
                <p class="mt-2 md-none">
                  Chief Commercial Officer, Marubeni-Itochu Tubulars Oceania Pty
                  Ltd
                </p>
                <p class="mt-2 dd-none">
                  Chief Commercial Officer Marubeni-Itochu Tubulars
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="63"
              data-nm="Krzysztof Palka"
              data-dsgn="Chief Executive Officer, Akine Inc."
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_63.jfif') }}"
              data-linkedin="https://www.linkedin.com/in/krzysztof-kris-palka-93bb8816/?originalSubdomain=ca"
            >
              <img
                src="{{ asset('website/assets/images/profile_63.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Krzysztof Palka
                </h5>
                <p class="mt-2 md-none">Chief Executive Officer, Akine Inc.</p>
                <p class="mt-2 dd-none">Chief Executive Officer Akine Inc.</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="72"
              data-nm="Hichem Mansour"
              data-dsgn="Chief Executive Officer, IPS (Innovative Production Services)"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_72.jfif') }}"
              data-linkedin="https://www.linkedin.com/in/hichem-mansour-25127b1?originalSubdomain=tn"
            >
              <img
                src="{{ asset('website/assets/images/profile_72.jfif') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Hichem Mansour
                </h5>
                <p class="mt-2 md-none">
                  Chief Executive Officer, IPS (Innovative Production Services)
                </p>
                <p class="mt-2 dd-none">
                  Chief Executive Officer IPS (Innovative
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="80"
              data-nm="John Mcfall"
              data-dsgn="Chief Executive Officer, SupplyChainWise"
              data-bio="Speaker Biography yet to be published"
              data-img="{{ asset('website/assets/images/profile_80.png') }}"
              data-linkedin="https://www.linkedin.com/in/johnmcfall5"
            >
              <img
                src="{{ asset('website/assets/images/profile_80.png') }}"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">John Mcfall</h5>
                <p class="mt-2 md-none">
                  Chief Executive Officer, SupplyChainWise
                </p>
                <p class="mt-2 dd-none">
                  Chief Executive Officer SupplyChainWise
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="93"
              data-nm="Greg Fallon"
              data-dsgn="Chief Executive Officer, SLB/ Germinus.ai"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_93.png"
              data-linkedin="https://www.linkedin.com/in/greg-fallon/"
            >
              <img
                src="./images/profile_93.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Greg Fallon</h5>
                <p class="mt-2 md-none">
                  Chief Executive Officer, SLB/ Germinus.ai
                </p>
                <p class="mt-2 dd-none">
                  Chief Executive Officer SLB/ Germinus.ai
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="94"
              data-nm="Kyle Henderson"
              data-dsgn="Chief Executive Officer, VizionAPI"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_94.jpg"
              data-linkedin="https://www.linkedin.com/in/kylehenderson/"
            >
              <img
                src="./images/profile_94.jpg"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Kyle Henderson
                </h5>
                <p class="mt-2 md-none">Chief Executive Officer, VizionAPI</p>
                <p class="mt-2 dd-none">Chief Executive Officer VizionAPI</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="99"
              data-nm="Carlos Tapia"
              data-dsgn="Chief Executive Officer and Owner, Balam Energy"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_99.png"
              data-linkedin="https://www.linkedin.com/in/carlostapia"
            >
              <img
                src="./images/profile_99.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Carlos Tapia
                </h5>
                <p class="mt-2 md-none">
                  Chief Executive Officer and Owner, Balam Energy
                </p>
                <p class="mt-2 dd-none">Chief Executive Officer and Owner</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="101"
              data-nm="Karthik Rau"
              data-dsgn="Chief Executive Officer,, Senzit, Inc"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_101.jpg"
              data-linkedin="Speaker LinkedIn URL"
            >
              <img
                src="./images/profile_101.jpg"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Karthik Rau</h5>
                <p class="mt-2 md-none">
                  Chief Executive Officer,, Senzit, Inc
                </p>
                <p class="mt-2 dd-none">Chief Executive Officer, Senzit, Inc</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="106"
              data-nm="Suneet Agera"
              data-dsgn="Chief Innovation Architect, SAP Sustainability Innovations, SAP"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_106.png"
              data-linkedin="https://www.linkedin.com/in/suneetagera"
            >
              <img
                src="./images/profile_106.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Suneet Agera
                </h5>
                <p class="mt-2 md-none">
                  Chief Innovation Architect, SAP Sustainability Innovations,
                  SAP
                </p>
                <p class="mt-2 dd-none">
                  Chief Innovation Architect, SAP Sustainability
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="117"
              data-nm="Geoffrey Thyne"
              data-dsgn="Chief Technology Officer, ESal"
              data-bio="Geoffrey Thyne has over 35 years of experience in oil and gas and is one world?s experts 
 in increasing oil production by manipulation of water chemistry. Geoffrey began his 
 career in 1979 as a Research Geochemist at the Arco Oil and Gas research facility in 
 Plano, Texas. He received his Ph.D. in Geology from the University of Wyoming in 
 1991, and taught at California State University-Bakersfield until 1996. He then moved to 
 Colorado School of Mines in 1996. He returned to the University of Wyoming in 2006 at
 the Enhanced Oil Recovery Institute. He became immersed in the possibilities of 
 changing water chemistry to improve oil recovery. Geoff left EORI in 2012 and returned 
 to the private sector at ESal, LLC"
              data-img="./images/profile_117.jfif"
              data-linkedin="https://www.linkedin.com/in/geoffrey-thyne-9258863/"
            >
              <img
                src="./images/profile_117.jfif"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Geoffrey Thyne
                </h5>
                <p class="mt-2 md-none">Chief Technology Officer, ESal</p>
                <p class="mt-2 dd-none">Chief Technology Officer ESal</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="119"
              data-nm="Ryan Jarvis"
              data-dsgn="Chief Technology Officer, Rock NRG"
              data-bio="With over 21 years of experience in the energy industry focused primarily in upstream exploration, Ryan Jarvis has made significant contributions as a geoscientist and computational scientist in developing subsurface technologies and implementing key strategies to modernize business workflows. Early in his career he received awards for his outstanding work automating processes around reservoir simulation and reducing the cycle time by orders of magnitude. Half-way through his career, he became a stratigrapher and was responsible for maturing prospects in the Gulf of Mexico, Africa, and Argentina. In that capacity he invented stratigraphic analytics to expedite assessments and predict reservoir character and drilling results. In the last 3 years he has been actively engaged as an industry thought leader having been the voice for ExxonMobil on upstream digital transformation and leveraging industry standards for sustainability, interoperability, and scalability to progress the modernization of 4D Seismic to Simulation and Rock & Fluids workflows on top of industry data platforms enriched with quality and trusted metadata to more confidently support AI, GenAI, and machine learning."
              data-img="./images/profile_119.png"
              data-linkedin="https://www.linkedin.com/in/ryan-d-jarvis"
            >
              <img
                src="./images/profile_119.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Ryan Jarvis</h5>
                <p class="mt-2 md-none">Chief Technology Officer, Rock NRG</p>
                <p class="mt-2 dd-none">Chief Technology Officer Rock NRG</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="122"
              data-nm="Shuzhen Ye"
              data-dsgn="Citizen Data Scientist at Processing Geophysics, Shell"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_122.jpg"
              data-linkedin="https://www.linkedin.com/in/ACwAAAU-8KYBMPL3_8DNLhcdrUY_JrLyZKiJEdE"
            >
              <img
                src="./images/profile_122.jpg"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Shuzhen Ye</h5>
                <p class="mt-2 md-none">
                  Citizen Data Scientist at Processing Geophysics, Shell
                </p>
                <p class="mt-2 dd-none">Citizen Data Scientist at Processing</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="124"
              data-nm="Brett Schroeder"
              data-dsgn="Co-Founder and Chief Executive Officer, AP-Network"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_124.png"
              data-linkedin="https://www.linkedin.com/in/brett-schroeder-794a641/"
            >
              <img
                src="./images/profile_124.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Brett Schroeder
                </h5>
                <p class="mt-2 md-none">
                  Co-Founder and Chief Executive Officer, AP-Network
                </p>
                <p class="mt-2 dd-none">
                  Co-Founder and Chief Executive Officer
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="126"
              data-nm="Pierre Garreau"
              data-dsgn="Co-founder, Chief Executive Officer, Searoutes"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_126.jpg"
              data-linkedin="https://www.linkedin.com/in/pgarreau/?originalSubdomain=de"
            >
              <img
                src="./images/profile_126.jpg"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Pierre Garreau
                </h5>
                <p class="mt-2 md-none">
                  Co-founder, Chief Executive Officer, Searoutes
                </p>
                <p class="mt-2 dd-none">
                  Co-founder, Chief Executive Officer Searoutes
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="128"
              data-nm="Mike Hepburn"
              data-dsgn="Commercial Vice President, Adarga"
              data-bio="Michael Hepburn is the Commercial Vice President at Adarga. Adarga specialises in Information Intelligence, helping organisations to mitigate geopolitical risk. He has over twenty years? experience with tech start-ups and scale-ups and holds a Masters in International Relations."
              data-img="./images/profile_128.jpg"
              data-linkedin="https://www.linkedin.com/in/mikehepburn/?originalSubdomain=uk"
            >
              <img
                src="./images/profile_128.jpg"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Mike Hepburn
                </h5>
                <p class="mt-2 md-none">Commercial Vice President, Adarga</p>
                <p class="mt-2 dd-none">Commercial Vice President Adarga</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="129"
              data-nm="Lee Cysouw"
              data-dsgn="Communication Manager, AUTOSOL"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/default.png"
              data-linkedin="https://www.linkedin.com/in/lee-cysouw-b32a8227?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3BYnKgZcjDQnSey2PeKkmD%2FA%3D%3D"
            >
              <img src="./images/default.png" class="img-fluid w-100" alt="" />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Lee Cysouw</h5>
                <p class="mt-2 md-none">Communication Manager, AUTOSOL</p>
                <p class="mt-2 dd-none">Communication Manager AUTOSOL</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="133"
              data-nm="Fred Clarke"
              data-dsgn="Consultant, Stepchange Global"
              data-bio="Fred has worked globally in the oil & gas business performing in a wide range of technical and management roles with E&P operators, major service companies and consultancies in United States Canada and Australia. 
  
  Currently he is based in Houston, TX and is a Principal Consultant with Stepchange Global which is an independent advisory company supporting operators to develop their Integrated Operations Programs, Digital Transformation Projects and Operational Technology. 
  
  He has authored and co-authored several SPE papers and has presented at many industry conferences in North America.
  
  His prior roles include Team Leader for Murphy Oil?s remote operations center and Senior Artificial Lift Advisor for their Eagle Ford unconventional asset in South Texas. 
  
  He also worked with Santos, Australia's largest onshore operator supporting their artificial lift program, automation and optimization of a large and remote conventional asset located in the Outback of Australia as well supporting the company's major coal bed methane project in Queensland."
              data-img="./images/profile_133.png"
              data-linkedin="https://www.linkedin.com/in/fred-clarke-0b06b7218"
            >
              <img
                src="./images/profile_133.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Fred Clarke</h5>
                <p class="mt-2 md-none">Consultant, Stepchange Global</p>
                <p class="mt-2 dd-none">Consultant Stepchange Global</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="139"
              data-nm="Andres Cadenas"
              data-dsgn="Container Liner Service Manager, MARGUISA SHIPPING LINES"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_139.png"
              data-linkedin="https://www.linkedin.com/in/andres-cadenas-casanova-534b4761"
            >
              <img
                src="./images/profile_139.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Andres Cadenas
                </h5>
                <p class="mt-2 md-none">
                  Container Liner Service Manager, MARGUISA SHIPPING LINES
                </p>
                <p class="mt-2 dd-none">
                  Container Liner Service Manager MARGUISA
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="147"
              data-nm="Blake Burnette"
              data-dsgn="CTO, IOTeq"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_147.png"
              data-linkedin="https://www.linkedin.com/in/blake-burnette-7b3269a?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3Ba%2BKTiBU7SseJ%2Bfvo0yk%2BLA%3D%3D"
            >
              <img
                src="./images/profile_147.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Blake Burnette
                </h5>
                <p class="mt-2 md-none">CTO, IOTeq</p>
                <p class="mt-2 dd-none">CTO IOTeq</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="158"
              data-nm="Sayed Habib"
              data-dsgn="DGM - Business Development, Danube Home"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_158.jpg"
              data-linkedin="https://www.linkedin.com/in/sayed-habib-8354a14/"
            >
              <img
                src="./images/profile_158.jpg"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Sayed Habib</h5>
                <p class="mt-2 md-none">
                  DGM - Business Development, Danube Home
                </p>
                <p class="mt-2 dd-none">DGM - Business Development Danube</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="159"
              data-nm="Sheikh Faraz Osmany"
              data-dsgn="DGM (Strategic Information Systems), Indian Oil Corporation Limited"
              data-bio="Having served with Indian Oil (IOCL) at Marketing Head Office, Corporate Office, Petroleum Planning & Analysis Cell, International Energy Agency, Faraz currently serves as Deputy General Manager (Strategic Information Systems) at Refineries HQ. He has 20+ years of wide experience across verticals and currently is part of the Digital team and leads the Robotic Process Automation, Cloud Computing, AI/ML & Emerging Technologies streams."
              data-img="./images/profile_159.png"
              data-linkedin="https://www.linkedin.com/in/sheikhfarazosmany"
            >
              <img
                src="./images/profile_159.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Sheikh Faraz Osmany
                </h5>
                <p class="mt-2 md-none">
                  DGM (Strategic Information Systems), Indian Oil Corporation
                  Limited
                </p>
                <p class="mt-2 dd-none">
                  DGM (Strategic Information Systems) Indian
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="161"
              data-nm="Jason Baihly"
              data-dsgn="Digital Production Business Owner North America Onshore, SLB"
              data-bio="Jason Baihly is the Digital Production Business Owner for SLB North America. In this role, he focuses on developing and deploying digital solutions along the entire hydrocarbon production and processing chain. He earned a bachelor's in Civil/Environmental Engineering from the South Dakota School of Mines and a master's in Management of the Oil and Gas Industry from Heriot-Watt University. Raj Kannan is an enterprise and data technology leader, as well as a SLB recognized Advisor, contributing to upstream oil and gas for more than 25 years. At SLB he has led business development, software design and architecture, service management with clients in North and South America. He believes in driving business success through open digital solutions, and collaboration between operators, ISVs, partners, and universities"
              data-img="./images/profile_161.png"
              data-linkedin="linkedin.com/in/jason-baihly-b2800258"
            >
              <img
                src="./images/profile_161.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Jason Baihly
                </h5>
                <p class="mt-2 md-none">
                  Digital Production Business Owner North America Onshore, SLB
                </p>
                <p class="mt-2 dd-none">
                  Digital Production Business Owner North
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="164"
              data-nm="Chika J. Nwokeji"
              data-dsgn="Digital Support Advisor, ExxonMobil"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_164.png"
              data-linkedin="https://www.linkedin.com/in/chika-nwokeji-pmp-9bb0016b?lipi=urn%3Ali%3Apage%3Ad_sales2_lead%3BExcxEKhTTgWPl%2B0aP9l6AA%3D%3D"
            >
              <img
                src="./images/profile_164.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Chika J. Nwokeji
                </h5>
                <p class="mt-2 md-none">Digital Support Advisor, ExxonMobil</p>
                <p class="mt-2 dd-none">Digital Support Advisor ExxonMobil</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="165"
              data-nm="Raj Rapaka"
              data-dsgn="Digital Transformation / Technology Scouting & Ventures Advisor, ExxonMobil"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_165.png"
              data-linkedin="https://www.linkedin.com/in/rajrapaka/"
            >
              <img
                src="./images/profile_165.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Raj Rapaka</h5>
                <p class="mt-2 md-none">
                  Digital Transformation / Technology Scouting & Ventures
                  Advisor, ExxonMobil
                </p>
                <p class="mt-2 dd-none">
                  Digital Transformation / Technology Scouting
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="170"
              data-nm="Jim Claunch"
              data-dsgn="Digital Transformation Thought Leader, Bain & Company"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_170.webp"
              data-linkedin="https://www.linkedin.com/in/jim-claunch-73932130/"
            >
              <img
                src="./images/profile_170.webp"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Jim Claunch</h5>
                <p class="mt-2 md-none">
                  Digital Transformation Thought Leader, Bain & Company
                </p>
                <p class="mt-2 dd-none">
                  Digital Transformation Thought Leader Bain
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="172"
              data-nm="Soumit Roy"
              data-dsgn="Director, Jade Global"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/default.png"
              data-linkedin="linkedin.com/in/soumit-roy-420a5044"
            >
              <img src="./images/default.png" class="img-fluid w-100" alt="" />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Soumit Roy</h5>
                <p class="mt-2 md-none">Director, Jade Global</p>
                <p class="mt-2 dd-none">Director Jade Global</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="179"
              data-nm="Alberto Iniesta"
              data-dsgn="Director- digital solutions, Worley"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_179.jpg"
              data-linkedin="https://www.linkedin.com/in/alberto-iniesta-serrano-16b65763"
            >
              <img
                src="./images/profile_179.jpg"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Alberto Iniesta
                </h5>
                <p class="mt-2 md-none">Director- digital solutions, Worley</p>
                <p class="mt-2 dd-none">Director- digital solutions Worley</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="188"
              data-nm="Tony Downes"
              data-dsgn="Director of Process Safety & Loss Prevention, Honeywell"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_188.jfif"
              data-linkedin="https://www.linkedin.com/in/tony-downes-99782121/"
            >
              <img src="./profile_188.jfif" class="img-fluid w-100" alt="" />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">Tony Downes</h5>
                <p class="mt-2 md-none">
                  Director of Process Safety & Loss Prevention, Honeywell
                </p>
                <p class="mt-2 dd-none">Director of Process Safety &</p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="200"
              data-nm="Crispin Chatar"
              data-dsgn="Drilling Subject Matter Expert- Silicon Valley, California, Schlumberger STIC"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_200.png"
              data-linkedin="https://www.linkedin.com/in/crispin-chatar-1aa45827"
            >
              <img
                src="./images/profile_200.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Crispin Chatar
                </h5>
                <p class="mt-2 md-none">
                  Drilling Subject Matter Expert- Silicon Valley, California,
                  Schlumberger STIC
                </p>
                <p class="mt-2 dd-none">
                  Drilling Subject Matter Expert- Silicon
                </p>
              </div>
            </div>
            <div
              class="card exclusiveSpeakers-card border-0 speaker-bio"
              data-id="201"
              data-nm="Alfredo Rodriguez"
              data-dsgn="E&P Digital Sr. Manager, Repsol"
              data-bio="Speaker Biography yet to be published"
              data-img="./images/profile_201.png"
              data-linkedin="https://www.linkedin.com/in/alfredo-rodr%C3%ADguez-a396538"
            >
              <img
                src="./images/profile_201.png"
                class="img-fluid w-100"
                alt=""
              />
              <div class="card-body px-3">
                <h5 class="card-title a-fw-900 a-ff-rh a-fs-18">
                  Alfredo Rodriguez
                </h5>
                <p class="mt-2 md-none">E&P Digital Sr. Manager, Repsol</p>
                <p class="mt-2 dd-none">E&P Digital Sr. Manager Repsol</p>
              </div>
            </div>
          </div>
        </div>
        <!--<div class="container mt-md-5 mt-3">
        <div class="row">
          <div
            class="col-xl-10 col-lg-9 col-md-8 col-12 d-flex align-items-center justify-content-md-end justify-content-center">
            <h6 class="mb-0 a-ff-ss a-fw-400 a-fs-16">Explore more about our conference speakers</h6>
          </div>
          <div
            class="col-xl-2  col-lg-3 col-md-4 col-12 d-flex align-items-center justify-content-md-end justify-content-center mt-md-0 mt-3">
            <button class="px-3 btn-blue-thin-fill" type="button" onclick="location.href='/speakers-directory';">
              View all speakers
            </button>
          </div>
        </div>
      </div>-->
      </div>
      <div class="a-mb-150">
        <div class="container">
          <div class="row m-0 d-flex align-items-center justify-content-center">
            <div class="col-12">
              <div
                class="row m-0 d-flex align-items-center justify-content-center"
              >
                <div
                  class="col-xl-8 col-lg-10 align-items-center justify-content-center flex-column company-slider-container"
                >
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-1.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 GOLD SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Inventing Technology for a Better Future
                        </h4>
                        <p>
                          Drives innovation in mobility, industry, and
                          sustainability, creating smart, connected solutions
                          that enhance lives and protect resources worldwide
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-2.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 GOLD SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Optimizing Subsea Operations with Innovation
                        </h4>
                        <p>
                          Enaimco's cloud-based Operational Twin enhances subsea
                          efficiency, reducing costs, risks, and emissions while
                          maximizing energy production sustainably
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-3.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2025 SESSION SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Ensuring high-reliability operations with expert
                          solutions worldwide
                        </h4>
                        <p>
                          Delivering top-tier project management, automation,
                          and operational readiness solutions to ensure
                          efficiency, reliability, and success for industries
                          globally.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-4.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2024 SESSION SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Plan, design, and deliver indoor wireless networks
                          smarter and faster with iBwave
                        </h4>
                        <p>
                          Revolutionizing indoor wireless networks with
                          cutting-edge software, seamless connectivity, enhanced
                          coverage, and expert training for telecom leaders
                          worldwide.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-5.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 PLATINUM SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Powering Innovation for a Sustainable Future
                        </h4>
                        <p>
                          Driving energy transformation with cutting-edge
                          technology, unlocking sustainable solutions, and
                          shaping a balanced planet for future generations
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-6.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2024 Diamond sponsors</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Unleash purposeful connections.
                        </h4>
                        <p>
                          Accenture and Adobe harness the very best of tech,
                          data, and creativity to deliver high-value, innovative
                          experiences that accelerate growth for our clients.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-7.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2022 GOLD SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Smart Solutions for a Sustainable Future
                        </h4>
                        <p>
                          Revolutionizing energy and chemicals with cutting-edge
                          technology, enabling net-zero goals through
                          innovation, automation, and sustainable digital
                          transformation.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-8.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2022 GOLD SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Unleashing Data, Powering Smart Decisions
                        </h4>
                        <p>
                          TIBCO transforms real-time data into intelligence,
                          enabling businesses to innovate faster, optimize
                          operations, and make smarter, data-driven decisions
                          effortlessly.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-9.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2024 Diamond sponsors</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Unleash purposeful connections.
                        </h4>
                        <p>
                          Accenture and Adobe harness the very best of tech,
                          data, and creativity to deliver high-value, innovative
                          experiences that accelerate growth for our clients.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-10.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 SESSION SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Optimizing salinity to maximize oil recovery
                          efficiently
                        </h4>
                        <p>
                          Boosting oil recovery 5-15% by optimizing
                          salinity—chemical-free, cost-effective, and
                          scientifically proven for maximum reservoir
                          performance.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-11.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 BRONZE SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Precision Leak Detection, Trusted Worldwide
                        </h4>
                        <p>
                          Atmos safeguards pipelines with advanced leak
                          detection, theft prevention, and simulation
                          technology—ensuring safety, efficiency, and
                          environmental protection globally.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-12.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 GOLD SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Optimizing Energy, Reducing Emissions Smartly
                        </h4>
                        <p>
                          Validere empowers energy companies with data-driven
                          emissions management, ensuring sustainability,
                          regulatory compliance, and operational efficiency for
                          a cleaner future
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-13.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 GOLD SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          AI-Powered Optimization for Manufacturing
                        </h4>
                        <p>
                          Accenture and Adobe harness the very best of tech,
                          data, and creativity to deliver high-value, innovative
                          experiences that accelerate growth for our
                          clients.Empowers process engineers with AI-driven
                          insights to enhance production efficiency, reduce
                          costs, and accelerate manufacturing scale-up
                          seamlessly.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-14.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2022 PLATINUM SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Smart Solutions for Phase Separation
                        </h4>
                        <p>
                          Optimize separation performance with MySep—advanced
                          software for designing, evaluating, and simulating
                          separators, ensuring efficiency, accuracy, and
                          seamless integration.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-15.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2022 PLATINUM SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Empowering Industry with Smart Innovation
                        </h4>
                        <p>
                          Transforms industries with AI-driven insights, secure
                          cloud solutions, and collaborative tools—enhancing
                          efficiency, sustainability, and global connectivity
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-16.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 GOLD SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Transforming Data into Powerful Solutions
                        </h4>
                        <p>
                          Palantir empowers institutions with advanced data
                          integration and analytics, solving critical challenges
                          in national security, healthcare, energy, and beyond
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-17.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 BRONZE SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Advancing Humanity Through Smart Engineering
                        </h4>
                        <p>
                          Shaping communities through engineering, science, and
                          advisory—WSP designs the future with smart,
                          sustainable, and human-centric solutions for all.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-18.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2022 GOLD SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Revolutionizing Energy with Smart Technology
                        </h4>
                        <p>
                          Delivers cutting-edge digital solutions and
                          technologies, optimizing energy exploration, well
                          construction, reservoir performance, and production
                          systems worldwide.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-19.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 GOLD SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Powering Control, Efficiency, and Innovation
                        </h4>
                        <p>
                          Nexus Controls supplies control and safety systems for
                          heavy duty and aeroderivative gas turbines, steam and
                          hydro turbines, generators, compressors
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="company-slider-container-area">
                    <div class="row">
                      <div
                        class="col-sm-4 d-flex align-items-center justify-content-center"
                      >
                        <img
                          src="{{ asset('website/assets/images/sponsor-20.jpg') }}"
                          alt=""
                          class="img-fluid w-100"
                        />
                      </div>
                      <div class="col-sm-8 mt-sm-0 mt-5">
                        <p class="mb-0">2023 GOLD SPONSOR</p>
                        <h4 class="a-fw-900 a-ff-rh">
                          Optimizing Infrastructure with Digital Intelligence
                        </h4>
                        <p>
                          Cohesive transforms asset management with digital
                          twins, expert advisory, and innovative solutions,
                          ensuring safe, efficient, and sustainable
                          infrastructure operations.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="row container m-0 d-flex align-items-center justify-content-center"
              >
                <div
                  class="col-xl-8 col-lg-10 align-items-center justify-content-center flex-column"
                >
                  <div
                    class="align-items-center justify-content-center d-flex mt-sm-4"
                  >
                    <button
                      class="btn left-arrow p-0 mr-1 prev-company-button h-auto"
                      type="button"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                      >
                        <path
                          fill="currentColor"
                          d="M11.03 8.53a.75.75 0 1 0-1.06-1.06l-4 4a.75.75 0 0 0 0 1.06l4 4a.75.75 0 1 0 1.06-1.06l-2.72-2.72H18a.75.75 0 0 0 0-1.5H8.31z"
                        ></path>
                      </svg>
                    </button>
                    <div class="w-100">
                      <div class="company-image-slider">
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-1.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-2.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-3.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-4.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-5.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-6.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-7.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-8.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-9.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-10.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-11.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-12.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-13.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-14.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-15.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-16.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-17.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-18.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-19.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                        <div
                          class="company-image p-3 d-flex align-items-center justify-content-center"
                        >
                          <img
                            src="{{ asset('website/assets/images/sponsor-20.jpg') }}"
                            alt=""
                            class="w-100 img-fluid"
                          />
                        </div>
                      </div>
                    </div>
                    <button
                      class="btn right-arrow p-0 ml-1 next-company-button h-auto"
                      aria-disabled="false"
                      type="button"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                      >
                        <path
                          fill="currentColor"
                          d="M13.47 8.53a.75.75 0 0 1 1.06-1.06l4 4a.75.75 0 0 1 0 1.06l-4 4a.75.75 0 1 1-1.06-1.06l2.72-2.72H6.5a.75.75 0 0 1 0-1.5h9.69z"
                        ></path>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Our brand -->
      <section class="a-mb-150">
        <div class="container">
          <h2 class="sub-headers">Our brand</h2>
          <div class="row mt-sm-5 mt-0">
            <div class="col-lg-3 col-sm-12 col-12 pb-md-0 p-3">
              <div
                class="ourbrand_cards"
                style="background-image: url({{ asset('website/assets/images/service-box-1.svg') }})"
              >
                <div class="h-100 ourbrand-cards-inner">
                  <div class="">
                    <h3 class="a-fw-900 a-ff-rh">Conferences</h3>
                    <p>
                      Explore emerging technologies, share transformative
                      insights, and foster unparalleled networking
                      opportunities.
                    </p>
                    <button
                      class="mt-4 btn-white-thick-border"
                      type="button"
                      onclick="location.href='/conferences';"
                    >
                      Explore Events
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-12 col-12 pb-md-0 p-3">
              <div
                class="ourbrand_cards"
                style="background-image: url({{ asset('website/assets/images/service-box-2.svg') }})"
              >
                <div class="h-100 ourbrand-cards-inner">
                  <div class="">
                    <h3 class="a-fw-900 a-ff-rh">Career Options</h3>
                    <p>
                      Explore a collaborative culture of inclusion, growth, and
                      originality, supported by resources that make a difference
                      in your life.
                    </p>
                    <button
                      class="mt-4 btn-white-thick-border"
                      type="button"
                      onclick="location.href='/careers';"
                    >
                      View Positions
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-12 col-12 pb-md-0 p-3">
              <div
                class="ourbrand_cards"
                style="background-image: url({{ asset('website/assets/images/service-box-3.svg') }})"
              >
                <div class="h-100 ourbrand-cards-inner">
                  <div class="">
                    <h3 class="a-fw-900 a-ff-rh">Strategic Lead Generation</h3>
                    <p>
                      Build your own lead gen strategy with us to improve
                      conversion rates and grow your revenue.
                    </p>
                    <button
                      class="mt-4 btn-white-thick-border"
                      type="button"
                      onclick="location.href='/leadgeneration';"
                    >
                      Become a Partner
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-12 col-12 pb-md-0 p-3">
              <div
                class="ourbrand_cards"
                style="background-image: url({{ asset('website/assets/images/service-box-4.svg') }})"
              >
                <div class="h-100 ourbrand-cards-inner">
                  <div class="">
                    <h3 class="a-fw-900 a-ff-rh">B2B Meetings</h3>
                    <p>
                      Connecting buyers and suppliers through VIP meetings to
                      facilitate collaboration and market-leading product
                      exchanges.
                    </p>
                    <button
                      class="mt-4 btn-white-thick-border"
                      type="button"
                      onclick="location.href='/leadgeneration';"
                    >
                      Experience Now
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
                 
@endsection
