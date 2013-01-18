<style>
	form{
		margin-bottom:0px;
	}
	
	.filled{
		background:#08C;
		height:20px;
	}
	.bar{
		width:100px;
	}
</style>
<h3><?php echo $poll->question; ?></h3>
<table class="table">
	<?php foreach($poll->options->find_all() as $option):?>
		<tr>
			<td><?php echo $option->text;?></td>
			<td><?php echo $option->votes;?></td>
			<td class="bar">
				<div class="filled" style="width:<?php echo $option->percent;?>%;"></div>
			</td>
			<td>
				<form method="POST">
					<input type="hidden" name="option" value="<?php echo $option->id; ?>" />
					<button class="btn btn-mini">Vote</button>
				</form>
			</td>
		</tr>
	<?php endforeach;?>
</table>
<a class="btn btn-link" href="/polls">&lt; Back to polls</a>