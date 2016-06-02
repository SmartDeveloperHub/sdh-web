<script type="html/template" id="html-template">
    @yield('html')
</script>
<script type="application/javascript">
     var DASHBOARD_HTML_HASH = "{{md5(View::getSections()['html'])}}";
    $(".main-content").html($('#html-template').html());
    _REQUIREJS_DASHBOARD_DEPENDENCIES = @yield('require', '[]');
    require( _REQUIREJS_DASHBOARD_DEPENDENCIES,

        // Dashboard script
        @yield('script', 'function(){}')

    );


</script>