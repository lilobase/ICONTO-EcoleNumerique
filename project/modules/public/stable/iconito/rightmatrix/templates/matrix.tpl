<h2>Welcome in the matrix</h2>
Your choice :
<a id="redpill" href="#">red pill</a> or <a id="bluepill" href="#" >blue pill</a>
<div id="matrix">
{$ppo->matrix}
</div>

{literal}
<script type="text/javascript">

    jQuery(document).ready(function($){
        $("#matrix").hide();

        $("#bluepill").click(function(){
            $("#matrix").show();
        });

        $("#redpill").click(function(){
            $("#matrix").hide();
        });
    });

    </script>
{/literal}