<?php $this->load->view('shared/top');?>

<script type="text/javascript">
	function show(id) {
		document.getElementById(id).style.display = "";
	}

function validateEntityForm()
{
	var input = document.forms["entityForm"]["definition"].value;
	if ((/^\s*$/).test(input)) 
	{
		alert("Ievadiet definīciju!");
		return false;
	}
	else
	{
		return true;
	}
}

function validateNameForm()
{
	var input1 = document.forms["nameForm"]["name"].value;

	if (input1 == 'nosaukums') input1 = '';
	
	if ((/^\s*$/).test(input1)) 
	{
		alert("Ievadiet nosaukumu!");
		return false;
	}
	else 
	{
		return true;
	}
}

function validateOntologyForm()
{
	var input1 = document.forms["ontologyForm"]["ontology_id"].value;

	if (input1 == 'objekta ID') input1 = '';
	
	if ((/^\s*$/).test(input1)) 
	{
		alert("Ievadiet objekta ID!");
		return false;
	}
	else 
	{
		return true;
	}
}

function validateResourceForm()
{
	var input1 = document.forms["resourceForm"]["resource_name"].value;
	var input2 = document.forms["resourceForm"]["resource_ref"].value;
	
	if (input1 == 'nosaukums') input1 = '';
	if (input2 == 'norāde') input2 = '';
	
	if ((/^\s*$/).test(input1) || (/^\s*$/).test(input2))
	{
		alert("Ievadiet resursa nosaukumu un norādi!");
		return false;
	}
	else 
	{
		return true;
	}
}
</script>

<!-- start: object info -->
<div class="tabHeader">
	<?php if (isset($bolEditEntity)) :?>
	<form id="entityForm" action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST" onsubmit="return validateEntityForm()">
		<input type="hidden" name="save_entity" value="1" />
		<input type="text" name="definition" value="<?=$definition?>" />
		<select name="category">
		<?php foreach ($arrCategories as $arrCategory) : // entītijas kategorijas ?>
			<option value="<?=$arrCategory['ID']?>" <?php if ($arrCategory['ID'] == $category) echo "selected='selected'"?>><?=$arrCategory['name']?></option>
		<?php endforeach;?>
		</select>
		<input type="text" name="time" value="<?=$time?>" />
		<input class="editBtn" type="submit" value="saglabāt" />
	</form>
	<?php else: ?>
	<span class="name" >
		<span><?=$definition?></span>
		<span><?=$category?></span>
		<span><?=$time?></span>
	</span>
	<?php if ($this->session->userdata("logged_in")): // pogas: objekta pamatinfo labošana; objekta dzēšana ?>
	<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst objektu?')">
		<input type="hidden" name="delete_entity" value="1" />
		<input class="editBtn" type="submit" value="dzēst" />
	</form>
	<span class="btnDelimiter">/</span>
	<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST">
		<input type="hidden" name="edit_entity" value="1" />
		<input class="editBtn" type="submit" value="labot" />
	</form>
	<?php endif;
	endif;?>
</div>
<!-- end: object info -->


