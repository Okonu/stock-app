 <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Fursa &amp; Developed by <a href="http://brancetech.com/" target="_blank">BranceTech</a> 2023</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->   
         <!-- Required vendors -->
    <script src="{{ asset('front/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('front/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('front/vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('front/vendor/owl-carousel/owl.carousel.js') }}"></script>
    <!-- Chart piety plugin files -->
    <script src="{{ asset('front/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('front/js/plugins-init/datatables.init.js') }}"></script>
    <script src="{{ asset('front/vendor/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('front/vendor/jquery-nice-select/js/jquery.nice-select.min.js') }}"></script>
    <!-- Apex Chart -->
    <script src="{{ asset('front/vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('front/vendor/apexchart/apexchart.js') }}"></script>
    <!-- Dashboard 1 -->
    <script src="{{ asset('front/js/dashboard/dashboard-1.js') }}"></script>
    <script src="{{ asset('front/js/custom.min.js') }}"></script>
    <script src="{{ asset('front/js/dlabnav-init.js') }}"></script>
    <script src="{{ asset('front/js/demo.js') }}"></script>
    <script src="{{ asset('front/js/styleSwitcher.js') }}"></script>
    <style>
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #2196F3;
}

input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

</style>
</body>
</html>