<?php $this->load->view('shared/top');?>

<script type="text/javascript">
function validateForm()
{
	var input = document.forms["myForm"]["definition"].value;
	if (input == null || input == "")
  	{
		alert("Ievadiet definīciju!");
		return false;
	}
	input = document.forms["myForm"]["name"].value;
	if (input == null || input == "")
  	{
		alert("Ievadiet nosaukumu!");
		return false;
	}
}
</script>

<div class="tabHeader">
	<span class="name">
		<span>Jauna objekta pievienošana</span>
	</span>
</div>
<div class="forma">
	<form name="myForm" action="/namedEntityDB/add_new_entity/" method="POST" onsubmit="return validateForm();">
		<table>
			<tr>
				<td>Definīcija:</td>
				<td>
					<input style="width: 400px;" type="text" name="definition" />
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>Laiks:</td>
				<td>
					<input style="width: 200px;" type="text" name="time" />
				</td>
			</tr>
			<tr>
				<td>Kategorija:</td>
				<td>
					<select style="width: 200px;" name="category">
						<?php foreach ($arrCategories as $arrCategory) :?>
						<option value="<?=$arrCategory['ID']?>" ><?=$arrCategory['name']?></option>
						<?php endforeach;?>
					</select>
					<span class="required">*</span>
				</td>
			</tr>
			<tr height="15px"></tr>
			<tr>
				<td>Nosaukums:</td>
				<td>
					<input style="width: 200px;" type="text" name="name" />
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>Laiks no:</td>
				<td>
					<input style="width: 200px;" type="text" name="name_time_from" />
				</td>
			</tr>
			<tr>
				<td>Laiks līdz:</td>
				<td>
					<input style="width: 200px;" type="text" name="name_time_to" />
				</td>
			</tr>
			<tr>
				<td>Komentārs:</td>
				<td>
					<textarea style="width: 200px" name="name_comment" ></textarea>
				</td>
			</tr>
			<tr height="15px"></tr>
			<tr>
				<td></td>
				<td>
					<input style="width: 200px;" type="submit" value="Pievienot" />
				</td>
			</tr>
		</table>
	</form>
</div>
	
<?php $this->load->view('shared/bottom');?>