  <style type="text/css">
#box {
width: 400px;
margin: 0 auto;
overflow: auto;
border: 1px solid #0f0;
padding: 2px;
text-align: justify;
background: transparent;
}
</style>

<script>
var pal = "A but tuba";
function palid(pal){
	var f = pal.replace(/ /g, "").toLocaleLowerCase();
	var b = f.split("").reverse().join("");
	return f === b;
}
console.log(palid(pal));
</script>
<body>



<textarea  class="form-control textarea-texts" id="add_post" name="add_post" placeholder="<?php echo "stuff";//$_SESSION['LANG']['what_new']; ?>">**<?php echo $this->teamDetails->team_fullname; ?>**:<?php echo $this->teamDetails->post_details; ?></textarea>
<div id="box">
<h4>My Links</h4>
<a href=">• Paragraph One Here.</p><p><a href="URL path to external link" title="Mouseover Description">Link Text Description</a></p>
<p><a href="URL path to external link" title="Mouseover Description">Link Text Description</a></p>
<p><a href="URL path to external link" title="Mouseover Description">Link Text Description</a></p>
<p><a href="URL path to external link" title="Mouseover Description">Link Text Description</a></p>
<p><a href="URL path to external link" title="Mouseover Description">Link Text Description</a></p>
</div>
</body>