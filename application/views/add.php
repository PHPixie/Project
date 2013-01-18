<script>
	$(function(){
		$('#addOption').click(function(evt){
			evt.preventDefault();
			
			var newOption=$('.option:eq(0)').clone();
			newOption.find('input').val('').attr('placeholder','Option #'+($('.option').length+1));
			$('#options').append(newOption);
		})
	})
</script>
<form method="POST">
<fieldset>
	<legend>Add a new poll</legend>
	<label>Question</label>
	<input type="text" placeholder="Type your question here..." name="question" />
	<label>Options</label>
	<div id="options">
		<div class="option">
			<input type="text" name="options[]" placeholder="Option #1" />
		</div>
		<div class="option">
			<input type="text" name="options[]" placeholder="Option #2" />
		</div>
		<div class="option">
			<input type="text" name="options[]" placeholder="Option #3" />
		</div>
	</div>
	<button class="btn" id="addOption"><i class="icon-plus"></i> Add option</button>
	<button class="btn btn-primary"><i class="icon-ok icon-white"></i> Save poll </button>
	
</fieldset>
</form>
<a class="btn btn-link" href="/polls">&lt; Back to polls</a>