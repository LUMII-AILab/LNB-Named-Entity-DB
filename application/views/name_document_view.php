<?php $this->load->view('shared/top');?>
<script type="text/javascript">
	function show(id) {
		document.getElementById(id).style.display = "";
	}

	function hide(id) {
		document.getElementById(id).style.display = "none";
	}

	function validateNameForm()
	{
		var input = document.forms["nameForm"]["name"].value;
		if ((/^\s*$/).test(input)) 
		{
			alert("Ievadiet nosaukumu!");
			return false;
		}
		else
		{
			return true;
		}
	}
	function validateDocumentForm(id)
	{
		var input1 = document.forms[id]["occurrences"].value;
		var input2 = document.forms[id]["title"].value;
		var input3 = document.forms[id]["reference"].value;

		if (input2 == 'nosaukums') input2 = '';
		if (input3 == 'norāde') input3 = '';
		
		if ((/^\s*$/).test(input1) || (/^\s*$/).test(input2) || (/^\s*$/).test(input3)) 
		{
			alert("Ievadiet nosaukuma sastopamību, dokumenta nosaukumu un norādi!");
			return false;
		}
		else
		{
			return true;
		}
	}
</script>

<!-- start: name info -->
<div class="tabHeader">
	<?php if (isset($bolEditName)) :?>
	<form id="nameForm" action="/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>" method="POST" onsubmit="return validateNameForm()">
		<input type="hidden" name="save_name" value="1" />
		<input style="width: 300px;" type="text" name="name" value="<?php echo htmlspecialchars($arrNameData['name'])?>" />
		<input class="editBtn" type="submit" value="saglabāt" />
	</form>
	<?php else: ?>
	<span class="name" >
		<span><a style="cursor: pointer; font-size: 15px; font-weight: normal;" 
		<?php if (sizeof($arrNameEntities) > 1) :?>
		onclick="show('nameEntities')"
		<?php elseif (sizeof($arrNameEntities) == 1) : ?>
		href="/namedEntityDB/browse/entity/<?=$arrNameEntities[0]['ID']?>"
		<?php endif;?>><?=$arrNameData['name']?></a></span>
	</span>
	<?php if ($this->session->userdata("logged_in")): // pogas: objekta pamatinfo labošana; objekta dzēšana ?>
	<form action="/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst nosaukumu?')">
		<input type="hidden" name="delete_name" value="1" />
		<input class="editBtn" type="submit" value="dzēst" />
	</form>
	<span class="btnDelimiter">/</span>
	<form action="/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>" method="POST">
		<input type="hidden" name="edit_name" value="1" />
		<input class="editBtn" type="submit" value="labot" />
	</form>
	<?php endif;
	endif;?>
</div>
<?php if (sizeof($arrNameEntities) > 1) :?>
<div id='nameEntities' style="display: none; ">
	<div class="tabHeaderSmall">
		<span class="name">Nosaukuma objekti</span>
		<span style="font-size: 9px; float: right; cursor: pointer;"><a onclick="hide('nameEntities')">x Aizvērt</a></span>
	</div>
	<table class='orangeTableSmall'>
		<tr class='orangeTableHeaderSmall'>
			<th style="width: 25%;">Definīcija</th>
			<th style="width: 12%;">Laiks</th>
			<th style="width: 13%;">Kategorija</th>
			<th style="width: 50%; border-right-width: 0;">Komentārs</th>
		</tr>
		<?php foreach ($arrNameEntities as $arrNameEntity) :?>
		<tr class='orangeTableRowSmall'>
			<td><a style="font-size: 10px;" href="/namedEntityDB/browse/entity/<?=$arrNameEntity['ID']?>"><?php echo htmlspecialchars($arrNameEntity['definition'])?></a></td>
			<td><?php echo htmlspecialchars($arrNameEntity['time'])?></td>
			<td><?=$arrNameEntity['category']?></td>
			<td style="border-right-width: 0;"><?php echo htmlspecialchars($arrNameEntity['comment'])?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<?php endif;?>
<!-- end: name info -->


