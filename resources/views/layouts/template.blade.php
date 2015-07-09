<script type="html/template" id="html-template">
    @yield('html')
</script>
<script type="application/javascript">
    $(".main-content").html($('#html-template').html());
    _REQUIREJS_DASHBOARD_DEPENDENCIES = @yield('require', '[]');
    require( _REQUIREJS_DASHBOARD_DEPENDENCIES, function() {

        // Dashboard script
        @yield('script')

        // Hide the loading animation
        finishLoading();

    });


</script>