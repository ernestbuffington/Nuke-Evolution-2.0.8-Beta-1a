<h1>{L_CONFIGURATION_TITLE}</h1>

<p>{L_CONFIGURATION_EXPLAIN}</p>

<!-- BEGIN use_dhtml -->
<script language="Javascript" type="text/javascript">
//<[CDATA[
function show(id){
    if (document.getElementById(id).style.display == ""){
        document.getElementById(id).style.display = "none";
    } else {
        document.getElementById(id).style.display = "";
	}
}
//]]>
</script>
<!-- END use_dhtml -->

<form action="{S_CONFIG_ACTION}" method="post">