<!-- start pagination -->
<form id="paginatorForm" action="/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>" method="POST">
	<input type='hidden' name='order_by' value='<?=$strOrderBy?>' />
	<input type='hidden' name='order_mode' value='<?=$strOrderMode?>' />
	<input id="row_count_per_page" type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
	<input id="pageNum" type="hidden" name="pageNum" value="<?=$intPageNum;?>" />
</form>
<div>
	<table cellspacing="0" cellpadding="0" class="paginatorMainPanel">
		<tbody>
			<tr>
				<td align="left" style="vertical-align: middle; ">
					<table cellspacing="0" cellpadding="0" class="paginatorLeftPanel">
						<tbody>
							<tr>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="paginatorWhiteCountButtons">
										<div <?php if ($intPageNum != 1) echo "onclick=\"document.getElementById('pageNum').value='".($intPageNum-1)."'; document.forms['paginatorForm'].submit();\"" ?> style="margin:0 -4px 0 -3px;">&lt;</div>
									</button>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Lapa</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<input onchange="if(this.value > 0 && this.value <= <?=(ceil($intRowsCount/$intRowCountPerPage));?>) { document.getElementById('pageNum').value=this.value;} else { document.getElementById('pageNum').value=<?=(ceil($intRowsCount/$intRowCountPerPage));?>;}; document.forms['paginatorForm'].submit();" type="text" value="<?=$intPageNum;?>" />
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">no <?=(ceil($intRowsCount/$intRowCountPerPage));?></div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="paginatorWhiteCountButtons">
										<div <?php if ($intPageNum != ceil($intRowsCount/$intRowCountPerPage)) echo "onclick=\"document.getElementById('pageNum').value='". ($intPageNum+1) ."'; document.forms['paginatorForm'].submit();\"" ?> style="margin:0 -4px 0 -3px;">&gt;</div>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td align="left" style="vertical-align: middle; ">
					<table cellspacing="0" cellpadding="0" class="paginatorRightPanel">
						<tbody>
							<tr>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Atrasti:</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="paginatorUnitCount"><?=$intRowsCount;?> rezultāti</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Rādīt lapā:</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="<?php if ($intRowCountPerPage == 20) echo 'paginatorBlackCountButtons'; else echo 'paginatorWhiteCountButtons'; ?>">
										<div onclick="document.getElementById('row_count_per_page').value='20'; document.forms['paginatorForm'].submit();"  style="margin:0 -4px 0 -3px;">20</div>
									</button>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="<?php if ($intRowCountPerPage == 40) echo 'paginatorBlackCountButtons'; else echo 'paginatorWhiteCountButtons'; ?>">
										<div onclick="document.getElementById('row_count_per_page').value='40'; document.forms['paginatorForm'].submit();" style="margin:0 -4px 0 -3px;">40</div>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- end pagination -->

