<script type="html/template" id="html-template">
    @yield('html')
</script>
<script type="application/javascript">
    $(".main-content").html($('#html-template').html());
    require( @yield('require', '[]'), function() {

        // Dashboard script
        @yield('script')

        // Hide the loading animation
        finishLoading();

    });


</script>