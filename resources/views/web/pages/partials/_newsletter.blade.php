<!-- 9. Newsletter Strip -->
<section class="py-5 bg-primary">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 text-white text-center text-lg-start mb-4 mb-lg-0">
                <h3 class="fw-bold mb-2 text-white font-heading">Join the Explorer Club</h3>
                <p class="mb-0 text-white fs-5">Get the best travel secrets and exclusive deals delivered to your inbox.</p>
            </div>
            <div class="col-lg-5">
                <form id="explorerClubForm" method="POST" action="{{ route('newsletter.subscribe') }}">
                    @csrf
                    <input type="hidden" name="source" value="explorer_club">
                    <div class="input-group input-group-lg shadow-lg rounded-pill overflow-hidden bg-white p-1">
                        <input type="email" name="email" class="form-control border-0 shadow-none px-4" placeholder="Enter your email address" required>
                        <button class="btn btn-primary rounded-pill px-4" type="submit">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
