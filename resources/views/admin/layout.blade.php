@include('admin.partials.header')
     
      <div class="container-fluid page-body-wrapper">
      
          @include('admin.partials.sidebar')
       
        <div class="main-panel">
           @yield('content')
           @include('admin.partials.footer')
         
        </div>
       
      </div>
      
    </div>
    <x-media-manager />
    
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="{{asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
        <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
   
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('assets/vendors/chart.js/chart.umd.js')}}"></script>
    <script src="{{asset('assets/vendors/progressbar.js/progressbar.min.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('assets/js/template.js')}}"></script>
    <script src="{{asset('assets/js/settings.js')}}"></script>
    <script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('assets/js/todolist.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{asset('assets/js/jquery.cookie.js')}}" type="text/javascript"></script>
   <script src="{{asset('assets/js/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/dashboard.js')}}"></script>
      @yield('script')
      
  </body>
</html>