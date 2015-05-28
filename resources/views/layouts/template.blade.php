<script type="html/template" id="html-template">
    @yield('html')
</script>
<script type="application/javascript">
    $(".main-content").html($('#html-template').html());
    @yield('script')
</script>