<style>
	.muted{
		float:right;
	}
</style>
<ul class="nav nav-tabs nav-stacked ">
	<?php foreach($polls as $poll):?>
		<li>
			<a href="<?php echo "/polls/poll/{$poll->id}"; ?>" >
				<?echo $poll->question;?>
				<div class="muted"><?php echo $poll->total_votes; ?> Votes</div>
			</a>
		</li>
	<?php endforeach;?>
</ul>
<a class="btn btn-block" href="/polls/add"><i class="icon-plus"></i> Add poll</a>