<!-- start: names -->
<table class='orangeTable' >
	<tr class='orangeTableHeader' >
		<th style="width: 38%">Nosaukums</th>
		<th style="width: 12%">Sastopamība</th>
		<th style="width: 10%">No</th>
		<th style="width: 10%">Līdz</th>
		<th style="width: 30%; border-right-width: 0;">
			<span <?php if ($this->session->userdata("logged_in")) echo 'style="display: inline-block; margin-top: 3px;"' ?>>Komentārs</span>
			<?php if ($this->session->userdata("logged_in")):?><button class="addBtn" onclick="show('nameAdding')">pievienot jaunu</button><?php endif; ?>
		</th>
	</tr>
	<?php if ($this->session->userdata("logged_in")): // name adding?>
	<tr class='orangeTableRow' id="nameAdding"  <?php if (!isset($strNameError)) echo 'style="display: none;"';?> >
		<form id="nameForm" action="/namedEntityDB/browse/entity/<?=$ID;?>" method="POST" onsubmit="return validateNameForm()">
		<td><input style="width: 280px" type="text" name="name" value="<?php if (isset($strName)) echo $strName; else echo 'nosaukums'?>" /></td>
		<td></td>
		<td><input style="width: 55px" type="text" name="name_time_from" value="<?php if (isset($strNameTimeFrom)) echo $strNameTimeFrom; ?>" /></td>
		<td><input style="width: 55px" type="text" name="name_time_to" value="<?php if (isset($strNameTimeTo)) echo $strNameTimeTo; ?>" /></td>
		<td style="border-right-width: 0;">
			<input style="width: 160px" type="text" name="name_comment" value="<?php if (isset($strNameComment)) echo $strNameComment;?>" />
			<input type="hidden" name="add_name" value="1" />
			<input class="editBtn" type="submit" value="pievienot" />
		</td>
		</form>
	</tr>
	<?php endif;
	foreach ($arrNames as $arrName):?>
	<tr class='orangeTableRow'>
		<td><a href="/namedEntityDB/browse/name_documents/<?=$arrName['ID']?>"><?=$arrName['name'];?></a></td>
		<td><?if ($arrName['totalOccurrences'] != '') echo $arrName['totalOccurrences']; else echo 0;?></td>
		<?php if (isset($intEditName) && $intEditName == $arrName['ID']) :?>
		<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST">
		<td><input style="width: 55px" type="text" name="time_from" value="<?=$arrName['timeFrom'];?>" /></td>
		<td><input style="width: 55px" type="text" name="time_to" value="<?=$arrName['timeTo'];?>" /></td>
		<td style=" border-right-width: 0;">
			<input type="hidden" name="save_name" value="1" />
			<input type="hidden" name="name_id" value="<?=$arrName['ID']?>" />
			<input style="width: 160px" type="text" name="name_comment" value="<?=$arrName['comment'];?>">
			<input class="editBtn" type="submit" value="saglabāt" />
		</td>
		</form>
		<?php else: ?>
		<td><?=$arrName['timeFrom'];?></td>
		<td><?=$arrName['timeTo'];?></td>
		<td style=" border-right-width: 0;">
			<div style="display: inline-block;"><?=$arrName['comment'];?></div>
			<?php if ($this->session->userdata("logged_in")): ?>
			<div style="display: inline-block; float: right; width: 70px">
			<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst objekta nosaukumu?')">
				<input type="hidden" name="delete_name" value="1" />
				<input type="hidden" name="name_id" value="<?=$arrName['ID']?>" />
				<input class="editBtn" type="submit" value="dzēst" />
			</form>
			<span class="btnDelimiter">/</span>
			<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST">
				<input type="hidden" name="edit_name" value="1" />
				<input type="hidden" name="name_id" value="<?=$arrName['ID']?>" />
				<input class="editBtn" type="submit" value="labot" />
			</form>
			</div>
		</td>
			<?php endif;
		endif;?>
	</tr>
		<?php endforeach;?>
</table>
<!-- end: names -->


<!-- start: ontologic entities -->
<table class='orangeTable'>
	<tr class='orangeTableHeader'>
		<th>
			Saistīti objekti
		</th>
		<th style=" border-right-width: 0;">
			<span <?php if ($this->session->userdata("logged_in")) echo 'style="display: inline-block; margin-top: 3px;"' ?>>Komentārs</span>
			<?php if ($this->session->userdata("logged_in")): // pogas jauna nosaukuma pievienošanai ?>
			<button class="addBtn" onclick="show('ontologyAdding')">pievienot jaunu</button>
			<?php endif; ?>
		</th>
	</tr>
	<?php if ($this->session->userdata("logged_in")): // name adding?>
	<tr class='orangeTableRow' id="ontologyAdding"  <?php if (!isset($strOntologyError)) echo 'style="display: none;"';?> >
		<form id="ontologyForm" action="/namedEntityDB/browse/entity/<?=$ID;?>" method="POST" onsubmit="return validateOntologyForm()">
		<td>
			<input style="width: 300px" type="text" name="ontology_id" value="<?php if (isset($intOntologyID)) echo $intOntologyID; else echo 'objekta ID'?>" />
		</td>
		<td style="border-right-width: 0;">
			<input style="width: 300px" type="text" name="ontology_comment" value="<?php if (isset($strOntologyComment)) echo $strOntologyComment;?>" />
			<input type="hidden" name="add_ontology" value="1" />
			<input class="editBtn" type="submit" value="pievienot" />
		</td>
		</form>
	</tr>
	<?php endif;
	foreach ($arrOntologies as $arrOnt):?>
	<tr class='orangeTableRow'>
		<td >
			<a href="/namedEntityDB/browse/entity/<?=$arrOnt['ID'];?>"><?=$arrOnt['definition'];?></a>
		</td>
		<td style=" border-right-width: 0;">
			<?php if (isset($intEditOntology) && $intEditOntology == $arrOnt['ID']) :?>
			<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST">
				<input type="hidden" name="save_ontology" value="1" />
				<input type="hidden" name="ontology_id" value="<?=$arrOnt['ID']?>" />
				<input type="text" name="ontology_comment" value="<?=$arrOnt['comment'];?>">
				<input class="editBtn" type="submit" value="saglabāt" />
			</form>
			<?php else:?>
			<div style="display: inline-block; width: 300px"><?=$arrOnt['comment'];?></div>
			<?php if ($this->session->userdata("logged_in")): // pogas: objekta pamatinfo labošana; objekta dzēšana ?>
			<div style="display: inline-block; float: right; width: 70px">
			<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst ontoloģiski saistīto objektu?')">
				<input type="hidden" name="delete_ontology" value="1" />
				<input type="hidden" name="ontology_id" value="<?=$arrOnt['ID']?>" />
				<input class="editBtn" type="submit" value="dzēst" />
			</form>
			<span class="btnDelimiter">/</span>
			<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST">
				<input type="hidden" name="edit_ontology" value="1" />
				<input type="hidden" name="ontology_id" value="<?=$arrOnt['ID']?>" />
				<input class="editBtn" type="submit" value="labot" />
			</form>
			</div>
			<?php endif;
		 	endif;?>		
		</td>
	</tr>
	<?php endforeach;?>
