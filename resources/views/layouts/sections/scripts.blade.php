<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/popper/popper.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/bootstrap.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/node-waves/node-waves.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/hammer/hammer.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/i18n/i18n.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/typeahead-js/typeahead.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/menu.js')) }}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>

<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>

<!-- custom js -->
<script src="{{asset('assets/js/developer.js')}}"></script>

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset(mix('assets/js/main.js')) }}"></script>

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function() {
      let activeForm = null;

      // Detect button click on delete forms
      $(document).on('click', 'form:has(input[name="_method"][value="DELETE"]) button[type="submit"], form:has(input[name="_method"][value="DELETE"]) input[type="submit"]', function(e) {
          activeForm = $(this).closest('form')[0];
      });

      // Detect form submit for delete forms
      $(document).on('submit', 'form:has(input[name="_method"][value="DELETE"])', function(e) {
          activeForm = this;
      });

      // Override global window.confirm to intercept native delete confirmation alerts
      const originalConfirm = window.confirm;
      window.confirm = function(message) {
          if (activeForm) {
              const form = activeForm;
              activeForm = null; // reset reference immediately

              // If this form has already been confirmed via SweetAlert2, proceed with submit
              if ($(form).data('confirmed')) {
                  return true;
              }

              // Display beautiful SweetAlert2 modal in the center
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#4f46e5', // Brand Indigo primary
                  cancelButtonColor: '#6b7280', // Secondary slate grey
                  confirmButtonText: 'Yes, delete it!',
                  cancelButtonText: 'Cancel',
                  customClass: {
                      confirmButton: 'btn btn-danger me-2',
                      cancelButton: 'btn btn-secondary'
                  },
                  buttonsStyling: false
              }).then((result) => {
                  if (result.isConfirmed) {
                      $(form).data('confirmed', true);
                      form.submit();
                  }
              });

              return false; // Prevent native form submit immediately
          }
          return originalConfirm(message);
      };

      // Global real-time frontend table search
      $(document).on('input', '.global-search-input', function() {
          var query = $(this).val().toLowerCase();
          var $table = $('table');
          
          $table.find('tbody tr').each(function() {
              var text = $(this).text().toLowerCase();
              
              // Skip empty state rows
              if ($(this).find('td[colspan]').length) {
                  return;
              }
              
              $(this).toggle(text.indexOf(query) > -1);
          });
      });
  });
</script>

@yield('page-script')
<!-- END: Page JS-->

<style>
  /* Force Add and Save/Submit buttons in the admin to be orange */
  html body a.btn.btn-primary[href*="create"],
  html body button.btn.btn-primary[onclick*="create"],
  html body .d-flex.align-items-center.gap-2 > a.btn.btn-primary,
  html body .d-flex.align-items-center.gap-2 > a.btn-primary,
  html body button[type="submit"].btn-primary,
  html body input[type="submit"].btn-primary,
  html body form button.btn-primary {
    background: #ff9f1c !important;
    background-color: #ff9f1c !important;
    background-image: none !important;
    border-color: #ff9f1c !important;
    color: #ffffff !important;
    --bs-btn-bg: #ff9f1c !important;
    --bs-btn-border-color: #ff9f1c !important;
    --bs-btn-hover-bg: #e58f19 !important;
    --bs-btn-hover-border-color: #e58f19 !important;
    --bs-btn-active-bg: #e58f19 !important;
    --bs-btn-active-border-color: #e58f19 !important;
  }
  html body a.btn.btn-primary[href*="create"]:hover,
  html body button.btn.btn-primary[onclick*="create"]:hover,
  html body .d-flex.align-items-center.gap-2 > a.btn.btn-primary:hover,
  html body .d-flex.align-items-center.gap-2 > a.btn-primary:hover,
  html body button[type="submit"].btn-primary:hover,
  html body input[type="submit"].btn-primary:hover,
  html body form button.btn-primary:hover {
    background: #e58f19 !important;
    background-color: #e58f19 !important;
    background-image: none !important;
    border-color: #e58f19 !important;
    color: #ffffff !important;
  }
</style>
