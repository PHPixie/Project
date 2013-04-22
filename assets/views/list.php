<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Name</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($fairies as $fairy):?>
			<tr>
				<td><?php echo $fairy->id;?></td>
				<td><a href="/fairies/view/<?php echo $fairy->id;?>"><?php echo $fairy->name;?></a></td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>