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
      // AJAX Sorting and Pagination
      $(document).on('click', '.ajax-sortable, .pagination a', function(e) {
          e.preventDefault();
          var url = $(this).attr('href') || $(this).data('url');
          if (!url || url === '#' || url === 'javascript:void(0)') return;

          var $container = $('#ajax-table-container');
          if (!$container.length) return; // Only if there is a container

          // Add a simple loading state
          $container.css('opacity', '0.5');

          $.ajax({
              url: url,
              type: 'GET',
              headers: { 'X-Requested-With': 'XMLHttpRequest' },
              success: function(response) {
                  $container.html(response);
                  $container.css('opacity', '1');
              },
              error: function() {
                  $container.css('opacity', '1');
                  console.error('Failed to load table data.');
              }
          });
      });
      // Prevent 'Enter' key in filter inputs from triggering the Export button
      $(document).on('keydown', 'form#filterForm input', function(e) {
          if (e.key === 'Enter' || e.keyCode === 13) {
              e.preventDefault();
              $(this).closest('form').find('button[type="submit"]:not([name="export"])').first().click();
          }
      });

      // AJAX form submission for Filter forms (prevents page reload and empty URL params)
      $(document).on('submit', 'form#filterForm', function(e) {
          var form = $(this);
          var submitter = e.originalEvent && e.originalEvent.submitter ? e.originalEvent.submitter : null;

          // If the Export button was clicked, allow standard form submission
          if (submitter && submitter.name === 'export') {
              // Temporarily disable empty fields so they don't appear in the URL
              form.find(':input').filter(function() { return !this.value; }).prop('disabled', true);
              setTimeout(function() { form.find(':input').prop('disabled', false); }, 100);
              return true;
          }

          // Otherwise, handle as an AJAX filter search
          e.preventDefault();

          // Serialize excluding empty fields
          var formData = form.serializeArray().filter(function(item) {
              return item.value.trim() !== '';
          });
          var queryString = $.param(formData);
          
          var url = form.attr('action');
          if (queryString) {
              url += '?' + queryString;
          }
          
          // Do NOT update URL with pushState so the address bar remains completely clean!
          // window.history.pushState({}, '', url);
          
          var $container = $('#ajax-table-container');
          if ($container.length) {
              $container.css('opacity', '0.5');
              $.ajax({
                  url: url,
                  type: 'GET',
                  headers: { 'X-Requested-With': 'XMLHttpRequest' },
                  success: function(response) {
                      $container.html(response);
                      $container.css('opacity', '1');
                  },
                  error: function() {
                      $container.css('opacity', '1');
                      console.error('Failed to load table data.');
                  }
              });
          } else {
              window.location.href = url;
          }
      });
  });

  // Global Reusable Dropdown Component Logic
  window.toggleCustomDropdown = function(idPrefix) {
    const panel  = document.getElementById(idPrefix + 'DropdownPanel');
    const arrow  = document.getElementById(idPrefix + 'Arrow');
    const trigger = document.getElementById(idPrefix + 'Trigger');
    if(!panel || !trigger) return;
    
    const isOpen = panel.style.display !== 'none';
    
    // Close all other dropdowns
    document.querySelectorAll('.custom-dropdown-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.custom-dropdown-arrow').forEach(a => a.style.transform = 'rotate(0deg)');
    document.querySelectorAll('.custom-dropdown-trigger').forEach(t => t.classList.remove('open'));
    
    if (isOpen) {
        panel.style.display = 'none';
        if(arrow) arrow.style.transform = 'rotate(0deg)';
        trigger.classList.remove('open');
    } else {
        panel.style.display = 'block';
        if(arrow) arrow.style.transform = 'rotate(180deg)';
        trigger.classList.add('open');
        const input = document.getElementById(idPrefix + 'SearchInput');
        if(input) input.focus();
    }
  };

  window.filterCustomDropdown = function(val, idPrefix) {
    const term  = val.toLowerCase();
    const items = document.querySelectorAll('#' + idPrefix + 'ItemsList .custom-item');
    let   found = 0;
    items.forEach(item => {
      const name = item.querySelector('.custom-item-name').textContent.toLowerCase();
      const show = name.includes(term);
      item.style.display = show ? '' : 'none';
      if (show) found++;
    });
    const noRes = document.getElementById(idPrefix + 'NoResults');
    if(noRes) noRes.classList.toggle('d-none', found > 0);
  };

  window.onCustomDropdownChange = function(rb, idPrefix) {
    const id    = rb.dataset.id;
    const name  = rb.dataset.name;
    const hidden= document.getElementById(idPrefix + '_value');
    const ph    = document.getElementById(idPrefix + 'Placeholder');

    if(hidden) hidden.value = id;
    document.querySelectorAll('#' + idPrefix + 'ItemsList .custom-item').forEach(l => l.classList.remove('selected'));
    
    const label = rb.closest('.custom-item');
    if(label) label.classList.add('selected');

    if(ph) ph.textContent = name;
    
    // Auto-close dropdown
    const panel = document.getElementById(idPrefix + 'DropdownPanel');
    if(panel) panel.style.display = 'none';
    const arrow = document.getElementById(idPrefix + 'Arrow');
    if(arrow) arrow.style.transform = 'rotate(0deg)';
    const trigger = document.getElementById(idPrefix + 'Trigger');
    if(trigger) trigger.classList.remove('open');
  };

  // Close dropdown on outside click
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-dropdown-wrapper')) {
      document.querySelectorAll('.custom-dropdown-panel').forEach(p => p.style.display = 'none');
      document.querySelectorAll('.custom-dropdown-arrow').forEach(a => a.style.transform = 'rotate(0deg)');
      document.querySelectorAll('.custom-dropdown-trigger').forEach(t => t.classList.remove('open'));
    }
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