<!-- start documents -->
<table class='orangeTable'>
	<tr class='orangeTableHeader'>
		<th style="width: 15%">
			<form style='padding: 0; margin: 0;' action='/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>' method='POST'>
				<input type='hidden' name='order_by' value='occ' />
				<input type='hidden' name='order_mode' value='<?php if (isset($strOrderMode) && $strOrderMode == 'ASC') echo 'DESC'; else echo 'ASC';?>' />
				<input type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
				<input class='tableHeaderBtn' <?php if ($strOrderBy == 'occ') echo 'style="text-decoration: underline;"'?> type='submit' value='Sastopamība' />
			</form>
		</th>
		<th style="width: 55%">
			<form style='padding: 0; margin: 0;' action='/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>' method='POST'>
				<input type='hidden' name='order_by' value='title' />
				<input type='hidden' name='order_mode' value='<?php if (isset($strOrderMode) && $strOrderMode == 'ASC') echo 'DESC'; else echo 'ASC';?>' />
				<input type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
				<input <?php if ($this->session->userdata("logged_in")) echo 'style="display: inline-block; margin-top: 3px;"' ?> class='tableHeaderBtn' <?php if ($strOrderBy == 'title') echo 'style="text-decoration: underline;"'?> type='submit' value='Dokuments' />	
			</form>
			
			<?php if ($this->session->userdata("logged_in")): // poga jauna dokumenta pievienošanai ?>
			<button class="addBtn" onclick="show('documentAdding')">pievienot jaunu</button>
			<?php endif; ?>
		</th>
		<th style="width: 10%">
			<form style='padding: 0; margin: 0;' action='/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>' method='POST'>
				<input type='hidden' name='order_by' value='date' />
				<input type='hidden' name='order_mode' value='<?php if (isset($strOrderMode) && $strOrderMode == 'ASC') echo 'DESC'; else echo 'ASC';?>' />
				<input type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
				<input class='tableHeaderBtn' <?php if ($strOrderBy == 'date') echo 'style="text-decoration: underline;"'?> type='submit' value='Datums' />
			</form>
		</th>
		<th style="width: 20%; border-right-width: 0;">
			<form style='padding: 0; margin: 0;' action='/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>' method='POST'>
				<input type='hidden' name='order_by' value='type' />
				<input type='hidden' name='order_mode' value='<?php if (isset($strOrderMode) && $strOrderMode == 'ASC') echo 'DESC'; else echo 'ASC';?>' />
				<input type='hidden' name='row_count_per_page' value='<?=$intRowCountPerPage?>' />
				<input class='tableHeaderBtn' <?php if ($strOrderBy == 'type') echo 'style="text-decoration: underline;"'?> type='submit' value='Tips' />
			</form>
		</th>
	</tr>
	<?php if ($this->session->userdata("logged_in")): // document adding ?>
	<tr class='orangeTableRow' id="documentAdding"  <?php if (!isset($strDocumentError)) echo 'style="display: none;"';?> >
		<form id="documentForm" action="/namedEntityDB/browse/name_documents/<?=$arrNameData['ID'];?>" method="POST" onsubmit="return validateDocumentForm('documentForm')">
		<td>
			<input style="width: 70px" type="text" name="occurrences" <?php if (isset($intOccurrences)) echo "value='$intOccurrences'";?> />
		</td>
		<td>
			<input type="text" name="title" value="<?php if (isset($strTitle)) echo htmlspecialchars($strTitle); else echo 'nosaukums'?>" />
			<input type="text" name="reference" value="<?php if (isset($strReference)) echo htmlspecialchars($strReference); else echo 'norāde'?>" />
			<input type="hidden" name="add_document" value="1" />
			<input class="editBtn" type="submit" value="pievienot" />
		</td>
		<td>
			<input style="width: 50px" type="text" name="date" value="<?php if (isset($strDate)) echo htmlspecialchars($strDate);?>" />
		</td>
		<td style="border-right-width: 0;">
			<input style="width: 50px" type="text" name="type" value="<?php if (isset($strType)) echo htmlspecialchars($strType);?>" />
		</td>
		</form>
	</tr>
	<?php endif;
	foreach ($arrDocuments as $arrDoc):?>
	<tr class='orangeTableRow'>
		<?php if (isset($intEditDocument) && $intEditDocument == $arrDoc['ID']) :?>
		<form id="saveDocument" action="/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>" method="POST" onsubmit="return validateDocumentForm('saveDocument')">
			<td><input style="width: 70px" type="text" name="occurrences" value="<?=$arrDoc['occurrences']?>"></td>
			<td>
				<input type="hidden" name="save_document" value="1" />
				<input type="hidden" name="document_id" value="<?=$arrDoc['ID']?>" />
				<input type="text" name="title" value="<?php echo htmlspecialchars($arrDoc['title'])?>">
				<input type="text" name="reference" value="<?php echo htmlspecialchars($arrDoc['reference'])?>">
				<input class="editBtn" type="submit" value="saglabāt" />
			</td>
			<td><input style="width: 50px" type="text" name="date" value="<?php echo htmlspecialchars($arrDoc['date'])?>"></td>
			<td><input style="width: 50px" type="text" name="type" value="<?=$arrDoc['type']?>"></td>
		</form>
		<?php else: ?>
		<td><?=$arrDoc['occurrences']?></td>
		<td>
			<a target="_blank" href="<?php echo (substr($arrDoc['reference'], 0, 4) == 'http' ? '' : 'http://').htmlspecialchars($arrDoc['reference']), "|query:", $arrNameData['name'];?>"><?=htmlspecialchars($arrDoc['title']);?></a>
			<?php if ($this->session->userdata("logged_in")): // pogas: objekta pamatinfo labošana; objekta dzēšana ?>
			<form action="/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst nosaukuma dokumentu?')">
				<input type="hidden" name="delete_document" value="1" />
				<input type="hidden" name="document_id" value="<?=$arrDoc['ID']?>" />
				<input class="editBtn" type="submit" value="dzēst" />
			</form>
			<span class="btnDelimiter">/</span>
			<form action="/namedEntityDB/browse/name_documents/<?=$arrNameData['ID']?>" method="POST">
				<input type="hidden" name="edit_document" value="1" />
				<input type="hidden" name="document_id" value="<?=$arrDoc['ID']?>" />
				<input class="editBtn" type="submit" value="labot" />
			</form>
			<?php endif;?>
		</td>
		<td><?=htmlspecialchars($arrDoc['date'])?></td>
		<td style=" border-right-width: 0;"><?=$arrDoc['type']?></td>
		<?php endif; ?>
	</tr>
	<?php endforeach;?>
