<?php $this->load->view('shared/top');?>

<div class="tabHeader">
	<span class="name">
		<span>Autorizēšanās īpašvārdu vārdnīcas sistēmā</span>
	</span>
</div>
<div class="forma">
	<form action="/namedEntityDB/login/" method="POST">
		<table>
			<tr>
				<td>Lietotājvārds:</td>
				<td>
					<input type="text" name="user" 
					<?php if (isset($strUser)) echo 'value="'.$strUser.'"';?>
					/>
				</td>
			</tr>
			<tr>
				<td>Parole:</td>
				<td>
					<input type="password" name="passw" />
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" value="Autorizēties" />
				</td>
			</tr>
		</table>
	</form>
</div>


<?php $this->load->view('shared/bottom');?>