</table>
<!-- end: ontologic entities -->

<!-- start: resources -->
<table class='orangeTable'>
	<tr class='orangeTableHeader'>
		<th>
			Ārējie resursi
		</th>
		<th style=" border-right-width: 0;">
			<span <?php if ($this->session->userdata("logged_in")) echo 'style="display: inline-block; margin-top: 3px;"' ?>>Komentārs</span>
			<?php if ($this->session->userdata("logged_in")): // pogas jauna nosaukuma pievienošanai ?>
			<button class="addBtn" onclick="show('resourceAdding')">pievienot jaunu</button>
			<?php endif; ?>
		</th>
	</tr>
	<?php if ($this->session->userdata("logged_in")): // name adding?>
	<tr class='orangeTableRow' id="resourceAdding"  <?php if (!isset($strResourceError)) echo 'style="display: none;"';?> >
		<form id="resourceForm" action="/namedEntityDB/browse/entity/<?=$ID;?>" method="POST" onsubmit="return validateResourceForm()">
		<td>
			<input style="width: 150px" type="text" name="resource_name" value="<?php if (isset($strResourceName)) echo $strResourceName; else echo 'nosaukums';?>" />
			<input style="width: 150px" type="text" name="resource_ref" value="<?php if (isset($strResourceRef)) echo $strResourceRef; else echo 'norāde'?>" />
		</td>
		<td style="border-right-width: 0;">
			<input style="width: 300px" type="text" name="resource_comment" value="<?php if (isset($strResourceComment)) echo $strResourceComment;?>" />
			<input type="hidden" name="add_resource" value="1" />
			<input class="editBtn" type="submit" value="pievienot" />
		</td>
		</form>
	</tr>
	<?php endif;
	foreach ($arrResources as $arrRes):?>
	<tr class='orangeTableRow'>
		<td>
			<a target="_blank" href="<?=(substr($arrRes['reference'], 0, 4) == 'http' ? '' : 'http://').$arrRes['reference'];?>"><?=$arrRes['name'];?></a>
		</td>
		<td style=" border-right-width: 0;">
			<?php if (isset($intEditResource) && $intEditResource == $arrRes['ID']) :?>
			<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST">
				<input type="hidden" name="save_resource" value="1" />
				<input type="hidden" name="resource_id" value="<?=$arrRes['ID']?>" />
				<input type="text" name="resource_comment" value="<?=$arrRes['comment']?>">
				<input class="editBtn" type="submit" value="saglabāt" />
			</form>
			<?php else: ?>
			<div style="display: inline-block; width: 300px"><?=$arrRes['comment'];?></div>
			<?php if ($this->session->userdata("logged_in")): // pogas: objekta pamatinfo labošana; objekta dzēšana ?>
			<div style="display: inline-block; float: right; width: 70px">
			<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst objekta ārējo resursu?')">
				<input type="hidden" name="delete_resource" value="1" />
				<input type="hidden" name="resource_id" value="<?=$arrRes['ID']?>" />
				<input class="editBtn" type="submit" value="dzēst" />
			</form>
			<span class="btnDelimiter">/</span>
			<form action="/namedEntityDB/browse/entity/<?=$ID?>" method="POST">
				<input type="hidden" name="edit_resource" value="1" />
				<input type="hidden" name="resource_id" value="<?=$arrRes['ID']?>" />
				<input class="editBtn" type="submit" value="labot" />
			</form>
			</div>
			<?php endif;
			endif;?>
		</td>
	</tr>
	<?php endforeach;?>
</table>
<!-- end: resources -->


<script type="text/javascript">
<?php
if (isset($strEntityError)) echo "alert('$strEntityError');";
if (isset($strNameError)) echo "alert('$strNameError');";
if (isset($strOntologyError)) echo "alert('$strOntologyError');";
if (isset($strResourceError)) echo "alert('$strResourceError');";
?>
</script>
<?php $this->load->view('shared/bottom');?>