</table>
<!-- end documents -->


<!-- start pagination -->
<div>
	<table cellspacing="0" cellpadding="0" class="paginatorMainPanel">
		<tbody>
			<tr>
				<td align="left" style="vertical-align: middle; ">
					<table cellspacing="0" cellpadding="0" class="paginatorLeftPanel">
						<tbody>
							<tr>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="paginatorWhiteCountButtons">
										<div <?php if ($intPageNum != 1) echo "onclick=\"document.getElementById('pageNum').value='".($intPageNum-1)."'; document.forms['paginatorForm'].submit();\"" ?> style="margin:0 -4px 0 -3px;">&lt;</div>
									</button>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Lapa</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<input onchange="if(this.value > 0 && this.value <= <?=(ceil($intRowsCount/$intRowCountPerPage));?>) { document.getElementById('pageNum').value=this.value;} else { document.getElementById('pageNum').value=<?=(ceil($intRowsCount/$intRowCountPerPage));?>;}; document.forms['paginatorForm'].submit();" type="text" value="<?=$intPageNum;?>" />
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">no <?=(ceil($intRowsCount/$intRowCountPerPage));?></div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="paginatorWhiteCountButtons">
										<div <?php if ($intPageNum != ceil($intRowsCount/$intRowCountPerPage)) echo "onclick=\"document.getElementById('pageNum').value='". ($intPageNum+1) ."'; document.forms['paginatorForm'].submit();\"" ?> style="margin:0 -4px 0 -3px;">&gt;</div>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td align="left" style="vertical-align: middle; ">
					<table cellspacing="0" cellpadding="0" class="paginatorRightPanel">
						<tbody>
							<tr>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Atrasti:</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="paginatorUnitCount"><?=$intRowsCount;?> rezultāti</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<div class="gwt-Label">Rādīt lapā:</div>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="<?php if ($intRowCountPerPage == 20) echo 'paginatorBlackCountButtons'; else echo 'paginatorWhiteCountButtons'; ?>">
										<div onclick="document.getElementById('row_count_per_page').value='20'; document.forms['paginatorForm'].submit();"  style="margin:0 -4px 0 -3px;">20</div>
									</button>
								</td>
								<td align="left" style="vertical-align: middle; ">
									<button type="button" class="<?php if ($intRowCountPerPage == 40) echo 'paginatorBlackCountButtons'; else echo 'paginatorWhiteCountButtons'; ?>">
										<div onclick="document.getElementById('row_count_per_page').value='40'; document.forms['paginatorForm'].submit();" style="margin:0 -4px 0 -3px;">40</div>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- end pagination -->


<script type="text/javascript">
<?php
if (isset($strDocumentError)) echo "alert('$strDocumentError');";
if (isset($strNameError)) echo "alert('$strNameError');";
?>
</script>
<?php $this->load->view('shared/bottom');?>