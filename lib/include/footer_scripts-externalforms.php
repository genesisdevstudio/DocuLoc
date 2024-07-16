<!--   Core JS Files   -->
<script src=".<?=PATHURL;?>assets/js/core/popper.min.js"></script>
<script src=".<?=PATHURL;?>assets/js/core/bootstrap.min.js"></script>
<script src=".<?=PATHURL;?>assets/js/plugins/chartjs.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script src=".<?=PATHURL;?>assets/js/customized/crop-image.js"></script>

<script>
    // if (win && document.querySelector('#sidenav-scrollbar')) {
    if (document.querySelector('#sidenav-scrollbar')) {
    var options = {
        damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src=".<?=PATHURL;?>assets/js/argon-dashboard.min.js?v=2.0.4"></script>